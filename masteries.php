<?php
    class MasteryData{
        public int $level;
        public int $points;
        public int $pointsSince;
        public int $pointsUntil;
        public date $lastPlayed;
        public bool $chest;
        public int $tokens;
        public function getProgress(){
            if($this->level != 7){
                $progress = match($level){
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