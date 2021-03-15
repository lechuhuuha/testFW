<?php

namespace Lchh;

class Markdown
{
    private $string;
    public function __construct($markdown)
    {
        $this->string = $markdown;
    }
    public function toHTML()
    {
        $html = 'p';
        return $html;
    }
}
