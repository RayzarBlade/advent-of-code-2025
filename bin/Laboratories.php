<?php

// Day 7 of Advent of Code 2025 - Laboratories
// https://adventofcode.com/2025

$filecontents = file_get_contents('/var/www/html/Sample-Data/Laboratories-sample.txt');

//$filecontents = file_get_contents('/var/www/html/Real-Data/Laboratories-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $filecontents);
$matrix = getMatrix($contentsAsArray);
$laserPositions = [];
$laserPositions[] = array_search('S', $matrix[0]);
$rows = count($matrix);
$cols = count($matrix[0]);
$splitLaserCount = 0;

$holdingLaserPositions = initializeArray($cols);
$holdingLaserPositions[$laserPositions[0]] = 1;

for ($i = 2; $i < $rows; $i += 2) {
    //echo "calculating row " . $i . PHP_EOL;
    $newLaserPositions = [];
    foreach ($laserPositions as $position) {
        if ($matrix[$i][$position] === '^') {
            $splitLaserCount++;
            $newLaserPositions[] = $position - 1;
            $newLaserPositions[] = $position + 1;
            $holdingLaserPositions[$position - 1] += $holdingLaserPositions[$position];
            $holdingLaserPositions[$position + 1] += $holdingLaserPositions[$position];
            $holdingLaserPositions[$position] = 0;
        } else {
            $newLaserPositions[] = $position;
        }
    }
    if (count($newLaserPositions) !== 0) {
        $laserPositions = array_unique($newLaserPositions);
    }
}

$total = getSumOfArray($holdingLaserPositions);

echo "Laser split (part 1): " . $splitLaserCount . PHP_EOL;


echo "Total lasers at the end (part 2): " . $total . PHP_EOL;

function getMatrix($contentsAsArray)
{
    $matrix = [];
    foreach ($contentsAsArray as $line) {
        $row = str_split($line);
        $matrix[] = $row;
    }
    return $matrix;
}

function initializeArray($size)
{
    $array = [];
    for ($i = 0; $i < $size; $i++) {
        $array[] = 0;
    }
    return $array;
}

function getSumOfArray($array)
{
    $sum = 0;
    foreach ($array as $value) {
        $sum += $value;
    }
    return $sum;
}
