<?php

namespace View;

class MainTable
{
    public const HEADERS = [
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
    public function __construct()
    {
        $firstRow = [];
        foreach (self::HEADERS as $headerId => $headerName) {
            array_push($firstRow, createTh($headerId, $headerName));
        }
        $this->table = [$firstRow];
    }
    public function __toString()
    {
        $result = "";
        $i = 0;
        foreach ($this->table as $row) {
            $result .= createTr("row[{$i}]", join("", $row));
            $i++;
        }
        return createTags("table", ["id" => "champions"], true, $result);
    }
    public function rows(): int
    {
        return count($this->table) - 1;
    }
    public function cols(): int
    {
        return count(self::HEADERS);
    }
    public function addRow(array $row)
    {
        array_push($this->table, $row);
    }
}
