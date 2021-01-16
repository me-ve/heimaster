<?php
    $masteryAPI = "lol/champion-mastery/v4/champion-masteries/by-summoner";
    $masteryQuery = "{$site}/{$masteryAPI}/{$summonerId}?api_key={$key}";
    $masteryJSON = file_get_contents($masteryQuery);
    $masteryData = json_decode($masteryJSON, 1);
?>