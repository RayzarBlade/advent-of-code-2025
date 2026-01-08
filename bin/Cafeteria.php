<?php

// Day 5 of Advent of Code 2025 - Cafeteria
// https://adventofcode.com/2025

$filecontents = file_get_contents('/var/www/html/Sample-Data/Cafeteria-sample.txt');

//$filecontents = file_get_contents('/var/www/html/Real-Data/Cafeteria-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $filecontents);
$arraySplitPosition = array_search('', $contentsAsArray);
$goodIngredientsCount = 0;
for ($i = $arraySplitPosition + 1; $i < count($contentsAsArray); $i++) {
    if (isGoodIngredient($contentsAsArray[$i], $contentsAsArray, $arraySplitPosition)) {
        $goodIngredientsCount++;
    }
}

echo "Total number of good ingredients(part 1): {$goodIngredientsCount}" . PHP_EOL;

$rangesArray = breakDownRanges(array_slice($contentsAsArray, 0, $arraySplitPosition));
$rangesArray = sortByFirstValue($rangesArray);
$rangesArray = mergeOverlappingRanges($rangesArray);
$totalRanges = calculateTotalRanges($rangesArray);

echo "Total number of possible ingredients(part 2): {$totalRanges}" . PHP_EOL;


function isGoodIngredient(string $ingredient, array $contentsAsArray, int $arraySplitPosition): bool
{
    for ($y = 0; $y < $arraySplitPosition; $y++) {
        $rangeParts = explode('-', $contentsAsArray[$y]);
        if ((int) $ingredient >= $rangeParts[0] && (int) $ingredient <= $rangeParts[1]) {
            return true;
        }
    }

    return false;
}

function breakDownRanges(array $ranges): array
{
    $result = [];
    foreach ($ranges as $range) {
        $rangeParts = explode('-', $range);
        $rangeParts[0] = (int) $rangeParts[0];
        $rangeParts[1] = (int) $rangeParts[1];
        $result[] = $rangeParts;
    }

    return $result;
}

function sortByFirstValue(array $ranges): array
{
    usort($ranges, function ($a, $b) {
        return $a[0] <=> $b[0];
    });

    return $ranges;
}

function mergeOverlappingRanges(array $ranges): array
{
    $mergedRanges = [];
    $currentRange = $ranges[0];

    for ($i = 1; $i < count($ranges); $i++) {
        if ($ranges[$i][0] <= $currentRange[1]) {
            // Ranges overlap, merge them
            $currentRange[1] = max($currentRange[1], $ranges[$i][1]);
        } else {
            // No overlap, add the current range to the merged list
            $mergedRanges[] = $currentRange;
            $currentRange = $ranges[$i];
        }
    }

    // Add the last range
    $mergedRanges[] = $currentRange;

    return $mergedRanges;
}

function calculateTotalRanges(array $ranges): int
{
    $total = 0;
    foreach ($ranges as $range) {
        $total += ($range[1] - $range[0] + 1);
    }

    return $total;
}
