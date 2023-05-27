<?php

namespace View;

class TagHelper
{
    public static function createTags(
        string $type,
        array $parameters = array(),
        bool $enclosed = false,
        string $content = ''
    ): string {
        $res = "<$type";
        foreach ($parameters as $parameter => $parameter_value) {
            if ('' != $parameter_value) {
                $res .= " {$parameter}='{$parameter_value}'";
            }
        }
        $res .= '>';
        if ($enclosed) {
            $res .= $content . "</$type>";
        }

        return $res;
    }
    public static function createBr(): string
    {
        return self::createTags('br');
    }
    public static function createImg(
        string $id,
        string $src,
        string $class = '',
        string $alt = ''
    ): string {
        $parameters = array(
            'id' => $id,
            'src' => $src,
            'class' => $class,
            'alt' => $alt,
        );

        return self::createTags('img', $parameters);
    }
    public static function setBasicParams(
        string $id,
        string $class,
        string $style
    ): array {
        return array(
            'id' => $id,
            'class' => $class,
            'style' => $style,
        );
    }
    public static function createBasicTag(
        string $type,
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createTags(
            $type,
            self::setBasicParams($id, $class, $style),
            true,
            $content
        );
    }
    public static function createTd(
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createBasicTag('td', $id, $content, $class, $style);
    }
    public static function createTdArray(array $cells): array
    {
        $result = array();
        foreach ($cells as $cell) {
            array_push(
                $result,
                self::createTd(
                    $cell[0],
                    $cell[1],
                    $cell[2],
                    $cell[3] ?? ''
                )
            );
        }

        return $result;
    }
    public static function createTh(
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createBasicTag('th', $id, $content, $class, $style);
    }
    public static function createTr(
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createBasicTag('tr', $id, $content, $class, $style);
    }
    public static function createDiv(
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createBasicTag('div', $id, $content, $class, $style);
    }
    public static function createSpan(
        string $id,
        string $content,
        string $class = '',
        string $style = ''
    ): string {
        return self::createBasicTag('span', $id, $content, $class, $style);
    }
    public static function createScript(string $code): string
    {
        return self::createTags('script', [], true, $code);
    }
    public static function createScriptFromSrc(string $src): string
    {
        return self::createTags('script', ['src' => $src], true);
    }
}
