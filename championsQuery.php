<?php
    $ddragonGeneral = "http://ddragon.leagueoflegends.com/cdn";
    $ddragonURL = "{$ddragonGeneral}/{$gameVersion}";
    $championsURL = "{$ddragonURL}/data/en_US/champion.json";
    $championsJSON = file_get_contents($championsURL);
    $championsData = json_decode($championsJSON, 1);
?>