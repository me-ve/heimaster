<?php

namespace View;

class ScriptHelper
{
    public static function executeScript(string $code): void
    {
        echo TagHelper::createScript($code);
    }
    public static function includeScript(string $src): void
    {
        echo TagHelper::createScriptFromSrc($src);
    }
    public static function setTopStyle($ddragonGeneral, $firstCodeName): void
    {
        $topBackgroundStyle
        = 'background: rgba(0, 0, 0, 0.5)' .
        "url({$ddragonGeneral}/img/champion/splash/{$firstCodeName}_0.jpg)" .
        'no-repeat center 15% / cover;';
        self::executeScript(
            "document.getElementById(`top`).style.cssText += '$topBackgroundStyle'"
        );
    }
    public static function setTitle($summoner): void
    {
        self::executeScript("document.title = '{$summoner->name} - Heimaster'");
    }
    public static function removeLogo(): void
    {
        self::executeScript("document.getElementById('logo').textContent = '';");
    }
}
