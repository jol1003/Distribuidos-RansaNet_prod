<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once '../Conext/Conexion.cls.php';
require_once '../Conext/Consulta.cls.php';
require_once '../Conext/BigConsulting.php';

$Contenido = new contenido;


$id_user = "01";

$ip_user = "10.1.1.9";
$ip_user_proxy = "10.1.1.9";
$axion = "Inicio Web Service BN";
$Contenido->ContenidoLog($axion, $id_user, $ip_user, $ip_user_proxy);
///////////////*************//////////////////

try {
    //$wsdl_url = 'https://zonasegura1.bn.com.pe/PagosBancoNacion/services/ConsultaPagosNacionBean/wsdl/ConsultaPagosNacionBean.wsdl'; /// URL de la Web Service BN
    //$wsdl_url = 'https://192.168.89.13/PagosBancoNacion/services/ConsultaPagosDiversos/wsdl/ConsultaPagosDiversos.wsdl';
    $wsdl_url = 'http://192.168.89.13/PagosBancoNacion/services/ConsultaPagosDiversos/wsdl/ConsultaPagosDiversos.wsdl';
    //$wsdl_url = 'https://192.168.89.11/PagosBancoNacion/services/ConsultaPagosDiversos?WSDL';
    $client = new SOAPClient($wsdl_url);


    /*$params = array(
        'adm' => "USER_INPE",
        'cont' => "PASS_INPE");
    */
    $adm = "USER_INPE"; // also can use $params->From = "date";
    $cont= "K;sN7aXj"; // also can use $params->to = "date";
    $return = $client->consultarPagos($adm, $cont);
    //print_r($return);
    $jota = $return->PagosDTO;
    print_r($jota);

	/*
    $return = $client->consultarPagos($adm, $cont);
    $jota = $return->consultarPagosReturn->PagosDTO;
*/
//	print_r($jota);
    //print_r($return);
    //print "<br />resultados";
} catch (Exception $e) {
   echo "Error SOAP " . $e;
}


/*$adm = "USER_INPE"; // also can use $params->From = "date";
$cont= "PASS_INPE"; // also can use $params->to = "date";

$client = new SoapClient("https://192.168.89.11/PagosBancoNacion/services/ConsultaPagosDiversos/wsdl/ConsultaPagosDiversos.wsdl");

try {
        print_r($client->consultarPagos($adm, $cont));
} catch (SoapFault $exception) {
        echo $exception;
} 
*/

//$jota = $return->consultarPagosReturn->PagosDTO;
//$jota = $return->consultarPagos->PagosDTO;

//print_r($jota);
/*echo "<h1>Array ".count($jota)."</h1><br>";

for($i=0;$i<=count($jota)-1;$i++ ) {
        echo "<tr>";
        echo "<td bgcolor=\"#FFFFFF\">$i</td>";
        foreach ($jota->$i as $key => $value) {
            echo "<td bgcolor=\"#FFFFFF\">".$value."</td>";
	}
        echo "</tr>";
}
echo "<h1>Objeto".count($jota)."</h1>";
*/

/*try {
   //$jota=$client->PagosDTO;
  $jota = $client->PagosDTO;
  
}
catch (Exception $e)
{
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
*/

