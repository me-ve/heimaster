<?php

namespace Model;

class Champion
{
    public string $id;
    public string $key;
    public string $name;
    public string $image;
    public MasteryData $mastery;
    public function __construct(string $id, string $key, string $name, string $image)
    {
        $this->id = $id;
        $this->key = $key;
        $this->name = $name;
        $this->image = $image;
    }
    public static function findName(string $id, array $champions)
    {
        foreach ($champions as $champion) {
            if ($champion["key"] == $id) {
                return $champion;
            }
        }
    }
    public static function retrieve(string $ddragonURL)
    {
        $championsURL = "{$ddragonURL}/data/en_US/champion.json";
        $championsJSON = file_get_contents($championsURL);
        $championsData = json_decode($championsJSON, 1);
        if (!isset($championsData)) {
            return false;
        }
        $champions = [];
        foreach ($championsData["data"] as $championArray) {
            $champion = new Champion(
                $championArray["id"],
                $championArray["key"],
                $championArray["name"],
                $championArray["image"]["full"]
            );
            array_push($champions, $champion);
        }
        return $champions;
    }
}
