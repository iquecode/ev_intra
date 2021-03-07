<div class="extrato">
        <h3>Demonstrativo Financeiro</h3>
        <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th class="num">Valor R$</th>
        </tr>
        <?php
        //gera extrato normal na tela e armazena lançamentos futuros 
        $today = strtotime(date('Y/m/d'));
        // echo '$today : '.$today;
        // var_dump($today);
      
        $futureEntries = [];
        $pendentes = false;
        foreach ($statement as $item) {        
            // echo '$item->getDate : '.$item->getDate();
            // var_dump($item->getDate()); 
            if (strtotime($item->getDate()) <= $today) {
                // extrato normal
                $date = date('d/m/Y',strtotime($item->getDate()));
                $description = $item->getDescription();
                $value = $item->getValue();
                $status = $item->getStatus();
                if ($value < 0) {
                    echo "<tr><td>".$date."</td><td>".$description."</td><td class='num neg'>".num($value)."</td>";
                } else {
                    
                    if ($status == 0) {
                        $pendentes = true;
                        echo "<tr>
                                <td class='pend'>" . $date . "</td>
                                <td class='pend'>" . $description." - a validar"."</td>
                                <td class='num pend'>" . num($value). "</td>";
                    } else {
                        echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
                    }
                }
                $total = $total + $value;
            } else {
                // armazena lançamentos futuros
                array_push($futureEntries, $item);  
            }      
        }
        echo "</table>";


        echo "<div id='rodape_ext'>";

            if ($pendentes) {
                echo "<div id='altera'>";
                echo "<a class='a_small' href='_changes.php'>Alterar / excluir lançamentos a validar.</a>";
                echo "</div>";
            }


            if ($total < 0) {
                echo "<span class='num neg'>Saldo atual: ".num($total)."</span>"; 
            } else {
                echo "<span class='num'>Saldo atual: ".num($total)."</span>";
            }        

        echo "</div>";

        ?>
    </div>