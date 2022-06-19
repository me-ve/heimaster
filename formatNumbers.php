<?php
function displayPercent(float $number, int $decimals=0){
    return displayWithSeparators(round($number * 100, $decimals))."%";
}
function displayWithSeparators(float $number, int $decimals=0){
    return number_format($number, $decimals, ".", ",");
}
?>