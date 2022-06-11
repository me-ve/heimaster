<?php
    require_once("timeElapsed.php");
    if($level!=7){
        $progressToNextLevel = match($level) {
            1, 2, 3, 4 => $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel),
            default => $tokens/($level - 3)
        };
        $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
    }
    else{
        $progressToNextLevel = "N/A";
    }
    $pointsFormat = number_format($points, 0, ".", ",");
    $avgFormat = number_format($partOfAvg, 0, ".", ",");
    $avgLogFormat = number_format($partOfAvgLog, 2, ".", ",");
    $tier = new Tier($partOfAvgLog);
    $date = date("Y-m-d H:i:s", $lastPlayTime/1000);
    $d = new DateTime($date);
    echo "<tr id='row[{$position}]'>";
    echo "<td id='position[{$position}]' class=positionCell>{$position}</td>";
    echo "<td id='image[{$position}]' class=championImage><img src='{$iconURL}' class='championImage' alt='{$name}'></td>";
    echo "<td id='name[{$position}]' class=cell>{$name}</td>";
    if($level>=5)
    {
        echo "<td id='level[{$position}]' style='color: #ceb572;' class=levelCell>{$level}</td>";
    }
    else
    {
        echo "<td id='level[{$position}]' class=levelCell>{$level}</td>";
    }
    echo "<td id='points[{$position}]' class=mediumCell>{$pointsFormat}</td>";
    echo "<td id='partofavg[{$position}]' class=mediumCell>{$avgFormat}%</td>";
    echo "<td id='partofavgtier[{$position}]' class=smallCell>{$avgLogFormat}</td>";
    echo "<td id='tier[{$position}]' class=smallCell>{$tier->tierSymbol}</td>";
    echo "<td id='progress[{$position}]' class=progressCell>{$progressToNextLevel}</td>";
    if($chests)
    {
        echo "<td id='chests[{$position}]' style='background-color: #ceb572;' class=chestCell>yes</td>";
    }
    else
    {
        echo "<td id='chests[{$position}]' style='background-color: #6c7b8b;' class=chestCell>no</td>";
    }
    echo "<td id='tokens[{$position}]' class=tokenCell>{$tokens}</td>";
    //displaying last time
    $currentDateNum = strtotime($currentDate);
    $dateNum = strtotime($date);
    $timeChange = time_elapsed($currentDateNum - $dateNum);
    echo "<td id='date[{$position}]' data-time='$date' class=longCell>{$timeChange}</td>";
    echo "</tr>";
?>
