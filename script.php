<?php
    if(isset($_GET["summonerName"]))
    {
        //this file is created only for storing Riot API key
        //if you want to use this tracker you need to retrieve your own
        //and store it in the $key variable
        //$key="";
        require("key.php");
        
        //receiving summoner id
        //todo: changing regions
        $summonerRegion = "eun1";
        $summonerName = $_GET["summonerName"];
        $gameVersion = "11.1.1";
        
        $site = "https://{$summonerRegion}.api.riotgames.com";
        $summonerAPI = "lol/summoner/v4/summoners/by-name";
        $summonerQuery = "{$site}/{$summonerAPI}/{$summonerName}?api_key={$key}";
        $summonerJSON = file_get_contents($summonerQuery);
        $summonerData = json_decode($summonerJSON, 1);

        $summonerId = $summonerData['id'];
        $summonerLevel = $summonerData['summonerLevel'];
        $summonerIcon = $summonerData['profileIconId'];
        
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
            echo "<table id='summoner'>";
            echo "<td><img id='summonerIcon' style='width: 60px;height: 60px;border-radius: 10px;' src='{$ddragonURL}/img/profileicon/{$summonerIcon}.png'></td>";
            echo "<td><h1 id='summonerData' style='margin: auto;'>{$summonerName}, Level {$summonerLevel}</h1></td>";
            echo "</table>";
            echo "<table id='champions'><tr id='row0'>";
            echo "<th id='position0'>#</th>";
            echo "<th id='name0'>Champion</th>";
            echo "<th id='image0'></th>";
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
                if($chests) $chests = "yes";
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
                echo "<td id='position{$position}' class=cell>{$position}</td>";
                echo "<td id='name{$position}' class=cell>{$name}</td>";
                echo "<td id='image{$position}'><img src='{$iconURL}'style='width: 40px; height: 40px; margin: auto;' alt='{$name}'></td>";
                echo "<td id='level{$position}' class=cell>{$level}</td>";
                echo "<td id='points{$position}' class=cell>{$pointsFormat}</td>";
                echo "<td id='progress{$position}' class=cell>{$progressToNextLevel}</td>";
                echo "<td id='chests{$position}' class=cell>{$chests}</td>";
                echo "<td id='tokens{$position}' class=cell>{$tokens}</td>";
                echo "<td id='date{$position}' class=cell>{$date}</td>";
                echo "</tr>";
                $position++;
            }
            echo "</table>";
        }
    }
?>