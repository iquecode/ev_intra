<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/ev_intra/db/UserDaoMysql.php');
require_once('../db/UserDaoMysql.php');

Class LoadStatment {
   private $stat;

    public function __construct($file)  
    {
        $extrato = file($file);
        $lancamentos = [];
        $i = 0;
        foreach ($extrato as $linha)
        { 
            $lancamentos[$i] =  explode(';',$linha);
            $i++;
        }
    
        $i=0;
        $entries = [];
        foreach ($lancamentos as $item) 
        {
            $entries[$i] = str_replace('"', '', $lancamentos[$i]);
            $entries[$i] = str_replace(PHP_EOL, '',  $entries[$i]);
            $i++;
        } 
    
        $i=0;
        foreach($entries as $row) {
            if($i > 0) 
            {
                $stat[$i] = array_map('trim', $row);
                $stat[$i]['date'] = $entries[$i][1];
                $stat[$i]['doc']  = $entries[$i][2];
                $stat[$i]['hist'] = $entries[$i][3];
                $stat[$i]['value'] = $entries[$i][4];
                $stat[$i]['cred_deb'] = $entries[$i][5];
                unset($stat[$i][0]); 
                unset($stat[$i][1]);
                unset($stat[$i][2]);
                unset($stat[$i][3]);
                unset($stat[$i][4]);
                unset($stat[$i][5]);  
            } 
            $i++;
        }
         $this->stat = $stat;
    }


    public function getCashFlow() 
    {
        $stat = $this->stat;
        $i = 1;
        foreach($stat as $row) {
            $d = substr( $row['date'], 6, 2 ); 
            $cashFlow[$i]['y'] = substr( $row['date'], 0, 4 );
            $cashFlow[$i]['m'] = substr( $row['date'], 4, 2 );
            $cashFlow[$i]['date'] = $d . "/" . $cashFlow[$i]['m'] . "/" . $cashFlow[$i]['y'];
            $cashFlow[$i]['portador'] = '1967003000006543';
            $cashFlow[$i]['doc'] = $row['doc'];
            $cashFlow[$i]['desc'] = $row['hist'];
            $cashFlow[$i]['tipo'] = 'RECEITAS';
            $cashFlow[$i]['classe'] = 'Pagamento Associados';
            $cashFlow[$i]['nr'] = substr( $row['value'], -2, 2);
            $cashFlow[$i]['forn'] = $cashFlow[$i]['nr'] . ". nome ecovileiro";
            $cashFlow[$i]['obs'] = '';
            $cashFlow[$i]['cred'] = '';
            $cashFlow[$i]['deb'] = '';
            switch ($row['cred_deb']) {
                case 'C':
                    $cashFlow[$i]['cred'] = $row['value'];
                    break;
                case 'D':
                    $cashFlow[$i]['deb'] = $row['value'];
                    $cashFlow[$i]['tipo'] = 'DESPESAS';
                    $cashFlow[$i]['classe'] = 'Conferir';
                    break;
            }
            $i++;
        }

        $dir = $_SERVER['DOCUMENT_ROOT'] . DIR_BASE . 'upload/cash_flow/';
        $fileName = 'fc' . time() . '.csv';
        $file = fopen($dir . $fileName, 'w');

        // Popular os dados
        foreach ($cashFlow as $row) {
            fputcsv($file, $row);
        }
        // Fechar o arquivo
        fclose($file);

        $fileName = 'upload/cash_flow/' . $fileName;
        //( $file, $dir );
        // print '<pre>';
        // print_r($cashFlow);
        return ['array' => $cashFlow, 'file' => $fileName];
        //return ['array' => 'a', 'file' => 'b'];

    }


    public function getDeposits()
    {
        $stat = $this->stat;
        $deposits = [];
        $i = 0;
        foreach ($stat as $item) 
        {
            $date = $item['date'];
            $y = substr($date, 0, 4);
            $m = substr($date, 4, 2);
            $d = substr($date, 6, 2);
            $date = "{$y}-{$m}-{$d}";

            $value = $item['value'];
            $cents =  substr($value, -2);
            $credDeb = $item['cred_deb'];

            if ($credDeb == 'C') 
            {
                $dao = new UserDaoMysql();
                $u = $dao->findByQuota($cents);
               
                if ($u) {   //se achou usuário com os centavos
                    $deposits[$i]['date'] = $date;
                    $deposits[$i]['id_user'] = $u->getId();
                    $deposits[$i]['quota'] = $u->getQuota();
                    $deposits[$i]['nickname'] = $u->getNickName();
                    $deposits[$i]['name'] = $u->getName();
                    $deposits[$i]['value'] = $value;
                    $i++;
                }
            }
        }
        
        return $deposits;
    }
      
}
