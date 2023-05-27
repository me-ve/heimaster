<?php

namespace Model;

class Summoner
{
    public const API = "lol/summoner/v4/summoners/by-name";
    public string $id;
    public string $region;
    public string $name;
    public string $icon;
    public int $level;
    public array $champions;
    public function __construct(
        string $id = "",
        string $region = "",
        string $name = "",
        string $icon = "",
        int $level = 0,
        array $champions = []
    ) {
        $this->id = $id;
        $this->region = $region;
        $this->name = $name;
        $this->icon = $icon;
        $this->level = $level;
        $this->champions = $champions;
    }
    public function createIconTd(string $ddragonURL): string
    {
        $icon = \View\TagHelper::createImg(
            "summonerIcon",
            "{$ddragonURL}/img/profileicon/{$this->icon}.png"
        );
        $levelDiv = \View\TagHelper::createDiv(
            "summonerLevel",
            \View\TagHelper::createSpan(
                "summonerLevelSpan",
                $this->level
            )
        );
        return \View\TagHelper::createTd("icon", $icon . $levelDiv);
    }
    public static function getFromAPI(
        string $site,
        mixed $context,
        string $region,
        string $name
    ): Summoner | null {
        $summonerData = API::doQuery($site, self::API, $name, $context);
        if (!isset($summonerData)) {
            return null;
        }
        return new Summoner(
            $summonerData['id'],
            $region,
            $summonerData['name'],
            $summonerData['profileIconId'],
            $summonerData['summonerLevel'],
        );
    }
}
