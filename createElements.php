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
function createImg(string $id, string $src, string $class="", string $alt=""){
    $parameters = [
        "id" => $id,
        "src" => $src,
        "class" => $class,
        "alt" => $alt
    ];
    $img = createTags("img", $parameters);
    return $img;
}
function createTd(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $td = createTags("td", $parameters, true, $content);
    return $td;
}
function createTh(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $th = createTags("th", $parameters, true, $content);
    return $th;
}
function createDiv(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $div = "<div id='$id' style='$style'>$content</div>";
    return $div;
}
function createSpan(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $span = createTags("span", $parameters, true, $content);
    return $span;
}
?>
