<?php

namespace View;

class NumberHelper
{
    public static function displayPercent(float $number, int $decimals = 0): string
    {
        return self::displayWithSeparators(
            round($number * 100, $decimals)
        ) . '%';
    }
    public static function displayWithSeparators(float $number, int $decimals = 0): string
    {
        return number_format($number, $decimals, '.', ',');
    }
}
