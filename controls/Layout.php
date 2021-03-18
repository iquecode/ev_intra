<?php

Class Layout {

private $html;
private $title;
private $css;
private $js;
private $content;

public function __construct($title, $css, $js, $content, $template=1, $header='', $footer='')
{
     $vCSS = filemtime($css);
     $vJS  = filemtime($js); 
     $this->html = file_get_contents("html/layout{$template}.html");
     $this->title = $title;
     $this->css = $css . '?v=' . $vCSS;
     $this->js = $js . '?v=' . $vJS;
     $this->content = $content;
     $this->header = $header;
     $this->footer = $footer;
}

public function load()
{
    $this->html = str_replace('{title}',      $this->title,    $this->html);
    $this->html = str_replace('{css}',        $this->css,      $this->html);
    $this->html = str_replace('{js}',         $this->js,  $this->html);
    $this->html = str_replace('{content}',    $this->content,  $this->html);
    $this->html = str_replace('{header}',     $this->header,  $this->html);
    $this->html = str_replace('{footer}',     $this->footer,  $this->html);
}

public function show() 
{
    $this->load();
    print $this->html;
}

public function getHTML() {
    $this->load();
    return $this->html;
}

}