<?php
function createTags(string $type, array $parameters, bool $enclosed=false, string $content=""){
    $res = "<$type";
    foreach($parameters as $parameter => $parameter_value){
        if($parameter_value != ""){
            $res .= " {$parameter}='$parameter_value'";
        }
    }
    $res .= ">";
    if($enclosed){
        $res .= $content."</$type>";
    }
    return $res;
}
function createBr(){
    return createTags("br", []);
}
function createImg(string $id, string $src, string $class="", string $alt=""){
    $parameters = [
        "id" => $id,
        "src" => $src,
        "class" => $class,
        "alt" => $alt
    ];
    return createTags("img", $parameters);
}
function createTd(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    return createTags("td", $parameters, true, $content);
}
function createTh(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    return createTags("th", $parameters, true, $content);
}
function createDiv(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    return createTags("div", $parameters, true, $content);
}
function createSpan(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    return createTags("span", $parameters, true, $content);
}
function createScript(string $code){
    return createTags("script", [], true, $code);
}
function createScriptFromSrc(string $src){
    return createTags("script", ["src" => $src], true);
}
?>
