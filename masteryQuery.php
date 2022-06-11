<?php
    $masteryAPI = "lol/champion-mastery/v4/champion-masteries/by-summoner";
    $masteryQuery = "{$site}/{$masteryAPI}/{$summonerId}";
    $masteryJSON = file_get_contents($masteryQuery, false, $context);
    $masteryData = json_decode($masteryJSON, 1);
?>
