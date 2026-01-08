<?php

// Day 6 of Advent of Code 2025 - Trash Compactor
// https://adventofcode.com/2025

$filecontents = file_get_contents('/var/www/html/Sample-Data/TrashCompactor-sample.txt');

//$filecontents = file_get_contents('/var/www/html/Real-Data/TrashCompactor-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $filecontents);

$fullMatrix = getFullMatrix($contentsAsArray);

$splitMatrix = getSplitMatrix($contentsAsArray);

$total = 0;
$rows = count($fullMatrix);
$cols = count($fullMatrix[0]);
for ($i = 0; $i < $cols; $i++) {
    $sum = getColumnTotal($fullMatrix, $i, $rows);
    $total += $sum;
}

echo "The total is (part 1): " . $total . PHP_EOL;

$rows = count($splitMatrix);
$maxColumnLenght = findMaxColumnLength($splitMatrix, $rows) + 1;
$splitMatrix = stretchAllRows($splitMatrix, $maxColumnLenght);


$position = 0;
$secondaryTotal = 0;
do {
$groupSize = findGroupSize($splitMatrix, $rows, $position + 1, $maxColumnLenght);
$sign = $splitMatrix[$rows - 1][$position];
switch ($sign) {
    case '*':
        $calculated = findColumnProduct($splitMatrix, $rows, $position, $groupSize);
        break;
    default:
        $calculated = findColumnSum($splitMatrix, $rows, $position, $groupSize);
        break;
}
$secondaryTotal += $calculated;
$position += $groupSize + 1;
} while ($position < $maxColumnLenght);

echo "The secondary total is (part 2): " . $secondaryTotal . PHP_EOL;

//print_r($splitMatrix);


function getFullMatrix($contentsAsArray)
{
    $matrix = [];
    foreach ($contentsAsArray as $line) {
        $line = trim($line);
        do {
            $line = str_replace('  ', ' ', $line, $count);
        } while ($count > 0);
        $row = explode(' ', $line);
        $matrix[] = $row;
    }
    return $matrix;
}

function getColumnTotal($matrix, $colIndex, $rows)
{
    switch ($matrix[$rows - 1][$colIndex]) {
        case '*':
            return getProductOfColumn($matrix, $colIndex, $rows);
        default:
            return getAdditionOfColumn($matrix, $colIndex, $rows);
    }
}

function getAdditionOfColumn($matrix, $colIndex, $rows)
{
    $total = 0;
    for ($i = 0; $i < $rows - 1; $i++) {
        $total += (int)$matrix[$i][$colIndex];
    }
    return $total;
}

function getProductOfColumn($matrix, $colIndex, $rows)
{
    $product = 1;
    for ($i = 0; $i < $rows - 1; $i++) {
        $product *= (int)$matrix[$i][$colIndex];
    }

    return $product;
}

function getSplitMatrix($contentsAsArray)
{
    $matrix = [];
    foreach ($contentsAsArray as $line) {
        $row = str_split($line);
        $matrix[] = $row;
    }
    return $matrix;
}

function findMaxColumnLength($matrix, $rows)
{
    $maxLength = 0;
    for ($i = 0; $i < $rows; $i++) {
        $currentLength = count($matrix[$i]);
        if ($currentLength > $maxLength) {
            $maxLength = $currentLength;
        }
    }
    return $maxLength;
}

function stretchLastRow($matrix, $maxColumnLength)
{
    $rows = count($matrix);
    $lastRowIndex = $rows - 1;
    $currentLength = count($matrix[$lastRowIndex]);
    $difference = $maxColumnLength - $currentLength;
    for ($i = 0; $i < $difference; $i++) {
        $matrix[$lastRowIndex][] = ' ';
    }
    return $matrix;
}

function findGroupSize($matrix, $rows, $position, $maxColumnLength)
{
    $groupSize = 0;
    for ($i = $position; $i < $maxColumnLength; $i++) {
        $char = $matrix[$rows - 1][$i];
        if ($char === ' ') {
            $groupSize++;
        } else {
            break;
        }
    }
    return $groupSize;
}

function findColumnProduct($matrix, $rows, $position, $groupSize)
{
    $product = 1;
    for ($i = $position; $i < $position + $groupSize; $i++) {
        $char = '';
        for ($j = 0; $j < $rows - 1; $j++) {
            $char .= $matrix[$j][$i];
        }
        if (is_numeric($char)) {
            $product *= (int)$char;
        }
    }
    return $product;
}

function findColumnSum($matrix, $rows, $position, $groupSize)
{
    $sum = 0;
    for ($i = $position; $i < $position + $groupSize; $i++) {
        $char = '';
        for ($j = 0; $j < $rows - 1; $j++) {
            $char .= $matrix[$j][$i];
        }
        if (is_numeric($char)) {
            $sum += (int)$char;
        }
    }
    return $sum;
}

function stretchAllRows($matrix, $maxColumnLength)
{
    $rows = count($matrix);
    for ($i = 0; $i < $rows; $i++) {
        $currentLength = count($matrix[$i]);
        $difference = $maxColumnLength - $currentLength;
        for ($j = 0; $j < $difference; $j++) {
            $matrix[$i][] = ' ';
        }
    }
    return $matrix;
}