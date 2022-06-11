<?php
    $summonerAPI = "lol/summoner/v4/summoners/by-name";
    $summonerQuery = "{$site}/{$summonerAPI}/{$summonerName}";
    $summonerJSON = file_get_contents($summonerQuery, false, $context);
    $summonerData = json_decode($summonerJSON, 1);
?>
