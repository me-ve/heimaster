<?php
class APIQuery{
    static function createQuery($site, $API, $item){
        return "{$site}/{$API}/{$item}";
    }
    static function doQuery($site, $API, $item, $context) : array{
        $JSON = file_get_contents(self::createQuery($site, $API, $item), false, $context);
        return json_decode($JSON, true);
    }
}
?>