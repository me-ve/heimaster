<?php
    $versionURL = "https://ddragon.leagueoflegends.com/api/versions.json";
    $versionJSON = file_get_contents($versionURL);
    $versionData = json_decode($versionJSON, 1);
?>