//echo "<h1>Array ".$jota."</h1><br>";
////////////////////***************////////////////
if (is_array($jota)) {
//    echo "<h1>Array ".count($jota)."</h1><br>";
    $tipo_retorno = "Array";

    for ($i = 0; $i <= count($jota); $i++) {
        $f22_BESTADO = $jota[$i]->f01_BESTADO;

        $f22_CTRIBUTO = $jota[$i]->f01_CTRIBUTO;
        $f22_TDOCUM = $jota[$i]->f01_TDOCUM;
        $f22_NDOCUM = $jota[$i]->f01_NDOCUM;
        $f22_STOTAL = $jota[$i]->f01_STOTAL;
        $f22_FMOVIM = $jota[$i]->f01_FMOVIM;
        $f22_HMOVIM = $jota[$i]->f01_HMOVIM;
        $f22_CAGENCIA = $jota[$i]->f01_CAGENCIA;
        $f22_CCAJERO = $jota[$i]->f01_CCAJERO;
        $f22_CJUZJADO = $jota[$i]->f01_CJUZGADO;
        $f22_DIGCHK = $jota[$i]->f01_DIGCHK;
        $f22_NUMSEC = $jota[$i]->f01_NUMSEC;

        $f22_NPAGOS = $jota[$i]->f01_NPAGOS;




        $valorTotal = substr($f22_STOTAL, 11);
        $f22_DATA1 = "";
        $f22_DATA2 = "";
        $f22_DATA3 = "";
        $f22_DATA4 = "";
        $cod_trb = str_pad($f22_CTRIBUTO, 5, "0", STR_PAD_LEFT);
        $tp_doc = $f22_TDOCUM;
        $num_doc = $f22_NDOCUM;
        $filter01 = $f22_DATA1;
        $nro_reg = 0;
        $mpt_sol = $f22_STOTAL;
        $fech_f22_FMOVIM = $f22_FMOVIM;
        $fech_axo = substr($fech_f22_FMOVIM, 0, 4);
        $fech_mes = substr($fech_f22_FMOVIM, 4, 2);
        $fech_dia = substr($fech_f22_FMOVIM, 6, 2);
        $fech = "$fech_axo-$fech_mes-$fech_dia";
        $fech2 = "$fech_axo$fech_mes$fech_dia";
        $num_sec = str_pad($f22_NUMSEC, 6, "0", STR_PAD_LEFT);
        $hor_f22_HMOVIM = str_pad($f22_HMOVIM, 6, "0", STR_PAD_LEFT);
        $hor_h = substr($hor_f22_HMOVIM, 0, 2);
        $hor_m = substr($hor_f22_HMOVIM, 2, 2);
        $hor_s = substr($hor_f22_HMOVIM, 4, 2);
        $hor = "$hor_h:$hor_m:$hor_s";
        $hor2 = "$hor_h$hor_m$hor_s";
        $filter2 = $f22_DATA2;
        $cod_ofi = str_pad($f22_CAGENCIA, 4, "0", STR_PAD_LEFT);
        $cod_caj = str_pad($f22_CCAJERO, 4, "0", STR_PAD_LEFT);
        $filter3 = $f22_DATA3;
        $filter4 = $f22_DATA4;
        $llave = "$fech2$hor2$cod_ofi$cod_caj";
        
        if ($f22_BESTADO == 1) {
            $sqlEst = "INSERT INTO `pagos_estornados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sqlEst);
            continue;
        }


        



//        echo "<br><br><br>";
//        echo  $sql="INSERT INTO `pagossoap`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
// VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
//        $retornasql=$Contenido->Ejecuta($sql);
//        echo "-$retornasql<br>";


        $Query = "SELECT COUNT(id)  FROM pagos p where llave='$llave' and num_sec='$num_sec'";
        $row = $Contenido->CuentaResultados($Query);
        if ($row[0] == 0) {
            $sql = "INSERT INTO `pagos`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sql);


            $okinsert++;
        } else {
            echo $error = "Ya Existe:$llave  -> $num_sec<br>";
            $sql = "INSERT INTO `pagos_rechazados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sql);
        }
    }
    //echo $f22_STOTAL;
    echo $okinsert;
}

