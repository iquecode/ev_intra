<?php
//gera extrato de lançamentos futuros na tela 
$total=0;
$items = '';

foreach ($futureEntries as $entry) 
{         
    // extrato normal
    $date = date('d/m/Y',strtotime($entry->getDate()));
    $description = $entry->getDescription();
    $value = $entry->getValue();
    $neg_pos = $value < 0 ? 'num neg_future' : 'num';
    $item = file_get_contents('html/futures_item.html');
    $item = str_replace( '{date}',          $date,          $item);
    $item = str_replace( '{description}',   $description,   $item);
    $item = str_replace( '{value}',         num($value),         $item);
    $item = str_replace( '{neg_pos}',       $neg_pos,       $item);            
    $total = $total + $value;
    // armazena lançamentos futuros
    array_push($futureEntries, $entry);  
    $items .= $item;         
}

$stat = file_get_contents('html/futures.html');
$stat = str_replace('{items}',   $items,    $stat);
$stat = str_replace('{neg_pos}', $neg_pos,  $stat);
$stat = str_replace('{total}',   num($total),    $stat);

print $stat;