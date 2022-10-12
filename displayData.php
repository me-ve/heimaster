<?php
    require_once("createElements.php");
    require_once("timeElapsed.php");
    require_once("formatNumbers.php");
    require_once("colors.php");
    if($level != 7){
        $progressToNextLevel = match($level) {
            1, 2, 3, 4 => $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel),
            default => $tokens/($level - 3)
        };
        $progressToNextLevel = displayPercent($progressToNextLevel, 2);
    }
    else $progressToNextLevel = "N/A";
    $pointsFormat = displayWithSeparators($points);
    $avgFormat = displayPercent($partOfAvg);
    $tierScore = Tier::calculateTierScore($tierBase, $level, $tokens);
    $tierScoreFormat = displayWithSeparators($tierScore, 2);
    $tier = new Tier($tierScore);
    $date = date("Y-m-d H:i:s", $lastPlayTime/1000);
    $d = new DateTime($date);
    $levelStyle = ($level >= 5) ? "color: ".COLORS["gold"] : "";
    $chestsColor = $chests ? COLORS["gold"] : COLORS["dark_grey_blue"];
    $chest = $chests ? "yes" : "no";
    //displaying last time
    $currentDateNum = strtotime($currentDate);
    $dateNum = strtotime($date);
    $timeChange = timeElapsed($currentDateNum - $dateNum);
    $cells = [
        ["position[$position]", "$position", "positionCell"],
        ["image[$position]", createImg("", $iconURL, "championImage", $name), "championImage"],
        ["name[$position]", $name, "cell"],
        ["level[$position]", "$level", "levelCell", $levelStyle],
        ["points[$position]", "$pointsFormat", "mediumCell"],
        ["partofavg[$position]", "$avgFormat", "mediumCell"],
        ["tierscore[$position]", "$tierScoreFormat", "smallCell"],
        ["tier[$position]", "$tier->tierSymbol", "smallCell"],
        ["progress[$position]", "$progressToNextLevel", "progressCell"],
        ["chests[$position]", $chest, "chestCell", "background-color: $chestsColor"],
        ["tokens[$position]", "$tokens", "tokenCell"]
    ];
    $cellsString = "";
    foreach ($cells as $cell){
        $cellsString .= createTd($cell[0], $cell[1], $cell[2], isset($cell[3]) ? $cell[3] : "");
    }
    $cellsString .= createTags("td", ["id"=>"date[$position]", "data-time"=>"$date", "class"=>"longCell"], true, "$timeChange");
    echo createTags("tr", ["id"=>"row[$position]"], true, $cellsString)
?>
