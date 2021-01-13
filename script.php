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
        
        $site = "https://{$summonerRegion}.api.riotgames.com";
        $summonerAPI = "lol/summoner/v4/summoners/by-name";
        $summonerQuery = "{$site}/{$summonerAPI}/{$summonerName}?api_key={$key}";
        $summonerJSON = file_get_contents($summonerQuery);
        $summonerData = json_decode($summonerJSON, 1);

        $summonerId = $summonerData['id'];
        $summonerLevel = $summonerData['summonerLevel'];
        
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
        $championsURL = "http://ddragon.leagueoflegends.com/cdn/11.1.1/data/en_US/champion.json";
        $championsJSON = file_get_contents($championsURL);
        $championsData = json_decode($championsJSON, 1);
        if(isset($championsData))
        {
            $champions = [];
            foreach($championsData["data"] as $champion) {
                $array = [
                    "key" => $champion["key"],
                    "name" => $champion["name"]
                ];
                array_push($champions, $array);
            }
            //displaying data
            echo "<h1>{$summonerName}, Level {$summonerLevel}</h1>";
            echo "<table><tr>";
            echo "<th>No.</th>";
            echo "<th>Champion</th>";
            echo "<th>Level</th>";
            echo "<th>Points</th>";
            echo "<th>Progress</th>";
            echo "<th>Chest</th>";
            echo "<th>Tokens</th>";
            echo "<th>Last played</th>";
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
                foreach($champions as $c)
                {
                    if($id == $c["key"])
                    {
                        $name = $c["name"];
                    }
                }
                $date = date("Y-m-d H:i", $lastPlayTime/1000);
                echo "<tr>";
                echo "<td>{$position}.</td>";
                echo "<td>{$name}</td>";
                echo "<td>{$level}</td>";
                echo "<td>{$points}</td>";
                echo "<td>{$progressToNextLevel}</td>";
                echo "<td>{$chests}</td>";
                echo "<td>{$tokens}</td>";
                echo "<td>{$date}</td>";
                echo "</tr>";
                $position++;
            }
            echo "</table>";
        }
    }
?>