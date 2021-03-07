<?
function num($n)  // formata número
{
    $n = number_format($n, 2, ',', '.');
    return $n;
}