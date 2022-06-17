<?php
    require("createElements.php");
    require("executeJSCode.php");
    require("tiers.php");
    require_once("summoner.php");
    require_once("champions.php");
    require_once("ranked.php");
    $tableHeaders = [
        "position" => "#",
        "image" => "Icon",
        "name" => "Champion",
        "level" => "Level",
        "points" => "Points",
        "partofavg" => "% of average",
        "partofavgtier" => "Tier Score",
        "tier" => "Tier",
        "progress" => "Progress",
        "chests" => "Chest",
        "tokens" => "Tokens",
        "date" => "Last played",
    ];
    function create_context(){
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
    function set_title(){
        execute_JS_code("document.title = '{$summoner->name} - Mastery Tracker'");
    }
    function get_latest_version(){
        $versionURL = "https://ddragon.leagueoflegends.com/api/versions.json";
        $versionJSON = file_get_contents($versionURL);
        $versionData = json_decode($versionJSON, 1);
        if(!isset($versionData)) return false;
        return $versionData[0];
    }
    if(isset($_GET["summonerName"], $_GET["region"]))
    {
        //import function to make queries from APIs
        require("doQuery.php");
        //change title to the Summoner name
        execute_JS_code("document.getElementById('logo').textContent = '';");
        //initializing dotenv
        if (file_exists('vendor/autoload.php')) {
            require_once('vendor/autoload.php');
        }
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        //loading key
        $dotenv->required('API_KEY');
        $context = create_context();
        //getting summoner data from form
        $summonerRegion = $_GET["region"];
        $summonerName = str_replace(' ', '', $_GET["summonerName"]);
        $site = "https://{$summonerRegion}.api.riotgames.com";
        //receiving current League version
        $version = get_latest_version();
        if($version != false){
        //receiving summoner id
        $summonerData = do_query(
            $site, "lol/summoner/v4/summoners/by-name", $summonerName, $context
        );
        if(isset($summonerData))
        {
            $summoner = new Summoner(
                $summonerData['id'],
                $_GET["region"],
                $summonerData['name'],
                $summonerData['profileIconId'],
                $summonerData['summonerLevel'],
                array()
            );
        //receiving rank
        $rankedData = do_query(
            $site, "lol/league/v4/entries/by-summoner", $summoner->id, $context
        );
        if(isset($rankedData))
        {
            $ranked = [];
            foreach($rankedData as $queue)
            {
                $rankedQueue = new Ranked(
                    $queue["queueType"],
                    $queue["tier"],
                    $queue["rank"],
                    $queue["leaguePoints"],
                    $queue["wins"],
                    $queue["losses"]
                );
                array_push($ranked, $rankedQueue);
            }
        }
        //require("mmrQuery.php");
        //receiving champions masteries for summoner
        $masteryData = do_query(
            $site, "lol/champion-mastery/v4/champion-masteries/by-summoner", $summoner->id, $context
        );
        if(isset($masteryData))
        {
            $mastery = [];
            $counter = 0;
            foreach($masteryData as $champion) {
                $array = [
                    "championId" => $champion["championId"],
                    "championLevel" => $champion["championLevel"],
                    "championPoints" => $champion["championPoints"],
                    "lastPlayTime" => $champion["lastPlayTime"],
                    "championPointsSinceLastLevel" => $champion["championPointsSinceLastLevel"],
                    "championPointsUntilNextLevel" => $champion["championPointsUntilNextLevel"],
                    "chestGranted" => $champion["chestGranted"],
                    "tokensEarned" => $champion["tokensEarned"]
                ];
                array_push($mastery, $array);
                $counter++;
            }
        };
        //getting list of champions names
        require("championsQuery.php");
        if(isset($championsData))
        {
            $champions = [];
            foreach($championsData["data"] as $championArray) {
                $champion = new Champion(
                    $championArray["id"],
                    $championArray["key"],
                    $championArray["name"],
                    $championArray["image"]["full"]
                );
                array_push($champions, $champion);
            }
            $mostPlayed = $mastery[0];
            echo
                "<div id='top'>".
                "<table id='summoner'>";
            $iconTd = $summoner->create_icon_td($ddragonURL);
            echo
                $iconTd.
                "<td>".
                "{$summonerRegion}<br>".
                create_tags("h1", ["id"=>"summonerData"], true, $summoner->name);
            foreach($ranked as $queue){
                echo $queue."<br>";
            }
            ?>
            </td>
            <td id="searchForm">
                <?php include ("form.html"); ?>
            </td>
            </table>
            </div>
            <?php
            
            ?>
            <table id='champions'>
                <?php
                    $headersHTML = "";
                    foreach($tableHeaders as $id => $name){
                        $headersHTML .= create_th("{$id}[0]", $name);
                    }
                    echo create_tags("tr", ["id"=>"row[0]"], true, $headersHTML);
                ?>
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
            $topBackgroundStyle =
            "background: rgba(0, 0, 0, 0.5)".
            "url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg)".
            "no-repeat center 15% / cover;";
            execute_JS_code("document.getElementById(`top`).style.cssText += '$topBackgroundStyle'");
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
