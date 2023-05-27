<?php

namespace View;

class TimeHelper
{
    public const TIME_UNITS = array(
    "second" => 1,
    "minute" => 60,
    "hour" => 60 * 60,
    "day" => 24 * 60 * 60,
    "week" => 7 * 24 * 60 * 60,
    "month" => 30 * 24 * 60 * 60,
    "year" => 365.25 * 24 * 60 * 60
    );
    public static function isInRange(int $a, int $lower_bound, int $higher_bound): bool
    {
        return $a >= $lower_bound
        && $a < $higher_bound;
    }
    public static function timeElapsed(int $time): string
    {
        $unit = match (true) {
            $time == 0 => "now",
            $time == 1 => "second",
            self::isInRange($time, 2, 2 * self::TIME_UNITS["minute"])
            => "seconds",
            self::isInRange($time, 2 * self::TIME_UNITS["minute"], 2 * self::TIME_UNITS["hour"])
            => "minutes",
            self::isInRange($time, 2 * self::TIME_UNITS["hour"], 2 * self::TIME_UNITS["day"])
            => "hours",
            self::isInRange($time, 2 * self::TIME_UNITS["day"], 2 * self::TIME_UNITS["week"])
            => "days",
            self::isInRange($time, 2 * self::TIME_UNITS["week"], 2 * self::TIME_UNITS["month"])
            => "weeks",
            self::isInRange($time, 2 * self::TIME_UNITS["month"], 2 * self::TIME_UNITS["year"])
            => "months",
            $time >= self::TIME_UNITS["year"]
            => "years",
            default
            => "time",
        };
        $count = match (true) {
            $time == 0 => "just",
            $time == 1 => "a",
            $time > 1
            => $count = round($time / self::TIME_UNITS[rtrim($unit, 's')]),
            default => "some",
        };
        return "{$count} {$unit} ago";
    }
}
