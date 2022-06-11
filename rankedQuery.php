<?php
    $rankedAPI = "lol/league/v4/entries/by-summoner";
    $rankedQuery = "{$site}/{$rankedAPI}/{$summonerId}";
    $rankedJSON = file_get_contents($rankedQuery, false, $context);
    $rankedData = json_decode($rankedJSON, 1);
?>
