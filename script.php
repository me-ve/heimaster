<?php
    if(isset($_GET["summonerName"])&&isset($_GET["region"]))
    {
        //this file is created only for storing Riot API key
        //if you want to use this tracker you need to retrieve your own
        //and store it in the $key variable
        //$key="";
        require("key.php");
        
        //receiving summoner id
        $summonerRegion = $_GET["region"];
        $summonerName = $_GET["summonerName"];
        $gameVersion = "11.1.1";
        
        $site = "https://{$summonerRegion}.api.riotgames.com";
        $summonerAPI = "lol/summoner/v4/summoners/by-name";
        $summonerQuery = "{$site}/{$summonerAPI}/{$summonerName}?api_key={$key}";
        $summonerJSON = file_get_contents($summonerQuery);
        $summonerData = json_decode($summonerJSON, 1);
        if(isset($summonerData))
        {
        $summonerId = $summonerData['id'];
        $summonerLevel = $summonerData['summonerLevel'];
        $summonerIcon = $summonerData['profileIconId'];

        //receiving rank
        $rankedAPI = "lol/league/v4/entries/by-summoner";
        $rankedQuery = "{$site}/{$rankedAPI}/{$summonerId}?api_key={$key}";
        $rankedJSON = file_get_contents($rankedQuery);
        $rankedData = json_decode($rankedJSON, 1);
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
        //receiving champions masteries for summoner
        $masteryAPI = "lol/champion-mastery/v4/champion-masteries/by-summoner";
        $masteryQuery = "{$site}/{$masteryAPI}/{$summonerId}?api_key={$key}";
        $masteryJSON = file_get_contents($masteryQuery);
        $masteryData = json_decode($masteryJSON, 1);
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
        $ddragonURL = "http://ddragon.leagueoflegends.com/cdn/{$gameVersion}";
        $championsURL = "{$ddragonURL}/data/en_US/champion.json";
        $championsJSON = file_get_contents($championsURL);
        $championsData = json_decode($championsJSON, 1);
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
            //displaying data
            echo "<table id='summoner' style='width:100%'>";
            echo "<td id='icon' style='width:75px; height:75px; padding-right:10px;'>";
            echo "<img id='summonerIcon' src='{$ddragonURL}/img/profileicon/{$summonerIcon}.png'>";
            echo "<div id='summonerLevel' style='top: 100%; left: 50%; width:75px; text-align:center; float:left;'>";
            echo "<span style='background-color:black; border-radius:3px; padding: 6px 6px 0px 6px;'>{$summonerLevel}</span></div>";
            echo "</td>";
            echo "<td><h1 id='summonerData' style='text-align:left; margin-top:auto; margin-bottom:auto;'>{$summonerName} ({$summonerRegion})</h1>";
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
            echo "</td>";
            echo "<td style='width: 470px'>";
            include ("form.html");
            echo "</td>";
            echo "</table>";
            echo "<table id='champions' style='width:100%'><tr id='row0'>";
            echo "<th id='position0'>#</th>";
            echo "<th id='image0'></th>";
            echo "<th id='name0'>Champion</th>";
            echo "<th id='level0'>Level</th>";
            echo "<th id='points0'>Points</th>";
            echo "<th id='progress0'>Progress</th>";
            echo "<th id='chests0'>Chest</th>";
            echo "<th id='tokens0'>Tokens</th>";
            echo "<th id='date0'>Last played</th>";
            echo "</tr>";
            $position = 1;
            foreach($mastery as $champion)
            {
                $id = $champion["championId"];
                $level = $champion["championLevel"];
                $points = $champion["championPoints"];
                $ptsSinceLastLevel = $champion["championPointsSinceLastLevel"];
                $ptsUntilNextLevel = $champion["championPointsUntilNextLevel"];
                $chests = $champion["chestGranted"];
                $lastPlayTime = $champion["lastPlayTime"];
                $tokens = $champion["tokensEarned"];
                if($level<5)
                {
                    $progressToNextLevel = $ptsSinceLastLevel/($ptsSinceLastLevel+$ptsUntilNextLevel);
                    $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
                }
                else if($level == 5)
                {
                    $progressToNextLevel = $tokens/2;
                    $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
                }
                else if($level == 6)
                {
                    $progressToNextLevel = $tokens/3;
                    $progressToNextLevel = (round($progressToNextLevel, 2) * 100)."%";
                }
                else
                {
                    $progressToNextLevel = "N/A";
                }
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
                $pointsFormat = number_format($points, 0, 0, ",");
                $date = date("Y-m-d H:i", $lastPlayTime/1000);
                echo "<tr id='row{$position}'>";
                echo "<td id='position{$position}' style='width:60px' class=cell>{$position}</td>";
                echo "<td id='image{$position}' class=championimage><img src='{$iconURL}' class='championimage' alt='{$name}'></td>";
                echo "<td id='name{$position}' class=cell>{$name}</td>";
                echo "<td id='level{$position}' style='width:60px' class=cell>{$level}</td>";
                echo "<td id='points{$position}' style='width:240px' class=cell>{$pointsFormat}</td>";
                echo "<td id='progress{$position}' style='width:60px' class=cell>{$progressToNextLevel}</td>";
                if($chests)
                {
                    echo "<td id='chests{$position}' style='background-color: #ceb572; width:60px' class=cell>yes</td>";
                }
                else
                {
                    echo "<td id='chests{$position}' style='background-color: #6c7b8b; width:60px' class=cell>no</td>";
                }
                echo "<td id='tokens{$position}' style='width:60px' class=cell>{$tokens}</td>";
                echo "<td id='date{$position}' style='width:240px' class=cell>{$date}</td>";
                echo "</tr>";
                $position++;
            }
            echo "</table>";
        }
    }
    else
    {
        include ("form.html");
        echo "Please input the valid Summoner name.";
    }
}
?>