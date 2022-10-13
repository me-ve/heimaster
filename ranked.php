<?php
require_once("formatNumbers.php");
require_once("apiQuery.php");
require_once("summoner.php");
class Ranked{
    const API = "lol/league/v4/entries/by-summoner";
    public string $WIN_SYMBOL = "\u{1F3C6}";
    public string $type;
    public string $tier;
    public string $rank;
    public int $leaguePoints;
    public int $wins;
    public int $losses;
    public static function doQuery(string $site, $context, Summoner $summoner) : array{
        $rankedData = APIQuery::doQuery(
            $site, self::API, $summoner->id, $context
        );
        return $rankedData;
    }
    public static function createFromQuery(array $queue) : Ranked{
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
    public function typeString() : string{
        switch($this->type){
            case "RANKED_SOLO_5x5": return "Solo"; break;
            case "RANKED_FLEX_SR": return "Flex"; break;
        }
    }
    public function tierString() : string{
        return ucfirst(strtolower($this->tier));
    }
    public function totalMatches() : int{
        return $this->wins + $this->losses;
    }
    public function winRate() : float{
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
    public function __toString() : string{
        $winRateStr = displayPercent($this->winRate());
        return "{$this->TypeString()}: {$this->TierString()} {$this->rank} {$this->leaguePoints} LP<br>".
        "{$this->WIN_SYMBOL} {$this->wins} of {$this->totalMatches()} ({$winRateStr})";
    }
}
function createRankedArray(string $site, $context, Summoner $summoner) : array{
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