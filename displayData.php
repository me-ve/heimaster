<?php
    require_once("createElements.php");
    require_once("timeElapsed.php");
    require_once("formatNumbers.php");
    if($level != 7){
        $progressToNextLevel = match($level) {
            1, 2, 3, 4 => $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel),
            default => $tokens/($level - 3)
        };
        $progressToNextLevel = display_percent($progressToNextLevel, 2);
    }
    else $progressToNextLevel = "N/A";
    $pointsFormat = display_with_separators($points);
    $avgFormat = display_percent($partOfAvg);
    $avgLogFormat = display_with_separators($partOfAvgLog, 2);
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
    $cells = [
        ["position[$position]", "$position", "positionCell"],
        ["image[$position]", create_img("", $iconURL, "championImage", $name), "championImage"],
        ["name[$position]", $name, "cell"],
        ["level[$position]", "$level", "levelCell", $levelStyle],
        ["points[$position]", "$pointsFormat", "mediumCell"],
        ["partofavg[$position]", "$avgFormat", "mediumCell"],
        ["partofavgtier[$position]", "$avgLogFormat", "smallCell"],
        ["tier[$position]", "$tier->tierSymbol", "smallCell"],
        ["progress[$position]", "$progressToNextLevel", "progressCell"],
        ["chests[$position]", $chest, "chestCell", "background-color: $chestsColor"],
        ["tokens[$position]", "$tokens", "tokenCell"]
    ];
    $cellsString = "";
    foreach ($cells as $cell){
        $cellsString .= create_td($cell[0], $cell[1], $cell[2], isset($cell[3]) ? $cell[3] : "");
    }
    $cellsString .= create_tags("td", ["id"=>"date[$position]", "data-time"=>"$date", "class"=>"longCell"], true, "$timeChange");
    echo create_tags("tr", ["id"=>"row[$position]"], true, $cellsString)
?>
