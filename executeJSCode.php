<?php
function executeJSCode(string $code){
    echo "<script>$code</script>";
}
function setTopStyle($ddragonGeneral, $firstCodeName){
    $topBackgroundStyle =
    "background: rgba(0, 0, 0, 0.5)".
    "url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg)".
    "no-repeat center 15% / cover;";
    executeJSCode("document.getElementById(`top`).style.cssText += '$topBackgroundStyle'");
}
function setTitle($summoner){
    executeJSCode("document.title = '{$summoner->name} - Heimaster'");
}
function removeLogo(){
    executeJSCode("document.getElementById('logo').textContent = '';");
}
?>