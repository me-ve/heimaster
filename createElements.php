<?php
function create_tags(string $type, array $parameters, bool $enclosed=false, string $content=""){
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
function create_img(string $id, string $src, string $class="", string $alt=""){
    $parameters = [
        "id" => $id,
        "src" => $src,
        "class" => $class,
        "alt" => $alt
    ];
    $img = create_tags("img", $parameters);
    return $img;
}
function create_td(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $td = create_tags("td", $parameters, true, $content);
    return $td;
}
function create_th(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $th = create_tags("th", $parameters, true, $content);
    return $th;
}
function create_div(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $div = "<div id='$id' style='$style'>$content</div>";
    return $div;
}
function create_span(string $id, string $content, string $class="", string $style=""){
    $parameters = [
        "id" => $id,
        "class" => $class,
        "style" => $style,
    ];
    $span = create_tags("span", $parameters, true, $content);
    return $span;
}
?>
