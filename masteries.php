<?php
    class MasteryData{
        public int $level;
        public int $points;
        public int $pointsSince;
        public int $pointsUntil;
        public string $lastPlayed;
        public bool $chest;
        public int $tokens;
        public function __construct(
            int $level,
            int $points,
            int $pointsSince,
            int $pointsUntil,
            string $lastPlayed,
            bool $chest,
            int $tokens
        ){
            $this->level = $level;
            $this->points = $points;
            $this->pointsSince = $pointsSince;
            $this->$pointsUntil = $pointsUntil;
            $this->lastPlayed = $lastPlayed;
            $this->chest = $chest;
            $this->tokens = $tokens;
        }
        public static function doQuery($site, $context, $summoner){
            $masteryData = doQuery(
                $site, "lol/champion-mastery/v4/champion-masteries/by-summoner", $summoner->id, $context
            );
            return $masteryData;
        }
        public function getProgress(){
            if($this->level != 7){
                $progress = match($this->level){
                    1, 2, 3, 4 => $this->pointsSince/($this->pointsSince+$this->pointsUntil),
                    default => $this->tokens/($this->level - 3)
                };
                return $progress;
            }
            else return INF;
        }
        public function progressString(){
            $progress = $this->getProgress();
            if($progress < INF){
                return (round($progress, 2) * 100)."%";
            }
            return "N/A";
        }
    }
?>