<?php

// Day 4 of Advent of Code 2025 - Printing Department
// https://adventofcode.com/2025

$fileContents = file_get_contents('/var/www/html/Sample-Data/PrintingDepartment-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/PrintingDepartment-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);

$rollsArray = convertToRollsArray($contentsAsArray);

$rollsArray = convertAccessRollsToX($rollsArray);
$totalXs = countXs($rollsArray);
echo "Total number of X's (part 1): {$totalXs}" . PHP_EOL;

$rollsArray = removeXsFromArray($rollsArray);

do {
    $rollsArray = convertAccessRollsToX($rollsArray);
    $countOfX = countXs($rollsArray);
    $totalXs = $totalXs + $countOfX;
    if ($countOfX > 0) {
        $rollsArray = removeXsFromArray($rollsArray);
    }
} while ($countOfX > 0);

echo "Total number of X's (part 2): {$totalXs}" . PHP_EOL;


function convertToRollsArray(array $contentsAsArray): array
{
    $result = [];
    foreach ($contentsAsArray as $line) {
        $lineAsArray = str_split($line);
        $result[] = $lineAsArray;
    }
    return $result;
}

function convertAccessRollsToX(array $multiDimensionalArray): array
{
    foreach ($multiDimensionalArray as $key => $line) {
        foreach ($line as $index => $roll) {
            if ($roll === '@') {
                $counter = 0;
                for ($i = -1; $i <= 1; $i++) {
                    for ($j = -1; $j <= 1; $j++) {
                        if (isset($multiDimensionalArray[$key + $i][$index + $j])) {
                            if (($i !== 0 || $j !== 0)) {
                                if (($multiDimensionalArray[$key + $i][$index + $j] === '@') || ($multiDimensionalArray[$key + $i][$index + $j] === 'X')) {
                                    $counter++;
                                }
                            }
                        }
                    }
                }
                if ($counter < 4) {
                    $multiDimensionalArray[$key][$index] = 'X';
                }
            }
        }
    }
    return $multiDimensionalArray;
}

function countXs(array $multiDimensionalArray): int
{
    $count = 0;
    foreach ($multiDimensionalArray as $line) {
        foreach ($line as $roll) {
            if ($roll === 'X') {
                $count++;
            }
        }
    }
    return $count;
}

function removeXsFromArray(array $multiDimensionalArray): array
{
    foreach ($multiDimensionalArray as $key => $line) {
        foreach ($line as $index => $roll) {
            if ($roll === 'X') {
                $multiDimensionalArray[$key][$index] = '.';
            }
        }
    }
    return $multiDimensionalArray;
}
