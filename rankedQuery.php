<?php
    $rankedAPI = "lol/league/v4/entries/by-summoner";
    $rankedQuery = "{$site}/{$rankedAPI}/{$summonerId}?api_key={$key}";
    $rankedJSON = file_get_contents($rankedQuery);
    $rankedData = json_decode($rankedJSON, 1);
?>