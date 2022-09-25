<?php
function executeScript(string $code){
    echo createScript($code);
}
function includeScript(string $src){
    echo createScriptFromSrc($src);
}
function setTopStyle($ddragonGeneral, $firstCodeName){
    $topBackgroundStyle =
    "background: rgba(0, 0, 0, 0.5)".
    "url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg)".
    "no-repeat center 15% / cover;";
    executeScript("document.getElementById(`top`).style.cssText += '$topBackgroundStyle'");
}
function setTitle($summoner){
    executeScript("document.title = '{$summoner->name} - Heimaster'");
}
function removeLogo(){
    executeScript("document.getElementById('logo').textContent = '';");
}
?>