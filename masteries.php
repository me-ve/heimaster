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
            $this->pointsUntil = $pointsUntil;
            $this->lastPlayed = $lastPlayed;
            $this->chest = $chest;
            $this->tokens = $tokens;
        }
        public static function doQuery(string $site, $context, Summoner $summoner) : array{
            $masteryData = doQuery(
                $site, "lol/champion-mastery/v4/champion-masteries/by-summoner", $summoner->id, $context
            );
            return $masteryData;
        }
        public function getProgress() : float{
            if($this->level != 7){
                $progress = match($this->level){
                    1, 2, 3, 4 => $this->pointsSince/($this->pointsSince+$this->pointsUntil),
                    default => $this->tokens/($this->level - 3)
                };
                return $progress;
            }
            else return INF;
        }
        public function progressString() : string{
            $progress = $this->getProgress();
            if($progress < INF){
                return (round($progress, 2) * 100)."%";
            }
            return "N/A";
        }
        public function pointsString() : string{
            return displayWithSeparators($this->points);
        }
        public function chestString() : string{
            return $this->chest ? "yes" : "no";
        }
        public function tokenString() : string{
            return "$this->tokens";
        }
        public function lastPlayedDateString() : string{
            return date("Y-m-d H:i:s", $this->lastPlayed/1000);
        }
        public function timeChange($currentDate) : string{
            return timeElapsed(strtotime($currentDate) - strtotime($this->lastPlayedDateString()));
        }
    }
?>