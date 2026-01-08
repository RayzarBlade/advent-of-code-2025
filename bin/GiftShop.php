<?php

// Day 2 of Advent of Code 2025 - Gift Shop
// https://adventofcode.com/2025

$fileContents = file_get_contents('/var/www/html/Sample-Data/GiftShop-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/GiftShop-realdata.txt');

$contentsAsArray = explode(',', $fileContents);

$sumOfInvalidNumbersInRange = 0;
$sumOfInvalidNumbersPart2 = 0;

foreach ($contentsAsArray as $key => $value) {
    $rangeParts = explode('-', $value);
    $min = (int) $rangeParts[0];
    $max = (int) $rangeParts[1];
    $sumOfInvalidNumbersInRange += getInvalidNumbersSumInRange($min, $max, 1);
    $sumOfInvalidNumbersPart2 += getInvalidNumbersSumInRange($min, $max, 2);
}

echo "Sum of all invalid product IDs in the ranges (part 1): {$sumOfInvalidNumbersInRange}" . PHP_EOL;
echo "Sum of all invalid product IDs in the ranges (part 2): {$sumOfInvalidNumbersPart2}" . PHP_EOL;

function getInvalidNumbersSumInRange(int $min, int $max, int $part): int
{
    $sum = 0;
    for ($i = $min; $i <= $max; $i++) {
        if (!isValidProductId($i) && $part === 1) {
            $sum += $i;
        } elseif (!isValidProductIdPart2($i) && $part === 2) {
            $sum += $i;
        }
    }
    return $sum;
}

function isValidProductId(int $productId): bool
{
    $numberAsString = (string)$productId;
    if (strlen($numberAsString) % 2 == 0) {
        $firstHalf = substr($numberAsString, 0, strlen($numberAsString) / 2);
        $secondHalf = substr($numberAsString, strlen($numberAsString) / 2);
        if ($firstHalf === $secondHalf) {
            return false;
        }
    }
    return true;
}

function isValidProductIdPart2(int $productId): bool
{
    $numberAsString = (string)$productId;
    $length = strlen($numberAsString);
    $halflength = intdiv($length, 2);
    for ($i = 1; $i <= $halflength; $i++) {
        if ($length % $i == 0) {
            $numberArray = str_split($numberAsString, $i);
            $allMatch = true;
            $firstPart = $numberArray[0];
            foreach ($numberArray as $part) {
                if ($part !== $firstPart) {
                    $allMatch = false;
                }
            }
            if ($allMatch) {
                return false;
            }
        }
    }
    return true;
}
