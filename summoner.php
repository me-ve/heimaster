<?php
require_once("champions.php");
class Summoner{
    public string $id;
    public string $region;
    public string $name;
    public string $icon;
    public int $level;
    public array $champions;
    public function __construct(
        string $id,
        string $region,
        string $name,
        string $icon,
        int $level,
        array $champions){
        $this->id = $id;
        $this->region = $region;
        $this->name = $name;
        $this->icon = $icon;
        $this->level = $level;
        $this->champions = $champions;
    }
}
?>