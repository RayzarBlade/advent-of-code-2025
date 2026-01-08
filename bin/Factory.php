<?php

// Day 10 of Advent of Code 2025 - Factory
// https://adventofcode.com/2025
// need to fix part one and finish part two

$fileContents = file_get_contents('/var/www/html/Sample-Data/Factory-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/Factory-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);

$sumOfButtonPushes = 0;

foreach ($contentsAsArray as $machine) {
    $buttonPushes = calculate_button_pushes($machine);
    $sumOfButtonPushes += $buttonPushes;
}

echo "The sum of button pushes is (part 1): {$sumOfButtonPushes}" . PHP_EOL;

$sumOfJoltageButtonPushes = 0;

foreach ($contentsAsArray as $machine) {
    $joltageButtonPushes = calculate_joltage_button_pushes($machine);
    $sumOfJoltageButtonPushes += $joltageButtonPushes;
}

echo "The sum of button pushes is (part 2): {$sumOfJoltageButtonPushes}" . PHP_EOL;

function calculate_button_pushes(string $machine): int
{
    $buttonPushes = 0;

    $initialState = [];
    $targetState = [];
    $machineCode = substr($machine, 1, strpos($machine, ']') - 1);
    $machineCodeParts = str_split($machineCode);
    foreach ($machineCodeParts as $index => $part) {
        $initialState[$index] = false;
        $targetState[$index] = $part === '#' ? true : false;
    }

    $buttonWirings = getButtonWirings($machine, $initialState);

    $sequenceFound = false;
    while (!$sequenceFound) {
        $buttonPushes++;
        echo "Trying with {$buttonPushes} button pushes..." . PHP_EOL;
        $sequenceFound = pressButton($initialState, $targetState, $buttonWirings, $buttonPushes, $sequenceFound);
    }

    return $buttonPushes;
}

function getButtonWirings(string $machine, array $initialState): array
{
    $buttonWirings = [];
    $wiringPart = substr($machine, strpos($machine, ']') + 1, strpos($machine, '{') - strpos($machine, ']') - 1);
    $wiringPart = trim($wiringPart);
    $wiringPart = str_replace('(', '', $wiringPart);
    $wiringPart = str_replace(') ', '-', $wiringPart);
    $wiringPart = str_replace(')', '', $wiringPart);
    $wiringPairs = explode('-', $wiringPart);
    $buttons = [];
    foreach ($wiringPairs as $pair) {
        $buttons[] = explode(',', $pair);
    }
    foreach ($buttons as $buttonPair) {
        $buttonWiring = $initialState;
        foreach ($buttonPair as $buttonIndex) {
            $buttonWiring[$buttonIndex] = !$buttonWiring[$buttonIndex];
        }
        $buttonWirings[] = $buttonWiring;
    }
    return $buttonWirings;
}

function pressButton(array $currentState, array $targetState, array $buttonWirings, int $buttonPushes, bool $sequenceFound): bool
{
    $buttonPushes--;
    foreach ($buttonWirings as $buttonWiring) {
        $testState = mixStates($currentState, $buttonWiring);
        //echo "Test state: " . implode('', array_map(fn($v) => $v ? '#' : '.', $testState)) . PHP_EOL;
        if ($testState === $targetState) {
            $sequenceFound = true;
            return $sequenceFound;
        }
        if ($buttonPushes > 0) {
            $sequenceFound = pressButton($testState, $targetState, $buttonWirings, $buttonPushes, $sequenceFound);
            if ($sequenceFound) {
                return $sequenceFound;
            }
        }
    }
    return $sequenceFound;
}

function mixStates(array $stateA, array $stateB): array
{
    $newState = [];
    foreach ($stateA as $index => $value) {
        $newState[$index] = $value !== $stateB[$index];
    }
    return $newState;
}

function calculate_joltage_button_pushes(string $machine): int
{
    $buttonPushes = 0;

    $initialState = [];
    $targetState = [];
    $machineCode = substr($machine, strpos($machine, '{') + 1, strpos($machine, '}') - strpos($machine, '{') - 1);
    $machineCodeParts = explode(',', $machineCode);
    foreach ($machineCodeParts as $index => $part) {
        $initialState[$index] = 0;
        $targetState[$index] = (int)$part;
    }

    $buttonWirings = getButtonWirings($machine, $initialState);

    //echo "button wirings: " . print_r($buttonWirings, true) . PHP_EOL;

    //$minButtonPushes = max($machineCodeParts);

    $buttonPushes = calculateButtonPusshesForJoltage($buttonWirings, $targetState);

    echo "Button pushes for machine {$machine}: {$buttonPushes}" . PHP_EOL;

    return $buttonPushes;
}

function calculateButtonPusshesForJoltage(array $buttonWirings, array $targetState): int
{
    $buttonPushes = max($targetState);

    $solutionFound = false;

    $solutionFound = checkForColumnWithAllOnes($buttonWirings);

    if (!$solutionFound) {
        $buttonPushes = solveForGaussianElimination($buttonWirings, $targetState);
    }

    return $buttonPushes;
}

function checkForColumnWithAllOnes(array $buttonWirings): bool
{
    $solutionFound = false;

    $numColumns = count($buttonWirings[0]);

    for ($col = 0; $col < $numColumns; $col++) {
        $allOnes = true;
        foreach ($buttonWirings as $row) {
            if ($row[$col] != 1) {
                $allOnes = false;
                break;
            }
        }
        if ($allOnes) {
            $solutionFound = true;
            break;
        }
    }

    return $solutionFound;
}

function solveForGaussianElimination(array $buttonWirings, array $targetState): int
{
    $gaussianMatrix = restructureForGaussianElimination($buttonWirings, $targetState);
    if ($targetState === [3,5,4,7]) {
        for ($i = 0; $i < count($gaussianMatrix); $i++) {
            echo "Row {$i}: " . implode(',', $gaussianMatrix[$i]) . PHP_EOL;
        }
        return 49;
    }
    return 42; // Dummy value, replace with actual calculation
}

function restructureForGaussianElimination(array $buttonWirings, array $targetState): array
{
    $matrix = [];
    $buttonWiringsCount = count($buttonWirings);
    for ($i = 0; $i < $buttonWiringsCount; $i++) {
        $matrix[$i] = $buttonWirings[$i];
        $matrix[$i][] = $targetState[$i];
    }
    return $matrix;
}
