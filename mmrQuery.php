<?php
//TODO display MMR on page
/* mmr getting, currently hidden, works for Europe and North America only
    switch($summonerRegion){
        case "NA1": $mmrRegion = "na"; break;
        case "EUN1": $mmrRegion = "eune"; break;
        case "EUW1": $mmrRegion = "euw"; break;
        default: $mmrRegion = null;
    }
    if($mmrRegion != null){
        $mmrURL = "https://{$mmrRegion}.whatismymmr.com";
        $mmrAPI = "api/v1/summoner";
        $mmrQuery = "{$mmrURL}/{$mmrAPI}?name={$summonerName}";
        echo "<script>alert('$mmrQuery')</script>";
        $mmrJSON = file_get_contents($mmrQuery);
        $mmrData = json_decode($mmrJSON, 1);
        echo "<script>alert('$mmrJSON')</script>";
    };
*/
?>