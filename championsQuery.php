<?php
    $ddragonURL = "http://ddragon.leagueoflegends.com/cdn/{$gameVersion}";
    $championsURL = "{$ddragonURL}/data/en_US/champion.json";
    $championsJSON = file_get_contents($championsURL);
    $championsData = json_decode($championsJSON, 1);
?>