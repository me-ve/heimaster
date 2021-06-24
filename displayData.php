<?php
require_once("timeElapsed.php");
//TODO make list of champions sortable
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
    $pointsFormat = number_format($points, 0, ".", ",");
    $avgFormat = number_format($partOfAvg, 0, ".", ",");
    $avgLogFormat = number_format($partOfAvgLog, 2, ".", ",");
    $date = date("Y-m-d H:i:s", $lastPlayTime/1000);
    $d = new DateTime($date);
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
    echo "<td id='points{$position}' class=mediumCell>{$pointsFormat}</td>";
    echo "<td id='partofavg{$position}' class=mediumCell>{$avgFormat}%</td>";
    echo "<td id='partofavgtier{$position}' class=smallCell>{$avgLogFormat}</td>";
    $tierFound = false;
    for($i=2.5; $i>=(-2.5) && !$tierFound; $i=$i-1)
    {
        if($partOfAvgLog>=$i)
        {
            $tierFound = true;
            $num = $i + 0.5;
            $championTier = $tierLetters[$num];
        }
    }
    if(!$tierFound) $championTier = $tierLetters[-3];
    echo "<td id='tier{$position}' class=smallCell>{$championTier}</td>";
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
    //displaying last time
    $currentDateNum = strtotime($currentDate);
    $dateNum = strtotime($date);
    $timeChange = time_elapsed($currentDateNum - $dateNum);
    echo "<td id='date{$position}' class=longCell>{$timeChange}</td>";
    echo "</tr>";
?>