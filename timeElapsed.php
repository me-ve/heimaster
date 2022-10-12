<?php
const TIME_UNITS = array(
    "second" => 1,
    "minute" => 60,
    "hour" => 60*60,
    "day" => 24*60*60,
    "week" => 7*24*60*60,
    "month" => 30*24*60*60,
    "year" => 365.25*24*60*60
);
function isInRange(int $a, int $lower_bound, int $higher_bound) : bool{
    return $a >= $lower_bound
        && $a < $higher_bound;
}
function timeElapsed(int $time) : string{
    $count = "some";
    $unit = "time";
    if(isInRange($time, 0, 2*TIME_UNITS["minute"])) {
        if($time == 1){
            $count = "a";
            $unit = "second";
        }
        else $unit = "seconds";
        $count = $time;
    } else{
        if(isInRange($time, 2*TIME_UNITS["minute"], 2*TIME_UNITS["hour"])) {
            $unit = "minutes";
        } else if(isInRange($time, 2*TIME_UNITS["hour"], 2*TIME_UNITS["day"])) {
            $unit = "hours";
        } else if(isInRange($time, 2*TIME_UNITS["day"], 2*TIME_UNITS["week"])) {
            $unit = "days";
        } else if(isInRange($time, 2*TIME_UNITS["week"], 2*TIME_UNITS["month"])) {
            $unit = "weeks";
        } else if(isInRange($time, 2*TIME_UNITS["month"], 2*TIME_UNITS["year"])) {
            $unit = "months";
        } else {
            $unit = "years";
        }
        $count = round($time/TIME_UNITS[rtrim($unit, 's')]);
    }
    return "{$count} {$unit} ago";
}
?>
