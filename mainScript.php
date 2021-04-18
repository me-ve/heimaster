<?php
    if(isset($_GET["summonerName"])&&isset($_GET["region"]))
    {
        //this file is created only for storing Riot API key
        //if you want to use this tracker you need to retrieve your own
        //and store it in the $key variable
        //TODO implement MVC to insert the key and the other non-constant data outside of code
        require("key.php");
        $summonerRegion = $_GET["region"];
        $summonerName = str_replace(' ', '', $_GET["summonerName"]);
        $site = "https://{$summonerRegion}.api.riotgames.com";
        $gameVersion = "11.8.1";
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
                    "loses" => $queue["loses"]
                ];
                array_push($ranked, $array);
            }
        }
        require("mmrQuery.php");
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
                    "key" => $champion["key"],
                    "name" => $champion["name"],
                    "image" => $champion["image"]["full"]
                ];
                array_push($champions, $array);
            }
            ?>
            <table id='summoner'>
                <td id='icon'>
                <?php
                echo "<img id='summonerIcon' src='{$ddragonURL}/img/profileicon/{$summonerIcon}.png'>";
                echo "<div id='summonerLevel'>";
                echo "<span id='summonerLevelSpan'>{$summonerLevel}</span></div>";
                echo "</td>";
                echo "<td>";
                echo "{$summonerRegion}<br>";
                echo "<h1 id='summonerData'>{$summonerName}</h1>";
                foreach($ranked as $queue)
                {
                    if($queue["queueType"] == "RANKED_SOLO_5x5") $type = "Solo";
                    if($queue["queueType"] == "RANKED_FLEX_SR") $type = "Flex";
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
            
            <table id='champions'>
                <tr id='row0'>
                    <th id='position0'>#</th>
                    <th id='image0'></th>
                    <th id='name0'>Champion</th>
                    <th id='level0'>Level</th>
                    <th id='points0'>Points</th>
                    <th id='partofavg0'>% of average</th>
                    <th id='partofavgtier0'>Tier Score</th>
                    <th id='tier0'>Tier</th>
                    <th id='progress0'>Progress</th>
                    <th id='chests0'>Chest</th>
                    <th id='tokens0'>Tokens</th>
                    <th id='date0'>Last played</th>
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
                foreach($champions as $c)
                {
                    if($id == $c["key"])
                    {
                        $name = $c["name"];
                        $icon = $c["image"];
                        $iconURL = "{$ddragonURL}/img/champion/{$icon}";
                    }
                }
                require("displayData.php");
                $position++;
            }
            ?>
            </table>
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
?>