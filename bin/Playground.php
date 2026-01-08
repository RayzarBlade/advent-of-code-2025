<?php

// Day 8 of Advent of Code 2025 - Playground
// https://adventofcode.com/2025
// Takes too long to run on real data set will optimize later
// Should also fix answers to match part 1 and part 2 requirements

$fileContents = file_get_contents('/var/www/html/Sample-Data/Playground-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/Playground-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);
$rangesArray = breakDownRanges($contentsAsArray);

$rowCount = count($rangesArray);

$circuitBoard = initializeCircuitBoard($rowCount);

$circuitBoard = findShortestPaths($rangesArray, $circuitBoard, 10000);

$productOfThreeLargestCircuits = calculateProductOfThreeLargestCircuits($circuitBoard);

echo "Product of the sizes of the three largest circuits (part 1): {$productOfThreeLargestCircuits}" . PHP_EOL;
echo "Final number of circuits (part 2): " . count($circuitBoard) . PHP_EOL;

//print_r($circuitBoard);


function breakDownRanges(array $ranges): array
{
    $result = [];
    foreach ($ranges as $range) {
        $rangeParts = explode(',', $range);
        $result[] = $rangeParts;
    }

    return $result;
}

function initializeCircuitBoard(int $columnCount): array
{
    $circuitBoard = [];
    for ($i = 0; $i < $columnCount; $i++) {
        $circuitBoard[$i][] = $i;
    }

    return $circuitBoard;
}

function calculateProductOfThreeLargestCircuits(array $circuitBoard): int
{
    $circuitSizes = array_map('count', $circuitBoard);
    rsort($circuitSizes);
    $topThreeSizes = array_slice($circuitSizes, 0, 3);

    return array_product($topThreeSizes);
}

function findShortestPaths(array $rangesArray, array $circuitBoard, int $maxSteps): array
{
    $step = 0;
    $start = 0;
    $distanceMatrix = buildDistanceMatrix($rangesArray);
    while (count($circuitBoard) > 1 && $step < $maxSteps) {
        $shortestDistance = 0;
        for ($x = 0; $x < count($rangesArray); $x++) {
            for ($i = 0; $i < count($rangesArray); $i++) {
                $distance = $distanceMatrix[$x][$i];
                if ($distance < $shortestDistance && $distance !== 0 || $shortestDistance === 0) {
                    $shortestDistance = $distance;
                    $checkIndex = $x;
                    $closestIndex = $i;
                }
            }
        }
        $circuitBoard = setCircuitBoardValues($circuitBoard, $checkIndex, $closestIndex);
        $distanceMatrix[$checkIndex][$closestIndex] = 0;
        $distanceMatrix[$closestIndex][$checkIndex] = 0;
        echo "Closest index to {$checkIndex} is {$closestIndex} with distance {$shortestDistance}" . PHP_EOL;
        $step++;
    }
    $firstX = $rangesArray[$checkIndex][0];
    $secondX = $rangesArray[$closestIndex][0];
    $product = $firstX * $secondX;
    echo "Product of {$firstX} and {$secondX} is {$product}" . PHP_EOL;
    //printDistanceMatrix($distanceMatrix);
    return $circuitBoard;
}

function getDistance(array $startRange, array $endRange): int
{
    $distance = sqrt(pow($startRange[0] - $endRange[0], 2) + pow($startRange[1] - $endRange[1], 2) + pow($startRange[2] - $endRange[2], 2));

    return $distance;
}

function getCollectionOfJunctionBoxesToCheck(array $circuitBoard): array
{
    $collection = [];
    foreach ($circuitBoard as $key => $row) {
        if (count($row) == 1) {
            $collection[] = $row[0];
        }
    }

    return $collection;
}

function setCircuitBoardValues(array $circuitBoard, int $checkIndex, int $closestIndex): array
{
    $targetRow = 0;
    $oldRow = 0;
    foreach ($circuitBoard as $key => $row) {
        if (in_array($checkIndex, $row)) {
            $targetRow = $key;
        }
        if (in_array($closestIndex, $row)) {
            $oldRow = $key;
        }
    }
    if ($targetRow === $oldRow) {
        return $circuitBoard;
    }
    $circuitBoard[$targetRow] = array_merge($circuitBoard[$targetRow], $circuitBoard[$oldRow]);
    unset($circuitBoard[$oldRow]);

    return $circuitBoard;
}

function buildDistanceMatrix(array $rangesArray): array
{
    $distanceMatrix = [];
    for ($i = 0; $i < count($rangesArray); $i++) {
        for ($j = 0; $j < count($rangesArray); $j++) {
            $distanceMatrix[$i][$j] = getDistance($rangesArray[$i], $rangesArray[$j]);
        }
    }

    return $distanceMatrix;
}

function printDistanceMatrix(array $distanceMatrix): void
{
    for ($i = 0; $i < count($distanceMatrix); $i++) {
        for ($j = 0; $j < count($distanceMatrix); $j++) {
            echo str_pad((string) round($distanceMatrix[$i][$j], 2), 8, ' ', STR_PAD_LEFT);
        }
        echo PHP_EOL;
    }
}
