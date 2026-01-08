<?php

// Day 1 of Advent of Code 2025 - Secret Entrance
// https://adventofcode.com/2025

$fileContents = file_get_contents('/var/www/html/Sample-Data/SecretEntrance-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/SecretEntrance-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);

$contentsAsArray = convertArrayToSingedIntegers($contentsAsArray);

$lockPosition = 50;
$countWhenEqualsZero = 0;
$countNumberOfTimesPastZero = 0;

foreach ($contentsAsArray as $index => $value) {
    $previousLockPosition = $lockPosition;
    $lockPosition = calcultateNewLockPosition($lockPosition, $value);

    $numberOfTimesPastZero = calculateNumberOfTimesPastZero($previousLockPosition, $lockPosition, $value);

    if ($lockPosition === 0) {
        $countWhenEqualsZero++;
    }
    $countNumberOfTimesPastZero += $numberOfTimesPastZero;
    //echo "Index: {$index} - Value: {$value} - Lock Position: {$lockPosition} - Number of Times Past Zero: {$numberOfTimesPastZero}" . PHP_EOL;
}

$totalNumber = $countWhenEqualsZero + $countNumberOfTimesPastZero;

echo "Total times lock position was 0 (part 1): {$countWhenEqualsZero}" . PHP_EOL;

echo "Total number of times lock passed zero or was zero (part 2): {$totalNumber}" . PHP_EOL;

function convertArrayToSingedIntegers(array $array): array
{
    foreach ($array as $key => $value) {
        if (str_contains($value, 'L')) {
            $array[$key] = - (int) str_replace('L', '', $value);
        } else {
            $array[$key] = (int) str_replace('R', '', $value);;
        }
    }
    return $array;
}

function calcultateNewLockPosition(int $lockPosition, int $moveBy): int
{
    $lockPosition = ($lockPosition + $moveBy) % 100;
    if ($lockPosition < 0) {
        $lockPosition += 100;
    }

    return $lockPosition;
}

function calculateNumberOfTimesPastZero(int $previousLockPosition, int $lockPosition, int $moveBy): int
{
    $numberOfTimesPastZero = 0;

    if ($moveBy > 0) {
        $numberOfTimesPastZero = intdiv($previousLockPosition + abs($moveBy) - 1, 100);
    } else {
        $numberOfTimesPastZero = intdiv($lockPosition + abs($moveBy) - 1, 100);
    }

    return $numberOfTimesPastZero;
}

