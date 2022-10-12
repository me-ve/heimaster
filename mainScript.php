<?php
    require_once("streamContext.php");
    require_once("createElements.php");
    require_once("executeCode.php");
    require_once("tiers.php");
    require_once("summoner.php");
    require_once("champions.php");
    require_once("ranked.php");
    require_once("masteries.php");
    require_once("colors.php");
    require_once("mainTable.php");
    require_once("timeElapsed.php");
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
    function getLatestVersion() : string{
        $versionURL = "https://ddragon.leagueoflegends.com/api/versions.json";
        $versionJSON = file_get_contents($versionURL);
        $versionData = json_decode($versionJSON, 1);
        if(!isset($versionData)) return false;
        return $versionData[0];
    }
    if(isset($_GET["summonerName"], $_GET["region"]))
    {
        removeLogo();
        //initializing dotenv
        if (file_exists('vendor/autoload.php')) {
            require_once('vendor/autoload.php');
        }
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        //loading key
        $dotenv->required('API_KEY');
        $context = StreamContext::getContext($_ENV["API_KEY"]);
        //getting summoner data from form
        $summonerRegion = $_GET["region"];
        $summonerName = str_replace(' ', '', $_GET["summonerName"]);
        $site = "https://{$summonerRegion}.api.riotgames.com";
        //receiving current League version
        $version = getLatestVersion();
        if($version != false){
        //receiving summoner id
        $summoner = Summoner::getFromAPI($site, $context, $_GET["region"], $summonerName);
        if($summoner != NULL){
            setTitle($summoner);
            //receiving ranks
            $rankedArray = createRankedArray($site, $context, $summoner);
            //require("mmrQuery.php");
            //receiving champions masteries for summoner
            $masteryData = MasteryData::doQuery($site, $context, $summoner);
            if(isset($masteryData))
            {
                $table = new MainTable();
                $mastery = [];
                $counter = 0;
                foreach($masteryData as $masteryRow) {
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
            };
            //getting list of champions names
            $ddragonGeneral = "http://ddragon.leagueoflegends.com/cdn";
            $ddragonURL = "{$ddragonGeneral}/{$version}";
            $champions = Champion::retrieve($ddragonURL);
            if($champions != false)
            {
                $mostPlayed = $mastery[0];
                echo
                    "<div id='top'>".
                    "<table id='summoner'>";
                $iconTd = $summoner->createIconTd($ddragonURL);
                echo
                    $iconTd.
                    "<td>".
                    $summonerRegion.createBr().
                    createTags("h1", ["id"=>"summonerData"], true, $summoner->name);
                $queueBrs = join(createBr(), $rankedArray);
                echo $queueBrs."</td>";
                ?>
                <td id="searchForm">
                    <?php include ("form.html"); ?>
                </td>
                </table>
                </div>
                <?php
                $position = 1;
                $totalPts = 0;
                $count = 0;
                foreach($mastery as $champion)
                {
                    $totalPts += $champion["championPoints"];
                    $count++;
                }
                if($count) $avgPts = $totalPts / $count;
                foreach($mastery as $champion)
                {
                    $id = $champion["championId"];
                    $masteryData = new MasteryData(
                        $champion["championLevel"],
                        $champion["championPoints"],
                        $champion["championPointsSinceLastLevel"],
                        $champion["championPointsUntilNextLevel"],
                        $champion["lastPlayTime"],
                        $champion["chestGranted"],
                        $champion["tokensEarned"]
                    );
                    $partOfAvg = $masteryData->points / $avgPts;
                    $tierBase = Tier::tierBase($partOfAvg);
                    $name = "";
                    $iconURL = "";
                    $codeName = "";
                    foreach($champions as $c)
                    {
                        if($id == $c->key)
                        {
                            $codeName = $c->id;
                            $name = $c->name;
                            $icon = $c->image;
                            $iconURL = "{$ddragonURL}/img/champion/{$icon}";
                        }
                    }
                    $progress = $masteryData->progressString();
                    if($position == 1){
                        $firstCodeName = $codeName;
                    }
                    $avgFormat = displayPercent($partOfAvg);
                    $tier = new Tier($partOfAvg, $masteryData);
                    $levelStyle = ($masteryData->level >= 5) ? "color: ".COLORS["gold"] : "";
                    $chestsColor = $masteryData->chest ? COLORS["gold"] : COLORS["dark_grey_blue"];
                    //displaying last time
                    $timeChange = $masteryData->timeChange($currentDate);
                    $cells = [
                        ["position[$position]", "$position", "positionCell"],
                        ["image[$position]", createImg("", $iconURL, "championImage", $name), "championImage"],
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
                    $row = createTdArray($cells);
                    array_push($row,
                        createTags("td", [
                            "id"=>"date[$position]",
                            "data-time"=>$masteryData->lastPlayedDateString(),
                            "class"=>"longCell"],
                            true, "$timeChange"));
                    $table->AddRow($row);
                    $position++;
                }
                echo $table;
                setTopStyle($ddragonGeneral, $firstCodeName);
                echo createScriptFromSrc("sort.js");
            }
        }
        else
        {
            include ("form.html");
            echo "Please input the valid Summoner name.";
        }
    }
}
?>
