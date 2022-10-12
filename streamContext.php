<?php
class StreamContext{
    private static $context;
    private function __construct(){}
    public static function getContext(string $key=null){
        if(self::$context == null || $key != null){
            $options = array(
                'http' => array(
                'method' => "GET",
                'header' => 
                    "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0)\n".
                    "X-Riot-Token: ".$key
                )
            );
            $context = stream_context_create($options);
        }
        return $context;
    }
}
?>