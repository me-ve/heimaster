<?php
class Champion{
    public string $id;
    public string $key;
    public string $name;
    public string $image;
    function __construct(string $id, string $key, string $name, string $image){
        $this->id = $id;
        $this->key = $key;
        $this->name = $name;
        $this->image = $image;
    }
}

function findChampionName(string $id, array $champions){
    foreach($champions as $champion){
        if($champion["key"] == $id){
            return $champion;
        }
    }
}
?>