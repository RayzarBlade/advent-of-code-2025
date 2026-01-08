<?php

// Day 9 of Advent of Code 2025 - Movie Theater
// https://adventofcode.com/2025
// Need to optimize part two for larger data sets

$fileContents = file_get_contents('/var/www/html/Sample-Data/MovieTheater-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/MovieTheater-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);
$cornerArray = breakDownConers($contentsAsArray);

$largestArea = 0;
foreach ($cornerArray as $index => $corner) {
    foreach ($cornerArray as $otherIndex => $otherCorner) {
        if ($index === $otherIndex) {
            continue;
        }

        $area = (abs($corner[0] - $otherCorner[0]) + 1) * (abs($corner[1] - $otherCorner[1]) + 1);
        if ($area > $largestArea) {
            $largestArea = $area;
        }
    }
}

echo "Largest area between corners (part 1): {$largestArea}" . PHP_EOL;
$largestArea = 0;
foreach ($cornerArray as $index => $corner) {
    foreach ($cornerArray as $otherIndex => $otherCorner) {
        if ($index === $otherIndex) {
            continue;
        }

        $area = (abs($corner[0] - $otherCorner[0]) + 1) * (abs($corner[1] - $otherCorner[1]) + 1);
        $areaIsValid = isAreaValid($corner, $otherCorner, $cornerArray);
        if ($area > $largestArea) {
            $areaIsValid = isAreaValid($corner, $otherCorner, $cornerArray);
            if ($areaIsValid) {
                $largestArea = $area;
            }

        }
    }
}

echo "Largest area between corners (part 2): {$largestArea}" . PHP_EOL;


function breakDownConers(array $coners): array
{
    $result = [];
    foreach ($coners as $coner) {
        $conerParts = explode(',', $coner);
        $result[] = $conerParts;
    }

    return $result;
}

function isAreaValid(array $cornerA, array $cornerB, array $allCorners): bool
{
    $minX = min($cornerA[0], $cornerB[0]);
    $maxX = max($cornerA[0], $cornerB[0]);
    $minY = min($cornerA[1], $cornerB[1]);
    $maxY = max($cornerA[1], $cornerB[1]);

    $maxCountAllCorners = count($allCorners);

    foreach ($allCorners as $key => $corner) {
        if ($key === 0) {
            $previousCorner = $allCorners[$maxCountAllCorners - 1];
        } else {
            $previousCorner = $allCorners[$key - 1];
        }
        if (redTilesIntersectArea($corner, $previousCorner, $minX, $maxX, $minY, $maxY)) {
            return false;
        }
    }

    return true;
}

function redTilesIntersectArea($corner, $previousCorner, $minX, $maxX, $minY, $maxY): bool
{
    $edminX = min($corner[0], $previousCorner[0]);
    $edmaxX = max($corner[0], $previousCorner[0]);
    $edminY = min($corner[1], $previousCorner[1]);
    $edmaxY = max($corner[1], $previousCorner[1]);
    //echo "Checking red tile between ({$previousCorner[0]},{$previousCorner[1]}) and ({$corner[0]},{$corner[1]})" . PHP_EOL;
    /* if ($minX === 7 && $maxX === 11 && $minY === 1 && $maxY === 7 ) {
        echo "Red tile between ({$previousCorner[0]},{$previousCorner[1]}) and ({$corner[0]},{$corner[1]})" . PHP_EOL;
    } */
    if ($previousCorner[0] === $corner[0]) {
        return ($minX < $edminX && $edminX < $maxX && ($edmaxY > $minY && $edminY < $maxY));
    } else {
        return ($minY < $edminY && $edminY < $maxY && ($edmaxX > $minX && $edminX < $maxX));
    }
}
