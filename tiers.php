<?php
class Tier{
    public float $tierScore;
    public string $tierSymbol;
    const TIERS = array(
        4 => "S+",
        3 => "S",
        2 => "S-",
        1 => "A",
        0 => "B",
        -1 => "C",
        -2 => "D+",
        -3 => "D",
        -4 => "D-"
    );
    function __construct($tierScore){
        $this->tierScore = $tierScore;
        $this->tierSymbol = self::findTierSymbol($tierScore);
    }
    static function findTierSymbol($tierScore){
        $tierFound = false;
        for($i=3.5; $i>=-3.5 && !$tierFound; $i--)
        {
            if($tierScore>=$i)
            {
                $tierFound = true;
                $num = $i + 0.5;
                $tierSymbol = self::TIERS[$num];
            }
        }
        if(!$tierFound) $tierSymbol = self::TIERS[-4];
        return $tierSymbol;
    }
    static function tierBase($partOfAvg){
        return log($partOfAvg, 3);
    }
    static function calculateTierScore($partOfAvgLog, $level, $tokens){
        return $partOfAvgLog + (($level >= 6) + ($level == 7))*0.5 + $tokens*0.125;
    }
}
?>
