<?php

// Day 3 of Advent of Code 2025 - Lobby
// https://adventofcode.com/2025

$fileContents = file_get_contents('/var/www/html/Sample-Data/Lobby-sample.txt');

//$fileContents = file_get_contents('/var/www/html/Real-Data/Lobby-realdata.txt');

$contentsAsArray = explode(PHP_EOL, $fileContents);

$voltage = 0;
$secondVoltage = 0;

foreach ($contentsAsArray as $bank) {
    $highestVoltage = findHighestVoltage($bank, 2);
    $secondHighestVoltage = findHighestVoltage($bank, 12);
    //echo "Highest voltage for bank " . $bank . " is: " . $highestVoltage . PHP_EOL;
    $voltage = $voltage + $highestVoltage;
    $secondVoltage = $secondVoltage + $secondHighestVoltage;
}

echo "Total voltage of all banks (part 1): {$voltage}" . PHP_EOL;

echo "Total voltage of all banks (part 2): {$secondVoltage}" . PHP_EOL;

function findHighestVoltage(string $bank, int $digits): int
{
    $bankAsArray = str_split($bank);
    $start = 0;
    $bankLength = strlen($bank);
    $voltagePosition = [];
    $voltage = 0;
    for ($i = 1; $i <= $digits; $i++) {
        $voltagePosition[$i] = findHighestDigitPosition($bankAsArray, $start, $bankLength - ($digits - $i + 1));
        $start = $voltagePosition[$i] + 1;
        $voltage = $voltage + pow(10, $digits - $i) * (int)$bankAsArray[$voltagePosition[$i]];
    }
    //$voltage = (int)$bankAsArray[$voltagePosition[1]] * 10 + (int)$bankAsArray[$voltagePosition[2]];
    return $voltage;
}

function findHighestDigitPosition(array $bankAsArray, int $startPosition, int $endPosition): int
{
    $highestDigitPosition = $startPosition;
    for ($i = $startPosition; $i <= $endPosition; $i++) {
        if ((int)$bankAsArray[$i] > (int)$bankAsArray[$highestDigitPosition]) {
            $highestDigitPosition = $i;
        }
    }
    return $highestDigitPosition;
}