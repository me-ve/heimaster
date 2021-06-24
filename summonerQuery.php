<?php
    $summonerAPI = "lol/summoner/v4/summoners/by-name";
    $summonerQuery = "{$site}/{$summonerAPI}/{$summonerName}?api_key={$key}";
    $summonerJSON = file_get_contents($summonerQuery);
    $summonerData = json_decode($summonerJSON, 1);
?>
