<?php
function executeScript(string $code) : void{
    echo createScript($code);
}
function includeScript(string $src) : void{
    echo createScriptFromSrc($src);
}
function setTopStyle($ddragonGeneral, $firstCodeName) : void{
    $topBackgroundStyle =
    "background: rgba(0, 0, 0, 0.5)".
    "url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg)".
    "no-repeat center 15% / cover;";
    executeScript("document.getElementById(`top`).style.cssText += '$topBackgroundStyle'");
}
function setTitle($summoner) : void{
    executeScript("document.title = '{$summoner->name} - Heimaster'");
}
function removeLogo() : void{
    executeScript("document.getElementById('logo').textContent = '';");
}
?>