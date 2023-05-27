<?php

function displayPercent(float $number, int $decimals = 0): string
{
    return displayWithSeparators(round($number * 100, $decimals)) . '%';
}
function displayWithSeparators(float $number, int $decimals = 0): string
{
    return number_format($number, $decimals, '.', ',');
}
