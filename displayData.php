<?php
    if($level<5)
    {
        $progressToNextLevel = $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel);
        $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
    }
    else if($level == 5)
    {
        $progressToNextLevel = $tokens/2;
        $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
    }
    else if($level == 6)
    {
        $progressToNextLevel = $tokens/3;
        $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
    }
    else
    {
        $progressToNextLevel = "N/A";
    }
    $pointsFormat = number_format($points, 0, 0, ",");
    $date = date("Y-m-d H:i", $lastPlayTime/1000);
    echo "<tr id='row{$position}'>";
    echo "<td id='position{$position}' style='width:60px' class=cell>{$position}</td>";
    echo "<td id='image{$position}' class=championimage><img src='{$iconURL}' class='championimage' alt='{$name}'></td>";
    echo "<td id='name{$position}' class=cell>{$name}</td>";
    echo "<td id='level{$position}' style='width:60px' class=cell>{$level}</td>";
    echo "<td id='points{$position}' style='width:240px' class=cell>{$pointsFormat}</td>";
    echo "<td id='progress{$position}' style='width:60px' class=cell>{$progressToNextLevel}</td>";
    if($chests)
    {
        echo "<td id='chests{$position}' style='background-color: #ceb572; width:60px' class=cell>yes</td>";
    }
    else
    {
        echo "<td id='chests{$position}' style='background-color: #6c7b8b; width:60px' class=cell>no</td>";
    }
    echo "<td id='tokens{$position}' style='width:60px' class=cell>{$tokens}</td>";
    echo "<td id='date{$position}' style='width:240px' class=cell>{$date}</td>";
    echo "</tr>";
?>