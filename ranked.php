<?php
require_once("formatNumbers.php");
class Ranked{
    public string $type;
    public string $tier;
    public string $rank;
    public int $leaguePoints;
    public int $wins;
    public int $losses;
    public static function doQuery($site, $context, $summoner){
        $rankedData = doQuery(
            $site, "lol/league/v4/entries/by-summoner", $summoner->id, $context
        );
        return $rankedData;
    }
    public static function createFromQuery(array $queue){
        $rankedQueue = new Ranked(
            $queue["queueType"],
            $queue["tier"],
            $queue["rank"],
            $queue["leaguePoints"],
            $queue["wins"],
            $queue["losses"]
        );
        return $rankedQueue;
    }
    public function typeString(){
        switch($this->type){
            case "RANKED_SOLO_5x5": return "Solo"; break;
            case "RANKED_FLEX_SR": return "Flex"; break;
        }
    }
    public function tierString(){
        return ucfirst(strtolower($this->tier));
    }
    public function totalMatches(){
        return $this->wins + $this->losses;
    }
    public function winRate(){
        return $this->wins / $this->totalMatches();
    }
    public function __construct(
        string $type,
        string $tier,
        string $rank,
        int $leaguePoints,
        int $wins,
        int $losses
    ){
        $this->type = $type;
        $this->tier = $tier;
        $this->rank = $rank;
        $this->leaguePoints = $leaguePoints;
        $this->wins = $wins;
        $this->losses = $losses;
    }
    public function __toString(){
        $winRateStr = displayPercent($this->winRate());
        return "{$this->TypeString()}: {$this->TierString()} {$this->rank} {$this->leaguePoints} LP<br>".
        "\u{1F3C6} {$this->wins} of {$this->totalMatches()} ({$winRateStr})";
    }
}
function createRankedArray($site, $context, $summoner){
    $rankedData = Ranked::doQuery($site, $context, $summoner);
    if(!isset($rankedData)) return NULL;
    $rankedArray = [];
    foreach($rankedData as $queue)
    {
        $rankedQueue = Ranked::createFromQuery($queue);
        array_push($rankedArray, $rankedQueue);
    }
    return $rankedArray;
}
?>