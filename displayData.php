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
    echo "<td id='position{$position}' class=positionCell>{$position}</td>";
    echo "<td id='image{$position}' class=championImage><img src='{$iconURL}' class='championImage' alt='{$name}'></td>";
    echo "<td id='name{$position}' class=cell>{$name}</td>";
    if($level>=5)
    {
        echo "<td id='level{$position}' style='color: #ceb572;' class=levelCell>{$level}</td>";
    }
    else
    {
        echo "<td id='level{$position}' class=levelCell>{$level}</td>";
    }
    echo "<td id='points{$position}' style='width:120px' class=cell>{$pointsFormat}</td>";
    echo "<td id='progress{$position}' class=progressCell>{$progressToNextLevel}</td>";
    if($chests)
    {
        echo "<td id='chests{$position}' style='background-color: #ceb572;' class=chestCell>yes</td>";
    }
    else
    {
        echo "<td id='chests{$position}' style='background-color: #6c7b8b;' class=chestCell>no</td>";
    }
    echo "<td id='tokens{$position}' class=tokenCell>{$tokens}</td>";
    echo "<td id='date{$position}' style='width:240px' class=cell>{$date}</td>";
    echo "</tr>";
?>