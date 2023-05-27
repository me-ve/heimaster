<?php

require_once "controllers/streamContext.php";

require_once "models/api.php";
require_once "models/champion.php";
require_once "models/masteryData.php";
require_once "models/rankedData.php";
require_once "models/summoner.php";
require_once "models/tier.php";

require_once "views/colors.php";
require_once "views/mainTable.php";
require_once "views/helpers/number.php";
require_once "views/helpers/script.php";
require_once "views/helpers/tag.php";
require_once "views/helpers/time.php";

const TABLE_HEADERS = [
    "position" => "#",
    "image" => "Icon",
    "name" => "Champion",
    "level" => "Level",
    "points" => "Points",
    "partofavg" => "% of average",
    "tierscore" => "Tier Score",
    "tier" => "Tier",
    "progress" => "Progress",
    "chests" => "Chest",
    "tokens" => "Tokens",
    "date" => "Last played",
];
function getLatestVersion(): string
{
    $versionURL = "https://ddragon.leagueoflegends.com/api/versions.json";
    $versionJSON = file_get_contents($versionURL);
    $versionData = json_decode($versionJSON, 1);
    if (!isset($versionData)) {
        return false;
    }
    return $versionData[0];
}
if (!isset($_GET["summonerName"], $_GET["region"])) {
    include "form.html";
    echo "Please input the valid Summoner name.";
    exit();
}
View\ScriptHelper::removeLogo();
//initializing dotenv
if (file_exists('vendor/autoload.php')) {
    include_once 'vendor/autoload.php';
}
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
//loading key
$dotenv->required('API_KEY');
$context = Controller\StreamContext::getContext($_ENV["API_KEY"]);
//getting summoner data from form
$summonerRegion = $_GET["region"];
$summonerName = str_replace(' ', '', $_GET["summonerName"]);

$site = "https://{$summonerRegion}.api.riotgames.com";

//receiving current League version
$version = getLatestVersion();

if (!$version) {
    exit("No version available.");
}

//receiving summoner id
$summoner = Model\Summoner::getFromAPI($site, $context, $_GET["region"], $summonerName);
if ($summoner == null) {
    exit("Summoner {$summonerRegion} {$summonerName} doesn't exist");
}

View\ScriptHelper::setTitle($summoner);

//receiving ranks
$rankedArray = Model\Ranked::createArray($site, $context, $summoner);

//receiving champions masteries for summoner
$masteryData = Model\MasteryData::doQuery($site, $context, $summoner);
if (!isset($masteryData)) {
    exit("No mastery data.");
}

$table = new View\MainTable();
$mastery = [];
$counter = 0;

foreach ($masteryData as $masteryRow) {
    $array = [
    "championId" => $masteryRow["championId"],
    "championLevel" => $masteryRow["championLevel"],
    "championPoints" => $masteryRow["championPoints"],
    "lastPlayTime" => $masteryRow["lastPlayTime"],
    "championPointsSinceLastLevel" => $masteryRow["championPointsSinceLastLevel"],
    "championPointsUntilNextLevel" => $masteryRow["championPointsUntilNextLevel"],
    "chestGranted" => $masteryRow["chestGranted"],
    "tokensEarned" => $masteryRow["tokensEarned"]
    ];
    array_push($mastery, $array);
    $counter++;
}

//getting list of champions names
$ddragonGeneral = "http://ddragon.leagueoflegends.com/cdn";
$ddragonURL = "{$ddragonGeneral}/{$version}";
$champions = Model\Champion::retrieve($ddragonURL);
if (!$champions) {
    exit("No champions.");
}
$mostPlayed = $mastery[0];
?>

<div id='top'>
<table id='summoner'>
    <?=
    $summoner->createIconTd($ddragonURL) .
    "<td>" .
    $summonerRegion . View\TagHelper::createBr() .
    View\TagHelper::createTags(
        "h1",
        [
            "id" => "summonerData"
        ],
        true,
        $summoner->name
    ) .
    join(
        View\TagHelper::createBr(),
        $rankedArray
    ) . "</td>"
    ?>
    <td id="searchForm">
    <?php include "form.html"; ?>
    </td>
    </table>
    </div>
    <?php
    $position = 1;
    $totalPts = 0;
    $count = 0;
    foreach ($mastery as $champion) {
        $totalPts += $champion["championPoints"];
        $count++;
    }
    if ($count) {
        $avgPts = $totalPts / $count;
    }
    foreach ($mastery as $champion) {
        $id = $champion["championId"];
        $masteryData = new Model\MasteryData(
            $champion["championLevel"],
            $champion["championPoints"],
            $champion["championPointsSinceLastLevel"],
            $champion["championPointsUntilNextLevel"],
            $champion["lastPlayTime"],
            $champion["chestGranted"],
            $champion["tokensEarned"]
        );
        $partOfAvg = $masteryData->points / $avgPts;
        $tierBase = Model\Tier::tierBase($partOfAvg);
        $name = "";
        $iconURL = "";
        $codeName = "";
        foreach ($champions as $c) {
            if ($id == $c->key) {
                $codeName = $c->id;
                $name = $c->name;
                $icon = $c->image;
                $iconURL = "{$ddragonURL}/img/champion/{$icon}";
            }
        }
        $progress = $masteryData->progressString();
        if ($position == 1) {
            $firstCodeName = $codeName;
        }
        $avgFormat = View\NumberHelper::displayPercent($partOfAvg);
        $tier = new Model\Tier($partOfAvg, $masteryData);
        $levelStyle = ($masteryData->level >= 5) ? "color: " . View\COLORS["gold"] : "";
        $chestsColor = $masteryData->chest ? View\COLORS["gold"] : View\COLORS["dark_grey_blue"];
        //displaying last time
        $timeChange = $masteryData->timeChange($currentDate);
        $cells = [
        ["position[$position]", "$position", "positionCell"],
        ["image[$position]", View\TagHelper::createImg("", $iconURL, "championImage", $name), "championImage"],
        ["name[$position]", $name, "cell"],
        ["level[$position]", "{$masteryData->level}", "levelCell", $levelStyle],
        ["points[$position]", "{$masteryData->pointsString()}", "mediumCell"],
        ["partofavg[$position]", $avgFormat, "mediumCell"],
        ["tierscore[$position]", $tier->tierScoreString(), "smallCell"],
        ["tier[$position]", $tier->tierSymbol, "smallCell"],
        ["progress[$position]", $masteryData->progressString(), "progressCell"],
        ["chests[$position]", $masteryData->chestString(), "chestCell", "background-color: $chestsColor"],
        ["tokens[$position]", $masteryData->tokenString(), "tokenCell"]
        ];
        $row = View\TagHelper::createTdArray($cells);
        array_push(
            $row,
            View\TagHelper::createTags(
                "td",
                [
                    "id" => "date[$position]",
                    "data-time" => $masteryData->lastPlayedDateString(),
                    "class" => "longCell"
                ],
                true,
                "$timeChange"
            )
        );
        $table->AddRow($row);
        $position++;
    }
    echo $table;
    View\ScriptHelper::setTopStyle($ddragonGeneral, $firstCodeName);
    echo View\TagHelper::createScriptFromSrc("sort.js");
