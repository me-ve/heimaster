<?php
    class MainTable{
        const HEADERS = [
            "position" => "#",
            "image" => "Icon",
            "name" => "Champion",
            "level" => "Level",
            "points" => "Points",
            "partofavg" => "% of average",
            "tierscore" => "Tier Score",
            "tier" => "Tier",
            "progress" => "Progress",
            "chests" => "Chest",
            "tokens" => "Tokens",
            "date" => "Last played",
        ];
        public array $table;
        public function __construct(){
            $this->table = createTableWithHeaders(self::HEADERS);
        }
        public function __toString(){
            return join("", $this->table);
        }
        public function rows() : int{
            return count($this->table)-1;
        }
        public function cols() : int{
            return count(self::HEADERS);
        }
    }
?>