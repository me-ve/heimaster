<?php

const TIME_UNITS = array(
    "second" => 1,
    "minute" => 60,
    "hour" => 60 * 60,
    "day" => 24 * 60 * 60,
    "week" => 7 * 24 * 60 * 60,
    "month" => 30 * 24 * 60 * 60,
    "year" => 365.25 * 24 * 60 * 60
);
function isInRange(int $a, int $lower_bound, int $higher_bound): bool
{
    return $a >= $lower_bound
        && $a < $higher_bound;
}
function timeElapsed(int $time): string
{
    $unit = match (true) {
        $time == 0 => "now",
        $time == 1 => "second",
        isInRange($time, 2, 2 * TIME_UNITS["minute"])
            => "seconds",
        isInRange($time, 2 * TIME_UNITS["minute"], 2 * TIME_UNITS["hour"])
            => "minutes",
        isInRange($time, 2 * TIME_UNITS["hour"], 2 * TIME_UNITS["day"])
            => "hours",
        isInRange($time, 2 * TIME_UNITS["day"], 2 * TIME_UNITS["week"])
            => "days",
        isInRange($time, 2 * TIME_UNITS["week"], 2 * TIME_UNITS["month"])
            => "weeks",
        isInRange($time, 2 * TIME_UNITS["month"], 2 * TIME_UNITS["year"])
            => "months",
        $time >= TIME_UNITS["year"]
            => "years",
        default
            => "time",
    };
    $count = match (true) {
        $time == 0 => "just",
        $time == 1 => "a",
        $time > 1
            => $count = round($time / TIME_UNITS[rtrim($unit, 's')]),
        default => "some",
    };
    return "{$count} {$unit} ago";
}
