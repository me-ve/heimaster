<?php
function display_percent(float $number, int $decimals=0){
    return display_with_separators(round($number * 100, $decimals))."%";
}
function display_with_separators(float $number, int $decimals=0){
    return number_format($number, $decimals, ".", ",");
}
?>