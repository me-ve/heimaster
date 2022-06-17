<?php
require_once("formatNumbers.php");
class Ranked{
    public string $type;
    public string $tier;
    public string $rank;
    public int $leaguePoints;
    public int $wins;
    public int $losses;
    public function TypeString(){
        switch($this->type){
            case "RANKED_SOLO_5x5": return "Solo"; break;
            case "RANKED_FLEX_SR": return "Flex"; break;
        }
    }
    public function TierString(){
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
        $winRateStr = display_percent($this->winRate());
        return "{$this->TypeString()}: {$this->TierString()} {$this->rank} {$this->leaguePoints} LP<br>".
        "\u{1F3C6} {$this->wins} of {$this->totalMatches()} ({$winRateStr})";
    }
}
?>