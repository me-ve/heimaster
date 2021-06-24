<?php
function time_elapsed($sec){
    $MINUTE = 60;
    $HOUR = 60 * $MINUTE;
    $DAY = 24 * $HOUR;
    $WEEK = 7 * $DAY;
    $YEAR = 365.25 * $DAY;
    $MONTH = $YEAR / 12;
    $count = "some";
    $unit = "time";
    if($sec>=0 && $sec<2*$MINUTE) {
        if($sec == 1){
            $count = "a";
            $unit = "second";
        }
        else $unit = "seconds";
        $count = $sec;
    } else if($sec>=2*$MINUTE && $sec<2*$HOUR) {
        $unit = "minutes";
        $count = round($sec/$MINUTE);
    } else if($sec>=2*$HOUR && $sec<2*$DAY) {
        $unit = "hours";
        $count = round($sec/($HOUR));
    } else if($sec>=2*$DAY && $sec<2*$WEEK) {
        $unit = "days";
        $count = round($sec/($DAY));
    } else if($sec>=2*$WEEK && $sec<2*$MONTH) {
        $unit = "weeks";
        $count = round($sec/($WEEK));
    } else if($sec>=2*$MONTH && $sec<2*$YEAR) {
        $unit = "months";
        $count = round($sec/($MONTH));
    } else {
        $unit = "years";
        $count = round($sec/($YEAR));
    }
    return "{$count} {$unit} ago";
}
?>
