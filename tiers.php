<?php

namespace Model;

class Tier
{
    public float $tierScore;
    public string $tierSymbol;
    public const TIERS = array(
        4 => "S+",
        3 => "S",
        2 => "S-",
        1 => "A",
        0 => "B",
        -1 => "C",
        -2 => "D+",
        -3 => "D",
        -4 => "D-",
    );
    public function __construct(float $partOfAvg, MasteryData $md)
    {
        $this->tierScore = $this->calculateTierScore($partOfAvg, $md);
        $this->tierSymbol = $this->findTierSymbol();
    }
    public function findTierSymbol(): string
    {
        $tierFound = false;
        for ($i = 3.5; $i >= -3.5 && !$tierFound; $i--) {
            if ($this->tierScore >= $i) {
                $tierFound = true;
                $num = $i + 0.5;
                $tierSymbol = self::TIERS[$num];
            }
        }
        if (!$tierFound) {
            $tierSymbol = self::TIERS[-4];
        }
        return $tierSymbol;
    }
    public static function tierBase(float $partOfAvg): float
    {
        return log($partOfAvg, 3);
    }
    public function calculateTierScore(float $partOfAvg, MasteryData $md): float
    {
        return self::tierBase($partOfAvg)
            + (($md->level >= 6)
            + ($md->level == 7)) * 0.5
            + $md->tokens * 0.125;
    }
    public function tierScoreString(): string
    {
        return \displayWithSeparators($this->tierScore, 2);
    }
}
