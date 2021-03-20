<?php

$template = file_get_contents('meuarquivo.html');

$j = json_encode($template);

echo "<pre>";
print_r($j);

$j = json_decode($j);

print_r($j);