if (is_object($jota)) {
    $tipo_retorno = "Objeto";

//      for($i=0;$i<=count($jota)-1;$i++ ) {
//        echo "<tr>";
//        echo "<td bgcolor=\"#FFFFFF\">$i</td>";
//        foreach ($jota->$i as $key => $value) {
//            echo "<td bgcolor=\"#FFFFFF\">".$value."</td>";
//
//
//
//        }
//        echo "</tr>";
//    }
//    echo "<h1>Objeto".count($jota)."</h1>";
    for ($i = 0; $i <= count($jota); $i++) {
        $f22_BESTADO = $jota->f01_BESTADO;

        $f22_CTRIBUTO = $jota->f01_CTRIBUTO;
        $f22_TDOCUM = $jota->f01_TDOCUM;
        $f22_NDOCUM = $jota->f01_NDOCUM;
        $f22_STOTAL = $jota->f01_STOTAL;
        $f22_FMOVIM = $jota->f01_FMOVIM;
        $f22_HMOVIM = $jota->f01_HMOVIM;
        $f22_CAGENCIA = $jota->f01_CAGENCIA;
        $f22_CCAJERO = $jota->f01_CCAJERO;
        $f22_CJUZJADO = $jota->f01_CJUZGADO;
        $f22_DIGCHK = $jota->f01_DIGCHK;
        $f22_NUMSEC = $jota->f01_NUMSEC;

        $f22_NPAGOS = $jota[$i]->f01_NPAGOS;

        $f22_DATA1 = "";
        $f22_DATA2 = "";
        $f22_DATA3 = "";
        $f22_DATA4 = "";
        $cod_trb = str_pad($f22_CTRIBUTO, 5, "0", STR_PAD_LEFT);
        $tp_doc = $f22_TDOCUM;
        $num_doc = $f22_NDOCUM;
        $filter01 = $f22_DATA1;
        $nro_reg = 0;
        $mpt_sol = $f22_STOTAL;
        $fech_f22_FMOVIM = $f22_FMOVIM;
        $fech_axo = substr($fech_f22_FMOVIM, 0, 4);
        $fech_mes = substr($fech_f22_FMOVIM, 4, 2);
        $fech_dia = substr($fech_f22_FMOVIM, 6, 2);
        $fech = "$fech_axo-$fech_mes-$fech_dia";
        $fech2 = "$fech_axo$fech_mes$fech_dia";
        $num_sec = str_pad($f22_NUMSEC, 6, "0", STR_PAD_LEFT);

//$num_sec=;
//    $hor_f22_HMOVIM=$f22_HMOVIM;
        $hor_f22_HMOVIM = str_pad($f22_HMOVIM, 6, "0", STR_PAD_LEFT);

        $hor_h = substr($hor_f22_HMOVIM, 0, 2);
        $hor_m = substr($hor_f22_HMOVIM, 2, 2);
        $hor_s = substr($hor_f22_HMOVIM, 4, 2);
        $hor = "$hor_h:$hor_m:$hor_s";
        $hor2 = "$hor_h$hor_m$hor_s";
        $filter2 = $f22_DATA2;
//$cod_ofi=$f22_CAGENCIA;
        $cod_ofi = str_pad($f22_CAGENCIA, 4, "0", STR_PAD_LEFT);
//$cod_caj=$f22_CCAJERO;
        $cod_caj = str_pad($f22_CCAJERO, 4, "0", STR_PAD_LEFT);
        $filter3 = $f22_DATA3;
        $filter4 = $f22_DATA4;

        $llave = "$fech2$hor2$cod_ofi$cod_caj";




        $filter4 = $f22_DATA4;
        $llave = "$fech2$hor2$cod_ofi$cod_caj";

        
        if ($f22_BESTADO == 1) {

            $sqlEst = "INSERT INTO `pagos_estornados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sqlEst);
            
            continue;
        }
        



        $Query = "SELECT COUNT(id)  FROM pagos p where llave='$llave' and num_sec='$num_sec'";
        $row = $Contenido->CuentaResultados($Query);
        if ($row[0] == 0) {
            $sql = "INSERT INTO `pagos`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sql);


            $okinsert++;
        } else {
            echo $error = "Ya Existe:$llave  -> $num_sec<br>";
$sql = "INSERT INTO `pagos_rechazados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = $Contenido->Ejecuta($sql);
        }
        echo $okinsert;
        //echo $f22_STOTAL;
    }
}


$id_user = "01";

$ip_user = "10.1.1.9";
$ip_user_proxy = "10.1.1.9";
$axion = "Fin Web Service BN:$okinsert migrados   - retorno : $tipo_retorno de " . count($jota) . "";
$Contenido->ContenidoLog($axion, $id_user, $ip_user, $ip_user_proxy);
?>
