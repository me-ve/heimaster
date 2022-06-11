<?php
    if(isset($_GET["summonerName"], $_GET["region"]))
    {
        //change title to the Summoner name
        echo "<script>document.getElementById('logo').textContent = '';</script>";
        //setting user-agent
        ini_set('user_agent', 'Mozilla/4.0 (compatible; MSIE 6.0)');
        //initializing dotenv
        if (file_exists('vendor/autoload.php')) {
            require_once('vendor/autoload.php');
        }
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        //loading key
        $dotenv->required('API_KEY');
        $options = array(
            'http' => array(
            'method' => "GET",
            'header' => 
                "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0)\n".
                "X-Riot-Token: ".$_ENV["API_KEY"]
            )
        );
        $context = stream_context_create($options);
        //getting summoner data from form
        $summonerRegion = $_GET["region"];
        $summonerName = str_replace(' ', '', $_GET["summonerName"]);
        $site = "https://{$summonerRegion}.api.riotgames.com";
        //receiving current League version
        require("versionQuery.php");
        if(isset($versionData)){
        $gameVersion = $versionData[0];
        //receiving summoner id
        require("summonerQuery.php");
        if(isset($summonerData))
        {
        $summonerId = $summonerData['id'];
        $summonerLevel = $summonerData['summonerLevel'];
        $summonerIcon = $summonerData['profileIconId'];
        $summonerName = $summonerData['name'];
        echo "<script>document.title = '$summonerName - Mastery Tracker'</script>";
        //receiving rank
        require("rankedQuery.php");
        if(isset($rankedData))
        {
            $ranked = [];
            foreach($rankedData as $queue)
            {
                $array = [
                    "queueType" => $queue["queueType"],
                    "tier" => $queue["tier"],
                    "rank" => $queue["rank"],
                    "leaguePoints" => $queue["leaguePoints"],
                    "wins" => $queue["wins"],
                    "loses" => $queue["losses"]
                ];
                array_push($ranked, $array);
            }
        }
        //require("mmrQuery.php");
        //receiving champions masteries for summoner
        require("masteryQuery.php");
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
            foreach($championsData["data"] as $champion) {
                $array = [
                    "id" => $champion["id"],
                    "key" => $champion["key"],
                    "name" => $champion["name"],
                    "image" => $champion["image"]["full"]
                ];
                array_push($champions, $array);
            }
            $mostPlayed = $mastery[0];
                echo "<div id='top'>";
                echo "<table id='summoner' style='height: 100%; margin: auto; padding: 0px 0 0 100px;'>";
                echo "<td id='icon'>";
                echo "<img id='summonerIcon' src='{$ddragonURL}/img/profileicon/{$summonerIcon}.png'>";
                echo "<div id='summonerLevel'>";
                echo "<span id='summonerLevelSpan'>{$summonerLevel}</span></div>";
                echo "</td>";
                echo "<td>";
                echo "{$summonerRegion}<br>";
                echo "<h1 id='summonerData'>{$summonerName}</h1>";
                foreach($ranked as $queue)
                {
                    switch($queue["queueType"]){
                        case "RANKED_SOLO_5x5": $type = "Solo"; break;
                        case "RANKED_FLEX_SR": $type = "Flex"; break;
                    }
                    $tier = ucfirst(strtolower($queue['tier']));
                    $rank  = $queue["rank"];
                    $LP = $queue["leaguePoints"];
                    $rankDisplay = "{$type}: {$tier} {$rank} ({$LP}LP)<br>";
                    echo "{$rankDisplay} ";
                }
                ?>
                </td>
                <td id="searchForm">
                    <?php include ("form.html"); ?>
                </td>
            </table>
            </div>
            <table id='champions'>
                <tr id='row[0]'>
                    <th id='position[0]'>#</th>
                    <th id='image[0]'></th>
                    <th id='name[0]'>Champion</th>
                    <th id='level[0]'>Level</th>
                    <th id='points[0]'>Points</th>
                    <th id='partofavg[0]'>% of average</th>
                    <th id='partofavgtier[0]'>Tier Score</th>
                    <th id='tier[0]'>Tier</th>
                    <th id='progress[0]'>Progress</th>
                    <th id='chests[0]'>Chest</th>
                    <th id='tokens[0]'>Tokens</th>
                    <th id='date[0]'>Last played</th>
                </tr>
            <?php
            $position = 1;
            $totalPts = 0;
            $count = 0;
            $pointsArray = [];

            $tierLetters = array(
                3 => "S+",
                2 => "S",
                1 => "A",
                0 => "B",
                -1 => "C",
                -2 => "D",
                -3 => "D-"
            );
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
                $logBase = 3;
                $partOfAvgLog = log($partOfAvg, $logBase);
                $partOfAvg = round($partOfAvg, 2)*100;
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
                    if($id == $c["key"])
                    {
                        $codeName = $c["id"];
                        $name = $c["name"];
                        $icon = $c["image"];
                        $iconURL = "{$ddragonURL}/img/champion/{$icon}";
                    }
                }
                if($position == 1){
                    $firstCodeName = $codeName;
                }
                require("displayData.php");
                $position++;
            }
            echo "</table>";
            echo "<script>document.getElementById(`top`).style = `width: 1215px; margin: auto; background: url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg) no-repeat center 15% / cover; height:360px; border-radius: 5px;`</script>";
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
