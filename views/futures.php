<div class="futuros">
        <h3>Lançamentos Futuros</h3>
        <table>
        <tr>
            <th>Data</th>
            <th>Descrição</th>
            <th class="num">Valor R$</th>
        </tr>
        <?php
        //gera extrato de lançamentos futuros na tela 
        $total=0;
        foreach ($futureEntries as $item) {         
            // extrato normal
            $date = date('d/m/Y',strtotime($item->getDate()));
            $description = $item->getDescription();
            $value = $item->getValue();
            if ($value < 0) {
                echo "<tr><td>".$date."</td><td>".$description."</td><td class='num neg_future'>".num($value)."</td>";
            } else {
                echo "<tr><td>".$date."</td><td>".$description."</td><td class='num'>".num($value)."</td>";
            }   
            
            $total = $total + $value;
            // armazena lançamentos futuros
            array_push($futureEntries, $item);           
        }
        echo "</table>";
        if ($value < 0) {
            echo "<span class='num neg_future'>Total lançamentos futuros: ".num($total)."</span>"; 
        } else {
            echo "<span class='num'>Total lançamentos futuros: ".num($total)."</span>";
        }
        ?>
    </div>