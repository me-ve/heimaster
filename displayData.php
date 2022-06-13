<?php
    require_once("createElements.php");
    require_once("timeElapsed.php");
    if($level != 7){
        $progressToNextLevel = match($level) {
            1, 2, 3, 4 => $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel),
            default => $tokens/($level - 3)
        };
        $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
    }
    else $progressToNextLevel = "N/A";
    $pointsFormat = number_format($points, 0, ".", ",");
    $avgFormat = number_format($partOfAvg, 0, ".", ",");
    $avgLogFormat = number_format($partOfAvgLog, 2, ".", ",");
    $tier = new Tier($partOfAvgLog);
    $date = date("Y-m-d H:i:s", $lastPlayTime/1000);
    $d = new DateTime($date);
    $levelStyle = "";
    if($level >= 5) $levelStyle = "color: #ceb572";
    $chestsColor = $chests ? "#ceb572" : "#6c7b8b";
    $chest = $chests ? "yes" : "no";
    //displaying last time
    $currentDateNum = strtotime($currentDate);
    $dateNum = strtotime($date);
    $timeChange = time_elapsed($currentDateNum - $dateNum);
    $cells = 
        create_td("position[$position]", "$position", "positionCell").
        create_td("image[$position]", create_img("", $iconURL, "championImage", $name), "championImage").
        create_td("name[$position]", $name, "cell").
        create_td("level[$position]", "$level", "levelCell", $levelStyle).
        create_td("points[$position]", "$pointsFormat", "mediumCell").
        create_td("partofavg[$position]", "$avgFormat%", "mediumCell").
        create_td("partofavgtier[$position]", "$avgLogFormat", "smallCell").
        create_td("tier[$position", "$tier->tierSymbol", "smallCell").
        create_td("progress[$position]", "$progressToNextLevel", "progressCell").
        create_td("chests[$position]", $chest, "chestCell", "background-color: $chestsColor").
        create_td("tokens[$position]", "$tokens", "tokenCell").
        create_tags("td", ["id"=>"date[$position]", "data-time"=>"$date", "class"=>"longCell"], true, "$timeChange");
    echo create_tags("tr", ["id"=>"row[$position]"], true, $cells)
?>
