<?php
function createTags(string $type, array $parameters=[], bool $enclosed=false, string $content="") : string{
    $res = "<$type";
    foreach($parameters as $parameter => $parameter_value){
        if($parameter_value != ""){
            $res .= " {$parameter}='{$parameter_value}'";
        }
    }
    $res .= ">";
    if($enclosed){
        $res .= $content."</$type>";
    }
    return $res;
}
function createBr() : string{
    return createTags("br");
}
function createImg(string $id, string $src, string $class="", string $alt="") : string{
    $parameters = [
        "id" => $id,
        "src" => $src,
        "class" => $class,
        "alt" => $alt
    ];
    return createTags("img", $parameters);
}
function setBasicParams(string $id, string $class, string $style) : array{
    return [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
}
function createBasicTag(string $type, string $id, string $content, string $class="", string $style="") : string{
    return createTags($type, setBasicParams($id, $class, $style), true, $content);
}
function createTd(string $id, string $content, string $class="", string $style="") : string{
    return createBasicTag("td", $id, $content, $class, $style);
}
function createTdArray(array $cells) : array{
    $result = [];
    foreach ($cells as $cell){
        array_push($result, createTd($cell[0], $cell[1], $cell[2], isset($cell[3]) ? $cell[3] : ""));
    }
    return $result;
}
function createTh(string $id, string $content, string $class="", string $style="") : string{
    return createBasicTag("th", $id, $content, $class, $style);
}
function createTr(string $id, string $content, string $class="", string $style="") : string{
    return createBasicTag("tr", $id, $content, $class, $style);
}
function createDiv(string $id, string $content, string $class="", string $style="") : string{
    return createBasicTag("div", $id, $content, $class, $style);
}
function createSpan(string $id, string $content, string $class="", string $style="") : string{
    return createBasicTag("span", $id, $content, $class, $style);
}
function createScript(string $code) : string{
    return createTags("script", [], true, $code);
}
function createScriptFromSrc(string $src) : string{
    return createTags("script", ["src" => $src], true);
}
?>
