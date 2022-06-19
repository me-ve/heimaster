<?php
function doQuery($site, $API, $item, $context){
    $query = "{$site}/{$API}/{$item}";
    $JSON = file_get_contents($query, false, $context);
    return json_decode($JSON, true);
}
?>