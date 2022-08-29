<?php
    require_once("createElements.php");
    require_once("executeJSCode.php");
    require_once("tiers.php");
    require_once("summoner.php");
    require_once("champions.php");
    require_once("ranked.php");
    require_once("masteries.php");
    $tableHeaders = [
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
    function createTableWithHeaders($headers){
        $headersHTML = "";
        foreach($headers as $id => $name){
            $headersHTML .= createTh("{$id}[0]", $name);
        }
        $tableRows = [createTags("tr", ["id"=>"row[0]"], true, $headersHTML)];
        return $tableRows;
    }
    function createContext(){
        $options = array(
            'http' => array(
            'method' => "GET",
            'header' => 
                "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0)\n".
                "X-Riot-Token: ".$_ENV["API_KEY"]
            )
        );
        return stream_context_create($options);
    }
    function getLatestVersion(){
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
        $context = createContext();
        //getting summoner data from form
        $summonerRegion = $_GET["region"];
        $summonerName = str_replace(' ', '', $_GET["summonerName"]);
        $site = "https://{$summonerRegion}.api.riotgames.com";
        //receiving current League version
        $version = getLatestVersion();
        if($version != false){
        //receiving summoner id
        $summoner = Summoner::getFromAPI($site, $context, $summonerName);
        if($summoner != NULL){
        setTitle($summoner);
        //receiving ranks
        $rankedArray = createRankedArray($site, $context, $summoner);
        //require("mmrQuery.php");
        //receiving champions masteries for summoner
        $masteryData = MasteryData::doQuery($site, $context, $summoner);
        if(isset($masteryData))
        {
            $tableRows = createTableWithHeaders($tableHeaders);
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
        $champions = Champion::retrieve($ddragonURL, $version);
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
                "{$summonerRegion}<br>".
                createTags("h1", ["id"=>"summonerData"], true, $summoner->name);
            foreach($rankedArray as $queue){
                echo $queue."<br>";
            }
            ?>
            </td>
            <td id="searchForm">
                <?php include ("form.html"); ?>
            </td>
            </table>
            </div>
            <table id='champions'>
            <?php
            $position = 1;
            $totalPts = 0;
            $count = 0;
            $pointsArray = [];
            foreach($mastery as $champion)
            {
                $totalPts += $champion["championPoints"];
                $count++;
                array_push($pointsArray, $champion["championPoints"]);
            }
            echo $tableRows[0];
            if($count) $avgPts = $totalPts / $count;
            foreach($mastery as $champion)
            {
                $id = $champion["championId"];
                $level = $champion["championLevel"];
                $points = $champion["championPoints"];
                $partOfAvg = $points / $avgPts;
                $partOfAvgLog = log($partOfAvg, 3);
                $ptsSinceLastLevel = $champion["championPointsSinceLastLevel"];
                $ptsUntilNextLevel = $champion["championPointsUntilNextLevel"];
                $chests = $champion["chestGranted"];
                $lastPlayTime = $champion["lastPlayTime"];
                $tokens = $champion["tokensEarned"];
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
                if($position == 1){
                    $firstCodeName = $codeName;
                }
                require("displayData.php"); // TODO change the way how data are printed
                $position++;
            }
            echo "</table>";
            setTopStyle($ddragonGeneral, $firstCodeName);
            ?>
            <script src="sort.js"></script>
            <?php
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
