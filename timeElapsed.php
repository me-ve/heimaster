<?php
function timeElapsed($sec){
    $between = fn($a, $b, $c) => $a <= $b && $b < $c;
    $MINUTE = 60;
    $HOUR = 60 * $MINUTE;
    $DAY = 24 * $HOUR;
    $WEEK = 7 * $DAY;
    $YEAR = 365 * $DAY + 6 * $HOUR;
    $MONTH = $YEAR / 12;
    $count = "some";
    $unit = "time";
    if($between(0, $sec, 2*$MINUTE)) {
        if($sec == 1){
            $count = "a";
            $unit = "second";
        }
        else $unit = "seconds";
        $count = $sec;
    } else if($between(2*$MINUTE, $sec, 2*$HOUR)) {
        $unit = "minutes";
        $count = round($sec/$MINUTE);
    } else if($between(2*$HOUR, $sec, 2*$DAY)) {
        $unit = "hours";
        $count = round($sec/($HOUR));
    } else if($between(2*$DAY, $sec, 2*$WEEK)) {
        $unit = "days";
        $count = round($sec/($DAY));
    } else if($between(2*$WEEK, $sec, 2*$MONTH)) {
        $unit = "weeks";
        $count = round($sec/($WEEK));
    } else if($between(2*$MONTH, $sec, 2*$YEAR)) {
        $unit = "months";
        $count = round($sec/($MONTH));
    } else {
        $unit = "years";
        $count = round($sec/($YEAR));
    }
    return "{$count} {$unit} ago";
}
?>
