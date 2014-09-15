<?php

class contenido extends Consulta {
    /*  ----------------------------------  JESUX    ----------------------------------------- */

    function VerificaPassword($usu, $pass) {
        $passmd5 = md5($pass);
        $query = parent::Query("SELECT * FROM Usuario WHERE usu_logi='$usu' AND usu_pass='$passmd5' AND usu_flag='1'");
        $nreg = parent::NumReg($query);
        if ($nreg) {
            return true;
        }
        return false;
    }

    function changepass($usu, $passn) {
        $passnmd5 = md5($passn);
        $query = parent::Query("update Usuario set usu_pass='$passnmd5' where usu_logi='$usu'");
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    function muestra_solicita_cod($codig) {

        $query = parent::Query("SELECT * FROM solicita where idsolicita='$codig'");

        while ($Row = parent::ResultAssoc($query)) {
            $idd[] = $Row["idsolicita"];
            $descr[] = $Row["soli_descripcion"];
        }
        return array($idd, $descr);
    }

    function convert_datetime($str) {

        list($date, $time) = explode(' ', $str);
        list($year, $month, $day) = explode('-', $date);
        list($hour, $minute, $second) = explode(':', $time);

        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

        return $timestamp;
    }

    function ContenidoMuestraDepartamentos() {

        $html = "<select name='SelectDepartamento' class='dni'><option value='0' selected='selected'>--Seleccione--</option>";
        $query = parent::Query("SELECT DISTINCT cod_dep,nom_dep  FROM ubigeo2007 order by cod_dep  ASC");
        while ($Row = parent::ResultAssoc($query)) {
            $html .= " <option value='" . $Row['cod_dep'] . "'>" . $Row['nom_dep'] . "</option>";
        }

        $html .= "</select>";

        return $html;
    }

    function GeneraCadenaProces() {
        $query = parent::Query($sql = "SELECT   ID  ,P.DESC  , PROC_DIR   FROM procedencia_lugar P  ORDER BY PROC_DIR ,id ");
        $cadenita.="";
        while ($Row = parent::ResultAssoc($query)) {
            $ID = $Row['ID'];
            $XDESC = $Row['DESC'];
            $PROC_DIR = $Row['PROC_DIR'];
            $cadenita.="$PROC_DIR.<option value='$ID'>$XDESC$ID</option>*";
        }
        return $cadenita;
    }

    function SelecDoc($TipoDoc) {

        $query = parent::Query("SELECT idtipo_documento, docu_nombre,docu_num_digito,tipo_dato FROM tipo_documento");
        $html = "<select name='tipdocu' id='tipdocu' onChange='javascript:cheka(this.value)' class='dni'>";
        $html.="<option value='0' >Tipo Doc.</option>";

        while ($Row = parent::ResultAssoc($query)) {



            $IdDoc = $Row['idtipo_documento'];
            $NomDoc = $Row['docu_nombre'];
            $NumDig = $Row['docu_num_digito'];
            $TipoDato = $Row['tipo_dato'];
            if ($TipoDoc == $Row['docu_nombre']) {
                $html.="<option value='$IdDoc.$TipoDato.$NumDig' selected >$NomDoc  </option>";
            } else {
                $html.="<option value='$IdDoc.$TipoDato.$NumDig' >$NomDoc  </option>";
            }
        }
        $html.="</select> ";

        return $html;
    }

    function num_tipo_pago($TipoPago) {
        $query = parent::Query($sql = "SELECT tiporeci_nombre,tiporeci_num_dig FROM tipo_recibo_pago");

        $html = "<select name='num_tipo_pago' id='num_tipo_pago' onChange=javascript:ChekaTipoRecb(this.value) class='select_insc'>";
        $html.="<option value='0' >Selec. Tipo Recibo</option>";

        while ($Row = parent::ResultAssoc($query)) {
            $tiporeci_nombre = $Row['tiporeci_nombre'];
            $tiporeci_num_dig = $Row['tiporeci_num_dig'];

            if ($TipoPago == $tiporeci_nombre) {
                $html.="<option value='$tiporeci_nombre.$tiporeci_num_dig' selected >$tiporeci_nombre  </option>";
            } else {
                $html.="<option value='$tiporeci_nombre.$tiporeci_num_dig' >$tiporeci_nombre  </option>";
            }
        }
        $html.="</select>";
        return $html;
    }

    function ProcedenciaInscrip($p_idsol) {
        $query = parent::Query($sql = "SELECT locales.ID as id , locales.DES_DR as descripcion FROM procedencia_direc as locales");
        $query2 = parent::Query($sql2 = "SELECT p_idsol,proc_dir,proc_lug FROM personas WHERE p_idsol='$p_idsol'");
        $Row2 = parent::ResultAssoc($query2);
        $html.="<select name='procedencia_direc' id='procedencia_direc' onChange=javascript:cambiarx(this.value) class='dni' >";
        //$html.="<select name='procedencia_direc' id='procedencia_direc' onChange=javascript:cambiarx(this.value) class='dni' >";

        $html.="<option value='0'>Select Proced </option>";
        $proc_dir = $Row2['proc_dir'];
        //echo "<script>alert(\"$proc_dir\")</script>";
        while ($Row = parent::ResultAssoc($query)) {
            $idsolicita = $Row['id'];
            $soli_descripcion = $Row['descripcion'];
            $idsolicita = str_pad($idsolicita, 2, "0", STR_PAD_LEFT);
            if ($idsolicita == $proc_dir) {
                $html .="<option value='$idsolicita' selected>$soli_descripcion </option>";
            } else {
                $html .="<option value='$idsolicita' >$soli_descripcion </option>";
            }
        }
        $html .="</select>";
        return $html;
    }

    function Foto() {

        $html = "<INPUT TYPE='radio' NAME='rbfoto' VALUE='0' style='border:0' CHECKED onClick=javascript:TipoFoto(this.value)>Sin Foto";
        $html .= "<INPUT TYPE='radio' NAME='rbfoto' VALUE='1' style='border:0' onClick=javascript:TipoFoto(this.value)>Foto Fija";
        $html .= "<INPUT TYPE='radio' NAME='rbfoto' VALUE='2' style='border:0' onClick=javascript:TipoFoto(this.value)>Web Cam";
        return $html;
    }

    function TipoTramite($tipo) {

        if ($tipo == '0') {
            $query = parent::Query("SELECT  idsolicita,soli_descripcion FROM solicita");
            $html = "<select name='TipoTramite' id='TipoTramite' class='dni'>";
            $html.="<option value='0'>Selec. Tipo Tramite </option>";
            while ($Row = parent::ResultAssoc($query)) {
                $idsolicita = $Row['idsolicita'];
                $soli_descripcion = $Row['soli_descripcion'];
                if ($idsolicita == 1) {
                    $html.= "<option value='$idsolicita' selected >$soli_descripcion</option>";
                } else {
                    $html.= "<option value='$idsolicita' >$soli_descripcion  </option>";
                }
            }
            $html.="</select>";
        } else {
            $query = parent::Query("SELECT  idsolicita,soli_descripcion FROM solicita");
            $html = "<select name='TipoTramite' id='TipoTramite' class='dni'>";
            $html.="<option value='0'>Selec. Tipo Tramite </option>";
            while ($Row = parent::ResultAssoc($query)) {
                $idsolicita = $Row['idsolicita'];
                $soli_descripcion = $Row['soli_descripcion'];
                if ($idsolicita == $tipo) {
                    $html.= "<option value='$idsolicita' selected >$soli_descripcion</option>";
                } else {
                    $html.= "<option value='$idsolicita' >$soli_descripcion  </option>";
                }
            }
            $html.="</select>";
        }
        return $html;
    }

    function ContenidoGradoInsx() {
        $html = "<select name='grado_ins'class='dni'><option value='0' selected='selected'>--Seleccione--</option>";
        $query = parent::Query("SELECT descripcion,flag FROM grado_instruccion");
        while ($Row = parent::ResultAssoc($query)) {
            $html.=" <option value='" . $Row['flag'] . "'>" . $Row['descripcion'] . "</option>";
        }
        $html.="</select>";

        return $html;
    }

    function ContenidoEstaCivil() {
        $html = "<select name='esta_civil' id='esta_civil' class='dni'>";
        $html.="<option value='0' selected='selected'>--Seleccione--</option>";
        $query = parent::Query("SELECT descripcion,flag FROM estado_civil order by idestado_civil desc");
        while ($Row = parent::ResultAssoc($query)) {
            $html.="<option value='" . $Row['flag'] . "'>" . $Row['descripcion'] . "</option>";
        }
        $html.="</select>";
        return $html;
    }

    function ContenidoMuestraNacionalidad() {

        $html = "<select name='SelectNacionalidad'  id='SelectNacionalidad'  class='dni'>";
        $html.="<option value='0' selected='selected'>--Seleccione--</option>";

        $query = parent::Query("SELECT cod_pais ,DES_NACIONALIDAD  FROM pais_mae");
        while ($Row = parent::ResultAssoc($query)) {
            $html.=" <option value='" . $Row['cod_pais'] . "'>" . $Row['DES_NACIONALIDAD'] . "</option>";
        }

        $html.="</select>";


        return $html;
    }

    /* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */

    function llenadoDias() {
        $html = "<select name='SelectDia'  id='SelectDia' onchange=javascript:muestra() >";
        $html.="<option value='00' selected='selected'>Dia</option>";

        for ($index = 1; $index <= 31; $index++) {
            $index = str_pad($index, 2, "0", STR_PAD_LEFT);
            $html.="<OPTION VALUE='$index'>$index</OPTION>";
        }
        $html.="</select>";

        return $html;
    }

    function llenadoMeses() {

        $html = "<select name='SelectMes'  id='SelectMes' onchange=javascript:muestra2() >";
        $html.="<option value='00' selected='selected'>Mes</option>";
        $Mes = array("01" => "ENE", "02" => "FEB", "03" => "MAR", "04" => "ABR", "05" => "MAY", "06" => "JUN", "07" => "JUL", "08" => "AGO", "09" => "SET", "10" => "OCT", "11" => "NOV", "12" => "DIC");

        foreach ($Mes as $key => $value) {

            $html.="<option value='$key'>$value</option>";
        }


        $html.="</select>";
        return $html;
    }

    function LlenadoAnios() {

        $html = "<select name='SelectAxo'  id='SelectAxo' onchange=javascript:muestra3() >";
        $html.="<option value='00' selected='selected'>a&ntilde;o</option>";

        for ($index = 2008; $index <= 2010; $index++) {
            $index = str_pad($index, 2, "0", STR_PAD_LEFT);
            $html.="<OPTION VALUE='$index'>$index</OPTION>";
        }

        $html.="</select>";
        return $html;
    }

////////////////////*****FUNCIONES EDICION*******///////////////
//
////////////////////*****FUNCIONES EDICION*******///////////////
    function ListadoGenerico($sql, $Name) {

        $KryPais = parent::Query($sql);
        $campos = parent::NumCampos($KryPais);
        $registro = parent::NumReg($KryPais);
        $html.=" <fieldset id='FsGen'><legend><center>Listado de $Name</center></legend>";
        $html.=" <table  border='1'  ><tr>";

        for ($I = 1; $I < $campos; $I++) {

            $nombre_campos = parent::NameCampos($KryPais, $I);
            $html.="<th>$nombre_campos </th>";
        }


        $html.="</tr>";

        while ($resl = parent::ResultArray($KryPais)) {
            $html.="<tr>";


            for ($index = 1; $index < $campos; $index++) {


                $html.="<td>" . $resl[$index] . "</td>";
            }


            $html.="</tr>";
        }

        $html.="</table></fieldset>";

        return $html;
    }

    function ListaEdit() {

        $sql = "SELECT  p_idsol ,p_idsol , p_apepat ,p_apemat,p_nombres  FROM personas   order by p_idsol desc  limit 10";
        $titulo = "Personas";
        $html = self::ListadoGenerico($sql, $titulo);

        return $html;
    }

    function CertificadoEdit($Find) {
        $html = "<br /><fieldset>";
        $html .= "<legend><strong><center> B&uacute;squeda de Solicitudes Ingresadas</center> </strong></legend>";
        $html .="<div id='ViCss' style='width:790px;'><input type='hidden' name='IdsInternos' />";
        $html .= "<div id='Filtros'>" . $this->FiltrosEdit($Find) . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Xprocedencia' style='display:none;'>";
        $html .=$this->SelectProcedencias() . "</div><div id='Busquedas'></div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEditCerti($Find) . "</div>";

        $html .= "</div>";
        return $html;
    }

    function ListadoEditCerti($Find) {
        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != ''  AND p.p_apemat != '' ";
        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] != '') ? " " : "AND p.cod_user = '$Find[4]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Find[3]%' AND p.p_apepat LIKE '$Find[1]%' AND p.p_apemat LIKE '$Find[2]%' $Admin $filterxelmomento ORDER BY p.p_apepat ASC";
            $IdUsuario = $Find[4];
            $Ocultaproc_dir = $Find[5];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            $Admin = ($Find[4] != '') ? " " : "AND p.cod_user = '$Find[3]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') $Admin $filterxelmomento ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
            $Ocultaproc_dir = $Find[4];
        } elseif ($Find[0] == 'Procede') {
            //$Admin = ($Find[4] == '2') ? "AND p.proc_dir='$Find[5]'" : "AND p.cod_user = '$Find[3]'";
            //AND p.proc_dir = '$Find[1]' AND p.proc_lug = '$Find[2]'
            $Admin = " ";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $filterxelmomento ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
            $Ocultaproc_dir = $Find[4];
        } elseif ($Find[0] == 'Doc') {
            $Admin = ($Find[3] != '') ? " " : "AND p.cod_user = '$Find[2]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Find[1]' $Admin $filterxelmomento ORDER BY p.p_idsol ASC";
            $IdUsuario = $Find[2];
            $Ocultaproc_dir = $Find[3];
        } else {
            $Admin = ($Find[1] != '') ? " " : "AND p.cod_user = '$Find[0]'";
	    $filtroHoy = " AND FROM_UNIXTIME(p.p_fechasol,'%Y-%m-%d') = '".date('Y-m-d')."'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $filterxelmomento $filtroHoy ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[0];
            $Ocultaproc_dir = $Find[1];
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 7;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:90px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>APEL. PATERNO&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>APEL. MATERNO&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>NOMBRES&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px;'>F.&nbsp;SOLICITUD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>FOTO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:15px;'>&nbsp;ESTADO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:15px;'>&nbsp;&nbsp;&nbsp;</th>";
        $html .= "</tr></thead><tbody>";
        $contador = 0;


        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $contador++;
                //$ValidaPago = $this->ValidarPago($Rows[7], $Rows[8], $Rows[9]);
                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none'>";
                $html .= "<td>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . ucwords(strtolower($Rows[13])) . "</td>";
                $html .= "<td>" . ucwords(strtolower($Rows[14])) . "</td>";
                $html .= "<td>" . ucwords(strtolower($Rows[15])) . "</td>";

                //$html .= "<td align='center'>" . $ValidaPago[0] . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . " " . $Rows[11] . "</td>";
                $Anti = $this->ResultPersonal($Rows[4], $Rows[0]);
                //$Anticucho = (($Anti == "SI") AND ($ValidaPago[1] == "NO")) ? "BUSCA" : $Anti ;
                //$html .= "<td align='center'>" . $Rows[11] . "</td>"; //HORA
                $Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); /><img src='../Img/negro.gif' style='border:none' /></a>";
                if ($Rows[12] == NULL) {
                    $html .= "<td align='center'></td>"; //FOTO
                } else {
                    $html .= "<td align='center'><img src='../Img/Img/user.png' border='0' /></td>"; //FOTO
                }
                //$html .= "<td align='center'>" . $Anticucho . "</td>";
                $html .= "<td align='center'>" . $Rows[16] . "</td>";
                $html .= "<td align='center'>";
                if ($Ocultaproc_dir != 3) {
                    $html .= "<a href='#' onclick=javascript:MantenimientoDatosEdit('Editar','$Rows[0]'); title='Editar Solicitud' ><img src='../Img/editar_.gif' border='0' /></a>&nbsp;";
                }
                $html .= "</td>";
                $html .= "</tr>";
            }
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' value='$contador' /></td></tr>";
            $html .= "<tr><td colspan='7' class='paginac'>" . $_pagi_navegacion . "</td></tr></table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }

    function FiltrosEdit($Find) {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table border='0' cellpadding='1' cellspacing='1'><tr>";

        if ($Find[1] != 3) {
            $html .= "<td>PROCEDENCIA&nbsp;:</td>";
            $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision(1)' ></td>";
            $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        }
        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision(5)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoEdit(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    //////////////////***********/////////////


    function NumDigXDoc($TipoDoc) {

        $sql = "SELECT  tiporeci_num_dig FROM tipo_recibo_pago  where tiporeci_nombre='$TipoDoc'";

        $re = parent::Query($sql);
        $Row = parent::ResultAssoc($re);
        return $Row['tiporeci_num_dig'];
    }

    /* --------------------------------------- */

    function FormInscSolToma_011010($sede, $p_idsol, $IdUsuario) {
        if ($p_idsol == 0) {
            $html = "<div id='divFormInsc'>";
            $html .= "<center><div id='divButonGraba' class='oculto'></div></center>";
            $html .= "<br><fieldset>";
            $html .= "<legend><strong><center> Inscripci&oacute;n de Solicitudes </center> </strong></legend>";
            $html .= "<table width='830px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row' style='width:70px; overflow:hidden'>APEL. PATERNO :</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apepat' id='apepat' size='25' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "<th align='right' scope='row' style='width:80px; overflow:hidden'>APEL. MATERNO :</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apemat' id='apemat' size='25'  onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "<th align='right' scope='row'>NOMBRES :</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='nombres' id='nombres' size='25' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row' valign='middle'>Tipo Doc </th>";
            $html .= "<td>";
            $html .= self::SelecDoc("DNI");
            $html .= "<br /><br /><input type='button' name='reniec' value='Validar DNI' onclick='javascript:AbrirReniec()'/></td>";

            $html .= "<th align='right'><strong>Nro. Doc</strong></th>";
            $html .= "<td id='numeroDigito'><input type='text' id='NumDoc' name='NumDoc' maxlength='8'  onkeypress='javascript:return valident(event)' ></td>";
            $html .= "<th align='right' valign='middle'>Tipo de Pago</th><td>";
            $html .= self::num_tipo_pago("VOUCHER");
            $max = self::NumDigXDoc("VOUCHER");
            $html .= "<br /><br /><div id='TipoRecibo'><input type='text' name='NumRecibo' maxlength='$max' id='NumRecibo' size='13'  onkeypress='javascript:return valident(event)' /> </div>";
            $html .= "</td>";
            //$html .= "<td>";
            //$html .="</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row'>Fecha: </th>";
            $datex = date('d-m-Y');
            $html .= "<td><input class='InputText' type='text' name='FechaVoucher' id='FechaVoucher' maxlength='10' size='11'  value='$datex' onKeyUp = 'this.value=formateafecha(this.value);' />";
            $html .= "<input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FechaVoucher,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='-1' /></td>";
            $html .= "</td>";

            $html .= "<th align='right'>Solicita </th>";
            $html .= "<td>";
            $html .= self::TipoTramite('0');
            $html .= "</td>";
            $html .= "<th align='right'>Observaciones </th>";
            $html .= "<td colspan='2'>";
            $html .= "<textarea name='tobserva' rows='2' style='width:150px; overflow:hidden'></textarea>";
            $html .= "</td>";
            $html .= "<div id='mensaje' ></div></td>";
            $html .= "</tr>";
            $html.="<tr>";
//            $html.="<th align='left' >Procedencia </th>";
//            $html.="<td colspan='2'>";
//            $html.=self::ProcedenciaInscrip("0");
//            $html.="<div id='localidad'><br> </div>";
//            $html.="</td>";
//            $html.="</tr>";

            $html .= "<tr>";
            $html .= "<th align='right'>Foto:</th>";
            $html .= "<td bgcolor='#DFEAEE' colspan='2'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
            $html .= self::Foto();
            $html .= "</td>";
            $html .= "<th>&iquest;Homonimia?</th>";
            $html .= "<td bgcolor='#DFEAEE'><input type='radio' name='RadioGroup1' style='border:0' value='rsi' id='rsi'  onclick=javascript:mostrarhommonimia();llenaAno(document.bigform.ano);  />";
            $html .= "Si";
            $html .= "<input type='radio' name='RadioGroup1' value='rno' id='rno' style='border:0' CHECKED  onclick=javascript:ocultarhommonimia() />";
            $html .= "No</td></tr>";
        } else {
            $sql = "SELECT p_idsol, p_apepat,p_apemat , p_nombres,p_tipdocu, p_numdocu ,p_tipo,tipo_pago,tipo_img,FROM_UNIXTIME(fec_pago,'%d-%m-%Y') as FechaPago,observacion  FROM personas  where p_idsol ='$p_idsol'  ";
            $resul = parent::Query($sql);
            $Row = parent::ResultAssoc($resul);
            $sql2 = "SELECT g.foto FROM generado_solicitud g where id_generado='$p_idsol'";
            $resul2 = parent::Query($sql2);
            $Row2 = parent::ResultAssoc($resul2);
            $html = "<div id='divFormInsc'>";
            $html .= "<input type='hidden' name='IdsInternos' value='$p_idsol'/>";
            $html .= "<center><div  id='divButonGraba' class='oculto'></div></center>";
            $html .= "<br><fieldset>";
            $html .= "<legend><strong><center> Inscripci&oacute;n de Solicitudes </center> </strong></legend>";
            $html .= "<table width='940px'>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row'>A. Paterno </th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apepat' id='apepat' size='30'  value='" . $Row['p_apepat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "<th align='right' scope='row'>A. Materno </th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apemat' id='apemat' size='30'  value='" . $Row['p_apemat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "<th align='right' scope='row'>Nombres</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='nombres' id='nombres' size='30' value='" . $Row['p_nombres'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row'>Tipo Doc </th>";
            $html .= "<td>";
            $html .= self::SelecDoc($Row['p_tipdocu']);
            $html .= "&nbsp;<input type='button' name='reniec' value='Validar DNI' onclick='javascript:AbrirReniec()'/></td>";
            $html .= "<th align='right'><strong>Nro. Doc</strong></th>";
            $html .= "<td id='numeroDigito'><input type='text' id='NumDoc' name='NumDoc' maxlength='8'  value='" . $Row['p_numdocu'] . "' onkeypress='javascript:return valident(event)' ></td>";
            $html .= "<th align='right'>Tipo de Pago</th><td>";
            $html .= self::num_tipo_pago($Row['tipo_pago']);
            $max = self::NumDigXDoc($Row['tipo_pago']);
            $html .= "</td>";
            $html .= "<td>";
            $html .= "<div id='TipoRecibo' style='float:left'><input type='text' name='NumRecibo' maxlength='$max' id='NumRecibo' size='13'  value='" . $Row['tipo_img'] . "' onkeypress='javascript:return valident(event)' /> </div></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='right' scope='row'>Fecha: </th>";
            $html .= "<td><input class='InputText' type='text' name='FechaVoucher' id='FechaVoucher' maxlength='10' size='10'  value='" . $Row['FechaPago'] . "' onKeyUp = 'this.value=formateafecha(this.value);' />";
            $html .= "<input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FechaVoucher,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='-1' /></td>";
            $html .= "</td>";

            $html .= "<th align='right'>Solicita Para </th>";
            $html .= "<td>";
            $html .= self::TipoTramite($Row['p_tipo']);
            $html .= "</td>";
            $html .= "<th align='right'>Observaciones </th>";
            $html .= "<td colspan='2'>";
            $html .= "<textarea name='tobserva' rows='2' cols='40'>" . $Row['observacion'] . "</textarea>";
            $html .= "</td>";

//            $html.="<tr>";
//            $html.="<th align='left' >Procedencia </th>";
//            $html.="<td colspan='2'>";
//            $html.=self::ProcedenciaInscrip($p_idsol);
//            $html.="<div id='localidad'><br> </div>";
//            $html.="</td>";
//            $html.="</tr>";

            if ($Row2['foto'] == NULL) {
                $html .= "<td>";
                $html .= "</td>";
                $html .= "</tr>";
                $html .= "<tr>";
                $html .= "<th align='left'>Foto:</th>";
                $html .= "<td bgcolor='#DFEAEE'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
                $html .= self::Foto();
                $html .= "</td>";
            }
        }
        $html .= "<div><table border='0' id='homonimiafrom'  border='0' style='width:800px; overflow:hidden'  cellpadding='0' cellspacing='0'>";
        $html .= "<tr>";
        $html .= "<th align='left' scope='row' width='90px' style='width:90px'>Fecha Nacimiento:</th>";
        $html .= "<td>";
        $html .= "<input type='hidden' name='ocultoFechaActual' value='" . date('j/m/Y') . "'>";


        $html .= "<table width='200px' style='width:200px; overflow:hidden'>";
        $html .= "<tr>";
        $html .= "<th>A&ntilde;o</th>";
        $html .= "<th>Mes</th>";
        $html .= "<th>Dia</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td><select name='ano' id='ano' onChange=javascript:cambia(0) >";
        $html .= "</select></td>";
        $html .= "<td><select name='mes'  id='mes' onChange=javascript:cambia(1)  >";
        $html .= "</select></td>";
        $html .= "<td><select name='dia' id='dia' onChange=javascript:cambia(2)  >";
        $html .= "</select></td>";
        $html .= "</tr>";
        $html .= "</table></td>";

        $html .= "</tr>";
        $html .= "<tr >";
        $html .= "<input name='fec_nac' type='hidden' disabled size='14' maxlength='12'>";
        $html .= "<input name='fec_nacx' type='hidden' disabled size='14' maxlength='12'></td>";
        $html .= "<th align='right' scope='row'>Edad</th>";
        $html .= "<td><input name='edad' type='text' id='edad' onkeypress=javascript:return valident(event) size='6' maxlength='2'  readonly='readonly' /></td>";
        $html .= "<th align='right'>Sexo</th>";
        $html .= "<td colspan='3'><select name='sexo' id='sexo' class='dni'>";
        $html .= "<option value='00' selected='selected'>--Seleccione--</option>";
        $html .= "<option value='xy'>Masculino</option>";
        $html .= "<option value='yy'>Femenino</option>";
        $html .= "</select></td>";
        $html .= "<th align='right'   scope='row'> Nacimiento:</th>";
        $html .= "<td colspan='2'>";
        $html .= self::ContenidoMuestraDepartamentos();
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<th align='right' scope='row'>Domicilio </th>";
        $html .= "<td colspan='2'>";
        $html .= "<input name='domicilio' type='text' id='domicilio' size='30' maxlength='200' />";
        $html .= "</td>";

        $html .= "<tr>";
        $html .= "<th align='right' scope='row'>Instrucci&oacute;n</th>";
        $html .= "<td>";
        $html .= self::ContenidoGradoInsx();
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Profesi&oacute;n";
        //$html .= "";
        $html .= "</th>";
        $html .= "<td colspan='3'>";
        $html .= "<input name='pro_ocup type='text' id='pro_ocup' size='22' maxlength='100' />";
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>E. Civil</th>";
        $html .= "<td colspan='3'>";
        $html .= self::ContenidoEstaCivil();
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<th align='right' scope='row'>Nacionalidad</th>";
        $html .= "<td>";
        $html .= self::ContenidoMuestraNacionalidad();
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Padre (Ape. y Nomb.)</th>";
        $html .= "<td colspan='3'>";
        $html .= "<input name='nom_pad' type='text' id='nom_pad' size='22' maxlength='230'  />";
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Madre (Ape. y Nomb.)</th>";
        $html .= "<td colspan='3'><em>";
        $html .= "<input name='nom_mad' type='text' id='nom_mad' size='22' maxlength='230'  />";
        $html .= "</em></td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</div>";
        $html .= "</div>";
        $html .= "<div id='EditionWindow'>" . self::CertificadoEdit($IdUsuario) . "</div>";
        return $html;
    }

    function FotoEstaticaHtml() {
        $html = "<fieldset>";
        $html .= "<legend><strong>Vista Previa - Foto Fija</center></legend>";
        $html .= "<img src='../Img/Foto_Previa/pilar_00001.JPG' width='250' height='300' border='1' alight='center'  />";
        $html .= "<br><hr><input type=button name='EliminaFoto' value='Volver a Tomar' onclick=javascript:VpFotojs(this)>";
        $html .= "&nbsp;<input type=button name='CloseWindow' value='Aceptar' onclick=javascript:VpFotojs(this) >";
        $html .= "</fieldset>";
        return $html;
    }

    function ContenidoValidaSedeCentral($voucher, $fec_pago_unix, $tiporecibo) {

        //$sql = " SELECT count(p_idsol) as numero, p_idsol  FROM personas  where tipo_img='$voucher' and fec_pago='$fec_pago_unix' and tipo_pago='$tiporecibo' group by p_idsol";
        $sql = " SELECT count(p_idsol) as numero, p_idsol  FROM personas  where tipo_img='$voucher' and fec_pago='$fec_pago_unix' and tipo_pago='$tiporecibo' AND p_apepat != '' AND p_apemat != '' group by p_idsol";
        $Query = parent::Query($sql);
        $valor = parent::ResultAssoc($Query);
        $html[] = $valor['numero'];
        $html[] = $valor['p_idsol'];
        return $html;
    }

    function muestra_id_local($codUser) {
        $Query = parent::Query("SELECT u.id_local as id_local FROM usuario u where cod_use='$codUser'");
        while ($result = parent::ResultAssoc($Query)) {
            $cod = $result["id_local"];
        }

        return $cod;
    }

    function BuscaTipodoc($tipdocu) {
        $ValDoc = explode('.', $tipdocu);
        $IdDoc = $ValDoc['0'];
        $Query = parent::Query("SELECT docu_nombre FROM tipo_documento where idtipo_documento='$IdDoc'");
        $Row = parent::ResultAssoc($Query);
        $tipo = $Row['docu_nombre'];
        return $tipo;
    }

    function ContenidoOptienID() {
        $sql = "SELECT max(p_idsol) as numero  FROM personas";
        $Query = parent::Query($sql);
        $num = parent::ResultAssoc($Query);
        return $num['numero'];
    }

    function DatosRegSolicitud($p_idsol) {

        $sql = "SELECT 	p_idsol,p_apepat, p_apemat, p_nombres, p_tipdocu, p_numdocu, p_monto, p_tipo, p_desc, p_fechasol, foto, thumb, tipo_img, flag, email, num_sec, f_val, des_ip_maquina, des_nombre_maquina, factualizacion, cod_user, estado, id_ofic, migrado, buscador, id_local, nom_ofi, observacion, tipo_pago, fec_pago, proc_dir, proc_lug   FROM personas   WHERE p_idsol='$p_idsol' ";

        $resul = parent::Query($sql);


        return parent::ResultAssoc($resul);
    }

    /*     * *************************************************************************************************************************** */

    function GbSolicitudEditada($_POST, $Ubica, $user) {
        $IdsInternos = $_POST["IdsInternos"];
        $apepat = mysql_real_escape_string($_POST["apepat"]);
        $email = mysql_real_escape_string($_POST["email"]);
        $apemat = mysql_real_escape_string($_POST["apemat"]);
        $nomb = mysql_real_escape_string($_POST["nombres"]);
        $tipdocu = mysql_real_escape_string($_POST["tipdocu"]);
        $tipdocu = self::BuscaTipodoc($tipdocu);
        $numdocu = mysql_real_escape_string($_POST["NumDoc"]);
///TIPO DE PAGO
        $tipoPago = mysql_real_escape_string($_POST["num_tipo_pago"]);
        $tobserva = preg_replace("/[\n|\r|\n\r]/i", " ", $_POST["tobserva"]);
        $ValDocPago = explode('.', $tipoPago);
        $tipoPago = $ValDocPago['0'];
        $procedencia_direc = $Ubica;
        //$procedencia_direc=mysql_real_escape_string($_POST["procedencia_direc"]);
        $procedencia_lugar = mysql_real_escape_string($_POST["localidadp"]);

        $fec_pago = self::FechaMysql($_POST["FechaVoucher"]);
        $fec_pago_unix = strtotime($fec_pago);
        $tbanco = $_POST["NumRecibo"];
        $monto = '0.00';


        $num_doc = $numdocu;
        $xva = $user;
        $id_local = $Ubica;

        $desc = $_POST["TipoTramite"];
        //echo "<script>alert(\"$des\")</script>";
        $FotoTupa = $_POST["ImgUrl"];
        $arraySolicitaIdDesc = self::muestra_solicita_cod($desc);

        for ($i = 0; $i < sizeof($arraySolicitaIdDesc[0]); $i++) {
            //echo "<option value='".$arrayDocu[0][$i]."'>".$arrayDocu[1][$i]."</option>";
            $tiposol = $arraySolicitaIdDesc[0][$i];
            $desc = $arraySolicitaIdDesc[1][$i];
        }

////////////////////////////////////////////////////////$test=Contenido::ContenidoValida($tbanco,$fec_pago); modificado x 10 dias
//  $tasa="29.44";//tasa por tupa de antecedentes judiciales a nivel nacional
//no c creo una tabla mantenimiento por motivos de tiempo
        $tasa = "20.27";
        $test[0] = "0000000002027";
        $test[1] = "0";
        $decimal = ($test[0] / 100);
        $valorvoucher = self::ContenidoValidaSedeCentral($tbanco, $fec_pago_unix, $tipoPago);
        $num = self::ContenidoOptienID();
        $idsol = sprintf('%06s', ($num + 1));

        switch (TRUE) {
            case ($tipoPago == 'VOUCHER' && $valorvoucher[0] == 0):
                $sql = "UPDATE personas p SET ";
                $sql.="p_apepat='$apepat'";
                $sql.=",p_apemat='$apemat'";
                $sql.=",p_nombres='$nomb'";
                $sql.=",p_tipdocu='$tipdocu'";
                $sql.=",p_numdocu='$num_doc'";

                $sql.=",p_tipo='$tiposol'";
                $sql.=",p_desc='$desc'";

                $sql.=", tipo_img='$tbanco'";
                $sql.=",observacion='$tobserva'";
                $sql.=",fec_pago='$fec_pago_unix'";
                $sql.=",tipo_pago='$tipoPago'";
                //$sql.=",proc_dir='$procedencia_direc'";
                //$sql.=",proc_lug='$procedencia_lugar'";
                $sql.=",migrado='0'"; //para q sea enviado nuevamente
                $sql.=",buscador='0'"; //para q sea buscado nuevamente
                $sql.=",emite='BUSCA'";
                $sql.=" WHERE p_idsol='$IdsInternos' ";
                $updateI = parent::Query($sql);
                if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                    self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else if ($FotoTupa) {
                    $idFot = explode("/", $_POST['ImgUrl']);
                    //$test=$idFot['6'];
                    //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                    self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else {
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                }
                $updaI = parent::Query($sqlI);
                if ($updateI) {
                    $html = "Edici&oacute;n Grabada con Exito ";
                } else {
                    $html = "No se Grabo:Contacte su Admin ";
                }
                return $html;
                break;
            case ($tipoPago != 'VOUCHER' && $valorvoucher[0] == 0):
                $sql = "UPDATE personas p SET ";
                $sql.="p_apepat='$apepat'";
                $sql.=",p_apemat='$apemat'";
                $sql.=",p_nombres='$nomb'";
                $sql.=",p_tipdocu='$tipdocu'";
                $sql.=",p_numdocu='$num_doc'";
                $sql.=",p_tipo='$tiposol'";
                $sql.=",p_desc='$desc'";
                $sql.=", tipo_img='$tbanco'";
                $sql.=",observacion='$tobserva'";
                $sql.=",fec_pago='$fec_pago_unix'";
                $sql.=",tipo_pago='$tipoPago'";
                //$sql.=",proc_dir='$procedencia_direc'";
                //$sql.=",proc_lug='$procedencia_lugar'";
                $sql.=",migrado='0'"; //para q sea enviado nuevamente
                $sql.=",buscador='0'"; //para q sea buscado nuevamente
                $sql.=",emite='BUSCA'";
                $sql.=" WHERE p_idsol='$IdsInternos' ";
                $updateII = parent::Query($sql);
                if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                    self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else if ($FotoTupa) {
                    $idFot = explode("/", $_POST['ImgUrl']);
                    //$test=$idFot['6'];
                    //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                    self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else {
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                }
                $updaI = parent::Query($sqlI);
                if ($updateII) {
                    $html = "Edici&oacute;n Grabada con Exito ";
                } else {
                    $html = "No se Grabo:Contacte su Admin ";
                }
                return $html;
                break;


            case($valorvoucher[0] == 1):
                $Row = self::DatosRegSolicitud($IdsInternos);
                $sqlVerP = "select * from pagos pa where pa.fech = '" . $fec_pago . "' and pa.num_sec='" . $tbanco . "' and pa.buscador = '0'";
                $updateverP = parent::Query($sqlVerP);
                $nroverP = parent::NumReg($updateverP);
                //&& $nroverP == 1

                if ($Row['tipo_img'] == $tbanco) {
                    $sql = "UPDATE personas p SET ";
                    $sql.="p_apepat='$apepat'";
                    $sql.=",p_apemat='$apemat'";
                    $sql.=",p_nombres='$nomb'";
                    $sql.=",p_tipdocu='$tipdocu'";
                    $sql.=",p_numdocu='$num_doc'";
                    $sql.=",p_tipo='$tiposol'";
                    $sql.=",p_desc='$desc'";
                    $sql.=", tipo_img='$tbanco'";
                    $sql.=",observacion='$tobserva'";
                    $sql.=",fec_pago='$fec_pago_unix'";
                    $sql.=",tipo_pago='$tipoPago'";
                    //$sql.=",proc_dir='$procedencia_direc'";
                    //$sql.=",proc_lug='$procedencia_lugar'";
                    $sql.=",migrado='0'"; //para q sea enviado nuevamente
                    $sql.=",buscador='0'"; //para q sea buscado nuevamente
                    $sql.=",emite='BUSCA'";
                    $sql.=" WHERE p_idsol='$IdsInternos' ";
                    $updateIII = parent::Query($sql);
                    if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                        self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    } else if ($FotoTupa) {
                        $idFot = explode("/", $_POST['ImgUrl']);
                        //$test=$idFot['6'];
                        //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                        self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    } else {
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    }
                    $updaI = parent::Query($sqlI);
                    if ($updateIII) {
                        $html = "Edici&oacute;n Grabada con Exito ";
                    } else {
                        $html = "No se Grabo:Contacte su Admin ";
                    }
                } else {
                    $html = "<i>Error: Para: $fec_pago Existe un $tipoPago  Asignado  a " . $valorvoucher[1] . "</i>";
                }
                break;


            default:
                $html = "dafault";
                break;
        }


        return $html;
    }

    /*     * ******************************************************************************************************************************** */

    function RotarFoto($rutaOrigen, $rutaDestino) {
        include 'Image.php';
        if (file_exists($rutaOrigen)) {
            $I = new Image($rutaOrigen);
            $I->rotate_left();
            $I->write($rutaDestino);
            $I->destroy();
            //$html []=unlink($rutaOrigen);
            return true;
        } else {
            return false;
        }
    }

    function MoverArchivo($rutaOrigen, $rutaDestino) {
        include 'Image.php';
        $I = new Image($rutaOrigen);
        //$I->resize($comprime);
        //$I->rotate_left();
        $I->write($rutaDestino);
        $I->destroy();
        $html [] = unlink($rutaOrigen);
        return $html;
    }

    function GbSolicitud($_POST, $Ubica, $user) {

        $apepat = mysql_real_escape_string($_POST["apepat"]);
        $email = mysql_real_escape_string($_POST["email"]);
        $apemat = mysql_real_escape_string($_POST["apemat"]);
        $nomb = mysql_real_escape_string($_POST["nombres"]);
        $tipdocu = mysql_real_escape_string($_POST["tipdocu"]);
        $tipdocu = self::BuscaTipodoc($tipdocu);
        $numdocu = mysql_real_escape_string($_POST["NumDoc"]);
        ///TIPO DE PAGO
        $tipoPago = mysql_real_escape_string($_POST["num_tipo_pago"]);
        $ValDocPago = explode('.', $tipoPago);
        $tipoPago = $ValDocPago['0'];
        //para poner procendia dentro de id_locales
        //ya no se pone de acuerdo al usuairo que se inscribe si no de acuerdo a lo que escoje el usuario
        //antes---
        //$id_local=mysql_real_escape_string($_POST["procedencia"]);
        $procedencia_direc = $Ubica;
        //$procedencia_direc=intval($_POST["procedencia_direc"]);
        $procedencia_lugar = $_POST["localidadp"];
        //id_local va depender de donde proviene el usuario
        ///para poner observacion
        $tobserva = preg_replace("/[\n|\r|\n\r]/i", " ", $_POST["tobserva"]);
        $SelectDia = mysql_real_escape_string($_POST["dia"]);
        $SelectMes = mysql_real_escape_string($_POST["mes"]);
        $SelectAxo = mysql_real_escape_string($_POST["ano"]);
        //$fec_pago="$SelectAxo-$SelectMes-$SelectDia";
        $fec_pago = self::FechaMysql($_POST["FechaVoucher"]);

        //echo "<script>alert(\"$procedencia_direc\")</script>";
        //echo "<script>alert(\"$procedencia_lugar\")</script>";
        //$fec_pago_unix=self::convert_datetime($fec_pago.'00:00:00');
        //         echo $fecha=date("d/m/Y H:i:s",time());
        //echo "<br>";
        //echo strtotime($fecha);//equivale a time()
        $fec_pago_unix = strtotime($fec_pago);
        $tbanco = $_POST["NumRecibo"];
        //$monto=$_POST["monto"];
        $monto = '0.00';
        $tiposol = $_POST["tiposol"];

//        $lista=$_POST["TipoTramite"];
//        echo "<script>alert(\"$lista\")</script>";

        $desc = $_POST["TipoTramite"];
        //echo "<script>alert(\"$des\")</script>";

        $arraySolicitaIdDesc = self::muestra_solicita_cod($desc);

        for ($i = 0; $i < sizeof($arraySolicitaIdDesc[0]); $i++) {
            //echo "<option value='".$arrayDocu[0][$i]."'>".$arrayDocu[1][$i]."</option>";
            $tiposol = $arraySolicitaIdDesc[0][$i];
            $desc = $arraySolicitaIdDesc[1][$i];
        }

        $num_doc = $numdocu;
        $xva = $user;
        $id_local = $Ubica;

//        echo "<script>alert(\"$fec_pago\")</script>";
//        echo "<script>alert(\"$fec_pago_unix\")</script>";
        ///////////////Datos Homonimia
        $fec_nac = $_POST["fec_nac"];
        $fecha = split('/', $fec_nac);
        $fec_nac = $fecha[3] . "-" . $fecha[2] . "-" . $fecha[0]; //fecha fomateada pa mysql
        $dir_hom = $_POST["domicilio"];
        $nom_pad = $_POST["nom_pad"];
        $nom_mad = $_POST["nom_mad"];
        $grad_ins = $_POST["grado_ins"];
        $pro_ocu = $_POST["pro_ocup"];
        $esta_civl = $_POST["esta_civil"];
        $nac_hom = $_POST["nacionalidad"];
        $edad_hom = $_POST["edad"];
        $sexo_hom = $_POST["sexo"];
        $lugar_naci_hom = $_POST["lug_nac"];
        $FotoTupa = $_POST["ImgUrl"];
	$fecnac1  = $_POST['fecnac'];
        $fec_nac  = explode("-",$fecnac1);
        $fecnac   = $fec_nac[2]."-".$fec_nac[1]."-".$fec_nac[0];
        ////////////////////////////////////////////////////////$test=Contenido::ContenidoValida($tbanco,$fec_pago); modificado x 10 dias
        //  $tasa="29.44";//tasa por tupa de antecedentes judiciales a nivel nacional
        //no c creo una tabla mantenimiento por motivos de tiempo
        $tasa = "20.27";
        $test[0] = "0000000002027";
        $test[1] = "0";
        $decimal = ($test[0] / 100);
        $valorvoucher = self::ContenidoValidaSedeCentral($tbanco, $fec_pago_unix, $tipoPago);
        $num = self::ContenidoOptienID();
        $idsol = sprintf('%06s', ($num + 1));

        switch (TRUE) {
            case ($tipoPago == 'VOUCHER' && $valorvoucher[0] == 0):
                //                $html="Grabo nuevo Voucher $tbanco  $fec_pago_unix".$valorvoucher[0].$valorvoucher[1];
                //////////////////////////////////////////////////////////////////////
                if ($apepat != "" && $apemat != "" && $nomb != "" && $procedencia_direc != "") {
                    $SqlPersonas = "INSERT INTO `personas` VALUES ('$idsol','$apepat','$apemat', '$nomb', '$tipdocu', '$numdocu',  '$monto', '$tiposol', '$desc', '" . time() . "','', '', '$tbanco', '0', '$email', '', '0', '0','0', null, '$xva',null,null,'0','0','$id_local',null,'$tobserva','$tipoPago','$fec_pago_unix','$procedencia_direc','$procedencia_lugar','','BUSCA')";
                    $RSqlPersonas = self::Ejecuta($SqlPersonas);
                    if ($FotoTupa) {
                        $idFot = explode("/", $_POST['ImgUrl']);
                        //$test=$idFot['6'];
                        //echo "<script>alert(\"$test\")</script>";
                        //0--h ttp://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                        self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$idsol.jpg");
                        $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,foto,cod_user) VALUES ('$idsol','0','$idsol.jpg','$xva') ";
                    } else if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                        self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$idsol.JPG");
                        $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,foto,cod_user) VALUES ('$idsol','0','$idsol.jpg','$xva') ";
                    } else {

                        $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,cod_user) VALUES ('$idsol','0','$xva') ";
                    }

                    //$idFot=explode("/",$_POST['ImgUrl']);
                    ////0--http:
                    ////1--
                    ////2--192.168.1.35
                    ////3--sip_pilar
                    ////4--Img
                    ////5--Foto_Previa
                    ////6--20100214172137.jpg
                    //self::MoverArchivo("../Img/Foto_Previa/".$idFot['6'],"../Img/certificados/$idsol.jpg") ;
                    //
                    $RSqlGeneraSol = self::Ejecuta($SqlGeneraSol);
	 	    $SqlGeneraNac  = "INSERT INTO `personas_fecha_nac` (p_idsol,fec_nac) VALUES ('$idsol','$fecnac') ";
                    $RSqlGeneraNac = self::Ejecuta($SqlGeneraNac);

                    if ($_POST["RadioGroup1"] == 'rsi') {
                        $fechaj = $SelectAno."-".$SelectMes."-".$SelectDia;
                        $SqlHom = "INSERT INTO `hommonimia` VALUES (NULL,'$idsol','$fecnac','$dir_hom','$nom_pad','$nom_mad','$grad_ins','$pro_ocu','$esta_civl','$nac_hom','$edad_hom','$sexo_hom','$lugar_naci_hom')  ";
                        $RSqlHom = self::Ejecuta($SqlHom);
                    }
                }
                //////////////////////////////////////////////////////////////////////

                return $RSqlPersonas . "1" . $RSqlGeneraSol . "2" . $RSqlHom;
                break;
            case ($tipoPago != 'VOUCHER' && $valorvoucher[0] == 0):
                //                $html="Grabo nuevo Recibo".$valorvoucher[0].$valorvoucher[1];
                //////////////////////////////////////////////////////////////////////
                if ($apepat != "" && $apemat != "" && $nomb != "" && $procedencia_direc != "") {
                    $SqlPersonas = "INSERT INTO `personas` VALUES ('$idsol','$apepat','$apemat', '$nomb', '$tipdocu', '$numdocu',  '$monto', '$tiposol', '$desc', '" . time() . "','', '', '$tbanco', '0', '$email', '', '0', '0','0', null, '$xva',null,null,'0','0','$id_local',null,'$tobserva','$tipoPago','$fec_pago_unix','$procedencia_direc','$procedencia_lugar','','BUSCA')";
                    //$SqlGeneraSol="INSERT INTO `generado_solicitud` (id_generado,flag,foto,cod_user) VALUES       ('$idsol','0','$idsol.jpg','$xva') ";
                    $RSqlPersonas = self::Ejecuta($SqlPersonas);
                    //  $RSqlGeneraSol=self::Ejecuta($SqlGeneraSol);
                    if ($FotoTupa) {
                        $idFot = explode("/", $_POST['ImgUrl']);
                        //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                        self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$idsol.jpg");
                        $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,foto,cod_user) VALUES ('$idsol','0','$idsol.jpg','$xva') ";
                    } else {

                        $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,cod_user) VALUES ('$idsol','0','$xva') ";
                    }


                    //                if($_FILES["FotoTupa"]["name"]=="") {
                    //                    $SqlGeneraSol="INSERT INTO `generado_solicitud` (id_generado,flag,cod_user) VALUES ('$idsol','0','$xva') ";
                    //                }else {
                    //
                    //                    $SqlGeneraSol="INSERT INTO `generado_solicitud` (id_generado,flag,foto,cod_user) VALUES ('$idsol','0','$idsol.jpg','$xva') ";
                    //                    include 'Image.php';
                    //                    $I = new Image($_FILES["FotoTupa"]["tmp_name"]);
                    //                    $I->resize('125');
                    //                    $I->rotate_left();
                    //
                    //                    $I->write("../Img/certificados/$idsol.jpg");
                    //                    $I->destroy();
                    //                }
                    $RSqlGeneraSol = self::Ejecuta($SqlGeneraSol);
		    
		    $SqlGeneraNac  = "INSERT INTO `personas_fecha_nac` (p_idsol,fec_nac) VALUES ('$idsol','$fecnac') ";
                    $RSqlGeneraNac = self::Ejecuta($SqlGeneraNac);
                    ////////-----------------------------------
                    if ($_POST["RadioGroup1"] == 'rsi') {
                        $SqlHom = "INSERT INTO `hommonimia` VALUES (NULL,'$idsol','$fecnac','$dir_hom','$nom_pad','$nom_mad','$grad_ins','$pro_ocu','$esta_civl','$nac_hom','$edad_hom','$sexo_hom','$lugar_naci_hom')  ";
                        $RSqlHom = self::Ejecuta($SqlHom);
                    }

                    //////////////////////////////////////////////////////////////////////
                    //                include 'Image.php';
                    //                $I = new Image($_FILES["FotoTupa"]["tmp_name"]);
                    //                $I->resize('125');
                    //                //   $I->rotate_left();   -------------------->esto
                    ////$I->save(); // will save the changes back to the file
                    //                $I->write("../Img/certificados/$idsol.jpg");
                    //
                    //                $I->destroy();
                    return $RSqlPersonas . $RSqlGeneraSol . $RSqlHom;
                }
                break;


            case($valorvoucher[0] == 1):

                $html = "<i>Error: Para: $fec_pago Existe un $tipoPago  Asignado  a " . $valorvoucher[1] . "</i>" . "chek:" . $FotoTupa;
                //                return $RSqlPersonas."big".$RSqlGeneraSol.$RSqlHom.$_POST['ImgUrl'];


                break;

            default:
                $html = "default";
                break;
        }






        return $html;
    }

    function RegistraAntecedenteClWs($personas) {

////////////////////



        foreach ($personas as $key => $valor) {
//            $num=self::ContenidoOptienID();
//            $idsol=sprintf('%06s',($num+1)); ResultAntecedentesWS($idsol);
//            $SqlPersonas="INSERT INTO `personas` VALUES ('$idsol','".$valor['p_apepat']."','".$valor['p_apemat']."', '".$valor['p_nombres']."', '".$valor['p_tipdocu']."','".$valor['p_numdocu']."',  '$monto', '".$valor['p_tipo']."', '".utf8_decode($valor['p_desc'])."', '".time()."','', '', '".$valor['tipo_img']."', '0', '$email', '', '0', '0','0', null, '".$valor['cod_user']."',null,null,'0','0','$id_local',null,'$tobserva','".$valor['tipo_pago']."','".$valor['fec_pago']."','".$valor['id_local']."','".$valor['p_idsol']."')";
//
//            $html=self::Ejecuta($SqlPersonas);

            $retorna[$key]['Id'] = $valor['p_idsol'];
            $retorna[$key]['Valor'] = self::GbSolicitudWS($valor);
        }

        return $retorna;
    }

    /* -------------------------------------------------------------------------------------------------------------------------------------------------- */

    function RegistraObservados($observados) {

        foreach ($observados as $key => $valors) {
            $id_solcitud = str_pad($valors['p_idsol'], 6, "0", STR_PAD_LEFT);
            $SqlUpdate = "UPDATE personas SET emite = 'OBSV',buscador= '1' WHERE id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "' ";
            $Execute = parent::Query($SqlUpdate);
            if ($Execute) {
                $html = $Execute;
            } else {
                $html = "Error: No se Grabo Observados en la WS solicitud:$id_solicitud Contacte su Admin ";
            }
            $retorna[$key]['Id'] = $valors['p_idsol'];
            $retorna[$key]['Valor'] = $html;
        }
        return $retorna;
    }

    /* -------------------------------------------------------------------------------------------------------------------------------------------------- */

    function RegistraAtendidos($atendidos) {
	$atend=0;
        $natend=0;
        foreach ($atendidos as $key => $valors) {
            $id_solcitud = str_pad($valors['p_idsol'], 6, "0", STR_PAD_LEFT);
            $SqlUpdate = "UPDATE personas SET emite = 'ATEND',buscador= '1' WHERE id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "' ";
            $Execute = parent::Query($SqlUpdate);

            $sqlgenerado = "select p_idsol, cod_user, tipo_img, fec_pago from personas where id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "'";
            $generado = parent::Query($sqlgenerado);
            $rowG = parent::ResultArray($generado);

            $pidsol = $rowG[0];
            $IDuser = $rowG[1];
            $nro_voucher = $rowG[2];
            $fec_voucher = $rowG[3];

            $Lugar  = $valors['id_local'];
            
            $sqlUg = "update generado_solicitud set flag = '1', fecha = NOW()  where id_generado = '" . $pidsol . "'";
            $queryUq = parent::Query($sqlUg);

            if ($Execute) {
                $html = $Execute;
                $axion_id = 18;
                $sqlAuditoria = "INSERT INTO auditoria (idUsuario,proc_dir, p_idsol, id_solcitud, axion_id, fecha,nro_voucher,fec_voucher)VALUES('".$IDuser."', '".$Lugar."','$pidsol', '$id_solcitud','$axion_id', NOW(),'$nro_voucher','$fec_voucher')";
                $queryAudi    = parent::Query($sqlAuditoria);

            } else {
                $html = "Error: No se Grabo Atendidos en la WS solicitud:$id_solicitud Contacte su Admin ";
            }
            $retorna[$key]['Id'] = $valors['p_idsol'];
            $retorna[$key]['Valor'] = $html;
        }
        return $retorna;
    }
    
    /* -------------------------------------------------------------------------------------------------------------------------------------------------- */

    function RegistraAtendidosOK($atendidos) {
        $atend=0;
        $natend=0;
        foreach ($atendidos as $key => $valors) {
            $id_solcitud = str_pad($valors['p_idsol'], 6, "0", STR_PAD_LEFT);
            $SqlUpdate = "UPDATE personas SET emite = 'ATEND',buscador= '1' WHERE id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "' ";
            $Execute = parent::Query($SqlUpdate);

            $sqlgenerado = "select p_idsol, cod_user, tipo_img, fec_pago from personas where id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "'";
            $generado = parent::Query($sqlgenerado);
            $rowG = parent::ResultArray($generado);

            $pidsol = $rowG[0];
            $IDuser = $rowG[1];
            $nro_voucher = $rowG[2];
            $fec_voucher = $rowG[3];

            $Lugar  = $valors['id_local'];
            
            $sqlUg = "update generado_solicitud set flag = '1', fecha = NOW()  where id_generado = '" . $pidsol . "'";
            $queryUq = parent::Query($sqlUg);
            
            //$SqlUpdateJ = "UPDATE personas SET emite = 'ATEND',buscador= '1' WHERE id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "' ";
            $SqlUpdateJ = "UPDATE personas SET emite = 'ATEND',buscador= '1' where p_idsol = '" . $pidsol . "' ";
            $ExecuteJ = parent::Query($SqlUpdateJ);
            
            if ($Execute) {
                $html = $Execute;
                //$html = "OK";
                $atend++;
                $axion_id = 18;
                $sqlAuditoria = "INSERT INTO auditoria (idUsuario,proc_dir, p_idsol, id_solcitud, axion_id, fecha,nro_voucher,fec_voucher)VALUES('".$IDuser."', '".$Lugar."','$pidsol', '$id_solcitud','$axion_id', NOW(),'$nro_voucher','$fec_voucher')";
                $queryAudi    = parent::Query($sqlAuditoria);

            } else {
                $html = "Error: No se Grabo Atendidos en la WS solicitud:$id_solicitud Contacte su Admin ";
                $natend++;
            }
            $retorna[$key]['Id'] = $valors['p_idsol'];
            $retorna[$key]['Valor'] = $html;
            
        }
        return array($retorna,$atend,$natend);
        //array($Nro, $Matriz)
    }

    /* -------------------------------------------------------------------------------------------------------------------------------------------------- */
    function ControlarAtend($parametro){
        $local    = $parametro;
        //$fechaActual = '2012-08-13';
        $fechaActual = date('Y-m-d');
        
        $Sqlwsj = "select COUNT(*) AS cantidadServ FROM personas WHERE emite = 'ATEND' AND id_local = ".$local." AND FROM_UNIXTIME(p_fechasol, '%Y-%m-%d') = '".$fechaActual."' ";
        $Kryj = parent::Query($Sqlwsj);
        $Filaj = parent::ResultArray($Kryj);  
        $cantidadServer = $Filaj['cantidadServ'];
        
        return $cantidadServer;
    }
    
    //JOL1
    function RegistraAtendidosRESAGADOS($atendidos) {
        $atendr=0;
        $natendr=0;
        foreach ($atendidos as $key => $valors) {
            $id_solcitud = str_pad($valors['p_idsol'], 6, "0", STR_PAD_LEFT);
            $sqlgenerado = "select p_idsol, cod_user, tipo_img, fec_pago from personas where id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "'";
            $generado = parent::Query($sqlgenerado);
            $rowG = parent::ResultArray($generado);
            $pidsol = $rowG[0];
            $IDuser = $rowG[1];
            $nro_voucher = $rowG[2];
            $fec_voucher = $rowG[3];
            $SqlUpdate = "UPDATE personas SET emite = 'ATEND',buscador= '1' where p_idsol = '" . $pidsol . "' ";
            $Execute = parent::Query($SqlUpdate);
            if ($Execute) {
                $atendr++;
            }else{
                $natendr++;
            }
            $retorna[$key]['Id'] = $valors['p_idsol'];
            $retorna[$key]['Valor'] = 1;
            //$retorna[$key]['atendr'] = $atendr;
            //$retorna[$key]['natendr'] = $natendr;
        }
        //return $retorna;
        return array($retorna,$atendr,$natendr);
    }

    function RegistraInvalidos($invalidos) {

        foreach ($invalidos as $key => $valors) {
            $id_solcitud = str_pad($valors['p_idsol'], 6, "0", STR_PAD_LEFT);
            $SqlUpdate = "UPDATE personas SET emite = 'INVAL',buscador= '1' WHERE id_local = '" . $valors['id_local'] . "' AND id_solcitud = '" . $id_solcitud . "' ";
            $Execute = parent::Query($SqlUpdate);
            if ($Execute) {
                $html = $Execute;
            } else {
                $html = "Error: No se Grabo Atendidos en la WS solicitud:$id_solicitud Contacte su Admin ";
            }
            $retorna[$key]['Id'] = $valors['p_idsol'];
            $retorna[$key]['Valor'] = $html;
        }
        return $retorna;
    }

    /* -------------------------------------------------------------------------------------------------------------------------------------------------- */

    function BuscaResultadosClWs($id_local) {
        //$sql = "SELECT  p_idsol, id_solcitud ,emite,nom_ofi FROM personas  where proc_dir='$id_local'  and  id_solcitud !=''  order by p_idsol desc LIMIT 1000";
	$fechaA = date('Y-m-d');
	//and FROM_UNIXTIME(p_fechasol, '%Y-%m-%d') = '2012-08-03' 
	//LIMIT 10
        $sql = "SELECT  p_idsol, id_solcitud ,emite,nom_ofi FROM personas  where proc_dir='$id_local'  and  id_solcitud !='' AND migrado='0' order by p_idsol desc ";
        $Rsql = parent::Query($sql);
        while ($row = parent::ResultAssoc($Rsql)) {
            $i++;
            $html[$i]["Id_solicitud"] = $row['id_solcitud'];
            $html[$i]["Listo"] = $row['emite'];
            $html[$i]["nom_ofi"] = $row['nom_ofi'];
        }
        return $html; //array con los datos solicitados.
    }

/////////////////////////
//  Web Services de Omar
    function ExtraerCoincidenciasLocal($IdLocal) {
        /*$SqlCoin = "SELECT id_soli_local, nombre, fcha, origen, id_interno, sip_name, sexo, region, penal, flag, relacion, secuencial, fec_ingreso, fec_naci, lug_naci, id_local, migra, nom_penal FROM buscador WHERE id_local='$IdLocal' AND migra = '0' limit 1";
        $Krys = parent::Query($SqlCoin);
        $Nro = parent::NumReg($Krys);

        while ($Filas = parent::ResultAssoc($Krys)) {
            $Matriz[] = $Filas;
        }
	*/
	$Nro      = 0;
	$Matriz[] = 0;

        return array($Nro, $Matriz);
    }

//Omar    WebService para ingresar los coincidencias del Servidor al Local
    function ActualizarMigracion($Arrays) {

        foreach ($Arrays as $valors) {
            $SqlUpdate = "UPDATE buscador SET migra = '1' WHERE id_soli_local = '" . $valors['id_soli_local'] . "' and id_local = '" . $valors['id_local'] . "'";
            $Kry = parent::Query($SqlUpdate);
            //  $Num = parent::Query($Kry);
            //echo $SqlUpdate;
        }
//        return $Num;
    }

    //Omar    WebService para ingresar los coincidencias del Servidor al Local
    function MigracionLocal($Arrays) {
        foreach ($Arrays as $valors) {
            $SqlUpdate = "UPDATE personas SET migrado='1' WHERE id_solcitud='" . $valors['id_soli_local'] . "' AND id_local = '" . $valors['id_local'] . "' ";
            $Kry = parent::Query($SqlUpdate);
        }
    }

    function GbSolicitudWS($valor) {

        $apepat = mysql_real_escape_string($valor['p_apepat']);
        $apemat = mysql_real_escape_string($valor['p_apemat']);
        $nomb = mysql_real_escape_string($valor['p_nombres']);
        $tipdocu = $valor['p_tipdocu'];
        $numdocu = mysql_real_escape_string($valor['p_numdocu']);
        $tipoPago = $valor['tipo_pago'];
        $procedencia_direc = $valor['id_local'];
//  $id_solicitud = mysql_real_escape_string($valor['p_idsol']);
        $id_solicitud = str_pad($valor['p_idsol'], 6, "0", STR_PAD_LEFT);
        $tobserva = mysql_real_escape_string($_POST["tobserva"]);
        $fec_pago_unix = $valor['fec_pago'];
        $tbanco = $valor['tipo_img'];
        $tiposol = $valor['p_tipo'];
        $desc = mysql_real_escape_string($valor['p_desc']);
        $num_doc = $numdocu;
        $xva = $valor['cod_user'];
        $valorvoucher = self::ContenidoValidaSedeCentral($tbanco, $fec_pago_unix, $tipoPago); //  Valida Voucher
        $num = self::ContenidoOptienID();   //  Capturar el maximo Valor
        $idsol = sprintf('%06s', ($num + 1));  //  Aumentar Ceros
        $monto = 0;
        $id_local = $procedencia_direc;

        $valorsolicitud = self::ChekeaIdSolicitud($id_solicitud, $procedencia_direc);   //  Id Local Existe en Personal
        if ($valorsolicitud[0]) {
            $IdsInternos = $valorsolicitud[1];
            switch (TRUE) {
                case ($tipoPago == 'VOUCHER' && $valorvoucher[0] == 0):
                    $sql = "UPDATE personas p SET ";
                    $sql.="p_apepat='$apepat'";
                    $sql.=",p_apemat='$apemat'";
                    $sql.=",p_nombres='$nomb'";
                    $sql.=",p_tipdocu='$tipdocu'";
                    $sql.=",p_numdocu='$numdocu'";
                    //$sql.=",p_monto=''";
                    $sql.=", tipo_img='$tbanco'";
                    $sql.=",fec_pago='$fec_pago_unix'";
                    $sql.=",tipo_pago='$tipoPago'";
                    $sql.=",buscador='0'"; //para q sea nuevamente buscado
                    $sql.=",migrado='0'"; //para q sea enviado nuevamente
                    $sql.=",emite='BUSCA'"; //REGRESA ESTADO A BUSCA
                    $sql.=" WHERE p_idsol='$IdsInternos' ";
                    $updateI = parent::Query($sql);
                    $sqlI = "UPDATE generado_solicitud g SET flag='0' WHERE id_generado='$IdsInternos'";
                    $updaI = parent::Query($sqlI);
                    if ($updateI) {
                        $html = $updateI;
                        $axion_id = 15;
                        $sqlAuditoria = "INSERT INTO auditoria (idUsuario,proc_dir, p_idsol, id_solcitud, axion_id, fecha,nro_voucher,fec_voucher)VALUES('$xva', '$procedencia_direc','$IdsInternos', '$id_solicitud','$axion_id', NOW(),'$tbanco','$fec_pago_unix')";
                        $queryAudi    = parent::Query($sqlAuditoria);

                    } else {
                        $html = "Error:No se Grabo en la WS:$id_solicitud Contacte su Admin ";
                    }
                    return $html;
                    break;
                case ($tipoPago != 'VOUCHER' && $valorvoucher[0] == 0):
                    $sql = "UPDATE personas p SET ";
                    $sql.="p_apepat='$apepat'";
                    $sql.=",p_apemat='$apemat'";
                    $sql.=",p_nombres='$nomb'";
                    $sql.=",p_tipdocu='$tipdocu'";
                    $sql.=",p_numdocu='$num_doc'";
                    //$sql.=",p_monto=''";
                    $sql.=", tipo_img='$tbanco'";
                    $sql.=",fec_pago='$fec_pago_unix'";
                    $sql.=",tipo_pago='$tipoPago'";
                    $sql.=",buscador='0'"; //para q sea nuevamente buscado
                    $sql.=",migrado='0'"; //para q sea enviado nuevamente
                    $sql.=",emite='BUSCA'"; //REGRESA ESTADO A BUSCA
                    $sql.=" WHERE p_idsol='$IdsInternos' ";
                    $updateII = parent::Query($sql);
                    $sqlI = "UPDATE generado_solicitud g SET flag='0' WHERE id_generado='$IdsInternos'";
                    $updaI = parent::Query($sqlI);
                    if ($updateII) {
                        $html = $updateII;
                        $axion_id = 15;
                        $sqlAuditoria = "INSERT INTO auditoria (idUsuario,proc_dir, p_idsol, id_solcitud, axion_id, fecha,nro_voucher,fec_voucher)VALUES('$xva', '$procedencia_direc','$IdsInternos', '$id_solicitud','$axion_id', NOW(),'$tbanco','$fec_pago_unix')";
                        $queryAudi    = parent::Query($sqlAuditoria);
                    } else {
                        $html = "Error:No se Grabo en la WS:$id_solicitud Contacte su Admin ";
                    }
                    return $html;
                    break;


                case($valorvoucher[0] == 1):
                    $Row = self::DatosRegSolicitud($IdsInternos);
                    if ($Row['tipo_img'] == $tbanco) {
                        $sql = "UPDATE personas p SET ";
                        $sql.="p_apepat='$apepat'";
                        $sql.=",p_apemat='$apemat'";
                        $sql.=",p_nombres='$nomb'";
                        $sql.=",p_tipdocu='$tipdocu'";
                        $sql.=",p_numdocu='$num_doc'";
                        //$sql.=",p_monto=''";
                        $sql.=", tipo_img='$tbanco'";
                        $sql.=",fec_pago='$fec_pago_unix'";
                        $sql.=",tipo_pago='$tipoPago'";
                        $sql.=",buscador='0'"; //para q sea nuevamente buscado
                        $sql.=",migrado='0'"; //para q sea enviado nuevamente
                        $sql.=",emite='BUSCA'"; //REGRESA ESTADO A BUSCA
                        $sql.=" WHERE p_idsol='$IdsInternos' ";
                        $updateIII = parent::Query($sql);
                        $sqlI = "UPDATE generado_solicitud g SET flag='0' WHERE id_generado='$IdsInternos'";
                        $updaI = parent::Query($sqlI);
                        if ($updateIII) {
                            $html = $updateIII;
                        } else {
                            $html = "Error:No se Grabo en la WS:$id_solicitud Contacte su Admin ";
                        }
                    } else {
                        $html = "$sql";
                    }

                    // $html=$Row['tipo_img'];


                    break;

                default:
                    $html = "default";
                    break;
            }

            //////////
        } else {
            $id_solicitud = str_pad($id_solicitud, 6, "0", STR_PAD_LEFT);

            switch (TRUE) {
                case ($tipoPago == 'VOUCHER' && $valorvoucher[0] == 0):
                    $SqlPersonas = "INSERT INTO `personas` VALUES ('$idsol','$apepat','$apemat', '$nomb', '$tipdocu', '$numdocu',  '$monto', '$tiposol', '$desc', '" . time() . "','', '', '$tbanco', '0', '$email', '', '0', '0','0', null, '$xva',null,null,'0','0','$id_local',null,'$tobserva','$tipoPago','$fec_pago_unix','$procedencia_direc',' ','$id_solicitud','BUSCA')";
                    $RSqlPersonas = self::Ejecuta($SqlPersonas);
                    $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,cod_user) VALUES ('$idsol','0','$xva') ";
                    $RSqlGeneraSol = self::Ejecuta($SqlGeneraSol);
                    $html = $RSqlPersonas;
                    break;
                case ($tipoPago != 'VOUCHER' && $valorvoucher[0] == 0):
                    $SqlPersonas = "INSERT INTO `personas` VALUES ('$idsol','$apepat','$apemat', '$nomb', '$tipdocu', '$numdocu',  '$monto', '$tiposol', '$desc', '" . time() . "','', '', '$tbanco', '0', '$email', '', '0', '0','0', null, '$xva',null,null,'0','0','$id_local',null,'$tobserva','$tipoPago','$fec_pago_unix','$procedencia_direc',' ','$id_solicitud','BUSCA')";
                    $RSqlPersonas = self::Ejecuta($SqlPersonas);
                    $SqlGeneraSol = "INSERT INTO `generado_solicitud` (id_generado,flag,cod_user) VALUES ('$idsol','0','$xva') ";
                    $RSqlGeneraSol = self::Ejecuta($SqlGeneraSol);
                    $html = $RSqlPersonas;
                    break;
                case($valorvoucher[0] == 1):
                    $html = "<i>Error: Para: $fec_pago Existe un $tipoPago  Asignado  a " . $valorvoucher[1] . "</i>";
                    break;
                default:
                    $html = "default";
                    break;
            }
        }
//
//if($idsol==$id_solicitud){
//    //este es el caso de una Actualizacion
//}


        return $html;
    }

    /////////////////
    function ChekeaIdSolicitud($id_solicitud, $Local) {

        $sql = "SELECT p_idsol,count(id_solcitud) as num FROM personas  where id_solcitud='$id_solicitud' AND proc_dir = '$Local'";

        $resultado = parent::Query($sql);
        $Row = parent::ResultAssoc($resultado);

        if ($Row['num'] == 1) {
            $html[] = true;
            $html[] = $Row['p_idsol'];
        } else {
            $html[] = false;
        }

        return $html;
    }

    function Ejecuta($sql) {

        $html = parent::Query($sql);
        return $html;
    }

    function CuentaResultados($sql) {
        $resultado = parent::Query($sql);
        $html = parent::ResultArray($resultado);
        return $html;
    }

    function ContenidoFlag($num_secuencia) {

        $sql = "UPDATE pagos SET flag='1' where num_sec='$num_secuencia' ";

        $Query = parent::Query($sql);


        return $Query;
    }

    function getRealIP() {

        if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $client_ip =
                    (!empty($_SERVER['REMOTE_ADDR']) ) ?
                    $_SERVER['REMOTE_ADDR'] :
                    ( (!empty($_ENV['REMOTE_ADDR']) ) ?
                            $_ENV['REMOTE_ADDR'] :
                            "unknown" );

            // los proxys van a�adiendo al final de esta cabecera
            // las direcciones ip que van "ocultando". Para localizar la ip real
            // del usuario se comienza a mirar por el principio hasta encontrar
            // una direcci�n ip que no sea del rango privado. En caso de no
            // encontrarse ninguna se toma como valor el REMOTE_ADDR

            $entries = split('[, ]', $_SERVER['HTTP_X_FORWARDED_FOR']);

            reset($entries);
            while (list(, $entry) = each($entries)) {
                $entry = trim($entry);
                if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
                    // http://www.faqs.org/rfcs/rfc1918.html
                    $private_ip = array(
                        '/^0\./',
                        '/^127\.0\.0\.1/',
                        '/^192\.168\..*/',
                        '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
                        '/^10\..*/');

                    $found_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);

                    if ($client_ip != $found_ip) {
                        $client_ip = $found_ip;
                        break;
                    }
                }
            }
        } else {
            $client_ip =
                    (!empty($_SERVER['REMOTE_ADDR']) ) ?
                    $_SERVER['REMOTE_ADDR'] :
                    ( (!empty($_ENV['REMOTE_ADDR']) ) ?
                            $_ENV['REMOTE_ADDR'] :
                            "unknown" );
        }

        return $client_ip;
    }

    function ipCheck() {



        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

    function ContenidoLog($axion, $id_user, $ip_user, $ip_user_proxy) {


        $sql = "INSERT INTO `log` (`id_log`,`axion`,`id_user`,`ip_user`,`ip_user_proxy`,`fec_hor`) VALUES (NULL ,'$axion','$id_user','$ip_user','$ip_user_proxy',NOW( ))";

        $Query = parent::Query($sql);

        return $Query;
    }

    /*  ----------------------------------  BIG1    ----------------------------------------- */

    function Bienvenida() {

        $html = "<center><h2>SIP - ANTECEDENTES</h2></center>";
        $html.="<div id='divContenedor'>" . $this->Estadisticas() . "</div>";
        $html.= "<div id='detalle_estadistica'>" . $this->Listado_detalle($proce, $fecha) . "</div>";




        return $html;
    }

    /* Administracion */

    function AdmListaUser() {

        $SqlPais = "SELECT  idUsuario,idUsuario, usu_logi as Login  ,concat(Usu_pate,'',usu_mate,' ',usu_nomb) as NOMBRES   FROM Usuario  ";

        $KryPais = parent::Query($SqlPais);
        $campos = parent::NumCampos($KryPais);
        $registro = parent::NumReg($KryPais);



        $html.=" <table border='1' align='center'><tr>";

        for ($I = 2; $I < $campos; $I++) {

            $nombre_campos = parent::NameCampos($KryPais, $I);
            $html.="<th>$nombre_campos </th>";
        }

        /**/
        $html.="<th colspan='2'>Opc. </th>";

        /**/

        $html.="</tr>";

        while ($resl = parent::ResultArray($KryPais)) {
            $html.="<tr>";


            for ($index = 2; $index < $campos; $index++) {


                $html.="<td>" . $resl[$index] . "</td>";
            }

            $html.="<td><img src='../Img/user_edit.png' alt='Editar User'     onClick=javascript:Editar_User('$resl[0]');> </td>";
            $html.="<td><img src='../Img/user_delete.png' alt='Borrar User' onClick=javascript:Eliminar_User('$resl[0]');> </td>";

            $html.="</tr>";
        }

        $html.="</table>";

        return $html;
    }

    function ValidaUserySexatrans($user, $pass) {

        $pass = mysql_real_escape_string($pass);
        $user = mysql_real_escape_string($user);
        $pass = md5($pass);
        $query = parent::Query($sql = "SELECT  idUsuario, usu_logi,usu_flag,usu_tipo,proc_id,ubica FROM Usuario  where  usu_flag='1' and  usu_logi='$user' and usu_pass  ='$pass'  ");
        $Row = parent::ResultAssoc($query);
        $num = parent::NumReg($query);

        if ($num > 0) {
            $sqlPa = "select * from procedencia_direc where ID = " . $Row["proc_id"];
            $queryP = parent::Query($sqlPa);
            $RowP = parent::ResultAssoc($queryP);
        }

        $retorna[] = $Row["usu_logi"];
        $retorna[] = $Row["idUsuario"];
        $retorna[] = $Row["usu_tipo"];
        $retorna[] = $Row["proc_id"];
        $retorna[] = $num;
        $retorna[] = $Row["ubica"];
        $retorna[] = $Row["usu_tipo"];
        $retorna[] = $RowP["id_parent"];

        return $retorna;
    }

    /* ////////////////////////////////////////////////////////OMAR0/////////////////////////////////////////////////////////////////// */

//  Combo Select Region OMAR
    function regiones() {
        $sql = "SELECT idregiones,descrip_region FROM regiones WHERE flag = '1' ORDER BY descrip_region ASC";
        $query = parent::Query($sql);
        while ($result = parent::ResultArray($query)) {
            $cod[] = $result["idregiones"];
            $nom[] = $result["descrip_region"];
        }
        return array($cod, $nom);
    }

//  Gun para Cargar Regiones
    function cargar_regiones($Id) {
        $html = "";
        $arrayDep = $this->regiones();
        for ($i = 0; $i < sizeof($arrayDep[0]); $i++) {
            if ($Id == $arrayDep[0][$i]) {
                $html .= "<option value='" . $arrayDep[0][$i] . "' selected >" . $arrayDep[1][$i] . "</option>";
            } else {
                $html .= "<option value='" . $arrayDep[0][$i] . "'>" . $arrayDep[1][$i] . "</option>";
            }
        }
        return $html;
    }

//  Cargando el Combo
    function ComboRegion($IdReg) {
        $html = "<SELECT name='oficReg' id='oficReg' tabindex='9' onchange='javascript:penales(this.value);' style='width:100%;' class='InputText'>";
        $html .= "<option value='0'>[ Seleccione Region ]</option>";
        $html .= $this->cargar_regiones($IdReg);
        $html .= "</SELECT>";
        return $html;
    }

//  Cargando el Combo
    function ComboPenal() {

        $html = "<div id='idpenales'>";
        $html .= "<SELECT name='idpenal' id='idpenal' tabindex='10' style='width:100%;' class='InputText'>";
        $html .= "<option value='0'>[ Seleccione Penal ]</option>";
        $html .= "</SELECT>";
        $html .= "</div>";
        return $html;
    }

    /*  Formulario que contiene los Formularios de Registro    */

    function FormContainerRegistro($Patron) {
        $html = "<br /><div id='ViCss' style='width:750px;'>";
        $html .="<input type='hidden' name='IdName' /><input type='hidden' name='IdsInternos' />";
        $html .= "<div id='Filtros'>" . $this->FormFiltrosInternos() . "</div>";
        $html .= "<div id='ContainerRegistro'>" . $this->FormRegistroInterno($Patron) . "</div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoInterno($Patron) . "</div>";
        $html .= "</div>";
        return $html;
    }

    /*  Formulario de Registro de Interno       */

    function FormRegistroInterno($UserTip) {
        $Region = $UserTip[2];  //  Codigo de Region

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='3' cellspacing='0'><tr>";
        $html .= "<td>PATERNO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='PatInt' id='PatInt' tabindex='5' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>MATERNO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='MatInt' id='MatInt' tabindex='6' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>NOMBRE&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='NomInt' id='NomInt' tabindex='7' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td><input type='button' name='GrabarInt' id='GrabarInt' value='<< Grabar >>' onclick='javascript:InsertarData(this.name);' $evento  $estiloopc width:100px;' tabindex='11'></td>";
        $html .= "</tr><tr>";
        $html .= "<td>SEXO&nbsp;:&nbsp;</td>";
        $html .= "<td><select class='InputText' name='SexInt' tabindex='8' style='width:100%;' ><option value='001'>HOMBRE</option><option value='002'>MUJER</option></select></td>";
        $html .= "<td>REGION : </td>";
        $html .= "<td><div id='RegionCombo'>" . $this->ComboRegion($Region) . "</div></td>";
        $html .= "<td>PENAL : </td>";
        $html .= "<td colspan='2'>" . $this->ComboPenal() . "</td>";
        $html .= "</tr></table>";

        return $html;
    }

    /*  Formulario de Busqueda de Interno       */

    function FormFiltrosInternos() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>NOMBRES&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:ViewFind(1)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>FECHA&nbsp;REGISTRO&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:ViewFind(2)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>TODO&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:ViewFind(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td><input type='hidden' name='CajaFind' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscarInterno' value='<< Buscar >>' onclick='javascript:InsertarData(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>";
        $html .= "<div id='DivCancel'></div>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table><div id='ShowFind'></div>";
        return $html;
    }

    /*  Funcion Listado de Todos los Internos   */

    function ListadoInterno($Ids) {

        if ($Ids[3] == 'Nombres') {
            if ($Ids[0] == 'S') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(TRIM(I.DES_APE_PATERNO),' ',TRIM(I.DES_APE_MATERNO),' ',TRIM(I.DES_NOMBRES)) AS Nombres,R.descrip_region,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN regiones R ON I.COD_REGION = R.idregiones LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.IND_ESTADO = '1' AND I.DES_APE_PATERNO LIKE '$Ids[4]%' AND I.DES_APE_MATERNO LIKE '$Ids[5]%' AND I.DES_NOMBRES LIKE '$Ids[6]%' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:130px;'>REGION</td>";
                $Columnas .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '4';
            } else if ($Ids[0] == 'R') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.COD_REGION = '$Ids[2]' AND I.IND_ESTADO = '1' AND I.DES_APE_PATERNO LIKE '$Ids[4]%' AND I.DES_APE_MATERNO LIKE '$Ids[5]%' AND I.DES_NOMBRES LIKE '$Ids[6]%' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '3';
            } else {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I WHERE I.COD_REGION = '$Ids[2]' AND I.COD_PENAL = '$Ids[1]'  AND I.IND_ESTADO = '1' AND I.DES_APE_PATERNO LIKE '$Ids[4]%' AND I.DES_APE_MATERNO LIKE '$Ids[5]%' AND I.DES_NOMBRES LIKE '$Ids[6]%' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "";
                $Colspan = '2';
            }
        } elseif ($Ids[3] == 'Fechas') {
            $Ini = $this->FechaMysql($Ids[4]);
            $Fin = $this->FechaMysql($Ids[5]);
            if ($Ids[0] == 'S') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,R.descrip_region,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN regiones R ON I.COD_REGION = R.idregiones LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.IND_ESTADO = '1' AND DATE(I.AUD_FEC_REGISTRO) >= '$Ini' AND DATE(I.AUD_FEC_REGISTRO) <= '$Fin' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:130px;'>REGION</td>";
                $Columnas .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '4';
            } else if ($Ids[0] == 'R') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.COD_REGION = '$Ids[2]'  AND I.IND_ESTADO = '1' AND DATE(I.AUD_FEC_REGISTRO) >= '$Ini' AND DATE(I.AUD_FEC_REGISTRO) <= '$Fin' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '3';
            } else {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I WHERE I.COD_REGION = '$Ids[2]' AND I.COD_PENAL = '$Ids[1]' AND I.IND_ESTADO = '1' AND DATE(I.AUD_FEC_REGISTRO) >= '$Ini' AND DATE(I.AUD_FEC_REGISTRO) <= '$Fin' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "";
                $Colspan = '2';
            }
        } else {
            if ($Ids[0] == 'S') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,R.descrip_region,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN regiones R ON I.COD_REGION = R.idregiones LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.IND_ESTADO = '1' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:130px;'>REGION</td>";
                $Columnas .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '4';
            } else if ($Ids[0] == 'R') {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,P.abreviatura_descrip,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I LEFT JOIN penales P  ON I.COD_PENAL = P.idpenales WHERE I.COD_REGION = '$Ids[2]' AND I.IND_ESTADO = '1' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>PENAL</td>";
                $Colspan = '3';
            } else {
                $_pagi_sql = "SELECT I.COD_INCULPADO,CONCAT(I.DES_APE_PATERNO,' ',I.DES_APE_MATERNO,' ',I.DES_NOMBRES) AS Nombres,I.AUD_FEC_REGISTRO FROM IDE_INCULPADO_MAE I WHERE I.COD_REGION = '$Ids[2]' AND I.COD_PENAL = '$Ids[1]' AND I.IND_ESTADO = '1' ORDER BY I.AUD_FEC_REGISTRO DESC";
                $Columnas = "";
                $Colspan = '2';
            }
        }
        $var = $Ids[2] . ' ' . $Ids[1];
        echo "<script>alert(" . $_pagi_sql . ")</script>";
        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];
        $_pagi_result = parent::Query($_pagi_sql);

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .="<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("Paginador.cls.php");

        $NumReg = parent::NumReg($_pagi_result);
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px;width:730px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:220px;'>NOMBRE&nbsp;COMPLETO</td>";
        $html .= $Columnas;
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:35px;'></td></tr>";

        /* while ($Rows = parent::ResultArray($_pagi_result)) {
          if ($Ids[0] == 'S')
          $Filas = "<td>" . $Rows[2] . "</td><td>" . $Rows[3] . "</td>";
          if ($Ids[0] == 'R')
          $Filas = "<td>" . $Rows[2] . "</td>";
          if ($Ids[0] == 'P')
          $Filas = "";
          $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none'>";
          $html .= "<td>" . $Rows[1] . "</td>";
          $html .= $Filas;
          $html .= "<td>";
          $html .= "<a href='#' onclick=javascript:MantenimientoDatos('Eliminar','$Rows[0]'); title='Eliminar' ><img src='../Img/del.gif' border='0' /></a>&nbsp;";
          $html .= "<a href='#' onclick=javascript:MantenimientoDatos('Editar','$Rows[0]'); title='Editar' ><img src='../Img/edi.gif' border='0' /></a>&nbsp;";
          $html .= "<a href='#' onclick=javascript:MantenimientoDatos('Asociado','$Rows[0]'); title='Nombre Asociado' ><img src='../Img/aso.gif' border='0' /></a>";
          $html .= "</td>";
          $html .= "</tr>";
          } */
        $html .= "<tr><td colspan='" . $Colspan . "'>" . $_pagi_navegacion . "</td></tr>";
        $html .= "</table>";

        return $html;
    }

    /*      Formulario de Nombres Asociados     */

    function FormRegistroAsociados($IdInt) {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $Names = $this->FullNameInterno($IdInt);
        $html = "<table border='0' cellpadding='3' cellspacing='0'><tr>";
        $html .= "<td colspan='5'><u>INTERNO&nbsp;(A)  :</u>&nbsp;&nbsp;<i>" . $Names[0] . "&nbsp;" . $Names[1] . "&nbsp;" . $Names[2] . "</i></td>";
        $html .= "</tr><tr><td><br /></td></tr><tr>";
        $html .= "<td>PATERNO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='PatAso' id='PatAso' tabindex='1' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>MATERNO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='MatAso' id='MatAso' tabindex='2' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>NOMBRE&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='NomAso' id='NomAso' tabindex='3' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "</tr><tr><td><br /></td></tr><tr>";
        $html .= "<td colspan='2'><input type='button' name='GrabarAsocia' id='GrabarAsocia' value='<< Grabar Asociado >>' onclick='javascript:InsertarData(this.name);' $evento  $estiloopc width:150px;' tabindex='4'></td>";
        $html .= "<td colspan='2'><input type='button' value='<< Retroceder >>' onclick='javascript:SalirProceso();' $evento  $estiloopc width:150px;' tabindex='5'></td>";
        $html .= "</tr></table>";

        return $html;
    }

    /*  Listado de los Nombres Asociados de un Interno      */

    function ListNombreAsociado($IdInt) {
        $KryList = parent::Query("SELECT CONCAT(DES_NOMBRES,' ',DES_APE_PATERNO,' ', DES_APE_MATERNO) AS Nombres  FROM IDE_INCULPADO_NOMBRES_MOV WHERE COD_INCULPADO = '$IdInt'");
        $NumList = parent::NumReg($KryList);

        $html = "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px;width:500px;'>";
        $html .= "<tr><td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:500px;'>NOMBRE&nbsp;COMPLETO</td></tr>";
        if ($NumList >= 1) {
            while ($Fila = parent::ResultArray($KryList)) {
                $html .= "<tr><td>" . $Fila[0] . "</td></tr>";
            }
        } else {
            $html .= "<tr><td align='center'>&nbsp;Ning&uacute;n&nbsp;Nombre&nbsp;Asociado</td></tr>";
        }
        $html .= "</table>";

        return $html;
    }

    /*  Funcion para Identificar el Tipo de Usuario */

    function TipoUsuario($Tipo) {
        $Opcion = array(substr($Tipo, 0, 4), substr($Tipo, 4));
        //echo "<script>alert(".$Opcion[0]." ".$Opcion[1].")</script>";
        if (($Opcion[0] == '0000') && ($Opcion[1] == '01')) {
            $TipoUsu = array('S', $Opcion[0], $Opcion[1]);
        } else if (($Opcion[0] == '0000') && ($Opcion[1] != '01')) {
            $TipoUsu = array('R', $Opcion[0], $Opcion[1]);
        } else {
            $TipoUsu = array('P', $Opcion[0], $Opcion[1]);
        }
        return $TipoUsu;
    }

    /*  Funcion de Fecha */

    function FechaMysql($Fecha) {
        $FecArray = split("-", $Fecha);
        $FechaMysql = $FecArray[2] . "-" . $FecArray[1] . "-" . $FecArray[0];
        return $FechaMysql;
    }

//  Function Reemplazar con diferentes valores
    function SustituirTexto($Texto) {

        $var1 = array('[a|aa|AA|à|á|ä|å|ã|æ|â|A|À|Á|Ä|Å|Ã|Æ|Â|ß]', '[e|ee|EE|è|é|ê|ë|E|È|É|Ê|Ë]', '[i|ii|II|ì|í|î|ï|I|Ì|Í|Î|Ï|y|ÿ|ý|Y|Ÿ|Ý|l|ll|L|LL]',
            '[o|oo|OO|ö|ô|õ|ò|ó|ø|O|Ö|Ô|Õ|Ò|Ó|Ø|0]', '[u|uu|UU|ü|û|ù|ú|U|Ü|Û|Ù|Ú]', '[b|B|v|V]', '[c|s|z|C|S|Z|K|k|Q|q]',
            '[g|j|G|J]', '[ñ|Ñ|n|nn|NN|N]', '[r|R|rr|RR]', '[t|T|tt|TT]'); //valores a ser buscados

        $var2 = array('[aàáäåãæâAÀÁÄÅÃÆÂß]', '[eèéêëEÈÉÊË]', '[iìíîïIÌÍÎÏyÿýYŸÝlL]',
            '[oöôõòóøOÖÔÕÒÓØ0]', '[uüûùúUÜÛÙÚ]', '[bBvV]', '[cszCSZKkQq]', '[gjGJ]', '[ñÑnN]', '[rR]', '[tT]'); //valores a ser reemplazados
        return preg_replace($var1, $var2, $Texto); //se busca var1 en texto y se reemplaza por var2
    }

//  Capturar el primer caracter
    function PrimeraLetra($BuscarTexto) {
        $palabras = split(' ', preg_replace("[\s+]", ' ', trim($BuscarTexto)));
        for ($i = 0; $palabras[$i]; $i++) {
            $Search .= substr($palabras[$i], 0, 1);
        }
        return $Search;
    }

    function juegoPalabras($paterno) {
	$criterioo1 = '';
        $criterioo2 = '';
	$criterioo3 = '';

	$var1 = array('/Z/', '/M/', '/B/', '/R/'); //,'/s/'
        $var2 = array('S', 'N', 'V', 'L'); //,'z'

        $NombreNuevo = preg_replace($var1, $var2, $paterno);
        $NombreNuevo1 = $NombreNuevo;

        $var1 = array('/N/', '/V/'); //,'/s/'
        $var2 = array('M', 'B'); //,'z'
        $NombreNuevo = preg_replace($var1, $var2, $NombreNuevo);

        $criterioo1 .= $NombreNuevo;
        $var1 = array('/S/', '/N/', '/V/', '/LL/'); //,'/s/'
        $var2 = array('Z', 'M', 'B', 'Y'); //,'z'
        $NombreNuevo = preg_replace($var1, $var2, $paterno);

        $criterioo2 .= $NombreNuevo;
        $var1 = array('/S/', '/N/', '/V/', '/Y/'); //,'/s/'
        $var2 = array('Z', 'M', 'B', 'LL'); //,'z'
        $NombreNuevo = preg_replace($var1, $var2, $paterno);

        $criterioo3 .= $NombreNuevo;

        $var1 = array('/B/'); //,'/s/'
        $var2 = array('V'); //,'z'
        $otro = preg_replace($var1, $var2, $paterno);

        $var1 = array('/R$/'); //,'/s/'$patron = "/^ca/"
        $var2 = array('L'); //,'z'
        $otro1 = preg_replace($var1, $var2, $otro);

        $var1 = array('/I/'); //,'/s/'
        $var2 = array('A'); //,'z'
        $otro2 = preg_replace($var1, $var2, $paterno);

        $var1 = array('/N/'); //,'/s/'
        $var2 = array('NN'); //,'z'
        $otro3 = preg_replace($var1, $var2, $paterno);

        $var1 = array('/Y/'); //,'/s/'
        $var2 = array('I'); //,'z'
        $otroY = preg_replace($var1, $var2, $paterno);

        $var1 = array('/RR/'); //,'/s/'
        $var2 = array('R'); //,'z'
        $otroR = preg_replace($var1, $var2, $paterno);
	
	$var1 = array('/I/'); //,'/s/'
        $var2 = array('Y'); //,'z'
        $otroI = preg_replace($var1, $var2, $paterno);

        //return $paterno."*".$NombreNuevo1."*".$criterioo1."*".$criterioo2."*".$criterioo3."*".$otro."*".$otro1."*".$otro2;
        return $paterno . "*" . $NombreNuevo1 . "*" . $criterioo1 . "*" . $criterioo2 . "*" . $criterioo3 . "*" . $otro . "*" . $otro1 . "*" . $otro2 . "*" . $otro3 . "*" . $otroY . "*" . $otroR. "*" . $otroI;
    }

//  Funcion Para Buscar una Solicitud en el SIP
    function BuscadorSolicitud($Id, $Name, $NombreBuscar, $Usuario, $Lugar, $Fecha, $Numero, $Tipo, $Buscar, $Pater, $Mater, $Nombr, $IdSoLocal, $Emite) {
	$SearchHerman = "";
	$SearchHermano = "";
//if ($Emite != 'VOUCH') {
//  Nombre Principal Exacto
//  AntesIF $SqlExac = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip.IDE_INCULPADO_MAE IIMA LEFT JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE TRIM(IIMA.DES_APE_PATERNO) = TRIM('$Pater') AND TRIM(IIMA.DES_APE_MATERNO) = TRIM('$Mater') AND TRIM(IIMA.DES_NOMBRES) = TRIM('$Nombr') AND IF(LENGTH(IIMA.COD_INCULPADO) > 5,IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1'),IIMO.COD_SECUENCIAL IS NULL)  LIMIT 1";
        $SqlExac = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE TRIM(IIMA.DES_APE_PATERNO) = TRIM('$Pater') AND TRIM(IIMA.DES_APE_MATERNO) = TRIM('$Mater') AND TRIM(IIMA.DES_NOMBRES) = TRIM('$Nombr') AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1') LIMIT 1";
        $KryExac = parent::Query($SqlExac);
        $NumExac = parent::NumReg($KryExac);
        if ($NumExac == 1) {
            $FilaExac = parent::ResultArray($KryExac);
	    $escapePaterJ = str_replace("'","\'",$FilaExac[1]);
	    $FilaExac[1] = $escapePaterJ;

            $SqlInsert = "INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$FilaExac[0]','$FilaExac[1]','$FilaExac[2]','$FilaExac[3]','$FilaExac[4]', '$FilaExac[5]','$FilaExac[6]','$FilaExac[7]','$FilaExac[8]','$IdSoLocal','$Lugar','$FilaExac[9]')";
            parent::Query($SqlInsert);
            $Emitir = 'POSIT';
        } elseif ($NumExac == 0) {
//  Nombre Asociado Exacto
//    $SqlExAso = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, (SELECT PE.ABREV_DES_PENAL FROM sip.PEN_PENAL_MAE PE WHERE PE.COD_PENAL=PPO.COD_PENAL) AS 'PENAL' FROM sip.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE TRIM(IINM.DES_APE_PATERNO) = TRIM('$Pater') AND TRIM(IINM.DES_APE_MATERNO) = TRIM('$Mater') AND TRIM(IINM.DES_NOMBRES) = TRIM('$Nombr') AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1') LIMIT 1";
            echo $SqlExAso = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE TRIM(IINM.DES_APE_PATERNO) = TRIM('$Pater') AND TRIM(IINM.DES_APE_MATERNO) = TRIM('$Mater') AND TRIM(IINM.DES_NOMBRES) = TRIM('$Nombr') AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1') LIMIT 1";
            $KryExAso = parent::Query($SqlExAso);
            $NumExAso = parent::NumReg($KryExAso);
            if ($NumExAso == 1) {
                $FilExAso = parent::ResultArray($KryExAso);
                parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$FilExAso[0]','$FilExAso[1]','$FilExAso[2]','$FilExAso[3]','$FilExAso[4]', '$FilExAso[5]','$FilExAso[6]','$FilExAso[7]','$FilExAso[8]','$IdSoLocal','$Lugar','$FilExAso[9]')");
                $Emitir = 'POSIT';
            } elseif ($NumExAso == 0) {

//  Juego de Palabras con el Nombre Completas en Tabla Principal
//  $SqlMain = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip.IDE_INCULPADO_MAE IIMA LEFT JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) RLIKE '$NombreBuscar' AND IF(LENGTH(IIMA.COD_INCULPADO) > 5,IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1'),IIMO.COD_SECUENCIAL IS NULL)";
		
		/*                
		$SqlMain = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                $KryMain = parent::Query($SqlMain);
                $NumMain = parent::NumReg($KryMain);
                if ($NumMain >= 1) {
                    while ($Fila = parent::ResultArray($KryMain)) {
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$Fila[0]','$Fila[1]','$Fila[2]','$Fila[3]','$Fila[4]', '$Fila[5]','$Fila[6]','$Fila[7]','$Fila[8]','$IdSoLocal','$Lugar','$Fila[9]')");
                    }
                }
		*/
		$NumMain=0;
//  Juego de Palabras con el Nombre Completas en Tabla Asociaddos
//	$SqlAso = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, (SELECT PE.ABREV_DES_PENAL FROM sip.PEN_PENAL_MAE PE WHERE PE.COD_PENAL=PPO.COD_PENAL) AS 'PENAL' FROM sip.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(IINM.DES_APE_PATERNO,' ',IINM.DES_APE_MATERNO,' ',IINM.DES_NOMBRES) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
		/*                
		$SqlAso = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(IINM.DES_APE_PATERNO,' ',IINM.DES_APE_MATERNO,' ',IINM.DES_NOMBRES) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                $KryAso = parent::Query($SqlAso);
                $NumAso = parent::NumReg($KryAso);
                if ($NumAso >= 1) {
                    while ($FilAso = parent::ResultArray($KryAso)) {
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$FilAso[0]','$FilAso[1]','$FilAso[2]','$FilAso[3]','$FilAso[4]', '$FilAso[5]','$FilAso[6]','$FilAso[7]','$FilAso[8]','$IdSoLocal','$Lugar','$FilAso[9]')");
                    }
                }
		*/
		$NumAso=0;
//  Busquedad de Hermanos Principal
                $Hermanos = explode(" ", $Nombr);
                for ($i = 0; $i < count($Hermanos); $i++) {
                    $SearchHermano .= "IIMA.DES_NOMBRES LIKE '%$Hermanos[$i]%' OR ";
                }
//  Juego de Palabras con Apellidos Completas y Nombres Filtrados en Tabla Principal
//        $SqlMain2 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip.IDE_INCULPADO_MAE IIMA LEFT JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO)) RLIKE '".$this->SustituirTexto($Pater.' '.$Mater)."' AND (".substr($SearchHermano,0,-4).") AND IF(LENGTH(IIMA.COD_INCULPADO) > 5,IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1'),IIMO.COD_SECUENCIAL IS NULL)";
		$dfa = explode("-", $Mater);
		$cguion = 0;
		for($r=0;$r < count($dfa); $r++){
			if($dfa[$r]=='-'){
				$cguion++;			
			}
		}                
// || $cguion<=3
		if ($Mater == '---' || $Mater == '') {
                    $criterioMate = "";
                } else {
		    $otroM = substr($Mater, 0, 4);
                    $criterioMate = " AND (MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$otroM*' IN BOOLEAN MODE) )";
                }

                //$SqlMain2 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO)) RLIKE '" . $this->SustituirTexto($Pater . ' ' . $Mater) . "' AND (" . substr($SearchHermano, 0, -4) . ") AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                //AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')

                $otro = substr($Nombr, 0, 3);
		$otroP = substr($Pater, 0, 4);
		

                echo $SqlMain2 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,
                IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
                FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL
                PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
                (MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$otroP*' IN BOOLEAN MODE))
                $criterioMate
                AND (MATCH(IIMA.DES_NOMBRES) AGAINST ('$otro*' IN BOOLEAN MODE))";

                $KryMain2 = parent::Query($SqlMain2);
                $NumMain2 = parent::NumReg($KryMain2);
                if ($NumMain2 >= 1) {
                    while ($Fila2 = parent::ResultArray($KryMain2)) {
			$escapePaterJ = str_replace("'","\'",$Fila2[1]);
	    		$Fila2[1] = $escapePaterJ;
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$Fila2[0]','$Fila2[1]','$Fila2[2]','$Fila2[3]','$Fila2[4]', '$Fila2[5]','$Fila2[6]','$Fila2[7]','$Fila2[8]','$IdSoLocal','$Lugar','$Fila2[9]')");
                    }
                }
//  Busquedad de Hermanos Asociado
                $Herman = explode(" ", $Nombr);
                for ($is = 0; $is < count($Herman); $is++) {
                    $SearchHerman .= "IINM.DES_NOMBRES LIKE '%$Herman[$is]%' OR ";
                }
//  Juego de Palabras con Apellidos Completas y Nombres Filtrados en Tabla Asociados
//	$SqlAso2 = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC,(SELECT PE.ABREV_DES_PENAL FROM sip.PEN_PENAL_MAE PE WHERE PE.COD_PENAL=PPO.COD_PENAL) AS 'PENAL' FROM sip.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO = IIMA.COD_INCULPADO INNER JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO)) RLIKE '".$this->SustituirTexto($Pater.' '.$Mater)."' AND (".substr($SearchHerman,0,-4).") AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                //$SqlAso2 = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC,PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO = IIMA.COD_INCULPADO INNER JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO)) RLIKE '" . $this->SustituirTexto($Pater . ' ' . $Mater) . "' AND (" . substr($SearchHerman, 0, -4) . ") AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                if ($Mater == '---' || $Mater == '') {
                    $criterioMat = "";
                } else {
                    $criterioMat = " AND MATCH(IINM.DES_APE_MATERNO) AGAINST ('" . $Mater . "*' IN BOOLEAN MODE)  ";
                }

                //AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')

                $SqlAso2 = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC,PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO = IIMA.COD_INCULPADO INNER JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE MATCH(IINM.DES_APE_PATERNO) AGAINST ('" . $Pater . "*' IN BOOLEAN MODE) $criterioMat AND MATCH(IINM.DES_NOMBRES) AGAINST ('" . $Nombr . "*' IN BOOLEAN MODE) AND (" . substr($SearchHerman, 0, -4) . ")";
                $KryAso2 = parent::Query($SqlAso2);
                $NumAso2 = parent::NumReg($KryAso2);
                if ($NumAso2 >= 1) {
                    while ($FilAso2 = parent::ResultArray($KryAso2)) {
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$FilAso2[0]','$FilAso2[1]','$FilAso2[2]','$FilAso2[3]','$FilAso2[4]', '$FilAso2[5]','$FilAso2[6]','$FilAso2[7]','$FilAso2[8]','$IdSoLocal','$Lugar','$FilAso2[9]')");
                    }
                }

//  Juego de Palabras con los Apellidos reverso en la Tabla Principal
//  $MainSql = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip.IDE_INCULPADO_MAE IIMA LEFT JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_NOMBRES)) RLIKE '$NombreBuscar' AND IF(LENGTH(IIMA.COD_INCULPADO) > 5,IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1'),IIMO.COD_SECUENCIAL IS NULL)";
		
		
                /*
		$MainSql = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_NOMBRES)) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
		*/

		$MainSql = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE 
 CONCAT(TRIM( IF(IIMA.DES_APE_MATERNO!='',IIMA.DES_APE_MATERNO,'')  ),' ',TRIM(IF(IIMA.DES_APE_PATERNO!='',IIMA.DES_APE_PATERNO,'')),' ',TRIM(IIMA.DES_NOMBRES)) LIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";

                $MainKry = parent::Query($MainSql);
                $MainNum = parent::NumReg($MainKry);
                if ($MainNum >= 1) {
                    while ($Alif = parent::ResultArray($MainKry)) {
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$Alif[0]','$Alif[1]','$Alif[2]','$Alif[3]','$Alif[4]', '$Alif[5]','$Alif[6]','$Alif[7]','$Alif[8]','$IdSoLocal','$Lugar','$Alif[9]')");
                    }
                }
//  Juego de Palabras con Apellidos reverso en la Tabla Asociaddos
//        $AsoSql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, (SELECT PE.ABREV_DES_PENAL FROM sip.PEN_PENAL_MAE PE WHERE PE.COD_PENAL=PPO.COD_PENAL) AS 'PENAL' FROM sip.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO = IIMA.COD_INCULPADO INNER JOIN sip.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(IINM.DES_APE_MATERNO,' ',IINM.DES_APE_PATERNO,' ',IINM.DES_NOMBRES) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
		/*                
		$AsoSql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO = IIMA.COD_INCULPADO INNER JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(IINM.DES_APE_MATERNO,' ',IINM.DES_APE_PATERNO,' ',IINM.DES_NOMBRES) RLIKE '$NombreBuscar' AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                $AsoKry = parent::Query($AsoSql);
                $AsoNum = parent::NumReg($AsoKry);
                if ($AsoNum >= 1) {
                    while ($AsoFil = parent::ResultArray($AsoKry)) {
                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$AsoFil[0]','$AsoFil[1]','$AsoFil[2]','$AsoFil[3]','$AsoFil[4]', '$AsoFil[5]','$AsoFil[6]','$AsoFil[7]','$AsoFil[8]','$IdSoLocal','$Lugar','$AsoFil[9]')");
                    }
                }
		*/
		$AsoNum = 0;
                //verdadero juego de palabras con apellido paterno y nombre
                //si no ingresa a los criterios anteriores
                if ($NumMain2 == 0 && $AsoNum == 0 && $MainNum == 0 && $NumAso2 == 0 && $NumAso == 0 && $NumMain == 0) {
                    //apellido Paterno
                    $listado = explode("*", $this->juegoPalabras($Pater));

                    $crite1 = $listado[0];
                    $crite2 = $listado[1];
                    $crite3 = $listado[2];
                    $crite4 = $listado[3];
                    $crite5 = $listado[4];
                    $crite6 = $listado[5];
                    $crite7 = $listado[6];
                    $crite8 = $listado[7];
                    $crite9 = $listado[9];
                    $crite10 = $listado[10];
		    $crite11 = $listado[11]; 	

                    $cadenaT = " (MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite1*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite2*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite3*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite4*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite6*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite7*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite8*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite9*' IN BOOLEAN MODE)";
                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite10*' IN BOOLEAN MODE)";
		    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite11*' IN BOOLEAN MODE)";
                    //$cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite10*' IN BOOLEAN MODE)";

                    $cadenaT .= " OR MATCH(IIMA.DES_APE_PATERNO) AGAINST ('$crite5*' IN BOOLEAN MODE)) ";

                    //nombre

                    $listadoN = explode("*", $this->juegoPalabras($Nombr));

                    $criteN1 = $listadoN[0];
                    $criteN2 = $listadoN[1];
                    $criteN3 = $listadoN[2];
                    $criteN4 = $listadoN[3];
                    $criteN5 = $listadoN[4];
                    $criteN6 = $listadoN[5];
                    $criteN7 = $listadoN[6];
                    $criteN8 = $listadoN[7];
                    $criteN9 = $listadoN[8];
                    $criteN10 = $listadoN[9];

                    $cadenaN = " AND (MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN1*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN2*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN3*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN4*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN6*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN7*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN8*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN9*' IN BOOLEAN MODE)";
                    //$cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN10*' IN BOOLEAN MODE)";
                    $cadenaN .= " OR MATCH(IIMA.DES_NOMBRES) AGAINST ('$criteN5*' IN BOOLEAN MODE)) ";

                    if ($Mater == '---' || $Mater == '') {
                        $criterioMate = "";
                    } else {

                        //materno
                        $listadoM = explode("*", $this->juegoPalabras($Mater));

                        $criteM1 = $listadoM[0];
                        $criteM2 = $listadoM[1];
                        $criteM3 = $listadoM[2];
                        $criteM4 = $listadoM[3];
                        $criteM5 = $listadoM[4];
                        $criteM6 = $listadoM[5];
                        $criteM7 = $listadoM[6];
                        $criteM8 = $listadoM[7];
                        $criteM9 = $listadoM[8];
                        $criteM10 = $listadoM[9];
                        $criteM11 = $listadoM[10];
			$criteM12 = $listadoM[11];

                        $cadenaM = " AND (MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM1*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM2*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM3*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM4*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM6*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM7*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM8*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM9*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM10*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM11*' IN BOOLEAN MODE)";
			$cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM12*' IN BOOLEAN MODE)";
                        $cadenaM .= " OR MATCH(IIMA.DES_APE_MATERNO) AGAINST ('$criteM5*' IN BOOLEAN MODE)) ";

                        //$criterioMate = " AND MATCH(IIMA.DES_APE_MATERNO) AGAINST ('".$Mater."*' IN BOOLEAN MODE)  "; 
                    }

                    //$SqlMain2 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME, IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO)) RLIKE '" . $this->SustituirTexto($Pater . ' ' . $Mater) . "' AND (" . substr($SearchHermano, 0, -4) . ") AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
                    //AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')
                    
                    
                    $SqlMain21 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,
                    IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
                    FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL
                    PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
                    $cadenaT
                    $cadenaM
                    $cadenaN  
		    GROUP BY IIMA.COD_INCULPADO
		    ";

                    $KryMain21 = parent::Query($SqlMain21);
                    $NumMain21 = parent::NumReg($KryMain21);
                    if ($NumMain21 >= 1) {
                        while ($Fila21 = parent::ResultArray($KryMain21)) {
                            parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$Fila21[0]','$Fila21[1]','$Fila21[2]','$Fila21[3]','$Fila21[4]', '$Fila21[5]','$Fila21[6]','$Fila21[7]','$Fila21[8]','$IdSoLocal','$Lugar','$Fila21[9]')");
                        }
                    }
                }

                if ($NumMain2 == 0 && $AsoNum == 0 && $MainNum == 0 && $NumAso2 == 0 && $NumAso == 0 && $NumMain == 0 && $NumMain21 == 0 && ($Pater == 'MORE' || $Mater == 'MORE')) {


                    $longitudM = strlen($Mater);
                    $longitudP = strlen($Pater);
                    $longitudN = strlen($Nombr);

                    if ($longitudP >= 4 && $longitudM >= 4) {

                        if ($Pater != 'MORE' && $Mater != 'MORE') {
                            $cadenaB = $Pater . " " . $Mater;
                        } elseif ($Pater == 'MORE' && $Mater != 'MORE') {
                            $cadenaB = $Mater;
                        } elseif ($Pater != 'MORE' && $Mater == 'MORE') {
                            $cadenaB = $Pater;
                        }
                        //$cadenaB = $Pater." ".$Mater;
                    } elseif ($longitudP < 4 && $longitudM >= 4) {
                        $cadenaB = $Mater;
                    } elseif ($longitudP >= 4 && $longitudM < 4) {
                        $cadenaB = $Pater;
                    }

                    $criterioNB = " MATCH(IIMA.DES_APE_PATERNO, IIMA.DES_APE_MATERNO) AGAINST ('$cadenaB*' IN BOOLEAN MODE)";
                    $criterioNN = "AND MATCH(IIMA.DES_NOMBRES) AGAINST ('$Nombr*' IN BOOLEAN MODE)";

                    $SqlMain22 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,
                    IIMA.COD_TT1_SEXO, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
                    FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN sip_omar.PEN_POBLACION_PENAL
                    PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
                    $criterioNB
                    $criterioNN
		    GROUP BY IIMA.COD_INCULPADO
		    ";

                    $KryMain22 = parent::Query($SqlMain22);
                    $NumMain22 = parent::NumReg($KryMain22);
                    if ($NumMain22 >= 1) {
                        while ($Fila22 = parent::ResultArray($KryMain22)) {
                            parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','$Fila22[0]','$Fila22[1]','$Fila22[2]','$Fila22[3]','$Fila22[4]', '$Fila22[5]','$Fila22[6]','$Fila22[7]','$Fila22[8]','$IdSoLocal','$Lugar','$Fila22[9]')");
                        }
                    }
                }
		
		// POPE$
                //echo "fklsf";
                //echo $NumMain22."--".$NumMain21."--".$NumMain2."--".$AsoNum."--".$MainNum."--".$NumAso2."--".$NumAso."--".$NumMain;
                //exit;
                
                if ($NumMain22 >= 0 || $NumMain21>=0 || $NumMain2 >= 0 || $AsoNum >= 0 || $MainNum >= 0 || $NumAso2 >= 0 || $NumAso >= 0 || $NumMain >= 0){
                    echo "entro";
                    $host = "10.4.0.11\sql2k5";
                    $user = "joncebay";
                    $psw = "123456";
                    $db = "db_inpe_gob_pe_sip_pope";

                    $cn = mssql_connect($host, $user, $psw) or die("Error al Conectarse al Servidor!");
                    $db = mssql_select_db("db_inpe_gob_pe_sip_pope", $cn);
		    
		    $escapeSqlPater = str_replace("\'","''",$Pater);
                    $escapeSqlMater = str_replace("\'","''",$Mater);
		    $escapeSqlNombr = str_replace("\'","''",$Nombr);
			
                    //"\'";

                    $sqlPope = mssql_query("select  
						int_id, 
						int_cod_rp, 
						int_ape_pat, 
						int_ape_mat,
						int_nom, 
						dbo.fn_fecha(int_fec_nac) as 'FechaNacimiento',
						dbo.fn_tablaparametrica('sexo_nombre',sex_id)  as sex_id,
						dbo.fn_ubigeo('departamento',ubg_id_nac) as ubi, int_dir_nom,
						dbo.fn_tablaparametrica('NivelAcademico_nombre',niv_Aca_id)  as niv_Aca,
						dbo.fn_tablaparametrica('profesion_nombre',pro_id)  as profesion,
						dbo.fn_tablaparametrica('ocupacion_nombre',ocu_id)  as ocupacion,
						dbo.fn_tablaparametrica('EstadoCivil_nombre',est_civ_id)  as estadocivil,
						dbo.fn_tablaparametrica('nacionalidad_nombre',nac_id)  as nac_id,
						(int_doc_num) as DNI,
						aut_fam_pad, 
						aut_fam_mad, 
						dbo.FN_InternoIngreso('fecha',dbo.FN_InternoIngreso('ingreso_id',int_id)) as FechaIngreso,
						_PenId, 
						int_hij_men 
						from 
						int_interno 
						where 
						_flg_eli=0
						and _penid in ( select d.pen_id  from sys_instalaciondetalle d
						where 
						ins_id >1 
						and _flg_eli=0
						) 
						and _penid in (select pen_id from int_penal where _flg_eli = 0 and pen_tip = 1 and pen_est = 1) 
						AND int_ape_pat = '".strtoupper($escapeSqlPater)."' 
						AND int_ape_mat = '".strtoupper($escapeSqlMater)."'
						AND int_nom = '".strtoupper($escapeSqlNombr)."' 
						order by 
						int_id") or die("Error de consulta SQL");

                    $NumMain23 = mssql_num_rows($sqlPope);
                    if ($NumMain23 >= 1) {
                        while ($Fila23 = mssql_fetch_array($sqlPope)) {
                            $sqlPenal = "SELECT pen_id, pen_cod from INT_Penal where pen_id = ".$Fila23['_PenId'];
                            $queryPenal = mssql_query($sqlPenal);
                            $rowPenal   = mssql_fetch_array($queryPenal);
                            $codPenal = $rowPenal['pen_cod'];

                            $completo = $Fila23['int_ape_pat']." ".$Fila23['int_ape_mat']." ".$Fila23['int_nom'];
                            //echo $completo;
                            $fechaMysql = explode("/",$Fila23[FechaNacimiento]);
                            $fechaNuevo = $fechaMysql[2]."-".$fechaMysql[1]."-".$fechaMysql[0];

                            $fechaMysqli = explode("/",$Fila23[FechaIngreso]);
                            $fechaIngres = $fechaMysqli[2]."-".$fechaMysqli[1]."-".$fechaMysqli[0];
                            //$Fila23[FechaIngreso]

                            parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','".$Fila23['int_cod_rp']."','$completo','$Fila23[sex_id]','$codPenal','$Fila23[FechaIngreso]', '$Fila23[FechaNacimiento]','$fechaIngres','$fechaNuevo','$Fila23[ubi] - $Fila23[int_dir_nom]','$IdSoLocal','$Lugar','$Fila23[_PenId]')");
                        }
                    }else{
                            
                            //lo ideal es buscar en todas las BD's por eso el cambio 28-03-14 JOL
				echo "entro 2";
				$host = "10.4.0.11\sql2k5";
				$user = "joncebay";
				$psw = "123456";
				$db = "db_inpe_gob_pe_sip_pope";

				$cn = mssql_connect($host, $user, $psw) or die("Error al Conectarse al Servidor!");
				$db = mssql_select_db("db_inpe_gob_pe_sip_pope", $cn);

				$escapeSqlPater = str_replace("\'","''",$Pater);
				$escapeSqlMater = str_replace("\'","''",$Mater);
				$escapeSqlNombr = str_replace("\'","''",$Nombr);
				
				$sqlPopej = mssql_query("select  
						int_id, 
						int_cod_rp, 
						int_ape_pat, 
						int_ape_mat,
						int_nom, 
						dbo.fn_fecha(int_fec_nac) as 'FechaNacimiento',
						dbo.fn_tablaparametrica('sexo_nombre',sex_id)  as sex_id,
						dbo.fn_ubigeo('departamento',ubg_id_nac) as ubi, int_dir_nom,
						dbo.fn_tablaparametrica('NivelAcademico_nombre',niv_Aca_id)  as niv_Aca,
						dbo.fn_tablaparametrica('profesion_nombre',pro_id)  as profesion,
						dbo.fn_tablaparametrica('ocupacion_nombre',ocu_id)  as ocupacion,
						dbo.fn_tablaparametrica('EstadoCivil_nombre',est_civ_id)  as estadocivil,
						dbo.fn_tablaparametrica('nacionalidad_nombre',nac_id)  as nac_id,
						(int_doc_num) as DNI,
						aut_fam_pad, 
						aut_fam_mad, 
						dbo.FN_InternoIngreso('fecha',dbo.FN_InternoIngreso('ingreso_id',int_id)) as FechaIngreso,
						_PenId, 
						int_hij_men 
						from 
						int_interno 
		                where _flg_eli=0
		                and _penid in (select d.pen_id  from sys_instalaciondetalle d
		                where ins_id >1 and _flg_eli=0
		                )  
				and _penid in (select pen_id from int_penal where _flg_eli = 0 and pen_tip = 1 and pen_est = 1) 
				AND int_ape_pat like '%".strtoupper(substr($escapeSqlPater,0,4))."%'
		                AND int_ape_mat like '%".strtoupper($escapeSqlMater)."%'
		                AND int_nom like '%".strtoupper(substr($escapeSqlNombr,0,3))."%'
		                order by int_id") or die("Error de consulta SQL2");

				$NumMain24 = mssql_num_rows($sqlPopej);
				
				if($NumMain24 >= 1){
		                    while ($Fila24 = mssql_fetch_array($sqlPopej)) {
		                        $sqlPenal = "SELECT pen_id, pen_cod from INT_Penal where pen_id = ".$Fila24['_PenId'];
		                        $queryPenal = mssql_query($sqlPenal);
		                        $rowPenal   = mssql_fetch_array($queryPenal);
		                        $codPenal = $rowPenal['pen_cod'];

		                        $completo = $Fila24['int_ape_pat']." ".$Fila24['int_ape_mat']." ".$Fila24['int_nom'];
		                        //echo $completo;
		                        $fechaMysql = explode("/",$Fila24[FechaNacimiento]);
		                        $fechaNuevo = $fechaMysql[2]."-".$fechaMysql[1]."-".$fechaMysql[0];

		                        $fechaMysqli = explode("/",$Fila24[FechaIngreso]);
		                        $fechaIngres = $fechaMysqli[2]."-".$fechaMysqli[1]."-".$fechaMysqli[0];
		                        //$Fila23[FechaIngreso]

		                        parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal','".$Fila24['int_cod_rp']."','$completo','$Fila24[sex_id]','$codPenal','$Fila24[FechaIngreso]', '$Fila24[FechaNacimiento]','$fechaIngres','$fechaNuevo','$Fila24[ubi] - $Fila24[int_dir_nom]','$IdSoLocal','$Lugar','$Fila24[_PenId]')");
		                    }
		                }
                        
                    }
                        
			

				// sybase
                                    $hostS = "orion.inpe.gob.pe:5000";
		                    $userS = "JONCEBAYL";
		                    $pswS  = "123456";
		                    $dbS   = "sip";
				    
				    echo "sybase";

		                    $cnS = sybase_connect($hostS,$userS,$pswS);
		                    $dbS = sybase_select_db("sip",$cnS);

		                    sybase_query("SET TEXTSIZE 2147483647",$cnS);
		                    ini_set('sybase.textlimit' , '2147483647');
		                    ini_set('sybase.textsize' , '2147483647'); 
		                    
		                    $escapeSqlPater = str_replace("\'","''",$Pater);
                            	    $escapeSqlMater = str_replace("\'","''",$Mater);
				    $escapeSqlNomb  = str_replace("\'","''",$Nombr);
                                    $escapeSqlMater = str_replace("Ñ","&Ntilde;",$escapeSqlMater);
                                    $escapeSqlMater = html_entity_decode($escapeSqlMater);
                        	
				    if ($escapeSqlMater == '---' || $escapeSqlMater == '' || $escapeSqlMater == '.') {
                                	$criterioMateS = "";
	                            } else {
                                	$criterioMateS = " AND IIMA.DES_APE_MATERNO = ltrim(rtrim('".$escapeSqlMater."')) ";
                            	    }
                                    
                                    /*
                                    echo "SELECT  IIMA.COD_INCULPADO, IIMA.DES_APE_PATERNO + ' ' + IIMA.DES_APE_MATERNO + ' ' + IIMA.DES_NOMBRES AS FULLNAME,
		                    IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' 
		                    FROM IDE_INCULPADO_MAE IIMA LEFT JOIN IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN PEN_POBLACION_PENAL 
		                    PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE 
		                    IIMA.DES_APE_PATERNO = ltrim(rtrim('".$escapeSqlPater."'))  
				    $criterioMateS 
				    AND 
		                    ltrim(rtrim(IIMA.DES_NOMBRES)) = ltrim(rtrim('".$Nombr."')) AND 
		                    IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) 
		                    FROM IDE_INCULPADO_MOV A WHERE
		                    A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO <> '2')";
                                    */
                                    
		                    $sqlSybase = sybase_query("SELECT  IIMA.COD_INCULPADO, IIMA.DES_APE_PATERNO + ' ' + IIMA.DES_APE_MATERNO + ' ' + IIMA.DES_NOMBRES AS FULLNAME,
		                    IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' 
		                    FROM IDE_INCULPADO_MAE IIMA LEFT JOIN IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN PEN_POBLACION_PENAL 
		                    PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE 
		                    IIMA.DES_APE_PATERNO = ltrim(rtrim('".$escapeSqlPater."'))  
				    $criterioMateS 
				    AND 
		                    ltrim(rtrim(IIMA.DES_NOMBRES)) = ltrim(rtrim('".$escapeSqlNomb."')) AND 
		                    IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) 
		                    FROM IDE_INCULPADO_MOV A WHERE
		                    A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO <> '2')");
		                    
		                    $NumMain25 = sybase_num_rows($sqlSybase);
		                    
                                    
                                    
                                    
		                    if($NumMain25 >= 1){
		                        while($Fila25 = sybase_fetch_array($sqlSybase)){
                                            parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal-JOL','$Fila25[0]','$Fila25[1]','$Fila25[2]','$Fila25[3]','$Fila25[4]', '$Fila25[5]','$Fila25[6]','$Fila25[7]','$Fila25[8]','$IdSoLocal','$Lugar','$Fila25[9]')");
		                        }
                                        //continue;
		                    }else{
                                        // inculpado mov
		                        $hostS = "orion.inpe.gob.pe:5000";
		                        $userS = "JONCEBAYL";
		                        $pswS  = "123456";
		                        $dbS   = "sip";

		                        $cnS = sybase_connect($hostS,$userS,$pswS);
		                        $dbS = sybase_select_db("sip",$cnS);

		                        sybase_query("SET TEXTSIZE 2147483647",$cnS);
		                        ini_set('sybase.textlimit' , '2147483647');
		                        ini_set('sybase.textsize' , '2147483647'); 
		                        
		                        $escapeSqlPater = str_replace("\'","''",$Pater);
		                    	$escapeSqlMater = str_replace("\'","''",$Mater);
					$escapeSqlNomb  = str_replace("\'","''",$Nombr);
                                        $escapeSqlMater = str_replace("Ñ","&Ntilde;",$escapeSqlMater);
                                        $escapeSqlMater = html_entity_decode($escapeSqlMater);
		                        
					if ($escapeSqlMater == '---' || $escapeSqlMater == '' || $escapeSqlMater == '.') {
                                		$criterioMateS = "";
                            		} else {
                                		$criterioMateS = " AND IIMA.DES_APE_MATERNO LIKE ltrim(rtrim('%".$escapeSqlMater."%')) ";
                            		}
                                        
                                        
                                        //exit;
                                        
		                        $sqlSybasej = sybase_query("SELECT  IIMA.COD_INCULPADO, IIMA.DES_APE_PATERNO + ' ' + IIMA.DES_APE_MATERNO + ' ' + IIMA.DES_NOMBRES AS FULLNAME,
		                        IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL' 
		                        FROM IDE_INCULPADO_MAE IIMA LEFT JOIN IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN PEN_POBLACION_PENAL 
		                        PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE 
		                        IIMA.DES_APE_PATERNO LIKE ltrim(rtrim('%".$escapeSqlPater."%'))  
					$criterioMateS 					
					AND 
		                        ltrim(rtrim(IIMA.DES_NOMBRES)) LIKE ltrim(rtrim('%".$escapeSqlNomb."%')) AND 
		                        IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL) 
		                        FROM IDE_INCULPADO_MOV A WHERE
		                        A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO <> '2')");
                                        
		                        $NumMain26 = sybase_num_rows($sqlSybasej);
                                        
                                        //echo $NumMain26;
                                        //exit;
                                    
		                        if($NumMain26 >= 1){
		                            while($Fila26 = sybase_fetch_array($sqlSybasej)){
		                                parent::Query("INSERT INTO buscador (id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, penal, flag, secuencial, fec_ingreso, fec_naci, lug_naci, id_soli_local, id_local,nom_penal) VALUES ('','$Id','$Name',NOW(),'Principal-JOL-ASO','$Fila26[0]','$Fila26[1]','$Fila26[2]','$Fila26[3]','$Fila26[4]', '$Fila26[5]','$Fila26[6]','$Fila26[7]','$Fila26[8]','$IdSoLocal','$Lugar','$Fila26[9]')");
		                            }
		                        }
                                        
                                    }
                                    
                                    
                                        
                    
               	
	
        }



//  Datos para el campo de Emision
                //$Emitir = $this->ResultAntecedentes($Id);
                //echo $NumMain23." - ".$NumMain25." - ".$NumMain24." - ".$NumMain26;
                
		if ($NumMain23 >= 1 || $NumMain25 >= 1 && ($NumMain24 >= 0 || $NumMain26 >= 0)) {
                    $Emitir = 'POSIT';
                }elseif($NumMain24 >= 1 || $NumMain26 >= 1 && ($NumMain23 == 0 && $NumMain25 == 0)){
		    $Emitir = 'COINC';
		}else{
                    $Emitir = $this->ResultAntecedentes($Id);
                }
                //echo $Emitir."<br />";
//  Cuando No Tiene Antecedentes Genera su PDF solamente en la Sede Central
                //AND ($Lugar == '1')

                if (($NumAso == 0) AND ($NumMain == 0) AND ($NumMain2 == 0) AND ($NumAso2 == 0) AND ($MainNum == 0) AND ($AsoNum == 0) AND ($Emitir == 'LISTO') AND ($IdSoLocal == ''))
                    $this->CreaCertificadoPDF('', $Id);
                //$sd = "";
            }
        }

        $ValidaPago = $this->ValidarPago($Fecha, $Numero, $Tipo);     //  Validando Pago
        if ($ValidaPago[1] == "NO") {
            $Emitir = "VOUCH";
        }
        if ($Emitir == 'VOUCH') {
            $idUsuario = '31';
            self::auditoria($idUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Numero, $Fecha);
            $last_auditoria = self::last_auditoria($Id);
            parent::Query("UPDATE personas SET emite = '$Emitir',migrado='0',nom_ofi='$last_auditoria' WHERE p_idsol = '$Id'");   //  Actualizamos el campo emitir
            /* ---------------------------------------------------------------------------------------- */
        } else {
            $idUsuario = '31';
            self::auditoria($idUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Numero, $Fecha);
            $last_auditoria = self::last_auditoria($Id);
            $SqlActualizar = "UPDATE personas SET emite = '$Emitir',buscador='1',nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'";
            parent::Query($SqlActualizar);
        }
        /*
          } else {
          //  Para que ya no busque las solicitudes que ya han sido buscadas y por falta de pago no se emiten
          $ValidasPago = $this->ValidarPago($Fecha,$Numero,$Tipo);     //  Validando Pago
          if($ValidasPago[1] == "SI") {
          $SqlActualiza = "UPDATE personas SET buscador='1', emite='LISTO' WHERE p_idsol='$Id'";
          parent::Query($SqlActualiza);
          if($Lugar == '1') $this->CreaCertificadoPDF('',$Id);
          }
          }
         */
//  Fin del Robot   12 segundos por un registro
    }

    function auditoria($idUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $nro_voucher, $fec_voucher) {
        switch ($Emitir) {
            case 'LISTO':
                $axion_id = 1;
                break;
            case 'COINC':
                $axion_id = 2;
                break;
            case 'POSIT':
                $axion_id = 3;
                break;
            case 'VOUCH':
                $axion_id = 10;
                break;
            default:
                $axion_id = $Emitir;
                break;
        }
        parent::Query("INSERT INTO auditoria (idUsuario,proc_dir, p_idsol, id_solcitud, axion_id, fecha,nro_voucher,fec_voucher)VALUES('$idUsuario', '$Lugar','$Id', '$IdSoLocal','$axion_id', NOW(),'$nro_voucher','$fec_voucher')");
    }

    /* ---------------------------------------------------------------------------------------------------------- */

    function last_auditoria($Id) {
        $sql = "SELECT MAX(audit_id) as max FROM auditoria WHERE p_idsol='$Id'";
        $result = parent::Query($sql);
        $audit_id = parent::ResultAssoc($result);
        return $audit_id['max'];
    }

    /* ---------------------------------------------------------------------------------------------------------- */

    function EjecutarRobot() {
	//AND (proc_dir = 2 OR proc_dir = 11)
	// AND (proc_dir = 11)
	/*
	SELECT p_idsol ,CONCAT(TRIM(CASE p_apepat WHEN '---' THEN '' ELSE p_apepat END),' ',TRIM(CASE p_apemat WHEN '---' THEN '' ELSE p_apemat END),' ',TRIM(p_nombres)) AS Nombre,cod_user,proc_dir,fec_pago,tipo_img,tipo_pago,buscador,TRIM(p_apepat) AS Paterno,TRIM(p_apemat) AS Materno,TRIM(p_nombres) AS Nombres,id_solcitud,emite FROM personas WHERE buscador='0'   ORDER BY emite ASC
	*/
	//AND (p.proc_dir = 2 OR p.proc_dir = 88 OR p.proc_dir = 11)
        $KryRob = parent::Query(" SELECT p.p_idsol ,CONCAT(TRIM(CASE p.p_apepat WHEN '---' THEN '' ELSE p.p_apepat END),' ',TRIM(CASE p.p_apemat WHEN '---' THEN '' ELSE p.p_apemat END),' ',TRIM(p.p_nombres)) AS Nombre
,p.cod_user,p.proc_dir,p.fec_pago,p.tipo_img, p.tipo_pago, p.buscador, TRIM(p.p_apepat) AS Paterno,TRIM(p.p_apemat) AS Materno,TRIM(p.p_nombres) AS Nombres,p.id_solcitud,p.emite  FROM personas p, 
procedencia_direc pd 
WHERE p.buscador='0'  
 AND p.proc_dir = pd.ID 
ORDER BY pd.id_preferencia  DESC, p.emite, p_fechasol "); //pope
        $NumRob = parent::NumReg($KryRob);
        if ($NumRob >= 1) {
            while ($FilaRobot = parent::ResultArray($KryRob)) {
//  Los Parametros son :                  IdSolicitud   NameFull                            NameFull        Usuario         Lugar       FechaPago     NumeroPago    TipoPago      Buscardor     Paterno        Materno      Nombre       IdSoliciLocal      EstadoSolicitante
                $this->BuscadorSolicitud($FilaRobot[0], $FilaRobot[1], $this->SustituirTexto($FilaRobot[1]), $FilaRobot[2], $FilaRobot[3], $FilaRobot[4], $FilaRobot[5], $FilaRobot[6], $FilaRobot[7], $FilaRobot[8], $FilaRobot[9], $FilaRobot[10], $FilaRobot[11], $FilaRobot[12]);
            }
        }
    }

//  Funciton para Imprimir PDF
    function CreaCertificadoPDF($Usuario, $Solicitud) {
        //
	$nombea = '';
        if (empty($Solicitud)) {
            $sqlcol_verconsu = "select p.p_idsol, p.p_apepat, p.p_apemat, p.p_nombres, p.p_tipdocu, p.p_numdocu, p.p_tipo, p.p_desc,g.foto,p.proc_dir,p.nom_ofi ";
            $sqlcol_verconsu .= "FROM personas p,generado_solicitud g  where p.p_idsol = g.id_generado and g.flag = '0' and g.cod_user = '$Usuario'";
        } elseif (empty($Usuario)) {
            $sqlcol_verconsu = "select p.p_idsol, p.p_apepat, p.p_apemat, p.p_nombres, p.p_tipdocu, p.p_numdocu, p.p_tipo, p.p_desc,g.foto,p.proc_dir,p.nom_ofi ";
            $sqlcol_verconsu .= "FROM personas p,generado_solicitud g  where p.p_idsol = g.id_generado and g.flag = '0' AND p.p_idsol = '$Solicitud'";
        } else {
            echo "Error para Generar los Pdfs";
        }

        $ejecQueri = parent::Query($sqlcol_verconsu);
        $numeroRegistro = parent::NumReg($ejecQueri);

        if ($numeroRegistro >= 1) {

            include_once 'fpdf.php';

            while ($result2 = parent::ResultArray($ejecQueri)) {

                $pdf = new FPDF();
                $pdf->AliasNbPages();
                $pdf->AddPage();

                if ($result2[8] != null) {
                    $foto = $result2[8];
                } else {
                    $foto = "";
                }
                $auditoria = $result2[10];
                $numeroregion = str_pad($result2[9], 2, "0", STR_PAD_LEFT);
                $numeroSolititud2 = $numeroregion . " - " . $result2[0];
                $numeroSolititud = $result2[0];
                $apePate = $this->DeletCaracter($result2[1]);
                $apeMate = $this->DeletCaracter($result2[2]);
                $nombe = $this->DeletCaracter($result2[3]);
                $numerodocu = $result2[5];
                $documento = utf8_decode($result2[4] . ': ');
                $solicita_muestra = $result2[7];

                $axion = "$numeroSolititud :se genero certificado batch $numeroSolititud";
                $this->AuditoriaPDF($axion, $Usuario, $Ip[0], $Ip[1]);

                $fecha = time();
                $fecha = $this->FechaFormateadaAnder($fecha, '');

                //  Numero de solititud
                $paraLineaYSolicitud = 280;
                $paraLineaXSolicitud = 117;

                //  apellidos
                $paraLineaY = 107;
                $paraLineaX1 = 35;
                $paraLineaX2 = 130;

                $paraLineaYnom = 128;
                $paraLineaXnom = 79;

                /*  CENTREAR PARRAFO    */
                $paraLineaYa = 101;
                $paraLineaX1a = 20;
                $paraLineaX2a = 118;
                $paraLineaYnoma = 122;
                $paraLineaXnoma = 40;

                // este es para limpiar los apellidos paternos que aparecen en la parte de arriba
                $apePatea = "";
                $apePatArriba = "";
                $apePatAbajo = "";
                $apeMatea = "";
                $apeMatArriba = "";
                $apeMatAbajo = "";

                $numApePat = strlen($apePate);
                $numApeMat = strlen($apeMate);
                $numNom = strlen($nombe);

                if ($numApePat >= 17) {
                    $LetrasApePat = explode(" ", $apePate);
                    $numLetrasApePat = sizeof($LetrasApePat);
                    $sizeNumLetraApe = 0;

                    For ($sizeNumLetraApel = 1; $sizeNumLetraApel <= $numLetrasApePat; $sizeNumLetraApel++) {
                        $contarCaracteresTodos += strlen($LetrasApePat[$sizeNumLetraApe]) + 1;

                        if ($contarCaracteresTodos <= 17) {
                            $apePatArriba.=$LetrasApePat[$sizeNumLetraApe] . ' ';
                        } else {
                            $apePatAbajo.=$LetrasApePat[$sizeNumLetraApe] . ' ';
                        }

                        $sizeNumLetraApe++;
                    }   //  Fin del FOR

                    $apePatea = $apePatArriba;
                    $numApePat = strlen($apePatea);
                    $apePate = $apePatAbajo;
                    $numApePatAbajo = strlen($apePate);
                } else {
                    $numApePatAbajo = strlen($apePate);
                }

                $contarCaracteresTodos = 0;

                if ($numApeMat >= 17) {
                    $LetrasApeMat = explode(" ", $apeMate);
                    $numLetrasApeMat = sizeof($LetrasApeMat);
                    $sizeNumLetraMat = 0;

                    For ($sizeNumLetraMatl = 1; $sizeNumLetraMatl <= $numLetrasApeMat; $sizeNumLetraMatl++) {
                        $contarCaracteresTodos += strlen($LetrasApeMat[$sizeNumLetraMat]) + 1;

                        if ($contarCaracteresTodos <= 17) {
                            $apeMatArriba .= $LetrasApeMat[$sizeNumLetraMat] . ' ';
                        } else {
                            $apeMatAbajo.=$LetrasApeMat[$sizeNumLetraMat] . ' ';
                        }

                        $sizeNumLetraMat++;
                    }

                    $apeMatea = $apeMatArriba;
                    $numApeMat = strlen($apeMatea);
                    $apeMate = $apeMatAbajo;
                    $numApeMatAbajo = strlen($apeMate);
                } else {
                    $numApeMatAbajo = strlen($apeMate);
                }

                $mitadNumApePat = ceil($numApePat / 2);
                $mitadNumApePatAbajo = ceil($numApePatAbajo / 2);
                $mitadNumApeMat = ceil($numApeMat / 2);
                $mitadNumApeMatAbajo = ceil($numApeMatAbajo / 2);
                $mitadNumNom = ceil($numNom / 2);

                $posicionLetraApePat = 64 - ceil(4.30 * $mitadNumApePat);
                $posicionLetraApePatAbajo = 66 - ceil(4.30 * $mitadNumApePatAbajo);
                $posicionLetraApeMat = 160 - ceil(4.45 * $mitadNumApeMat);
                $posicionLetraApeMatAbajo = 162 - ceil(4.45 * $mitadNumApeMatAbajo);
                $posicionLetraNom = 117 - ceil(4.30 * $mitadNumNom);
                $paraLineaX1a = $posicionLetraApePat;
                $paraLineaX1 = $posicionLetraApePatAbajo;
                $paraLineaX2a = $posicionLetraApeMat;
                $paraLineaX2 = $posicionLetraApeMatAbajo;
                $paraLineaXnom = $posicionLetraNom;

                $paraLineaYdni = 159;
                $paraLineaXdni = 103;

                $paraLineaYdocumento = 147;
                $paraLineaXdocumento = 78;

                $paraLineaXsoliti = 45;
                $paraLineaYsoliti = 164;

                $paraLineaXest = 110;
                $paraLineaYest = 167;
                $paraLineaXcol = 174;
                $paraLineaYcol = 167;
                $paraLineaXvia = 57;
                $paraLineaYvia = 179;
                $paraLineaXtra = 110;
                $paraLineaYtra = 179;
                $paraLineaXotro = 154;
                $paraLineaYotro = 177;

                //fecha
                $paraLineaXfecha = 142;
                $paraLineaYfecha = 188;

                //auditoria
                $paraLineaXauditoria = 13;
                $paraLineaYauditoria = 272;

                if ($foto != "" && file_exists('../Img/certificados/' . $foto)) {
                    $pdf->Image('../Img/certificados/' . $foto, 8, 52, 33);
                }

                $pdf->Image('../Img/certificados/FIRMA3.jpg', 25, 236, 83, 50);

                /*  Desabilitada por que ya no se va poner como codigo unico el numero de la solitud    */
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Text($paraLineaXSolicitud, $paraLineaYSolicitud, "$numeroSolititud2");

                //  APELLIDOS Y NOMBRES
                $pdf->SetFont('Arial', 'B', 18);

                /*  Apellido paterno y materno arriba   */
                $pdf->Text($paraLineaX1a, $paraLineaYa, utf8_encode("$apePatea"));
                $pdf->Text($paraLineaX2a, $paraLineaYa, "$apeMatea");
                $pdf->Text($paraLineaXnoma, $paraLineaYnoma, "$nombea");

                /*  Apellido paterno y materno abajo    */
                $apeP = str_replace("Ñ", "&Ntilde;", $apePate);
                $apeM = str_replace("Ñ", "&Ntilde;", $apeMate);
                $apeP = str_replace("Ü","&Uuml;",$apeP);

                $pdf->Text($paraLineaX1, $paraLineaY, html_entity_decode("$apeP"));
                $pdf->Text($paraLineaX2, $paraLineaY, html_entity_decode("$apeM"));
                $pdf->Text($paraLineaXnom, $paraLineaYnom, "$nombe");

                //  documento y el numero de documento

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Text($paraLineaXdocumento, $paraLineaYdocumento, "$documento $numerodocu");

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Text($paraLineaXsoliti, $paraLineaYsoliti, html_entity_decode("$solicita_muestra."));

                $pdf->SetFont('Arial', '', 12);
                $pdf->Text($paraLineaXfecha, $paraLineaYfecha, "$fecha");

                if ($auditoria == NULL) {
                    $auditoria = "";
                }
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Text($paraLineaXauditoria, $paraLineaYauditoria, "$auditoria");


                $sqlupdaFinal = "update generado_solicitud set batch_solici='" . $numeroSolititud . ".pdf',fecha=now() where id_generado='" . $result2[0] . "'  ";
                parent::Query($sqlupdaFinal);

                $pdf->Output("../pdf/certificados/" . $numeroSolititud . ".pdf", "F");
            }
        }
    }

//  Funcion para incrementar el secuencual apartir del de Codig de Interno
    function IdSecuencial($Id) {
        $Kry = parent::Query("SELECT COUNT(*) FROM IDE_INCULPADO_NOMBRES_MOV WHERE COD_INCULPADO = '$Id'");
        $Num = parent::NumReg($Kry);
        $Fila = parent::ResultArray($Kry);
        $Incremento = $Fila[0] + 1;
        $Secuencial = sprintf("%03s\n", "$Incremento");

        return $Secuencial;
    }

//  Funcion para Seleccionar un Interno
    function FullNameInterno($IDs) {
        $Interno = parent::Query("SELECT DES_APE_PATERNO, DES_APE_MATERNO, DES_NOMBRES, COD_REGION, COD_PENAL, COD_TT1_SEXO FROM IDE_INCULPADO_MAE WHERE COD_INCULPADO = '$IDs'"); //  Actualizar Interno
        $Filas = parent::ResultArray($Interno);
        return $Filas;
    }

//////////////////////////////// Busqueda
//  Listado de Busqueda
    function ListadoBusqueda() {
        $html = "<br /><div id='ViCss' style='width:850px;'>";
        $html .= "<div id='Filtros'>" . $this->FiltrosBusqueda() . "</div>";
        $html .= "<div id='ContainerRegistro'></div>";
        $html .= "<div id='ListadosInternos'></div>";
        $html .= "</div>";
        return $html;
    }

    /*  main/login.phpmain/login.phpFmain/login.phpormulario de Busqueda       */

    function FiltrosBusqueda() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>NOMBRES&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltros(1)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>FECHAS&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltros(2)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>SOLICITUDES&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltros(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>OFICIOS&nbsp;:&nbsp;</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltros(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td><input type='hidden' name='CajaFiltro' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaFiltro' value='<< Buscar >>' onclick='javascript:FiltrosBuscar(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    /*  Funcion Listado de Todos los Internos   */

    function ListadoFiltrados($Ids) {
        if ($Ids[3] == 'Nombres') {
            $_pagi_sql = "SELECT   p_idsol , CONCAT( p_tipdocu ,': ' , p_numdocu)AS Documento, CONCAT(p_apepat,' ', p_apemat,' ',p_nombres) AS Fullname ,email AS E_mail , FROM_UNIXTIME(p_fechasol,'%d-%m-%Y')AS Fec,id_ofic,f_val,estado,buscador,flag FROM personas WHERE p_apepat LIKE '$Ids[4]%' AND p_apemat LIKE '$Ids[5]%' AND p_nombres LIKE '$Ids[6]%' ORDER BY p_idsol ASC ";
        } elseif ($Ids[3] == 'Fechas') {
            $Ini = $this->FechaMysql($Ids[4]);
            $Fin = $this->FechaMysql($Ids[5]);
            $_pagi_sql = "SELECT p_idsol , CONCAT( p_tipdocu ,': ' , p_numdocu)AS Documento, CONCAT(p_apepat,' ', p_apemat,' ',p_nombres) AS Fullname,email AS E_mail , FROM_UNIXTIME(p_fechasol,'%d-%m-%Y')AS Fec,id_ofic,f_val,estado,buscador,flag FROM personas WHERE p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') ORDER BY p_idsol ASC";
        } elseif ($Ids[3] == 'Solicito') {
            $_pagi_sql = "SELECT p_idsol, CONCAT( p_tipdocu ,': ', p_numdocu)AS Documento, CONCAT(p_apepat,' ', p_apemat,' ',p_nombres) AS Fullname ,email AS E_mail , FROM_UNIXTIME(p_fechasol,'%d-%m-%Y')AS Fec,id_ofic,f_val,estado,buscador,flag FROM personas WHERE p_idsol LIKE '$Ids[4]%'  ORDER BY p_idsol ASC";
        } elseif ($Ids[3] == 'Oficio') {
            $_pagi_sql = "SELECT   p_idsol, CONCAT( p_tipdocu ,': ' , p_numdocu)AS Documento, CONCAT(p_apepat,' ', p_apemat,' ',p_nombres) AS Fullname ,email AS E_mail ,FROM_UNIXTIME(p_fechasol,'%d-%m-%Y')AS Fec,id_ofic,f_val,estado,buscador,flag FROM personas WHERE id_ofic LIKE '$Ids[4]%' ORDER BY p_idsol ASC";
        }

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 20;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];


        if (!isset($_REQUEST['_pagi_actual'])) {
            $_pagi_actual = 1;
        } else if ($NumReg <= 20) {
            $_pagi_actual = 1;
        } else {
            $_pagi_actual = $_REQUEST['_pagi_actual'];
        }
        /*
          $_pagi_result = parent::Query($_pagi_sql);
          $NumReg = parent::NumReg($_pagi_result);
         */
        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .="<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("Paginador.cls.php");

        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px;width:730px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>Nro</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>DOCUMENTOS</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:220px;'>NOMBRE&nbsp;COMPLETO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>OFICIO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>FECHA</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:135px;'>ESTADO</td></tr>";

        while ($Rows = parent::ResultArray($_pagi_result)) {

            $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none'>";
            $html .= "<td>" . $Rows[0] . "</td>";
            $html .= "<td>" . $Rows[1] . "</td>";
            $html .= "<td>" . $Rows[2] . "</td>";
            $html .= "<td>" . $Rows[5] . "</td>";
            $html .= "<td>" . $Rows[4] . "</td>";
            $html .= "<td>" . $this->EstadoSituacional($Rows) . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr><td colspan='4'>" . $_pagi_navegacion . "</td></tr>";
        $html .= "</table>";

        return $html;
    }

//  Funcion Para Determinar el Estado de Situacion
    function EstadoSituacional($Filas) {
        if ($Filas[7] == '0') {
            $htmltable .= $this->buscarCertifiEstaPorEmitir($Filas[0]) . "<br>";
        }
        if ($Filas[7] == '1') {
            $htmltable.= ">>Positivo <br>";
        }
        if ($Filas[7] == null) {
            if ($Filas[6] == '0') {
                $htmltableNEW = ">>Solicitud Nueva<br>";
            } elseif ($Filas[6] == '1' & $Filas[9] == '0') {
                $htmltableNEW = ">>Seleccionado Para oficio <br>";
            } elseif ($Filas[6] == '2') {
                $htmltableNEW = ">>Observado<br>";
            } elseif ($Filas[9] == '1') {
                $htmltableNEW = ">>Emitido en oficio<br>";
            }
            $htmltable .= $htmltableNEW;
        }
        return $htmltable;
    }

//  FUNCION PARA BUSCAR CERTIFICADOS esta por emitir
    function buscarCertifiEstaPorEmitir($idofics) {
        $sql86 = "SELECT estado,flag FROM generado_solicitud  where id_generado='" . $idofics . "' and estado=0 ";
        $result86 = parent::Query($sql86);
        $numeroRegistro = parent::NumReg($result86);
        $result868 = parent::ResultRow($result86);
        $dato_estado = $result868[0];
        $dato_flag = $result868[1];

        if ($numeroRegistro >= 1) {
            if ($dato_flag == '0') {
                $retornaVali = 'Selecionado para Emitir certificado';
            } elseif ($dato_flag == '1') {
                $retornaVali = "Generado Certificado";
            } else {
                $retornaVali = "Listado de Emitir Certificados";
            }
        } else {
            $retornaVali = "Negativo [Antiguo Oficio]";
        }

        return $retornaVali;
    }

//  Funcion para Mostrar las Solicitudes y Emision de Documentos
    function CertificadoSolicitud($Find) {
        $html = "<br /><div id='ViCss' style='width:840px;'>";
        $html .= "<div id='Filtros3'>" . $this->FiltrosEmisionCertifica() . "</div>";
        $html .= "<div id='ContainerRegistro2'><div id='Xprocedencia' style='display: none;'>" . self::SelectProcedencias() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        $html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div>";
        $html .= "<div id='capaoficio' style='display: none;'>" . self::nrooficio() . "</div>";
        $html .= "<div id='impresionj' style='display: none;'>" . self::nroImpresion() . "</div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCerti($Find) . "</div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .= "</div>";
        return $html;
    }

    function busquedaNombres() {
        $html = "<table border='0' cellpadding='2' cellspacing='2'>";
        $html .= "<tr><td colspan='6'><strong>Ingrese el nombres a buscar :</strong></td></tr>";
        $html .= "<tr>";
        $html .= "<td>PATERNO : </td>";
        $html .= "<td><input class='InputText' type='text' name='PatEmite' id='PatEmite' tabindex='1' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>MATERNO : </td>";
        $html .= "<td><input class='InputText' type='text' name='MatEmite' id='MatEmite' tabindex='2' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "<td>NOMBRE : </td>";
        $html .= "<td><input class='InputText' type='text' name='NomEmite' id='NomEmite' tabindex='3' onKeyPress='javascript:return valtexto(event)' onblur='ClearSpace(this);ConvertMayuscula(this);' size='40'/></td>";
        $html .= "</tr></table>";

        return $html;
    }

    function nrodocu() {
        $html = "<table border='0' cellpadding='2' cellspacing='2'>";
        $html .= "<tr><td colspan='2'><strong>Ingrese el Nro Documento :</strong></td></tr>";
        $html .= "<tr>";
        $html .= "<td>N&ordm;&nbsp;DOCUMENTO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='NroDoc' tabindex='3' id='NroDoc' tabindex='1' onblur='ClearSpace(this);' size='40'/></td>";
        $html .= "</tr></table>";

        return $html;
    }

    function nrooficio() {
        $html = "<table border='0' cellpadding='2' cellspacing='2'>";
        $html .= "<tr><td colspan='2'><strong>Ingrese el Nro de Oficio :</strong></td></tr>";
        $html .= "<tr>";
        $html .= "<td>N&ordm;&nbsp;OFICIO&nbsp;:&nbsp;</td>";
        $html .= "<td><input class='InputText' type='text' name='NroOfi' tabindex='3' id='NroOfi' tabindex='1' onblur='ClearSpace(this);' size='40'/></td>";
        $html .= "</tr></table>";

        return $html;
    }

    function fechas_filtros() {
        $html = "<table border='0' cellpadding='2' cellspacing='2'>";
        $html .= "<tr><td colspan='2'><strong>Ingrese las Fechas de Busqueda :</strong></td></tr>";
        $html .= "<tr>";
        $html .= "<td>INICIO : </td>";
        $html .= "<td><input class='InputText' type='text' name='IniSolici' id='IniSolici' readonly='true' /><input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.IniSolici,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='1' /></td>";
        $html .= "<td>FINAL : </td>";
        $html .= "<td><input class='InputText' type='text' name='FinSolici' id='FinSolici' readonly='true' /><input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FinSolici,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='2' /></td>";
        $html .= "</tr></table>";

        return $html;
    }

    function estados() {
        $html = "<table border='0' cellpadding='1' cellspacing='1' >";
        $html .= "<tr><td colspan='3'><b>Estado&nbsp;Del&nbsp;Solicitante&nbsp;:&nbsp;&nbsp;</b></td></tr>";
        $html .= "<tr><td>VOUCHER</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='VOUCH' onclick='asignaestado(this.value);' /></td>";
        $html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;&nbsp;COINC.</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='COINC' onclick='asignaestado(this.value);' /></td>";
        $html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;&nbsp;LISTO</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='LISTO' onclick='asignaestado(this.value);' /></td>";
        $html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;&nbsp;OBSERVADOS</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='OBSV' onclick='asignaestado(this.value);' /></td>";
        $html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;&nbsp;POSITIVOS</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='POSIT' onclick='asignaestado(this.value);' /></td>";
        //$html .= "<td>&nbsp;&brvbar;&nbsp;POSIT.&nbsp;ATENDIDOS</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='POS-A' onclick='asignaestado(this.value);' /></td>";
        $html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;&nbsp;INVALIDOS</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='INVAL' onclick='asignaestado(this.value);' /></td>";
        //$html .= "<td>&nbsp;&brvbar;&nbsp;&nbsp;ATENDIDOS</td><td><INPUT TYPE='RADIO' NAME='EstEmi' id='EstEmi' value='ATEND' onclick='asignaestado(this.value);' /></td>";
        $html .= "</tr></table>";

        return $html;
    }

    /*  Formulario de Busqueda para la Emision y Certificadio       */

    function FiltrosEmisionCertifica() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='local' NAME='color' onclick='javascript:CamposEmision_filtrado(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_filtrado(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_filtrado(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='soli' onclick='javascript:CamposEmision_filtrado(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificado(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>ESTADO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='esta' onclick='javascript:CamposEmision_filtrado(6)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;OFICIO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='oficio' onclick='javascript:CamposEmision_filtrado(7)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>COD. IMPRES.:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='impre' id='impre' onclick='javascript:ocultar_filtros_otro()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:ocultar_filtros()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>CERTIFICADOS WEB&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='cweb' id='cweb' value='1' onclick='javascript:ocultar_filtros_otro_web()' > <input type='hidden' name='comodin' id='comodin' value='0' /></td>";
        $html .= "</tr>";

        $html .= "</table>";
        return $html;
    }

//  Combo Select Region OMAR
    function Direcciones() {
        $sql = "SELECT ID,DES_DR FROM procedencia_direc WHERE FLAG = '1' ORDER BY DES_DR ASC";
        $query = parent::Query($sql);
        while ($result = parent::ResultArray($query)) {
            $cod[] = $result["ID"];
            $nom[] = $result["DES_DR"];
        }
        return array($cod, $nom);
    }

    function dniApoderado() {
        $criterio = " AND proc_dir = " . $_SESSION['sede'] . "";

        $sql = "SELECT DISTINCT(foto) FROM personas WHERE foto != '' $criterio AND emite = 'LISTO'";
        $query = parent::Query($sql);

        $html .= "<table border='0' cellpadding='2' cellspacing='2'><tr><td>SELECCIONE DNI&nbsp:</td><td><SELECT name='dnitramitador' id='dnitramitador' class='InputText'>";
        $html .= "<option value='0'>[ DNI ]</option>";

        while ($result = parent::ResultArray($query)) {
            //$result["foto"];
            $html .= "<option value='" . $result["foto"] . "'>" . $result["foto"] . "</option>";
        }
        $html .= "</SELECT></td></tr></table>";
        return $html;
    }

//  Gun para Cargar Regiones
    function MixSelect($Id, $arrayDep) {
        $html = "";
        for ($i = 0; $i < sizeof($arrayDep[0]); $i++) {
            if ($Id == $arrayDep[0][$i]) {
                $html .= "<option value='" . $arrayDep[0][$i] . "' selected >" . $arrayDep[1][$i] . "</option>";
            } else {
                $html .= "<option value='" . $arrayDep[0][$i] . "'>" . $arrayDep[1][$i] . "</option>";
            }
        }
        return $html;
    }

//  Select de Procedencias
    function SelectProcedencias() {
        $html = "<div style='padding-bottom:10px; overflow:hidden'><table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>Direcciones</td>";
        $html .= "<td><SELECT name='IdDir' id='IdDir' onchange='javascript:Lugares(this.value);' class='InputText'>";
        $html .= "<option value='0'>[ Direcciones ]</option>";
        $html .= $this->MixSelect('', $this->Direcciones());
        $html .= "</td>";
//        $html .= "<td>Lugares </td>";
//        $html .= "<td><div id='IdLugares'><SELECT name='IdLugar' id='IdLugar' class='InputText'>";
//        $html .= "<option value='0'>[ Lugares ]</option>";
//        $html .= "</div></td>";
        $html .= "</tr>";
        $html .= "</table></div>";

        return $html;
    }

    /*  Funcion Listado de Emision de Certificados   */

    function ListadoEmiteCerti_old($Find) {
        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != ''  AND p.p_apemat != '' ";
        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Find[3]%' AND p.p_apepat LIKE '$Find[1]%' AND p.p_apemat LIKE '$Find[2]%' $Admin $filterxelmomento  ORDER BY p.p_apepat ASC";
            $IdUsuario = $Find[4];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') $Admin $filterxelmomento  ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Procede') {
            //AND p.cod_user = '$Find[3]'
            $Admin = ($Find[4] == '2') ? "" : "";
            //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.proc_dir = '$Find[1]' AND p.proc_lug = '$Find[2]' $Admin  ORDER BY p.p_fechasol ASC";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.proc_dir = '$Find[1]' $Admin $filterxelmomento  ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Doc') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $sql1 = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Find[1]' $Admin $filterxelmomento ORDER BY p.p_idsol ASC";
            $sql2 = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.id_solcitud = '$Find[5]' $Admin $filterxelmomento ORDER BY p.p_idsol ASC";
            $_pagi_sql = ($Find[5] == '') ? $sql1 : $sql2;
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Estado') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $Estados = ($Find[1] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[1]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin  $Estados $filterxelmomento ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        } else {
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[0]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' $filterxelmomento ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[0];
        }
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. PATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. MATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PAGO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>&nbsp;FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>&nbsp;HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>ESTADO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "</tr></thead><tbody>";

        //echo $_pagi_sql;
        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $procedencia = self::Procedencia($Rows[18]);
                $Antecedente = $Rows[11];
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                $html .= "<td align='center'>" . $TipPago . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . "</td>";
                $html .= "<td align='center'>" . $Rows[12] . "</td>";

                //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                //onclick='ver_pdf_doc($Rows[0], this.href)'
                if ($Rows[13] == '0') {
                    $userP = self::UsuarioDescrip($Rows['cod_user']);
                    $titleimg = self::TitleDescrip($Antecedente);
                    if ($Antecedente == 'ATEND') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/accept.png' style='border:none' title='$titleimg'/>";
                    } else {
                        $Imagenes = "<img id='leyendaimg' src='../Img/info2.gif' style='border:none' title='$titleimg'/>";
                    }
                    $html .= "<td align='center'>" . $Antecedente . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                    $Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' title='SOLICITUD LISTA PARA SER IMPRESA: NO TIENE ANTECEDENTES PENALES' /></a>";
                    $Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                    $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                    $html .= "<td align='center'>" . $Imprimir . "</td>";
                    $html .= "<td align='center'></td>";
                    $html .= "<td align='center'>&nbsp;" . $procedencia[1] . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                    /*
                      $CheckTrue = ($Rows[17] == '1') ? "CHECKED" : "";
                      $Cheked = ($Antecedente == 'LISTO') ? "<input type='checkbox' id='SolitaAnular' name='AnularSol[]' value='$Rows[0]' $CheckTrue />" : "";
                      $html .= "<td align='center'>".$Cheked."</td>";
                     */
                } else {
                    $procedencia = self::Procedencia($Rows[18]);
                    $html .= "<td align='center'>&nbsp;" . $procedencia[1] . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                    $html .= "";
                }

                $html .= "</tr>";
            }
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6'>" . $_pagi_navegacion . "</td></tr></table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</tbody></table>";
        $html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }

    function ListadoEmiteCerti($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];

        $codImpre = $Find[14];
        $cweb = $Find[15];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";
	$criterioImp = "";
	$menosVouch  = "";
        $diaj  = date('d');
        $mesj  = date('m');
        $anioj = date('Y');
        $diferenciaj = mktime(00,00,00,$mesj,$diaj-3,$anioj);
        $fechaj      =  date('Y-m-d',$diferenciaj);


        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            //echo "<script>alert(\"$Find[7]\")</script>";
            //echo "<script>alert(\"$Find[8]\")</script>";

            if ($Find[7] != "" && $Find[8] != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioF = "AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59')";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                $criterioDir = " AND p.proc_dir = '$Find[9]'";
            }

            if ($doc != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDoc = " AND p.p_idsol = '$Find[10]'" ;
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                
                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }


            if ($Find[11] != "") {
                //$Estados = ($Find[11] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[11]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[11] != 'ATEND') {

                    if($Find[11] != 'VOUCH'){
                        //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                        $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                        $menosVouch = " ";
                    }elseif($Find[11] == 'VOUCH'){
                        //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                        $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                        $menosVouch = " ";
                    }
                    
                } else {
                    //$criterioEs = " AND p.emite = '$Find[11]' ";
                    $criterioEs = "";
                }
            } else {
                $criterioEs = "";
            }

            if ($nombre == '' && $apepat == '' && $apemat == '' && $Find[7] == "" && $Find[8] == "" && $dir == 0 && $doc == '' && $Find[11] == '') {
                $criterioImp = " and g.flag = 0";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
            }

            if ($nroOfi != "") {
                $criterioofi = " AND g.oficio_solici  LIKE  '%$nroOfi%' ";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
            }

            if ($codImpre) {
                $criterioCodImpre = " AND p.id_ofic  LIKE  '%$codImpre%' ";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
            } else {
                $criterioCodImpre = "";
            }

            if ($cweb > 0) {
                $criterioCweb = " AND p.proc_dir >=100";
                $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                
            } else {
                $criterioCweb = "";
            }

            //AND p.f_val = '0'
            

            
            $_pagi_sql = "SELECT STRAIGHT_JOIN p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud , g.oficio_solici FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $criterioImp $Admin $criterioN $criterioF $criterioDir $criterioDoc $criterioEs $criterioofi $filterxelmomento $criterioCodImpre $criterioCweb $menosVouch and p.emite != 'ATEND' and p.emite != 'POS-A'  ORDER BY p.p_idsol DESC";
        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $filtroHoy = " AND FROM_UNIXTIME(p.p_fechasol,'%Y-%m-%d') = '".date('Y-m-d')."'"; 
            $Estados = "";
            $menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";

            $_pagi_sql = "SELECT STRAIGHT_JOIN p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud, g.oficio_solici FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento $menosVouch $filtroHoy and p.emite != 'ATEND' and p.emite != 'POS-A' and p.emite != 'INVAL' and p.emite != 'VOUCH' AND FROM_UNIXTIME(p.p_fechasol, '%Y-%m-%d') >= '2012-12-31'  ORDER BY p.p_idsol DESC"; 
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        //para impresion
        $_pagi_resultImp = $_pagi_sql;
        $_pagi_result1 = parent::Query($_pagi_sql);
        $idd = 0;
        while ($rows = parent::ResultArray($_pagi_result1)) {
            if ($rows['p_idsol'] != 0) {
                $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                $idd++;
            }
        }
        //$_SESSION['sql'] = $idSol;
        //echo "<script>alert(\"$idSol\")</script>";
        //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();
        /* ---------------------------------------------------  MARCO  OBSERVAR  ------------------------------------------------- */
        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";
        /* ---------------------------------------------------  MARCO  ELIMINAR  ------------------------------------------------- */
        $html .= "<div id='popupdelete' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popupdelete');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/popupdelete.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";
        /* ------------------------------------------------------------------------------------------------------------------------------ */
        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='width: 820px ; overflow-x:scroll'>";
        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>MATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;DOC IDEN</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";


        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>ESTADO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;Nro&nbsp;OFI&nbsp;&nbsp;</th>";


        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "</tr></thead><tbody>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $procedencia = self::Procedencia($Rows[18]);
                $Antecedente = $Rows[11];
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                //$html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                //$html .= "<td align='center'>" . $TipPago . "</td>";
                $html .= "<td align='center'>" . $Rows['Docs'] . "</td>";

                $html .= "<td align='center'>" . $Rows[3] . "</td>";
                $html .= "<td align='center'>" . $Rows[12] . "</td>";

                //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                //onclick='ver_pdf_doc($Rows[0], this.href)'
                //if ($Rows[13] == '0') {
                $userP = self::UsuarioDescrip($Rows['cod_user']);
                $titleimg = self::TitleDescrip($Antecedente);
                //$Imagenes = (($Antecedente == 'PEND1') || ($Antecedente == 'PEND2')) ? "<img src='../Img/info2.gif' style='border:none' />" : "<img src='../Img/alert.gif' style='border:none' />";
                if ($Antecedente == 'ATEND') {
                    $Imagenes = "<img id='leyendaimg' src='../Img/Img/accept.png' style='border:none' title='$titleimg'/>";
                } else if ($Antecedente == 'VOUCH') {
                    $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_red.png' style='border:none' title='$titleimg' />";
                } else if ($Antecedente == 'POSIT' || $Antecedente == 'COINC') {
                    $Imagenes = "<img id='leyendaimg' src='../Img/Img/user_add.png' style='border:none' title='$titleimg' />";
                } else if ($Antecedente == 'BUSCA') {
                    $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_find.png' style='border:none' title='$titleimg'  />";
                } else if ($Antecedente == 'OBSV') {
                    $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_magnify.png' style='border:none' title='$titleimg'  />";
                } else {
                    $Imagenes = "<img id='leyendaimg' src='../Img/info2.gif' style='border:none' title='$titleimg'/>";
                }
                $html .= "<td align='center'>" . $Antecedente . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                $Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                $Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                $html .= "<td align='center'>" . $Imprimir . "</td>";
                //$html .= "<td align='center'></td>";
                if ($procedencia[1] == 'INFRAESTRUCTURA') {
                    $proced = "INFRA";
                } else {
                    $proced = $procedencia[1];
                }
                $html .= "<td align='center'>&nbsp;" . $proced . "&nbsp;</td>";
                $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                //generado_solicitud
                $html .= "<td align='center'>" . str_replace("OFICIO-", "", $Rows['oficio_solici']) . "</td>";
                $html .= "</tr>";
            }
            $html .= "<table>";
            $html .= "<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
            $html .= "</table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
        $html .= "</tbody></table>";
        $html .= "</div>";
        $html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }

    function TitleDescrip($emite) {
        switch ($emite) {
            case 'BUSCA':
                $html = "SOLICITUD NUEVA: EN PROCESO DE B&Uacute;SQUEDA.";
                break;
            case 'COINC':
                $html = "SOLICITUD TIENE COINCIDENCIAS: ES DECIR EL USUARIO TIENE NOMBRE COINCIDENTE CON ALG&Uacute;N INTERNO.";
                break;
            case 'POSIT':
                $html = "SOLICITUD POSITIVA: ES DECIR EL USUARIO TIENE ANTECEDENTES PENALES.";
                break;
            case 'VOUCH':
                $html = "SOLICITUD EN PROCESO DE VALIDAR VOUCHER: EL VOUCHER ESTA SIENDO VALIDADO POR EL BANCO DE LA NACI&Oacute;N, O EL VOUCHER ES ERRONEO.";
                break;
            case 'POS-A':
                $html = "POSITIVO ATENDIDO: POSITIVO QUE LLEG&Oacute; AL FIN DE SU PROCESO.";
                break;
            case 'OBSV':
                $html = "SOLICITUD TIENE OBSERVACIONES: LA SOLICITUD ESTA EN PROCESO DE VERIFICACI&Oacute;N POR TENER SOSPECHA DE TENER ANTECEDENTES.";
                break;
            case 'ATEND':
                $html = "SOLICITUD ATENDIDA.";
                break;
            default:
                $html = "PROCESANDO LA SOLICITUD";
                break;
        }
        return $html;
    }

    function Procedencia($proc_dir) {
        $sql = "SELECT ID,DES_DR FROM procedencia_direc WHERE ID='$proc_dir'";
        $query = parent::Query($sql);
        $row = parent::ResultArray($query);
        return array($row['ID'], $row['DES_DR']);
    }

//  Funcion para Mostrar las Positos para Imprimir en Bloques
    function PrintPDFPositivo($Search) {
        $html = "<div id='ViCssPopup' style='width:720px;'>";
        $html .= "<div id='FiltraPos'>" . $this->FiltrosPositivoPdf() . "</div>";
        $html .= "<div id='ParamPos'><div id='Busquedas'></div></div>";
        $html .= "<div id='ListaPos'>" . $this->ListaPositivoPdf($Search) . "</div>";
        $html .= "</div>";
        return $html;
    }

    function PrintPDFPositivo_web($Search) {
        $html = "<div id='ViCssPopup' style='width:720px;'>";
        $html .= "<div id='FiltraPos'>" . $this->FiltrosPositivoPdf_web() . "</div>";
        $html .= "<div id='ParamPos'><div id='Busquedas'></div></div>";
        $html .= "<div id='ListaPos'>" . $this->ListaPositivoPdf_web($Search) . "</div>";
        $html .= "</div>";
        return $html;
    }

    function FiltrosPositivoPdf_web() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table border='0' cellpadding='2' cellspacing='2'><tr>";
        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(2)'></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(3)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(5)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmisionPosit' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoPosit(this.name);' $evento  $estiloopc width:100px;'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    function ListaPositivoPdf_web($Search) {
        //echo "<script>alert(\"$Search[0]\")</script>";
        //echo "<script>alert(\"$Search[1]\")</script>";
        //echo "<script>alert(\"$Search[2]\")</script>";
        if ($Search[0] == 'Nombres') {
            //$Admin = ($Search[5] == '2') ? " " : "AND p.cod_user = '$Search[4]'";
            $Admin = " AND p.proc_dir= '$Search[6]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Search[3]%' AND p.p_apepat LIKE '$Search[1]%' AND p.p_apemat LIKE '$Search[2]%' $Admin  AND p.emite='LISTO' and p.id_local > 100 ORDER BY p.p_apepat ASC";
            $IdUsuario = $Search[4];
        } elseif ($Search[0] == 'Doc') {
            //$Admin = ($Search[3] == '2') ? " " : "AND p.cod_user = '$Search[2]'";
            $Admin = " AND p.proc_dir= '$Search[4]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Search[1]' $Admin  AND p.emite='LISTO' and p.id_local > 100 ORDER BY p.p_idsol ASC";
            $IdUsuario = $Search[2];
        } else {
            //$Admin = ($Search[1] == '2') ? " " : "AND p.cod_user = '$Search[0]'";
            $Admin = " AND p.proc_dir= '$Search[2]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' AND p.emite='LISTO' and p.id_local > 100 ORDER BY p.p_idsol DESC";
            $IdUsuario = $Search[0];
        }
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 10;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("PopuPaginador.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:705px;'>";
        $html .= "<tr><td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. PATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. MATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:190px;'>NOMBRES</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>OBS</td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {

                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[14])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[15])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[16])) . "</td>";
                if ($Rows[13] == '0') {
                    $CheckTrue = ($Rows[17] == '1') ? "CHECKED" : "";
                    $Cheked = ($Rows[11] == 'LISTO') ? "<input type='checkbox' id='SolitaAnular' name='AnularSol[]' value='$Rows[0]' $CheckTrue />" : "";
                    $html .= "<td align='center'>" . $Cheked . "</td>";
                } else {
                    $html .= "";
                }
                $html .= "</tr>";
            }

            $html .="<tr><td colspan='4' align='right'></td></tr>";
            $html .= "<tr><td colspan='5'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td>&nbsp;</td></tr><tr>";
            //$html .= "<td align='right' colspan='2'><b>&nbsp;FECHA&nbsp;DE&nbsp;IMPRESION&nbsp;:&nbsp;</b></td>";
            //$datex = date('d-m-Y');
            //$html .= "<td align='left'><input class='InputText' style='width:80px;' type='text' name='FechaPrintPdf' id='FechaPrintPdf' ReadOnly='true' value='$datex' onKeyUp='this.value=formateafechaposit(this.value);' /></td>";
            $html .= "<td align='center'>";
            //$html .= "<input type='button' id='ImprimirTodos' value='Imprimir PDFs' onclick=javascript:ExportarPDFPosit(FechaPrintPdf.value); $evento  $estiloopc width:120px;' />";
            //$html .= "</td><td align='left'>";
            $html .= "<input type='button' id='AnularPositivo' value='OBSERVAR' onclick=javascript:FindCertificadoPosit(this.id); $evento  $estiloopc width:120px;' />";
            $html .= "</td></tr>";
        } else {
            $html .= "<tr><td colspan='6' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</table>";
        return $html;
    }

    /*  Formulario de Busqueda para la Emision y Certificadio       */

    function FiltrosPositivoPdf() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table border='0' cellpadding='2' cellspacing='2'><tr>";
        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(2)'></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(3)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(5)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmisionPosit' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoPosit(this.name);' $evento  $estiloopc width:100px;'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

//  Funcion para Mostrar los archivos para seleccionar los que se van a eliminar
    function EliminaSolicitudes($Search) {
        $html = "<div id='ViCssPopup' style='width:720px;'>";
        $html .= "<div id='FiltraPos'>" . $this->FiltrosEliminaSolicitudes() . "</div>";
        $html .= "<div id='ParamPos'><div id='Busquedas'></div></div>";
        $html .= "<div id='ListaPos'>" . $this->ListaEliminaSolicitudes($Search) . "</div>";
        $html .= "</div>";
        return $html;
    }

    /* ------------------------------------------------------------------------------------------------------------------------------------------------------ */
    /*  Formulario de Busqueda para la eliminacion       */

    function FiltrosEliminaSolicitudes() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table border='0' cellpadding='2' cellspacing='2'><tr>";
        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(2)'></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(3)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposFiltrosPosit(5)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmisionPosit' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoPosit(this.name);' $evento  $estiloopc width:100px;'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    /* ------------------------------------------------------------------------------------------------------------------------------------------------------ */

//  Listado de los que van a ser eliminados
    function ListaEliminaSolicitudes($Search) {
        if ($Search[0] == 'Nombres') {
            $Admin = ($Search[5] == '2') ? "AND p.proc_dir='$Search[6]'" : "AND p.cod_user = '$Search[4]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Search[3]%' AND p.p_apepat LIKE '$Search[1]%' AND p.p_apemat LIKE '$Search[2]%' $Admin  ORDER BY p.p_apepat ASC";
            $IdUsuario = $Search[4];
        } elseif ($Search[0] == 'Doc') {
            $Admin = ($Search[3] == '2') ? "AND p.proc_dir='$Search[4]'" : "AND p.cod_user = '$Search[2]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Search[1]' $Admin   ORDER BY p.p_idsol ASC";
            $IdUsuario = $Search[2];
        } else {
            $Admin = ($Search[1] == '2') ? "AND p.proc_dir='$Search[2]'" : "AND p.cod_user = '$Search[0]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' ORDER BY p.p_idsol DESC";
            $IdUsuario = $Search[0];
        }    //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 10;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("PopuPaginador.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:705px;'>";
        $html .= "<tr><td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. PATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. MATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:190px;'>NOMBRES</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ELIMINAR&nbsp;</td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {

                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[14])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[15])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[16])) . "</td>";
                $Cheks = "<input type='checkbox' id='SolitaAnular' name='AnularSol[]' value='$Rows[0]' />";
                $html .= "<td align='center'>" . $Cheks . "</td>";
                $html .= "</tr>";
            }

            $html .="<tr><td colspan='4' align='right'></td></tr>";
            $html .= "<tr><td colspan='5'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td>&nbsp;</td></tr><tr>";
            $html .= "<td align='center'>";
            $html .= "<input type='button' id='AnularPositivo' value='RECHAZAR' onclick=javascript:FindCertificadoPosit(this.id); $evento  $estiloopc width:120px;' />";
            $html .= "</td></tr>";
        } else {
            $html .= "<tr><td colspan='6' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</table>";
        return $html;
    }

//  Listado de los Positivos para ser Impresos
    function ListaPositivoPdf($Search) {
        if ($Search[0] == 'Nombres') {
            $Admin = ($Search[5] == '2') ? " " : "AND p.cod_user = '$Search[4]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Search[3]%' AND p.p_apepat LIKE '$Search[1]%' AND p.p_apemat LIKE '$Search[2]%' $Admin  AND p.emite='LISTO' ORDER BY p.p_apepat ASC";
            $IdUsuario = $Search[4];
        } elseif ($Search[0] == 'Doc') {
            $Admin = ($Search[3] == '2') ? " " : "AND p.cod_user = '$Search[2]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Search[1]' $Admin  AND p.emite='LISTO' ORDER BY p.p_idsol ASC";
            $IdUsuario = $Search[2];
        } else {
            $Admin = ($Search[1] == '2') ? " " : "AND p.cod_user = '$Search[0]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' AND p.emite='LISTO' ORDER BY p.p_idsol DESC";
            $IdUsuario = $Search[0];
        }    //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 10;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("PopuPaginador.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:705px;'>";
        $html .= "<tr><td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. PATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>AP. MATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:190px;'>NOMBRES</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>OBS</td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {
	    $hj = 1;
            while ($Rows = parent::ResultArray($_pagi_result)) {

                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[14])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[15])) . "</td>";
                $html .= "<td>" . utf8_encode(strtoupper($Rows[16])) . "</td>";
                if ($Rows[13] == '0') {
                    $CheckTrue = ($Rows[17] == '1') ? "CHECKED" : "";
                    $Cheked = ($Rows[11] == 'LISTO') ? "<input type='checkbox' onclick='mostrar_obs(".$Rows[0].");' id='SolitaAnular".$Rows[0]."' name='AnularSol[]' value='$Rows[0]' $CheckTrue />" : "";
                    $html .= "<td align='center'>" . $Cheked . "</td>";
                } else {
                    $html .= "";
                }
                $html .= "</tr>";

                $html .= "<tr>";
                $html .= "<td colspan='6'>";
                $html .= "<div id=".$Rows[0]." style='display:none'> ";

                $html .= "<div style='width:100px; float:left; overflow:hidden;color:#FF0000'>Observacion :</div>";
                //$html .= "<div><textarea id=".$Rows[0]." name='texto".$Rows[0]."' cols='36' rows='3'></textarea></div>";
                $html .= "<div><input type='text' id='texto".$hj."' name='texto".$hj."' style='width:450px;' /></div>";
                

                $html .= "</div>";
                
                $html .= "</td>";
                $html .= "</tr>";
		$hj++;
            }

            $html .="<tr><td colspan='4' align='right'></td></tr>";
            $html .= "<tr><td colspan='5'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td>&nbsp;</td></tr><tr>";
            //$html .= "<td align='right' colspan='2'><b>&nbsp;FECHA&nbsp;DE&nbsp;IMPRESION&nbsp;:&nbsp;</b></td>";
            //$datex = date('d-m-Y');
            //$html .= "<td align='left'><input class='InputText' style='width:80px;' type='text' name='FechaPrintPdf' id='FechaPrintPdf' ReadOnly='true' value='$datex' onKeyUp='this.value=formateafechaposit(this.value);' /></td>";
            $html .= "<td align='center'>";
            //$html .= "<input type='button' id='ImprimirTodos' value='Imprimir PDFs' onclick=javascript:ExportarPDFPosit(FechaPrintPdf.value); $evento  $estiloopc width:120px;' />";
            //$html .= "</td><td align='left'>";
            $html .= "<input type='hidden' name='total' id='total' value='0' /> <input type='button' id='AnularPositivo' value='OBSERVAR' onclick=javascript:FindCertificadoPosit(this.id); $evento  $estiloopc width:120px;' />";
            $html .= "</td></tr>";
        } else {
            $html .= "<tr><td colspan='6' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</table>";
        return $html;
    }

//  Imprimir los Pdfs en Bloques y por usuarios
    function FormImprimirPdfsFull() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<div id='popUpDiv' style='display:none;width:300px;height:80px;'>";
        $html .= "<table cellpadding='4' cellspacing='4' style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:300px;' >";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td colspan='2' align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr>";
        //$html .= "<tr><td colspan='2'>&nbsp;</td></tr>";
        $html .= "<tr>";
        $html .= "<td align='right'>FECHA&nbsp;DE&nbsp;IMPRESION</td>";
        $datex = date('d-m-Y');
        $html .= "<td><input class='InputText' style='width:80px;' type='text' name='FechaPrintPdf' id='FechaPrintPdf' value='$datex' onKeyUp='this.value=formateafecha(this.value);' /></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td colspan='2' align='right'><input type='button' id='ImprimirTodos' value='Imprimir PDFs' onclick=javascript:ExportarPDF(); $evento  $estiloopc width:100px;' /></td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</div>";

        return $html;
    }

//  Funcion para setear estado
    function SetEstado($Estado) {
        if (($Estado == 'OK') || ($Estado == 'SI')) {
            $html = "OK";
        } elseif (($Estado == 'PEND1') || ($Estado == 'PEND2')) {
            $html = "PEND";
        } elseif (($Estado == 'RECH1') || ($Estado == 'RECH2')) {
            $html = "RCHZ";
        } else {
            $html = "BAD";
        }
        return $html;
    }

//  Funcion de Mostrar el Detalle del Personal
    function PersonalDetalle($Id) {
        if ($Id[1] == 'VOUCH') {
            $Estado = array('HIDE', 'POR VALIDAR', "<img src='../Img/busca0.gif' style='border:none' />");
        } elseif (($Id[1] == 'SI') || ($Id[1] == 'LISTO')) {
            $Estado = array('HIDE', 'NEGATIVO', 'LISTO PARA IMPRIMIR');
        } elseif ($Id[1] == 'COINC') {
            $Estado = array('SHOW', 'TIENE COINCIDENCIAS', '');
        } elseif ($Id[1] == 'POS-A') {
            $Estado = array('HIDE', 'POSITIVO ATENDIDO', '');
        } elseif ($Id[1] == 'ATEND') {
            $Estado = array('HIDE', 'SOLICITUD ATENDIDA', "<img src='../Img/Img/accept.png' style='border:none' />");
        } elseif ($Id[1] == 'OBSV') {
            $Estado = array('HIDE', 'SOLICITUD OBSERVADA', self::ShowObservados($Id));
        } elseif ($Id[1] == 'POSIT') {
            $Estado = array('HIDE', 'TIENE ANTECEDENTES', $this->ShowCoincideDetalle($Id));
        } elseif ($Id[1] == 'INVAL') {
            //$Estado = array('HIDE','VOUCHER DENEGADO',$this->ErrorDetalleWS($Id[0]));
            $Estado = array('HIDE', 'VOUCHER DENEGADO', "<img src='../Img/busca0.gif' style='border:none' />");
        } elseif ($Id[1] == 'BUSCA') {
            $Estado = array('HIDE', 'PROCESO DE BUSQUEDA', "<img src='../Img/busca0.gif' style='border:none' />");
        } else {
            $Estado = array('HIDE', 'ERROR', 'ERROR');
        }

        $SqlConj = "SELECT p_idsol, p_apepat, p_apemat, p_nombres FROM personas WHERE p_idsol = '$Id[0]' ";
        $Kryj = parent::Query($SqlConj);
        $Filasj = parent::ResultArray($Kryj);

        $SqlCono = "SELECT p_idsol, texto_observado FROM personas_observadas WHERE p_idsol = '$Id[0]' ";
        $Kryjo = parent::Query($SqlCono);

        $gh=0;
        while($Filaso = parent::ResultArray($Kryjo)){
            //$varij.=($gh==0?'':',').$Filaso['texto_observado'];
	    $varij.=($gh==0?'':',').preg_replace("/[\n|\r|\n\r]/i","",trim($Filaso['texto_observado']));
            $gh++;
        }
        
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<TABLE BORDER='0' style='width:500px; overflow:hidden'>";
        $html .= "<TR><TD><TABLE style='width:500px; overflow:hidden'>";

        $html .= "<tr>";
        $html .= "<td colspan='7'><strong>PERSONA:</strong> ";
        $html .= $Filasj['p_apepat']." ".$Filasj['p_apemat']." ".$Filasj['p_nombres'];
        $html .= "</td>";
        $html .= "</tr>";

        
        
        $html .= "<TR>";
        $html .= "<TD width='25%'><b>Nro.&nbsp;SOLICITUD&nbsp;:&nbsp;</b> $Id[0]</TD>";
        $html .= "</tr>";
        
        //$html .= "<TD width='25%'>" . $Id[0] . "&nbsp;&nbsp;&nbsp;</TD>";
        $html .= "<TR>";
        $html .= "<TD><b>ESTADO&nbsp;:&nbsp;</b> $Estado[1]</TD>";
        $html .= "</TR>";

        //$html .= "<TD>" . $Estado[1] . "&nbsp;&nbsp;&nbsp;</TD>";

        $html .= "<tr>";
        $html .= "<TD><b>Nro&nbsp;DEL&nbsp;VOUCHER&nbsp;:&nbsp;</b> $Id[3]</TD>";
        $html .= "</tr>";

        //$html .= "<TD>" . $Id[3] . "&nbsp;&nbsp;&nbsp;</TD>";
        $html .= "<tr>";
        $html .= "<TD><b>FECHA&nbsp;DEL&nbsp;VOUCHER&nbsp;:&nbsp;</b> ".date('d-m-Y', $Id[2])."</TD>";
        //$html .= "<TD>" . date('d-m-Y', $Id[2]) . "</TD>";
        $html .= "</TR>";

        $html .= "<tr>";
        $html .= "<td colspan='7'><b>Observacion(Porque paso a este etado):</b> ".$varij."</td></tr>";
        //$html .= trim();
        //$html .= "";
        //$html .= "</tr>";
        $SqlHom     = "SELECT * FROM hommonimia WHERE co_persona = '$Id[0]' ";
        $KryjoHom   = parent::Query($SqlHom);
        $FilasHom   = parent::ResultArray($KryjoHom);

        if($FilasHom['dir_hom']!=''){
            $html .= "<tr>";
            $html .= "<td colspan='7'>------------------------------------------------------</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Ingreso datos de Homonimia</b></td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Dire.:</b> ".$FilasHom['dir_hom']."</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Edad:</b> ".$FilasHom['edad_hom']."</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Nom. Pad.:</b> ".$FilasHom['nom_pad']."</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Nom. Mad.:</b> ".$FilasHom['nom_mad']."</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'><b>Lug. Nac.:</b> ".$FilasHom['lug_nac_hom']."</td></tr>";

            $html .= "<tr>";
            $html .= "<td colspan='7'>------------------------------------------------------</td></tr>";
        }
        
        $html .="</TABLE></TD></TR>";
        $html .= "<TR><TD><TABLE style='border: 1px solid #CCCCCC;border-collapse: collapse;' cellpadding='4' cellspacing='4' width='780'>";
        $Print = ($Estado[0] == 'HIDE') ? "<TR><TD>" . $Estado[2] . "</TD></TR>" : $this->ConcidenciaSolicitud($Id[0]);
        $html .= $Print;
        $html .= "</TABLE></TD></TR>";
        $html .= "<TR><TD>";

        $Boton = ($Estado[0] == 'HIDE') ? "" : "<BR />&nbsp;&nbsp;<input type='button' name ='GrabarSelect' id='GrabarSelect' value='<< Grabar >>' onclick=javascript:DetallePersona(this.name,'$Id[0]','',''); $evento  $estiloopc width:100px;'>";
        $html .= $Boton;
        $html .= "</TD></TR></TABLE>";

        return $html;
    }

//  Funcion de las Considencias del SOlicitante
    function ConcidenciaSolicitud($IdSoli) {
        $SqlCon = "SELECT id, p_idsol, nombre, fcha, origen, id_interno, sip_name, sexo, region, penal, flag,relacion FROM buscador WHERE p_idsol = '$IdSoli' GROUP BY  sip_name";
        $Kry = parent::Query($SqlCon);
        $Num = parent::NumReg($Kry);
        $html = "<TR><TD><TABLE style='border: 1px solid #CCCCCC;border-collapse: collapse;' cellpadding='4' cellspacing='4' >";
        $html .= "<TR><TD COLSPAN='3' ALIGN='CENTER'><b>COINCIDENCIA</b></TD></TR>";
        if ($Num >= 1) {
            while ($Filas = parent::ResultArray($Kry)) {
                $col = ($Filas[11] == '1') ? "#CCC" : "#FFF";
                $html .= "<TR style='background-color: $col'  onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='$col'>";
                $html .= "<TD><INPUT TYPE='RADIO' NAME='Eli' VALUE='$Filas[0]' onclick=javascript:DetalleCoincide('CoinciDetalle','$Filas[0]'); /></TD>";
                $html .= "<TD>" . preg_replace("/[\n|\r|\n\r]/i","",ucwords(strtolower(trim($Filas[6])))) . "</TD>";
		$html .= "<TD>&nbsp;&nbsp;&nbsp;</TD>";
                $html .= "</TR>";
            }
            $html .= "<TR onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none'>";
            $html .= "<TD><INPUT TYPE='RADIO' NAME='Eli' VALUE='NO' onclick=javascript:DetalleCoincide('ClearDetalle','$IdSoli'); /></TD>";
            $html .= "<TD><b>Ninguno</b></TD>";
            $html .= "<TD>&nbsp;</TD>";
            $html .= "</TR>";
        } else {
            $html .= "<TR><TD>No Tiene Antecedentes</TD></TR>";
        }
        $html .= "</TABLE></TD>";
        $html .= "<TD><div id='CoinciDetalle'></div></TD>";
        $html .= "<TD><input type='hidden' name='IdDetalleCoincide' id='IdDetalleCoincide' /></div></TD>";
        $html .= "</TR>";
        return $html;
    }

//  Funcion para Mostrar el Resultado de la Busqueda
    function ShowCoincideDetalle($Id) {
        if ($Id[1] == 'POSIT') {
            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
            $boton = "<BR />&nbsp;&nbsp;<input type='button' name ='GrabarPosit' id='GrabarPosit' value='<< Rehabilitar >>' onclick=javascript:DetalleCoincide('PositDetalle',''); $evento  $estiloopc width:120px;'>";
            $boton .= "&nbsp;<input type='button' name ='GrabarPostA' id='GrabarPostA' value='<< ATENDIDO >>' onclick=javascript:ValidaRehab('POS-A'); $evento  $estiloopc width:120px;'>";

            $Kry = parent::Query("SELECT p_idsol, id FROM buscador WHERE p_idsol = '$Id[0]' ORDER BY id DESC LIMIT 1");
            $Num = parent::NumReg($Kry);
            if ($Num >= 1) {
                $Filas = parent::ResultArray($Kry);
                $Ids = $Filas[1];
            } else {
                $Ids = '';
            }
        } else {
            $Ids = $Id[0];
            $boton = "";
        }

        $SqlSent = "SELECT sip_name,(CASE sexo WHEN '001' THEN 'MASCULINO' WHEN '002' THEN 'FEMENINO' ELSE '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' END) AS sexo, (SELECT abreviatura_descrip FROM penales WHERE idpenales=penal) AS penals, IF(flag='1','ACTIVO','PASIVO') AS estado, DATE_FORMAT(fec_ingreso,'%d-%m-%Y') AS ingreso, DATE_FORMAT(fec_naci,'%d-%m-%Y') AS naci, lug_naci FROM buscador WHERE id = '$Ids'";
        $kry = parent::Query($SqlSent);
        $Fila = parent::ResultArray($kry);

        $html = "<div id='DetallePosit'>";
        $html .= "<TABLE style='border: 1px solid #CCCCCC;border-collapse: collapse;' cellpadding='4' cellspacing='0' width='400' >";
        $html .= "<TR><TD colspan='4' align='center'><b>DETALLES</b></TD></TR>";
        $html .= "<TR><TD align='right'><b>Nombre&nbsp;:</b></TD><TD>" . $Fila[0] . "</TD><TD align='right'><b>Sexo&nbsp;:</b></TD><TD>" . $Fila[1] . "</TD></TR>";
        $html .= "<TR><TD align='right'><b>Penal&nbsp;:</b></TD><TD>" . $Fila[2] . "</TD><TD align='right'><b>Ingreso&nbsp;:</b></TD><TD>" . $Fila[4] . "</TD></TR>";
        $html .= "<TR><TD align='right'><b>Lugar&nbsp;Nac.&nbsp;:</b></TD><TD>" . $Fila[6] . "</TD><TD align='right'><b>Fecha&nbsp;Nac.&nbsp;:</b></TD><TD>" . $Fila[5] . "</TD></TR>";

        $html .= "<TR><TD colspan='4' align='center' valign='top'><b>OBS</b><input type='text' name='observa' id='observa' style='width:340px;' /></TD></TR>";
        
        $html .= "<TD><input type='hidden' name='IdBuscador' id='IdBuscador' value='$Id[0]'/></div></TD>"; ///
        $html .= "</div>";
        $html .= "</TABLE>";
        $html .= $boton;
        return $html;
    }

//////////////////////////////////////////////////////////////////// -- JESUX --//////////////////////////////////////////////////////////////////////////////////
    function ShowRehabilitacion($estado, $IdBuscador) {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        switch ($estado) {
            case 'POSIT':
                $html = "<TABLE style='border: 1px solid #CCCCCC;border-collapse: collapse;' cellpadding='4' cellspacing='0' width='600' >";
                $html .= "<tr><td align='right'><b>Nro Oficio :</b></td><td><input type='text' name='nroficio' onBlur='javascript:pasarMayusculas(this)'/></td></tr>";
                $html .= "<tr><td align='right'><b>Procedencia del Documento :</b></td><td>" . self::procreha() . "</td></tr>";
                $html .= "<tr><td align='right'><b>Nro Expediente :</b></td><td><input type='text' name='nroexp' onBlur='javascript:pasarMayusculas(this)'/></td></tr>";
                $html .= "<tr><td align='right'><b>Autoridad Judicial :</b></td><td><input type='text' name='autjudic' onBlur='javascript:pasarMayusculas(this)'/></td></tr>";
                $html .= "<tr><td align='right'><b>Fecha de Rehab.(dd/mm/yyyy) :</b></td><td><input type='text' name='fecrehab' size='10' onKeyUp = 'this.value=formateafecha(this.value);' /></td></tr>";
                $html .= "<tr><td align='right'><b>Observaci&oacute;n :</b></td><td><textarea name='obs_reh' rows='2' cols='65'></textarea></td></tr>";
                $html .= "</TABLE>";
                $html .= "<input type='hidden' name='IdBuscador' id='IdBuscador' value='$IdBuscador'/>";
                $html .= "<BR />&nbsp;&nbsp;<input type='button' name ='GrabarRehab' id='GrabarRehab' value='<< Rehabilitar >>' onclick=javascript:ValidaRehab('POSIT'); $evento  $estiloopc width:120px;'>";
                $html .= "&nbsp;<input type='button' name ='HomoDetalle' id='HomoDetalle' value='<< Homonimia >>' onclick=javascript:DetalleCoincide('HomoDetalle',$IdBuscador); $evento  $estiloopc width:120px;'>";
                break;
            case 'COINC':
                $html = "<TABLE style='border: 1px solid #CCCCCC;border-collapse: collapse;' cellpadding='4' cellspacing='0' width='600' >";
                $html .= "<tr><td align='right'><b>Observaci&oacute;n :</b></td><td><textarea name='obs_reh' id='obs_reh' rows='4' cols='65'></textarea></td></tr>";
                $html .= "</TABLE>";
                $Boton = "<BR />&nbsp;&nbsp;<input type='button' name ='GrabarRehab' id='GrabarRehab' value='<< Poner LISTO >>' onclick=javascript:ValidaRehab('COINC'); $evento  $estiloopc width:140px;'>";
                $Boton .= "&nbsp;<input type='button' name ='GbCObserv' id='GbCObserv' value='<< Observar >>' onclick=javascript:ValidaRehab('COBSERV'); $evento  $estiloopc width:140px;'>";
                $html .= $Boton;
                break;
        }
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function ShowObservados($Id) {


        /*$sqlObj = "select * from personas_observadas where p_idsol = ".$Id[0];
        $kryj   = parent::Query($sqlObj);
        $Filaj  = parent::ResultArray($kryj);
        */
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        
        //$html  = "OBS <textarea rows='4' cols='38' name='obsj'></textarea>";
        $html  = "OBS <input type='text' name='obsj' id='obsj' style='width:480px;font:normal 11px Arial, Helvetica, sans-serif'></textarea>";
        $html .= "<BR />&nbsp;&nbsp;<input type='button' name ='CPosit' id='CPosit' value='<< Colocar POSITIVO >>' onclick=javascript:ValidaRehab('CPosit'); $evento  $estiloopc width:160px;'>";
        $html .= "&nbsp;<input type='button' name ='CListo' id='CListo' value='<< Colocar LISTO >>' onclick=javascript:ValidaRehab('CListo'); $evento  $estiloopc width:150px;'>";
        $html .= "<input type='hidden' name='IdDetalleCoincide' id='IdDetalleCoincide' value='$Id[0]'/>";
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function ShowHomonimia($_POST) {
        $IdBuscador = mysql_real_escape_string($_POST["IdBuscador"]);
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<b>Observaci&oacute;n:</b><br /><br /><textarea name='tobservah' rows='2' cols='50'></textarea>";
        $html .= "<br /><br /><input type='button' name ='GrabarHomo' id='GrabarHomo' value='<< Homonimia >>' onclick=javascript:ValidaRehab('HOMO'); $evento  $estiloopc width:120px;'>";
        $html .= "<input type='hidden' name='IdBuscador' id='IdBuscador' value='$IdBuscador'/>";
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function procdoc() {
        $query = parent::Query($sql = "SELECT ID,DES_DR FROM procedencia_direc");

        $html.="<select name='proc_direc' id='proc_direc' >";
        $html.="<option value='0'>-- Select Proced --</option>";

        while ($Row = parent::ResultAssoc($query)) {
            $idsolicita = $Row['ID'];
            $soli_descripcion = $Row['DES_DR'];
            $idsolicita = str_pad($idsolicita, 2, "0", STR_PAD_LEFT);
            $html .="<option value='$idsolicita'>$soli_descripcion</option>";
        }
        $html .="</select>";
        return $html;
    }

    /**/
    function procreha() {
        $query = parent::Query($sql = "SELECT id_procedencia,nombre_procedencia FROM procedencia_rehabilitacion");

        $html.="<select name='proc_direc' id='proc_direc' >";
        $html.="<option value='0'>-- Select Proced --</option>";

        while ($Row = parent::ResultAssoc($query)) {
            $idsolicita = $Row['id_procedencia'];
            $soli_descripcion = $Row['nombre_procedencia'];
            $idsolicita = str_pad($idsolicita, 2, "0", STR_PAD_LEFT);
            $html .="<option value='$idsolicita'>$soli_descripcion</option>";
        }
        $html .="</select>";
        return $html;
    }
    /**/
    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function procdescrip($ID) {
        $query = parent::Query($sql = "SELECT DES_DR FROM procedencia_direc where ID='$ID'");
        $Row = parent::ResultAssoc($query);
        $procdes = $Row['DES_DR'];
        return $procdes;
    }

    function procdescripR($ID) {
        $query = parent::Query($sql = "SELECT id_procedencia,nombre_procedencia FROM procedencia_rehabilitacion where id_procedencia = '$ID'");
        $Row = parent::ResultAssoc($query);
        $procdes = $Row['nombre_procedencia'];
        return $procdes;
    }
    
    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GetVoucher($pidsol) {
        $query = parent::Query("SELECT tipo_img,fec_pago FROM personas WHERE p_idsol='$pidsol'");
        while ($row = parent::ResultAssoc($query)) {
            $nro_voucher = $row["tipo_img"];
            $fec_voucher = $row["fec_pago"];
        }
        return array($nro_voucher, $fec_voucher);
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbRehabilitadoPOSIT($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdBuscador"]);
        $nroficio = mysql_real_escape_string($_POST["nroficio"]);
        $proc_direc = mysql_real_escape_string($_POST["proc_direc"]);
        $proc_descrip = self::procdescripR($proc_direc);
        $nroexp = mysql_real_escape_string($_POST["nroexp"]);
        $autjudic = mysql_real_escape_string($_POST["autjudic"]);
        $fecrehab = self::FechaMysql($_POST["fecrehab"]);
        $obs_reh = mysql_real_escape_string($_POST["obs_reh"]);
        $sql = "SELECT p_idsol,p_apepat,p_apemat,p_nombres FROM personas where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $sqlinsert = "INSERT INTO rehabilitacion (id_solicitud, idUsuario,p_apepat, p_apemat, p_nombres, nro_oficio, proc_doc, nro_exped, aut_judicial, fec_reh, obs)";
        $sqlinsert .=" VALUES ('$IdBuscador','$IdUsuario', '" . $Row['p_apepat'] . "', '" . $Row['p_apemat'] . "', '" . $Row['p_nombres'] . "', '$nroficio', '$proc_descrip', '$nroexp', '$autjudic', '$fecrehab', '$obs_reh');";
        $query = parent::Query($sqlinsert);
        $query2 = parent::Query($sqlupdate = "UPDATE personas SET emite = 'LISTO' WHERE p_idsol = '$IdBuscador'");
        self::CreaCertificadoPDF('', $IdBuscador);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "6";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbRehabilitadoCOINC($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdDetalleCoincide"]);
        $obs_reh = mysql_real_escape_string($_POST["obs_reh"]);
        $sql = "SELECT p_idsol,p_apepat,p_apemat,p_nombres FROM personas where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $sqlinsert = "INSERT INTO rehabilitacion (id_solicitud,idUsuario, p_apepat, p_apemat, p_nombres, obs)";
        $sqlinsert .=" VALUES ('$IdBuscador','$IdUsuario', '" . $Row['p_apepat'] . "', '" . $Row['p_apemat'] . "', '" . $Row['p_nombres'] . "', '$obs_reh');";
        $query = parent::Query($sqlinsert);
        $query2 = parent::Query($sqlupdate = "UPDATE personas SET emite = 'LISTO' WHERE p_idsol = '$IdBuscador'");
        self::CreaCertificadoPDF('', $IdBuscador);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "7";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        //$sqljh = "INSERT personas_observadas(p_idsol, idUsuario, texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario." ,'".$_POST['obsj']."', NOW())";
        $sqljh = "INSERT personas_observadas(p_idsol, idUsuario ,texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario." , '".$_POST['obs_reh']."', NOW())";
        $querjhy = parent::Query($sqljh);
        
        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbPositA($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdBuscador"]);
        $sql = "update personas set emite='POS-A' where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "9";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        $sqljh = "INSERT personas_observadas(p_idsol, idUsuario, texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario." ,'".$_POST['observa']."', NOW())";
        $querjhy = parent::Query($sqljh);
        
        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbHomo($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdBuscador"]);
        $tobservah = mysql_real_escape_string($_POST["tobservah"]);
        $sql = "SELECT p_idsol,p_apepat,p_apemat,p_nombres FROM personas where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $sqlinsert = "INSERT INTO rehabilitacion (id_solicitud, p_apepat, p_apemat, p_nombres, obs)";
        $sqlinsert .=" VALUES ('$IdBuscador', '" . $Row['p_apepat'] . "', '" . $Row['p_apemat'] . "', '" . $Row['p_nombres'] . "', '$tobservah');";
        $query = parent::Query($sqlinsert);

        $sql2 = "update personas set emite='LISTO' where p_idsol='$IdBuscador';";
        $query2 = parent::Query($sql2);
        self::CreaCertificadoPDF('', $IdBuscador);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "6";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbCObserv($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdDetalleCoincide"]);
        $sql = "update personas set estado='1',emite='OBSV' where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "8";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        $sqljh = "INSERT personas_observadas(p_idsol, idUsuario, texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario.", '".$_POST['obs_reh']."', NOW())";
        $querjhy = parent::Query($sqljh);
        
        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbCPosit($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdDetalleCoincide"]);
        $sql = "update personas set estado=NULL,emite='POSIT' where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "17";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        //$sqljh = "update personas_observadas set texto_observado = '".$_POST['obsj']."' where p_idsol='$IdBuscador';";
        //$querjhy = parent::Query($sqljh);

        $sqljh = "INSERT personas_observadas(p_idsol, idUsuario, texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario." , '".$_POST['obsj']."', NOW())";
        $querjhy = parent::Query($sqljh);

        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        //echo "UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'";
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function GbCListo($_POST, $IdUsuario) {
        $IdBuscador = mysql_real_escape_string($_POST["IdDetalleCoincide"]);
        $sql = "update personas set emite='LISTO' where p_idsol='$IdBuscador';";
        $query = parent::Query($sql);
        self::CreaCertificadoPDF('', $IdBuscador);

        $Solicitud = self::DatosSolicitud($IdBuscador);
        $Id = $IdBuscador;
        $Emitir = "5";
        $Lugar = $Solicitud[1];
        $IdSoLocal = $Solicitud[0];

        //$sqlz = "update personas_observadas set texto_observado = '".$_POST['obsj']."' where p_idsol='$IdBuscador';";
        //$queryz = parent::Query($sqlz);
        $sqljh = "INSERT personas_observadas(p_idsol, idUsuario, texto_observado, fecha_registro) values( ".$IdBuscador.", ".$IdUsuario." ,'".$_POST['obsj']."', NOW())";
        $querjhy = parent::Query($sqljh);
        
        $Datos = self::GetVoucher($IdBuscador);
        self::auditoria($IdUsuario, $Id, $Emitir, $Lugar, $IdSoLocal, $Datos[0], $Datos[1]);
        $last_auditoria = self::last_auditoria($Id);
        parent::Query("UPDATE personas set nom_ofi='$last_auditoria',migrado='0' WHERE p_idsol='$Id'");
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------------------------- */

    function DatosSolicitud($Id) {
        $sql = "SELECT id_solcitud,proc_dir FROM personas WHERE p_idsol='$Id'";
        $query = parent::Query($sql);
        $Result = parent::ResultAssoc($query);
        return array($Result['id_solcitud'], $Result['proc_dir']);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//  Funcion para Mostrar el Resultado de la Busqueda
    function ResultPersonal($Buscar, $Solicita) {
        if ($Buscar == '1') {
            $html = $this->ResultAntecedentes($Solicita);
        } elseif ($Buscar == '0') {
            $html = "<img src='../Img/busca0.gif' style='border:none' />";
        } else {
            $html = "Error";
        }
        return $html;
    }

//  Busqueda de Personas Encontradas

    function ResultAntecedentesWS($id_Solicitud) {
        //  E es por que escojio la opcion Ninguno
        $Kry = parent::Query("SELECT * FROM buscador where p_idsol = '$id_Solicitud' and relacion != 'E'");
        $NumReg = parent::NumReg($Kry);
        $html = ($NumReg >= 1) ? true : false;
        return $html . "-SELECT * FROM buscador where p_idsol = '$id_Solicitud' and relacion != 'E'";
    }

    function ResultAntecedentes($Solicitud) {
        //  E es por que escojio la opcion Ninguno
        $Kry = parent::Query("SELECT * FROM buscador where p_idsol = '$Solicitud' and relacion != 'E'");
        $NumReg = parent::NumReg($Kry);
        $html = ($NumReg >= 1) ? "COINC" : "LISTO";
        return $html;
    }

    /*     * validar si la solicitud esta correcto el pago o no? */

    function ValidarPago($fechaPago, $numeroVaucher, $tipo_pago) {

        if ($tipo_pago == 'VOUCHER') {
            //  Seleccionar los que no han sido buscado
            $result86 = parent::Query("select * from pagos pa where pa.fech = FROM_UNIXTIME('" . $fechaPago . "') and pa.num_sec='" . $numeroVaucher . "' and pa.buscador='0'");
            $numeroRegistro = parent::NumReg($result86);

            if ($numeroRegistro >= 1) {
                //  Actualizar el estado de busqueda, para no ser buscado
                $Kryuss = parent::Query("UPDATE pagos SET buscador='1' where fech = FROM_UNIXTIME('" . $fechaPago . "') and num_sec='" . $numeroVaucher . "'");

                //  Seleccionar pagos que tengan el flag 0 y buscador 1
                $Kry = parent::Query("select * from pagos pa where pa.fech = FROM_UNIXTIME('" . $fechaPago . "') and pa.num_sec='" . $numeroVaucher . "' and pa.buscador='1' and pa.flag='0'");
                $NumRegKry = parent::NumReg($Kry);

                //  Si el flag es 0 entonces todavia no se a impreso
                $Color = ($NumRegKry >= 1) ? array("VO.", "SI") : array("VO.", "NO");
            } else {
                //  Seleccionar pagos que tengan el flag 0 y buscador 1
                // and pa.flag='0'

                $Kry = parent::Query("select * from pagos pa where pa.fech = FROM_UNIXTIME('" . $fechaPago . "') and pa.num_sec='" . $numeroVaucher . "' and pa.buscador='1'");
                $NumRegKry = parent::NumReg($Kry);

                //  Si el flag es 0 entonces todavia no se a impreso
                $Color = ($NumRegKry >= 1) ? array("VO.", "SI") : array("VO.", "NO");
                //$Color = ($NumRegKry >= 1) ? array("VO.", "NO") : array("VO.", "SI");
            }
        } elseif ($tipo_pago == 'RECIBO') {
            $Color = array("RE.", "SI");
        } elseif ($tipo_pago == 'RECIBO-TESORERIA') {
            $Color = array("TE.", "SI");
        } else {
            $Color = array("OT.", "SI");
        }

        return $Color;
    }

//  Funcion para saber si hay Certificados para Emitir
    function ExistePdf($Usuario) {
        $Sql = "SELECT g.id_generado,CONCAT(p_apepat,' ',p_apemat,' ',p_nombres) AS NombreFull FROM personas p , generado_solicitud g WHERE g.id_generado = p.p_idsol AND g.flag = '0' AND g.cod_user = '$Usuario'";
        $Kry = parent::Query($Sql);
        $Num = parent::NumReg($Kry);
        //  $Resulta = parent::ResultArray($Kry);
        //  array_unshift($Resulta, $Num);

        return $Num;
    }

//  Quitar Caracteres Especiales
    function DeletCaracter($cadena) {
        return str_replace("\\", '', $cadena);
    }

//  Auditoria de Texto
    function AuditoriaPDF($axion, $id_user, $ip_user, $ip_user_proxy) {
        $sql = "INSERT INTO log (id_log, axion, id_user, ip_user, ip_user_proxy, fec_hor) VALUES (NULL ,'$axion','$id_user','$ip_user','$ip_user_proxy',NOW( ))";
        parent::Query($sql);
    }

//  Fecha para Imprimir Certificados
    function FechaFormateadaAnder($FechaStamp, $FehaOmar) {
        if (empty($FehaOmar)) {
            $ano = date('Y', $FechaStamp); //<-- Año
            $mes = date('m', $FechaStamp); //<-- número de mes (01-31)
            $dia = date('d', $FechaStamp); //<-- Día del mes (1-31)
        } else {
            $dia = substr($FehaOmar, 0, 2); //<-- Año
            $mes = substr($FehaOmar, 3, 2); //<-- número de mes (01-31)
            $ano = substr($FehaOmar, 6, 4); //<-- Día del mes (1-31)
        }

        switch ($mes) {
            case '01': $mesletra = "Enero";
                break;
            case '02': $mesletra = "Febrero";
                break;
            case '03': $mesletra = "Marzo";
                break;
            case '04': $mesletra = "Abril";
                break;
            case '05': $mesletra = "Mayo";
                break;
            case '06': $mesletra = "Junio";
                break;
            case '07': $mesletra = "Julio";
                break;
            case '08': $mesletra = "Agosto";
                break;
            case '09': $mesletra = "Septiembre";
                break;
            case '10': $mesletra = "Octubre";
                break;
            case '11': $mesletra = "Noviembre";
                break;
            case '12': $mesletra = "Diciembre";
                break;
        }

        return "Lima, $dia de $mesletra del $ano";
    }

//  Funcion de Informacion
    function Info() {
        $html = "<table border='0' cellpadding='0' cellspacing='0'>";
        $html .= "<tr><td bgcolor='#FFE1E6'>&nbsp;Voucher&nbsp;</td>";
        $html .= "<td bgcolor='#D2F9D2'>&nbsp;Recibo&nbsp;</td></tr>";
        $html .= "<tr><td bgcolor='#ADD8E6' colspan='2'>&nbsp;Recibo-Tesoreria&nbsp;</td></tr>";
        $html .= "</table>";

        return $html;
    }

//  Estadistica de Registro
    function Estadisticas($fecha) {

        if ($fecha == '') {
            $fecha = date('Y-m-d');
        }
        //set_locale(LC_ALL,'es_ES@euro','es_ES','esp');
        //$fecha
        $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');
        $partes = explode("-", $fecha);
        $sd = ((int) $partes[1]) - 1;
        $fechaL = $partes[2] . " de " . $mes[$sd] . " del " . $partes[0];

        $SqlEsta = "SELECT d.DES_DR,COUNT(*), p.proc_dir FROM personas p , procedencia_direc d WHERE FROM_UNIXTIME(p_fechasol,'%Y-%m-%d') BETWEEN '" . $fecha . "' AND '" . $fecha . "' AND p.proc_dir = d.ID AND p_apepat!='' GROUP BY d.DES_DR ASC";
        $SqlTotal = "SELECT COUNT(*) AS total FROM personas p , procedencia_direc d WHERE FROM_UNIXTIME(p_fechasol,'%Y-%m-%d') BETWEEN '" . $fecha . "' AND '" . $fecha . "' AND p.proc_dir = d.ID AND p_apepat!=''";

        $Kry = parent::Query($SqlEsta);

        $result = parent::Query($SqlTotal);
        $Total = parent::ResultArray($result);
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='clear:both'></div>";
        $html .= "<div style='padding-top:30px; overflow:hidden'></div>";
        $html .= "<fieldset id='Totales'>";
        $html .= "<div style='padding-top:30px; overflow:hidden'></div>";
        $html .= "<legend>Registro del D&iacute;a: &nbsp;<b>$fechaL</b></legend>";
        $html .= "<div style='padding-top:10px; overflow:hidden'></div>";
        $html .= "<table align='center' border='1' style='border-collapse:collapse;border: solid 1px #aacfe4;font-size:10px;width:'auto';height='auto' cellpadding='2' cellspacing='2'><tr><td align='center'><b>DIRECCIONES</b></td><td align='center'><b>Nro.&nbsp;REGISTRO</b></td></tr>";
        while ($Fila = parent::ResultArray($Kry)) {
            $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none'>";
            $html .= "<td>" . $Fila[0] . "</td>";
            $html .= "<td align='center'>" . $Fila[1] . "</td>";
            $html .= "</tr>";
        }
        $html .= "<tr style='border-collapse:collapse;border: solid 2px #aacfe4';onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none'>";
        $html .= "<td><b>TOTAL</b></td>";
        $html .= "<td align='center'><font color='red'><b>" . $Total[0] . "</b></font></td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "<hr color='silver'><center><input type='button' name='BackFecha' id='BackFecha' value='<<' onclick='javascript:Estadisticas(this.id)';' $evento  $estiloopc width:50px;'/><input type='button' name='LastFecha' id='LastFecha' value='>>' onclick='javascript:Estadisticas(this.id)';' $evento  $estiloopc width:50px;'/></center>";
        $html .= "</fieldset>";

        $html .= "<input type='hidden' name='hiddenEstadistica' id='hiddenEstadistica' value='" . $fecha . "'  /></td>";

        return $html;
    }

//  Funcion para eliminar la foto en el cliente
    function EliminaFotoClient() {
        $html = "<iframe src='http://localhost/borra.php' frameborder='0' scrolling='no' width='250px' height='30px'></iframe>";
        return $html;
    }

//  Listado de Busqueda
    function ListadoDocumentos($Search) {
        $html = "<br /><div id='divFormInsc' style='width:984px;'><fieldset id='field'>";
        $html .= "<legend><strong><center> Generar Documento </center> </strong></legend>";
        $html .= "<div id='Filtros1'>" . $this->DocDestinatario() . "</div>";
        $html .= "<div id='Filtros1'>" . $this->opcionesDocumento() . "</div>";
        $html .= "<div style='clear:both'></div><div id='ContainerRegistro1'>" . $this->DocPredeterminado() . "</div></fieldset>";
        $html .= "<div style='clear:both'></div><br /><div id='ListadosInternos'><fieldset id='field'><legend><strong><center> Listado de Positivos Pendientes de Impresi&oacute;n </center> </strong></legend><br />" . $this->ListadoEmiteOficiosDoc($Search) . "</fieldset></div>";

        $html .= "<div style='clear:both'></div><br /><div id='ListadosInternosO'>" . $this->ListadoEmiteOficiosPositivo($Search, $nro, $fecha) . "</div>";

        $html .= "</div>";
        return $html;
    }

//  Destinatario
    function DocDestinatario() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<table border='0' cellpadding='2' cellspacing='2' style='width:100%'><tr style='height:30px'>";
        $html .= "<td><b>Nro.&nbsp;OFICIO&nbsp;:</b></td><td><input class='InputText' style='width:80px;' type='text' name='NroOficioDocs' onBlur='this.value=ignoreSpaces(this.value);' onkeypress='javascript:return valident(event);' maxlength='20' /></td>";
        $html .= "<td align='right'><b>&nbsp;FECHA&nbsp;DE&nbsp;IMPRESION&nbsp;:&nbsp;</b></td>";
        $datex = date('d-m-Y');
        $html .= "<td><input class='InputText' style='width:80px;' type='text' name='FechaPrintDoc' id='FechaPrintDoc' value='$datex' onKeyUp='this.value=formateafecha(this.value);' readonly /></td>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>";
        $html .= "<input TYPE='BUTTON' VALUE='Generar' onClick=javascript:generaOficio(); $evento  $estiloopc width:80px;'/>";
        $html .= "</td></tr></table>";

        return $html;
    }

    function opcionesDocumento() {
        $html .= "<table border='0' cellpadding='2' cellspacing='2' style='width:578px'><tr style='height:30px'><td>&nbsp;Regiones&nbsp;y&nbsp;Establecimientos&nbsp;Penitenciario</td>";
        $html .= "<td><input type='radio' name='defectoValor' checked value='xdefecto' style='border:0' onclick=javascript:mostrarDefecto('defecto'); /></td>";
        $html .= "<td>&nbsp;</td>";
        $html .= "<td>Seleccionar&nbsp;Destinatario:</td>";
        $html .= "<td><input type='radio' name='defectoValor' value='nxdefecto' style='border:0' onclick=javascript:mostrarDefecto('nodefecto'); /></td>";
        $html .= "<td>&nbsp;</td>";
        $html .= "</tr></table>";

        return $html;
    }

//  Seleccionar Destinatario predeterminado
    function DocPredeterminado() {

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<div id='defecto' style='display: none'></div>";
        $html .= "<div id='nodefecto' style='display: none'>";

        $arraymuestraDirecion = $this->ShowRegionOficio();
        $incrementEstablecimiento = 0;

        for ($i = 0; $i < sizeof($arraymuestraDirecion[0]); $i++) {
            $regionChecArri = "";
            $regionChecArri .= "<input style='border:0' type='CHECKBOX' name='acts[" . $i . "]' value='" . $arraymuestraDirecion[1][$i] . "' >";
            $regionChecArri .= "<b>" . $arraymuestraDirecion[1][$i] . "&nbsp;&nbsp;&nbsp;</b>";
            $regionChecArri .= "<img name=section" . $i . "  src='../Img/mas.gif' alt='+' border='0' onClick=javascript:ocultarDatos('row[" . $i . "]');cambiarImg(this.name);><br>";
            $regionChecArri .= "<input type='hidden' name='numacts' />";
            //$regionChecArri .= "";

            $regionChecArri .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;width:450px;'  cellspacing='1' cellpadding='1' >";
            $regionChecArri .= "<tr id='row[" . $i . "]' style='display:none' >";
            $regionChecArri .= "<td>";

            $html .= $regionChecArri;

            $arraymuestraEstablecimiento = $this->ShowEEPPOficio($arraymuestraDirecion[0][$i]);
            for ($ii = 0; $ii < sizeof($arraymuestraEstablecimiento[0]); $ii++) {
                $html .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                $html .="<img src='../Img/vista.gif' alt='+' border='0'>";
                $html .= "&nbsp;<input style='border:0' type='CHECKBOX' value='" . $arraymuestraEstablecimiento[1][$ii] . "' name='nameEstablecimiento[" . $i . "][" . $ii . "]'> " . $arraymuestraEstablecimiento[1][$ii] . "<br>";
                $html .= "<input type='hidden' name='numEstablecimiento[" . $i . "]' value'" . $ii . "' />";
            }
            $imenosUno = $ii - 1;
            $regionChecArri = "";
            $regionChecArri = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
            $regionChecArri .="<img src='../Img/vista.gif' alt='+' border='0'>";
            $regionChecArri .= "&nbsp;<input type='CHECKBOX' name='todoEstab[" . $i . "]' onclick=ponerMarcasEstablecimiento('" . $i . "','" . $imenosUno . "'); >";

            $regionChecArri .= "<i><u>Seleccionar Todos Los Penales</u></i>";
            $regionChecArri .= "</td>";
            $regionChecArri .= "</tr>";
            $regionChecArri .= "</table>";

            $html .= $regionChecArri;
        }

        $html .= "<br />";
        $html .= "<input TYPE='BUTTON' VALUE='Marcar Todos' onClick=javascript:ponerMarcas(); $evento  $estiloopc width:120px;'/>";
        $html .= "&nbsp;&nbsp;&nbsp;<input TYPE='BUTTON' VALUE='Desmarcar' onClick=javascript:quitarMarcas(); $evento  $estiloopc width:120px;'/>";
        $html .= "</div>";

        return $html;
    }

//  Mostrar Direcciones para el Oficio
    function ShowRegionOficio() {
        $sql = "SELECT COD_DR,DES_DR FROM direciones";
        $query = parent::Query($sql);
        while ($result = parent::ResultAssoc($query)) {
            $cod[] = $result["COD_DR"];
            $nom[] = $result["DES_DR"];
        }
        return array($cod, $nom);
    }

//  Mostrar la EEPP de la Regiones
    function ShowEEPPOficio($dirRegional) {
        $sql = "SELECT esta_nombre,idestablecimiento FROM esta_penitenciario where region_idregion=" . $dirRegional . " and flag=1";
        $query = parent::Query($sql);
        while ($result = parent::ResultAssoc($query)) {
            $cod[] = $result["idestablecimiento"];
            $nom[] = $result["esta_nombre"];
        }
        return array($cod, $nom);
    }

    /*  Funcion Listado de de los Documentos Impresos y los q se crearan   */

    function ListadoEmiteOficiosDoc($Find) {
        //$_pagi_sql = "SELECT g.cod_user,CONCAT(u.usu_pate,' ',u.usu_mate,' ',u.usu_nomb) AS Nombre,g.batch_solici,DATE_FORMAT(g.fecha,'%d-%m-%Y') AS Fecha,DATE_FORMAT(g.fecha,'%H:%i:%s') AS Hora FROM generado_solicitud g , Usuario u WHERE g.cod_user = u.idUsuario AND u.proc_id = '$Find[0]' AND g.batch_solici IS NOT NULL GROUP BY batch_solici ORDER BY g.fecha DESC";
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_sql = "SELECT *, (FROM_UNIXTIME(p.p_fechasol, '%d-%m-%Y')) as fechasol FROM personas p, generado_solicitud g WHERE p.p_idsol=g.id_generado AND (p.emite = 'POSIT' OR p.emite = 'OBSV' OR p.emite = 'COINC') AND g.flag='0' AND p.estado is NULL AND p.p_apepat !='' AND p.p_apemat !='' AND p.p_nombres !='' ORDER BY p.p_idsol ASC";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 10;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";

        require_once("Paginador.cls.php");

        $html .= "<div style='padding-left:10px'><table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>APELLIDO PATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>APELLIDO MATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:90px;'>DOCUMENTO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>NRO DOCUMENTO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>FECHA SOLICITUD</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:290px;'>OBSERVACION</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:70px;'>ESTADO</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td> <input style='border:0;cursor:pointer' type='checkbox' name='chkpersona[]' value='$Rows[0]' title='Imprimir' /></td>";
                $html .= "<td>" . strtoupper($Rows[1]) . "</td>";
                $html .= "<td>" . $Rows[2] . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . "</td>";

                $html .= "<td align='center'>" . $Rows['p_tipdocu'] . "</td>";
                $html .= "<td align='center'>" . $Rows['p_numdocu'] . "</td>";
                $html .= "<td align='center'>" . $Rows['fechasol'] . "</td>";
                $html .= "<td align='center'>" . substr($Rows['observacion'], 0, 30) . "...</td>";

                $html .= "<td align='center'>" . $Rows['emite'] . "</td>";
                //$html .= "<td align='center'><a href='../pdf/certificados/" . $Rows[2] . "' target='_blank' ><img src='../Img/dow.gif' alt='Descargar' border='0' /></a></td>";
                $html .= "</tr>";
            }

            $html .= "<tr><td colspan='9' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>No se encontraron registros para Positivos Pendientes !</b></font></td></tr>";
        }
        $html .= "</table></div>";
        return $html;
    }

    /////
    function ListadoEmiteOficiosPositivo($Find, $numero="", $fecha="") {

        $hoy = date('d-m-Y');

        if ($numero != "") {
            $nror = str_replace("OFICIO-", "", $numero);
            $nror = str_replace(".pdf", "", $nror);

            $criterioN = " AND g.batch_solici LIKE '%" . $nror . "%' ";
        } else {
            $criterioN = "";
        }

        if ($fecha != "") {
            $criterioF = " AND DATE_FORMAT(g.fecha,'%d-%m-%Y') = '" . $fecha . "'";
        } else {
            $criterioF = "";
        }

        if ($numero != "" || $fecha != "") {
            $cri = "";
        } elseif ($numero == "" && $fecha == "") {
            $cri = " AND DATE_FORMAT(g.fecha,'%d-%m-%Y') = '" . $hoy . "' ";
        }

        $_pagi_sql = "SELECT p.cod_user, p.proc_dir ,CONCAT(u.usu_pate,' ',u.usu_mate,' ',u.usu_nomb) AS Nombre,g.batch_solici,DATE_FORMAT(g.fecha,'%d-%m-%Y') AS Fecha,
DATE_FORMAT(g.fecha,'%H:%i:%s') AS Hora, g.flag , g.id_generado FROM generado_solicitud g , Usuario u, personas p WHERE
-- g.cod_user = u.idUsuario
p.cod_user = u.idUsuario AND
 p.p_idsol = g.id_generado  AND g.batch_solici IS NOT NULL $cri $criterioN $criterioF  GROUP BY batch_solici ORDER BY g.fecha DESC";

        //$_pagi_sql = "SELECT g.cod_user,CONCAT(u.usu_pate,' ',u.usu_mate,' ',u.usu_nomb) AS Nombre,g.batch_solici,DATE_FORMAT(g.fecha,'%d-%m-%Y') AS Fecha,DATE_FORMAT(g.fecha,'%H:%i:%s') AS Hora, g.flag , g.id_generado FROM generado_solicitud g , Usuario u WHERE g.cod_user = u.idUsuario AND u.proc_id = '$Find[0]' AND g.batch_solici IS NOT NULL $cri $criterioN $criterioF  GROUP BY batch_solici ORDER BY g.fecha DESC";
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_pagi_sql = "SELECT *, (FROM_UNIXTIME(p.p_fechasol, '%d-%m-%Y')) as fechasol FROM personas p, generado_solicitud g WHERE p.p_idsol=g.id_generado AND (p.emite = 'POSIT' OR p.emite = 'OBSV') AND g.flag='0' ORDER BY p.p_idsol ASC";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $html .= "<fieldset id='field'><legend><strong><center> B&uacute;squeda de Oficio de Positivos </center> </strong></legend><br /><div style='padding-left:10px'><table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px;'>";
        $html .= "<tr><td colspan='2'>Nro de Documento <input type='text' name='nro' /></td>";
        $html .= "<td colspan='4'>Fecha Emisi&oacute;n de Documento <input type='text' name='fecha1' id='fecha1' /> <input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.fecha1,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='1' /></td>";
        $html .= "<td><input type='button' value='Buscar' onClick='javascript:buscarOficio();' /></td></tr>";
        $html .= "<tr><td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>ID</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>Procedencia.</td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>Usuario.</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:300px;'>USUARIO</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>DOCUMENTO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>FECHA</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:90px;'>HORA</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>ESTADO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>IMPRIMIR</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NRO IMPRESIONES</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:70px;'>ESTADO</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $estado = $Rows['flag'] == 1 ? "Impreso" : "Por Imprimir";
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows['id_generado'] . "</td>";
                //$html .= "<td>" . strtoupper($Rows[1]) . "</td>";
                //$html .= "<td>" . $Rows[2] . "</td>";
                //$html .= "<td align='center'>" . $Rows[3] . "</td>";
                $html .= "<td align='center'>" . $Rows['proc_dir'] . "</td>";
                $html .= "<td align='center'>" . $Rows['cod_user'] . "</td>";

                $html .= "<td align='center'>" . $Rows['Hora'] . "</td>";
                $html .= "<td align='center'>" . $estado . "</td>";
                $html .= "<td align='center'><a href='../pdf/certificados/" . $Rows[3] . "' target='_blank' title='Imprimir' ><img src='../Img/dow.gif' alt='Descargar' border='0' /></a></td>";
                //$html .= "<td align='center'>" . $Rows['fechasol'] . "</td>";
                //$html .= "<td align='center'>" . substr($Rows['observacion'],0,30) . "...</td>";
                //$html .= "<td align='center'>" . $Rows['emite'] . "</td>";
                $html .= "<td align='center'>&nbsp;</td>";
                $html .= "</tr>";
            }
        } else {
            $html .= "<tr><td colspan='7' align='center'><font color='red'><b> No se encontraron Oficios emitidos al dia de hoy !</b></font></td></tr>";
        }
        $html .= "</table></div></fieldset>";
        //}
        return $html;
    }

    //////////////////////////fin
    //
//  Funcion para Extraer la Primera letra de su nombre Completo
    function NameInicial($BuscarTexto) {
        $palabras = split(' ', preg_replace("[\s+]", ' ', trim($BuscarTexto)));
        for ($i = 0; $palabras[$i]; $i++) {
            $Search .= substr($palabras[$i], 0, 1);
        }
        return $Search;
    }

//  Para Extraer sus Datos del USuario
    function DataUsuario($IdUser) {
        $SqlUsu = "SELECT idUsuario, proc_id, usu_pate, usu_mate, usu_nomb, usu_logi, usu_pass, usu_flag, usu_tipo, ubica	FROM Usuario WHERE idUsuario='$IdUser'";
        $KryUsu = parent::Query($SqlUsu);
        $Data = parent::ResultArray($KryUsu);
        return $Data;
    }

    /* ////////////////////////////////////////////////////////OMAR1/////////////////////////////////////////////////////////////////// */

    function closeMysql() {
        $Query = new Consulta();
        $Query->close();
    }

    function Flistado($Find) {

        $html .= "<br /><div id='divFormInsc' style='width:968px; overflow:hidden'>";
        $html .= "<fieldset id='field'>";
        $html .= "<legend><strong><center> Pagos </center> </strong></legend>";
        $html .= "<div id='Filtros'>" . $this->FiltrosEmisionCertifica1() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Procedencia' style='display:none;'>" . $this->SelectProcedencias() . "</div><div id='Busquedas'></div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCerti1($Find) . "</div></fieldset>";
        $html .= "</div>";
        return $html;
    }

    function FiltrosEmisionCertifica1() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1' style='width:794px'><tr>";
        //$html .= "<td>PROCEDENCIA&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision1(1)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(2)'></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(3)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>F.&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(4)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>ESTADO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(6)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>T. DOC&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(7)' ></td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmision1(5)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificado1(this.name);' $evento  $estiloopc width:90px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table><br />";
        return $html;
    }

    function ListadoEmiteCerti1($Find) {

        //echo "<script>alert(\"$Find[0]\")</script>";

        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";
            $criterio = " AND p.p_nombres LIKE '$Find[3]%' AND p.p_apepat LIKE '$Find[1]%' AND p.p_apemat LIKE '$Find[2]%' ";
            $IdUsuario = $Find[4];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $criterio = " AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') ";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Procede') {
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Doc') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $criterio = " AND p.tipo_img = '$Find[1]' ";
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Estado') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            //$Estados = ($Find[1] != "ATEN") ? "AND g.flag = '0' AND p.emite = '$Find[1]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
            //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin  $Estados  ORDER BY p.p_idsol DESC";
            $criterio = " AND pg.flag = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        } elseif ($Find[0] == 'tipoD') {
            $criterio = " AND p.tipo_pago = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        }

        $_pagi_sql = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag , pg.fech, pg.hor FROM personas p , pagos_historial pg WHERE p.tipo_img = pg.num_sec $criterio $Admin GROUP BY p.tipo_img ORDER BY p.p_fechasol DESC";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables

        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table  style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once('Paginador.cls.php');

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='padding-left:7px'><table  style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:790px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:54px;background: #dfeaee;'><strong>NroVO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong> PATERNO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong> MATERNO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong>NOMBRES</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>FECHA&nbsp;SOL.</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>HORA&nbsp;SOL.</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>FECHA&nbsp;PAGO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>HORA&nbsp;PAGO</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px;background: #dfeaee'>&nbsp;<strong>IMPORTE</strong></td>";

        if ($Column != 'ATEN') {
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:10px;background: #dfeaee'><strong>CONSUMIDO</strong></td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
        }
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {

                $importe = (int) $Rows['importe'];
                $importe1 = substr($importe, 0, 2);
                $importe2 = substr($importe, 2, 2);
                if ($importe2 < 0 || $importe2 == "") {
                    $importe2 = "00";
                }

                $nuevoImporte = $importe1 . "." . $importe2;


                $subtotal += $nuevoImporte;

                $Antecedente = $Rows[11];
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";

                $html .= "<td>" . strtoupper($Rows['p_apepat']) . "</td>";
                $html .= "<td>" . strtoupper($Rows['p_apemat']) . "</td>";
                $html .= "<td>" . strtoupper($Rows['p_nombres']) . "</td>";

                $html .= "<td align='center'>" . $Rows['fecha'] . "</td>";
                $html .= "<td align='center'>" . $Rows['hora'] . "</td>";

                $html .= "<td align='center'>" . $Rows['fech'] . "</td>";
                $html .= "<td align='center'>" . $Rows['hor'] . "</td>";

                $html .= "<td align='center'>" . $nuevoImporte . "</td>";

                $estado = $Rows['flag'] == 0 ? "No" : " Si";

                $html .= "<td align='center'>" . $estado . "</td>";

                $html .= "</tr>";
            }

            $html .="<tr><td colspan='10' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
            $html .= "<tr><td colspan='10' class='paginac'>" . $_pagi_navegacion . "</td>";
            $html .= "</tr>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }

        //para obtener el total Actual
        //$tot       = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag FROM personas p , pagos pg WHERE p.tipo_img = pg.num_sec $criterio $Admin ORDER BY p.p_idsol DESC";
        $tot = "SELECT mpt_sol as importe FROM pagos_historial";
        $_pagi_tot = parent::Query($tot);
        $numtot = parent::NumReg($_pagi_tot);
        while ($rowT = parent::ResultArray($_pagi_tot)) {
            $importe = (int) $rowT['importe'];
            $importe1 = substr($importe, 0, 2);
            $importe2 = substr($importe, 2, 2);
            $nuevoImporte = $importe1 . "." . $importe2;
            $total += $nuevoImporte;
        }

        $totalNeto = $totalA + $total;
        $sumaHH = $totalj;

        $totVo = "SELECT COUNT(*) as cantidad FROM pagos_historial";
        $_vo_tot = parent::Query($totVo);
        $numtot = parent::ResultArray($_vo_tot);

        $totalPagosAH = $numtot['cantidad'] + $numtotH['cantidad'];
        //personas

        $totPe = "SELECT COUNT(*) as cantidad FROM personas where p_apepat != ''";
        $_pe_tot = parent::Query($totPe);
        $numtotpe = parent::ResultArray($_pe_tot);

        $sumaPersonas = $numtotpe['cantidad'] + $numtotpeH['cantidad'];

        //certificados impresos
        $sqlImp = "SELECT COUNT(*) AS canti FROM personas p, generado_solicitud g WHERE p.p_idsol=g.id_generado AND (p.emite = 'LISTO' OR p.emite = 'POST-A') AND g.flag='1' AND g.fecha IS NOT NULL ORDER BY p_idsol ASC";
        $query = parent::Query($sqlImp);
        $rowC = parent::ResultArray($query);

        $html .="<tr><td>&nbsp;</td><td colspan='8' style='border-top:#666 1px solid; text-align:center'>";
        $html .="</td><td>&nbsp;</td></tr>";

        $html .="<tr><td>&nbsp;</td><td colspan='8' style='text-align:center'> <strong>Cuadro de Estadisticas </strong>";
        $html .="</td><td>&nbsp;</td></tr>";

        $html .="<tr><td colspan='10'>";
        $html .= "<div id='todo'>";
        $html .= "<div style='width:auto; float:left; overflow:hidden'>";
        $html .= "<div class='personas-title'><strong>Personas que m&aacute;s solicitan certificados </strong></div><div class='cantidad-title'><strong>Cantidad</strong></div>";
        $html .= "<div class='clear'></div>";
        $sqlPP = "SELECT p_nombres,p_apepat, p_apemat, COUNT(p_numdocu) AS cantidad  FROM personas where p_apepat != '' AND p_apemat!='' GROUP BY p_numdocu ORDER BY cantidad DESC LIMIT 6";
        $queryPP = parent::Query($sqlPP);
        while ($rowPP = parent::ResultArray($queryPP)) {
            $html .= "<div class='personas' align='left'>&nbsp;&nbsp;" . $rowPP['p_nombres'] . " " . $rowPP['p_apepat'] . " " . $rowPP['p_apemat'] . "</div><div class='cantidad' align='left'>" . $rowPP['cantidad'] . "</div>";
            $html .= "<div class='clear'></div>";
        }

        //vouchers validados por el sistema
        $sql = "SELECT COUNT(*) as cantV FROM personas WHERE (emite = 'LISTO' OR emite = 'POSIT' OR  emite = 'COINC' OR emite = 'OBSV' OR emite = 'POS-A') AND p_apepat!=''";
        $query = parent::Query($sql);
        $rowV = parent::ResultArray($query);

        $totHJ = $rowV['cantV'] + $rowVHJ['cantidad'];

        //vouchers validados por el sistema pero no consumidos
        $sqlN = "SELECT COUNT(*) as cantV FROM personas WHERE (emite = 'VOUCH' OR emite = 'BUSCA') AND p_apepat!=''";
        $queryN = parent::Query($sqlN);
        $rowVN = parent::ResultArray($queryN);

        //vouchers nulos
        $sumaNul = $rowVN['cantV'] + $rowVNu['cantVN'];

        //$sqlVOU = "SELECT COUNT(*) as cantiv FROM personas p, pagos pg WHERE p.tipo_img = pg.num_sec AND pg.fech = FROM_UNIXTIME(fec_pago, '%Y-%m-%d')";
        //$sqlVOU = "SELECT fech, num_sec FROM pagos ORDER BY id DESC";
        $sqlVOU = "SELECT COUNT(*) AS cantiv FROM pagos_historial WHERE num_sec IN (SELECT tipo_img FROM personas)";
        $queryVOU = parent::Query($sqlVOU);
        $rowVOU = parent::ResultArray($queryVOU);

        //un poco pesado
        /* $sqlNoVou   = "SELECT COUNT(*) AS cantidadNO FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas)";
          $queryNoVou = parent::Query($sqlNoVou);
          $rowNoVou   = parent::ResultArray($queryNoVou);
         */

        $html .= "</div>";
        $html .= "<div style='width:334px; float:left; overflow:hidden; padding-left:5px'>";
        $html .= "<div class='personas1'><strong>Nro de Voucher Registrados:</strong></div><div class='totall' align='left'>" . number_format($totalPagosAH) . "</div>";
        $html .= "<div class='personas1'><strong>Importe de Voucher Registrados:</strong></div><div class='totall' align='left'>S/." . number_format($totalNeto, 2) . "</div>";

        //$html .= "<div class='personas'><strong>Cantidad de Certificados Impresos :</strong></div><div class='cantidad' align='left'>&nbsp;&nbsp;" . number_format($rowC['canti']) . "</div>";
        //$html .= "<div class='personas'><strong>Importe de Certificados Impresos :</strong></div><div class='cantidad' align='left'>&nbsp;&nbsp; S/. " . number_format($totalff,2) . "</div>";

        $html .= "<div class='personas1'><strong>Nro de Solic. Emitidas </strong></div> <div class='totall' align='left'>" . number_format($totHJ) . "</div>";
        $html .= "<div class='personas1'><strong>Cantidad de Solic. No Emitidas</strong> </div> <div class='totall' align='left'>" . number_format($sumaNul) . "</div>";

        $html .= "<div class='personas1'><strong>Total de Solicitudes Registradas:</strong></div><div class='totall' align='left'>S/." . number_format($sumaPersonas) . "</div>";
        //$html .= "<div class='personas1'><strong>Importe de Solicitudes Registradas:</strong></div><div class='totall' align='left'> S/." . number_format($sumaHH, 2) . "</div>";
        //$html .= "<br /><br />";
        $html .= "<div class='personas1'><strong>Nro de Voucher Usados </strong></div> <div class='totall' align='left'><a href='voucher_usados.php' title='Exportar Voucher Usados' onClick='Modalbox.show(this.href, {title: this.title, width: 600}); return false;'>" . number_format($rowVOU['cantiv']) . " <img src='../Img/Img/page_excel.png' title='Exportar a Excel' height='10' border='0' /></a> </div>";

        $restaa = $totalPagosAH - $rowVOU['cantiv']; //$rowNoVou['cantidadNO']

        $html .= "<div class='personas1'><strong>Nro de Voucher Sin Usar </strong></div> <div class='totall' align='left'><a href='voucher_sin_usar.php' title='Exportar Voucher Sin Usar' onClick='Modalbox.show(this.href, {title: this.title, width: 600}); return false;'>" . number_format($restaa) . " <img src='../Img/Img/page_excel.png' title='Exportar a Excel' height='10' border='0' /></a> </div>";

        $html .= "</div>";
        //$html .="<div style='clear:both; padding:10px'></div>";

        $html .= "<div style='clear:both'></div>";


        $html .= "<div style='width:auto; overflow:hidden; padding-top:15px'>";
        $anio = date('Y');
        $anio1 = date('Y') - 1;
        $anio2 = date('Y') - 2;
        $anio3 = date('Y') - 3;
        /*
          $html .= "<div class='personas-title'><strong>Reporte de Voucher - ".date('Y')." </strong></div><div class='cantidad-title'><strong>Cantidad Emitidos</strong></div>";
          $html .= "<div class='cantidad-title'><strong>Consumidos</strong></div>";
          $html .= "<div class='cantidad-title'><strong>Pendientes X Consumir</strong></div>";
          $html .= "<div class='clear'></div>";

          $me=1;
          $anio = date('Y');
          $anio1 = date('Y') - 1;
          $anio2 = date('Y') - 2;
          $anio3 = date('Y') - 3;

          for($f=0;$f<12;$f++){
          //echo "<script>alert(\"$sqlG\")</script>";
          $sqlG   = "SELECT COUNT(*) as cantM FROM pagos WHERE MONTH(fech) = ".$me." AND YEAR(fech) = '".$anio."'";
          $queryG = parent::Query($sqlG);
          $rowG   = parent::ResultArray($queryG);

          //consumidos
          $sqlCo   = "SELECT COUNT(*) as cantCo FROM pagos WHERE num_sec IN (SELECT tipo_img FROM personas) AND MONTH(fech) = ".$me." AND YEAR(fech) = '".$anio."'";
          $queryCo = parent::Query($sqlCo);
          $rowCo   = parent::ResultArray($queryCo);

          //No consumidos
          $sqlNo   = "SELECT COUNT(*) as cantNo FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas) AND MONTH(fech) = ".$me." AND YEAR(fech) = '".$anio."'";
          $queryNo = parent::Query($sqlNo);
          $rowNo   = parent::ResultArray($queryNo);


          $mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Setiembre', 'Octubre', 'Noviembre', 'Diciembre');

          $html .= "<div class='personas' align='left'>".$mes[$f]."</div>";
          $html .= "<div class='cantidad' align='left'><a href='imprimir_voucher_mes.php?mes=$me&anio=$anio&nombre=$mes[$f]&cp=2' title='Exportar mes de $mes[$f] - a&ntilde;o $anio'>" .number_format($rowG['cantM']). "</a></div>";
          $html .= "<div class='cantidad' align='left'><a href='imprimir_voucher_mes.php?mes=$me&anio=$anio&nombre=$mes[$f]&cp=1' title='Exportar mes de $mes[$f] - a&ntilde;o $anio'>" .number_format($rowCo['cantCo']). "</a></div>";
          $html .= "<div class='cantidad' align='left'><a href='imprimir_voucher_mes.php?mes=$me&anio=$anio&nombre=$mes[$f]&cp=0' title='Exportar mes de $mes[$f] - a&ntilde;o $anio'>" .number_format($rowNo['cantNo']). "</a></div>";
          $html .= "<div class='clear'></div>";
          $me++;
          $sum+=$rowG['cantM'];
          }

          $html .= "<div class='personas' align='left'><strong>Total</strong></div>";
          $html .= "<div class='cantidad' align='left'>" .number_format($sum). "</div>";
          $html .= "<div class='cantidad' align='left'>&nbsp;</div>";
          $html .= "<div class='cantidad' align='left'>&nbsp;</div>";
          $html .= "<div class='clear'></div>";
         */
        $html .= "</div>";

        $html .= "<div style='clear:both'></div>";

        $html .= "<div id='reportes' style='padding-top:5px'><strong>Reporte de Voucher -  $anio</strong> <a href='javascript:;'  onclick='reporte_anuala($anio)'>ver detalle &raquo;</a></div>";
        $html .= "<div id='anio'></div>";

        $html .= "<div id='reportes' style='padding-top:5px'><strong>Reporte de Voucher -  $anio1</strong> <a href='javascript:;'  onclick='reporte_anual($anio1)'>ver detalle &raquo;</a></div>";
        $html .= "<div id='anio1'></div>";

        $html .= "<div style='clear:both'></div>";

        $html .= "<div id='reportes' style='padding-top:5px'><strong>Reporte de Voucher -  $anio2</strong> <a href='javascript:;' onclick='reporte_anual1($anio2)'>ver detalle &raquo;</a></div>";
        $html .= "<div id='anio2'></div>";

        //$html .= "<div style='padding-top:5px'>Reporte de Voucher -  $anio3 <a href='javascript:;' onclick='reporte_anual2($anio3)'>ver detalle</a></div>";
        //$html .= "<div id='anio3'></div>";

        $html .= "</div>";

        $html .="</td></tr>";

        $html .= "</table>";



        $html .= "</div>";
        $html .= "<div><a href='#' onclick='imprimir_estadistica11();'>Imprimir Cuadro estadistico</a></div>";
        //$html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }

    function UsuarioDescrip($idUsuario) {
        $sql = "SELECT idUsuario,usu_logi FROM Usuario WHERE idUsuario='$idUsuario';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $idUsuario = $Row['idUsuario'];
        $usu_logi = $Row['usu_logi'];
        return $usu_logi;
    }

    function UsuarioDescripCompleto($idUsuario) {
        $sql = "SELECT idUsuario, CONCAT(usu_pate,' ',usu_mate,' ',usu_nomb) AS nombre FROM Usuario WHERE idUsuario='$idUsuario';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $idUsuario = $Row['idUsuario'];
        $usu_logi = $Row['nombre'];
        return $usu_logi;
    }
    
    function FlistadoUsuarios($Find) {
        //$html = "<br /><div id='ViCss' style='width:auto;'>";
        $html = "<br /><div id='divFormInsc1' style='width:850px;overflow:hidden'><fieldset id='field'>";
        $html .= "<legend><strong><center> Usuarios </center> </strong></legend>";
        $html .= "<div style='float:left; width:700px;overflow:hidden'>";
        $html .= "<div id='FiltrosUsu'>" . $this->FiltrosUsuarios() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Busquedas'></div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoUsuarios($Find) . "</div>";
        $html .= "</div>";
        $html .="<div id='nuevo' style='width:696px;overflow:hidden;padding-left:4px; padding-top:5px'></div>";
        //$html .="<div style='clear:both'></div>";
        $html .="<div id='datos' style='width:696px;overflow:hidden;padding-left:4px; padding-top:5px'></div>";
        //$html .="<div style='clear:both'></div>";
        $html .="<div id='permisos' style='width:696px;overflow:hidden;padding-left:4px; padding-top:5px'></div>";
        $html .="<div id='elimina'></div>";
        $html .="<div style='clear:both'></div>";
        $html .= "</fieldset></div>";

        return $html;
    }

    function paginar($actual, $total, $por_pagina, $enlace) {
        $total_paginas = ceil($total / $por_pagina);
        $anterior = $actual - 1;
        $posterior = $actual + 1;
        if ($actual > 1)
            $texto = "<a href=\"$enlace$anterior\">&laquo;</a> ";
        else
            $texto = "<b>&laquo;</b> ";
        for ($i = 1; $i < $actual; $i++)
            $texto .= "<a href=\"$enlace$i\">$i</a> ";
        $texto .= "<b>$actual</b>";
        for ($i = $actual + 1; $i <= $total_paginas; $i++)
            $texto .= " <a href=\"$enlace$i\">$i</a> ";
        if ($actual < $total_paginas)
            $texto .= " <a href=\"$enlace$posterior\">&raquo;</a>";
        else
            $texto .= " <b>&raquo;</b>";
        return $texto;
    }

    function ListadoUsuarios($Find) {

        //echo "<script>alert(\"$Find[0]\")</script>";

        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";
            $criterio = " AND usu_nomb LIKE '$Find[3]%' AND usu_pate LIKE '$Find[1]%' AND usu_mate LIKE '$Find[2]%' ";
            $IdUsuario = $Find[4];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $criterio = " AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') ";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Procede') {
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Doc') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $criterio = " AND usu_tipo = '$Find[1]' ";
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Estado') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            //$Estados = ($Find[1] != "ATEN") ? "AND g.flag = '0' AND p.emite = '$Find[1]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
            //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin  $Estados  ORDER BY p.p_idsol DESC";
            $criterio = " AND usu_flag = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        } /* else {
          $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[0]'";
          //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' ORDER BY p.p_idsol DESC";
          $_pagi_sql = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag FROM personas p , pagos pg WHERE p.tipo_img = pg.num_sec $Admin ORDER BY p.p_idsol DESC";

          $IdUsuario = $Find[0];
          } */


        //$_pagi_sql = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag FROM personas p , pagos pg WHERE p.tipo_img = pg.num_sec $criterio $Admin ORDER BY p.p_idsol DESC";
        $_pagi_sql = "SELECT (idUsuario) AS id, usu_nomb, usu_pate, usu_mate , usu_tipo , usu_flag FROM Usuario WHERE idUsuario<>0 $criterio $Admin";

        //echo "<script>alert(\"$_pagi_sql\")</script>";

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";

        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once('Paginador.cls.php');

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='padding-left:4px; overflow:hidden; width:696px; padding-top:2px'><table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:696px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;background:#dfeaee'>Id</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'>APEL. PATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'>APEL. MATERNO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'>NOMBRES</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;background:#dfeaee'>TIPO USUARIO</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;background:#dfeaee'>ESTADO</td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;background:#dfeaee'>OPCIONES</td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>&nbsp;</td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {
            //_pagi_result = parent::Query($_pagi_sql);
            while ($Rows = parent::ResultArray($_pagi_result)) {

                $importe = (int) $Rows['importe'];
                $importe1 = substr($importe, 0, 2);
                $importe2 = substr($importe, 2, 2);
                if ($importe2 < 0 || $importe2 == "") {
                    $importe2 = "00";
                }
                /*
                if ($Rows['usu_tipo'] == 1) {
                    $tipe = "Administrador";
                } else
                */
                if ($Rows['usu_tipo'] == 2) {
                    $tipe = "Administrador";
                } else {
                    $tipe = "Operador Web";
                }

                $estadd = $Rows['usu_flag'] == 1 ? "Activo" : "Inactivo";
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . strtoupper($Rows['usu_pate']) . "</td>";
                $html .= "<td>" . strtoupper($Rows['usu_mate']) . "</td>";
                $html .= "<td>" . strtoupper($Rows['usu_nomb']) . "</td>";
                $html .= "<td>" . $tipe . "</td>";
                $html .= "<td>" . $estadd . "</td>";
                $html .= "<td align='center'><a href='#' onclick='ver_edit(" . $Rows[0] . ");'><img src='../Img/editar_.gif' title='Editar' border='0' nombre='Editar' /></a>  <a href='#' onclick='eliminar_usuario(" . $Rows[0] . ");'><img src='../Img/eliminar_.gif' border='0' title='Eliminar' nombre='Eliminar' /></a> <a href='#' onclick='permisos(" . $Rows[0] . ")'><img src='../Img/permisos.png' border='0' title='Permisos' nombre='Permisos' /></a></td>";
                //$html .= "<td>" . $tipe . "</td>";
                $html .= "</tr>";
            }

            $html .="<tr><td colspan='7' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
            //$html .= "<tr><td colspan='6'>&nbsp;</td></tr>";
            $html .= "<tr><td colspan='7' class='paginac'>" . $_pagi_navegacion . "</td>";
            //$html .= "<td colspan='3' align='center'>";
            //$html .= "<a href='#' onclick=javascript:popup('popUpDiv'); >Imprimir</a>";
            //$html .= "<input type='button' id='ImprimirTodos' value='Imprimir Listo' onclick=javascript:popup('popUpDiv'); $evento  $estiloopc width:130px;' />";
            //$html .= "</td><td align='center'>";
//            $html .= "<input type='button' id='AnularPositivo' value='Anular' onclick=javascript:DetallePersona(this.id,'','',''); $evento  $estiloopc width:70px;' />";
            $html .= "</td>";
            $html .= "</tr>";
        } else {
            $html .= "<tr><td colspan='7' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }

        $html .= "<tr><td colspan='7' align='center'><a href='#' onclick='exportar_list();'>Exportar Listado de Usuarios por acceso</a></td></tr>";
        
        $html .="<tr><td colspan='7' align='center'>";

        $html .="<div style='text-align:right; padding-right:64px'>";
        $html .="<input type='button' value='Nuevo' class='btn_todo' onclick='ver_nuevo();' />";
        $html .="</div>";
        /*
          $html .="<div id='datos'>";
          $html .="<table width='100%'>";
          $html .="<table width='100%'>";
          $html .="<tr>";
          $html .="<td>Nombre : </td>";
          $html .="<td><input type='text' name='nombre' /></td>";
          $html .="<td>Apellido Paterno : </td>";
          $html .="<td><input type='text' name='apepat' /></td>";
          $html .="</tr>";
          $html .="<tr>";
          $html .="<td>Apellido Materno : </td>";
          $html .="<td><input type='text' name='apemat' /></td>";
          $html .="<td>Password : </td>";
          $html .="<td><input type='password' name='passw' /></td>";
          $html .="</tr>";
          $html .="</table>";
          $html .="</div>";
         */
        $html .="</td></tr></table></div>";

        return $html;
    }

    function procedenciaLitado() {
        $sqlP = "SELECT ID, DES_DR FROM procedencia_direc";
        $query = parent::Query($sqlP);

        while ($row = parent::ResultArray($query)) {
            echo "<option value=" . $row[0] . ">" . $row[1] . "</option>";
        }
    }

    function FiltrosUsuarios() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<div style='clear:both'></div><div style:'padding-bottom:10px; overflow:hidden'><table border='0' cellpadding='1' cellspacing='1' style='width:700px'><tr>";
        //$html .= "<td>PROCEDENCIA&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision1(1)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>NOMBRES:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0; width:15px' NAME='color' onclick='javascript:CamposBuscaUsuario(2)'></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>TIPO:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0; width:15px' NAME='color' onclick='javascript:CamposBuscaUsuario(3)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>ESTADO:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0; width:15px' NAME='color' onclick='javascript:CamposBuscaUsuario(6)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' style='border:0; width:15px' onclick='javascript:CamposBuscaUsuario(5)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindUsuario(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table></div><br />";
        return $html;
    }

    function listadoPenalesP() {
        //$sqlPenal   = "SELECT COD_PENAL, ABREV_DES_PENAL AS penal FROM sip_omar.PEN_PENAL_MAE";
        $sqlPenal = "SELECT COD_PENAL, ABREV_DES_PENAL AS penal FROM sip_omar.PEN_PENAL_MAE WHERE ABREV_DES_PENAL IS NOT NULL AND COD_PENAL > 0";
        $queryPenal = parent::Query($sqlPenal);

        $html = "<option value='0'>TODOS</option>";
        while ($row = parent::ResultArray($queryPenal)) {
            $html .="<option value=" . $row['COD_PENAL'] . ">" . $row['penal'] . "</option>";
        }

        return $html;
    }

    function FlistadoServer($Find) {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<br /><div id='divFormInsc' style='width:968px;overflow:hidden'>";
        $html .= "<br><fieldset id='field'>";
        $html .= "<legend><strong><center> B&uacute;squeda de Internos </center> </strong></legend>";
        $html .= "<br /><div id='Filtros'>" . $this->FiltrosInculpados() . "";
        $html .= "<table border='0' cellpadding='2' cellspacing='2'  style='width:700px;overflow:hidden'><tr>";
        $html .= "<td>PATERNO </td>";
        $html .= "<td><input class='InputText' type='text' name='PatEmite' id='PatEmite' tabindex='1'  onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
        $html .= "<td>MATERNO </td>";
        $html .= "<td><input class='InputText' type='text' name='MatEmite' id='MatEmite' tabindex='2'  onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
        $html .= "<td>NOMBRE </td>";
        $html .= "<td><input class='InputText' type='text' name='NomEmite' id='NomEmite' tabindex='3' onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
        $html .= "<td><input type='hidden' name='CajaEmision' value='2' /><input type='button' id='BuscaEmision' value='<< Buscar >>' name='EmiteNombre' onclick='javascript:FindInculpado(this.name);' $evento  $estiloopc width:94px;' tabindex='4'></td>";

        $html .= "<tr>";
        $html .= "<td>PENAL </td>";
        $html .= "<td><select name='cbopenal' style='width:200px'>" . $this->listadoPenalesP() . "</select></td>";
        $html .= "<td>ESTADO </td>";
        $html .= "<td colspan='3'>ACTIVO <input class='InputText' type='radio' name='estado' id='estado' tabindex='2' value='1' style='border:0' />   INACTIVO <input class='InputText' type='radio' name='estado' style='border:0' id='estado' tabindex='3' value='2' /> TODOS <input class='InputText' type='radio' style='border:0' name='estado' id='estado' tabindex='3' checked='checked' value='3' /></td>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan='7'>Caracteres permitidos : ?  %(reemplaza al *)</td>";
        $html .= "<tr>";
        //$html .= "<tr>";
        //$html .= "<td colspan='7'>Nota: Los comodines ? ejecutan una b&uacute;squeda m&aacute;s precisa y los comodines % todas las coincidencias.</td>";
        //$html .= "<tr>";
        $html .= "</tr></table>";
        $html .= "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Busquedas'></div></div>";
        $html .= "<div style='clear:both'></div><br /><div id='ListadosInternos'>" . $this->ListadoInculpado($Find) . "</div>";
        //$html .= "<fieldset>";
        $html .= "</fieldset></div>";
        return $html;
    }

    function ListadoInculpado_old($Find, $orden=0, $ordenm=0) {

        //echo "<script>alert(\"$Find[0]\")</script>";
        $criterio = "";
        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";

            $penal = $Find[6] != 0 ? " AND PPO.COD_PENAL = " . $Find[6] . "" : "";

            $activo = $Find[7];
            if ($activo == 1) {
                $crity = " AND PPO.IND_ESTADO = 1 ";
            } elseif ($activo == 2) {
                $crity = " AND PPO.IND_ESTADO = 0 ";
            } else {
                $crity = "";
            }


            $criterio = "AND TRIM(IINM.DES_APE_PATERNO) LIKE '$Find[1]%' AND TRIM(IINM.DES_APE_MATERNO) LIKE '$Find[2]%' AND TRIM(IINM.DES_NOMBRES) LIKE '$Find[3]%'";
            $IdUsuario = $Find[4];

            $criterioOrden = "";

            //apellido paterno
            if ($orden == 1) {
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO ASC";
                $orden = 2;
            } elseif ($orden > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO DESC";
                $orden = 1;
            }

            if ($ordenm == 1) { //apellido materno
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO ASC";
                $ordenm = 2;
            } elseif ($ordenm > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO DESC";
                $ordenm = 1;
            }

            $_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,TRIM(IINM.DES_APE_PATERNO) as pate, TRIM(IINM.DES_APE_MATERNO) as mate, TRIM(IINM.DES_NOMBRES) as nombre ,
            IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,DATE_FORMAT(IIMO.FEC_INGRESO, '%d-%m-%Y') as fecha ,DATE_FORMAT(IIMO.FEC_SALIDA, '%d-%m-%Y') AS fechas ,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
            , (PPO.IND_ESTADO) AS estadoa FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN
            sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON
            IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE IIMO.COD_INCULPADO<>0 $criterio $penal $crity GROUP BY IIMO.COD_INCULPADO $criterioOrden
            ";
            //echo "<script>alert(\"$_pagi_sql\")</script>";
            $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 20;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";

            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";

            require_once('Paginador.cls.php');

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:1130px; overflow:hidden'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            $html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($orden,0);' title='$title'>AP. PATERNO</a></td>";
            $html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($ordenm,1);' title='$title'>AP. MATERNO</a></td>";
            $html .= "<td align='center' class='ordenpat'>NOMBRES</td>";
            $html .= "<td align='center' class='ordenpat'>PENAL</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:210px;'>FECHA INGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:210px;'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:210px;'>ESTADO</td>";
            $html .= "</tr>";

            if ($NumReg >= '1') {
                //_pagi_result = parent::Query($_pagi_sql);
                while ($Rows = parent::ResultArray($_pagi_result)) {

                    $sqlPenal = "SELECT ABREV_DES_PENAL penal FROM sip_omar.PEN_PENAL_MAE WHERE COD_PENAL = " . $Rows['PENAL'];
                    $queryPenal = parent::Query($sqlPenal);
                    $row = parent::ResultArray($queryPenal);

                    $estadop = $Rows['estadoa'] != 1 ? 'Inactivo' : 'Activo';
                    $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                    //$html .= "<td align='center'>" . $Rows[0] . "</td>";
                    $html .= "<td style='width:250px;'>" . strtoupper($Rows['pate']) . "</td>";
                    $html .= "<td style='width:250px;'>" . strtoupper($Rows['mate']) . "</td>";
                    $html .= "<td style='width:250px;'>" . strtoupper($Rows['nombre']) . "</td>";
                    $html .= "<td style='width:250px;'>" . ucwords($row[0]) . "</td>";
                    $html .= "<td style='width:210px;'>" . strtoupper($Rows['fecha']) . "</td>";
                    $html .= "<td style='width:210px'>" . strtoupper($Rows['fechas']) . "</td>";
                    $html .= "<td style='width:210px'>" . $estadop . "</td>";
                    $html .= "</tr>";
                }

                //$html .="<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
                //$html .= "<tr><td colspan='6'>&nbsp;</td>";
                $html .= "<tr><td colspan='6'>" . $_pagi_navegacion . "</td>";
                //$html .= "<td colspan='3' align='center'>";
                //$html .= "</td>";
                $html .= "</tr>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
            }
            //$html .="<tr><td colspan='9' align='left'>";
            //$html .="<div id='datos'>";
            //$html .="</div>";
            //$html .="</td></tr></table>";
            $html .="</table>";
        } else {
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];


            $html = "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:1130px;'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. PATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. MATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PENAL</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>FECHA INGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>ESTADO</td>";
            $html .= "</tr>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .="</table>";
        }
        return $html;
    }

    function ListadoInculpado($Find, $orden=0, $ordenm=0) {

        //echo "<script>alert(\"$Find[0]\")</script>";
        $criterio = "";
        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";

            $penal = $Find[6] != 0 ? " AND PPO.COD_PENAL = " . $Find[6] . "" : "";

            $activo = $Find[7];
            if ($activo == 1) {
                $crity = " AND PPO.IND_ESTADO = 1 ";
            } elseif ($activo == 2) {
                $crity = " AND PPO.IND_ESTADO = 0 ";
            } else {
                $crity = "";
            }


            $criterio = "AND TRIM(IIMA.DES_APE_PATERNO) LIKE '$Find[1]%' AND TRIM(IIMA.DES_APE_MATERNO) LIKE '$Find[2]%' AND TRIM(IIMA.DES_NOMBRES) LIKE '$Find[3]%'";
            $IdUsuario = $Find[4];

            $criterioOrden = "";

            //apellido paterno
            if ($orden == 1) {
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO ASC";
                $orden = 2;
            } elseif ($orden > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO DESC";
                $orden = 1;
            }

            if ($ordenm == 1) { //apellido materno
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO ASC";
                $ordenm = 2;
            } elseif ($ordenm > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO DESC";
                $ordenm = 1;
            }

            if ($Find[1] != "") {
                $critApepat = "AND MATCH(IIMA.DES_APE_PATERNO) AGAINST ('" . $Find[1] . "*' IN BOOLEAN MODE)";
            } else {
                $critApepat = "";
            }

            if ($Find[2] != "") {
                $critApemat = "AND MATCH(IIMA.DES_APE_MATERNO) AGAINST ('" . $Find[2] . "*' IN BOOLEAN MODE)";
            } else {
                $critApemat = "";
            }

            if ($Find[3] != "") {
                $critNombre = "AND MATCH(IIMA.DES_NOMBRES) AGAINST ('" . $Find[3] . "*' IN BOOLEAN MODE)";
            } else {
                $critNombre = "";
            }
            //$_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre ,
            //IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,DATE_FORMAT(IIMO.FEC_INGRESO, '%d-%m-%Y') as fecha ,DATE_FORMAT(IIMO.FEC_SALIDA, '%d-%m-%Y') AS fechas ,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
            //, (PPO.IND_ESTADO) AS estadoa FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN
            //sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON
            //IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE IIMO.COD_INCULPADO<>0 $criterio $penal $crity GROUP BY IIMO.COD_INCULPADO $criterioOrden
            //";
            //busqueda exacta
            $_pagi_sql1 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,
                    TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre,
                    IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
                    FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN
                    sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
                    IIMA.COD_INCULPADO <> 0 
                    $critApepat
                    $critApemat
                    $critNombre
                    $crity
                    $penal
                    AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL)
                    FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";

            //echo "<script>alert(\"$_pagi_sql\")</script>";
            $_pagi_result = parent::Query($_pagi_sql1);
            $NumReg = parent::NumReg($_pagi_result);

            if ($NumReg == 0) {
                $_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,
                     IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS
                     'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN
                     sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON
                     IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE

                     IIMO.COD_INCULPADO <> 0
                     $critApepat
                     $critApemat
                     $critNombre
                     $crity
                     $penal
                     AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE
                     A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1') ";
            } else {
                $_pagi_sql = $_pagi_sql1;
            }

            //echo "<script>alert(\"$_pagi_sql\")</script>";
            //echo "<script>alert(\"$_pagi_sql\")</script>";
            $_pagi_result = parent::Query($_pagi_sql);

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 20;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";

            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
            //$html .= "<textarea>$_pagi_sql</textarea>";

            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:760px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";

            require_once('Paginador.cls.php');

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px; overflow:hidden'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            //$html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($orden,0);' title='$title'>AP. PATERNO</a></td>";
            //$html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($ordenm,1);' title='$title'>AP. MATERNO</a></td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>AP. PATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>AP. MATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>NOMBRES</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>PENAL</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>FECHA INGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>ESTADO</td>";
            $html .= "</tr>";

            if ($NumReg >= '1') {
                //_pagi_result = parent::Query($_pagi_sql);
                while ($Rows = parent::ResultArray($_pagi_result)) {

                    $sqlPenal = "SELECT ABREV_DES_PENAL penal FROM sip_omar.PEN_PENAL_MAE WHERE COD_PENAL = " . $Rows['PENAL'];
                    $queryPenal = parent::Query($sqlPenal);
                    $row = parent::ResultArray($queryPenal);

                    $estadop = $Rows['estadoa'] != 1 ? 'Inactivo' : 'Activo';
                    $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                    //$html .= "<td align='center'>" . $Rows[0] . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['pate']) . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['mate']) . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['nombre']) . "</td>";
                    $html .= "<td style='width:180px;'>" . ucwords($row[0]) . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['fecha']) . "</td>";
                    $html .= "<td style='width:180px'>" . strtoupper($Rows['fechas']) . "</td>";
                    $html .= "<td style='width:180px'>" . $estadop . "</td>";
                    $html .= "</tr>";
                }

                //$html .="<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
                //$html .= "<tr><td colspan='6'>&nbsp;</td>";
                $html .= "<tr><td colspan='7'>" . $_pagi_navegacion . "</td>";
                //$html .= "<td colspan='3' align='center'>";
                //$html .= "</td>";
                $html .= "</tr>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
            }
            //$html .="<tr><td colspan='9' align='left'>";
            //$html .="<div id='datos'>";
            //$html .="</div>";
            //$html .="</td></tr></table>";
            $html .="</table>";
        } else {
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];


            $html = "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px;'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>AP. PATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>AP. MATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>NOMBRES</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>PENAL</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>FECHA INGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>ESTADO</td>";
            $html .= "</tr>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .="</table>";
        }
        return $html;
    }

    function FiltrosInculpados() {
        /* $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
          $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
          $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
          $html .= "<table border='0' cellpadding='1' cellspacing='1'><tr>";
          $html .= "<input type='hidden' name='CajaEmision' value='2' /><input type='button' id='BuscaEmision' value='<< Buscar >>' name='EmiteNombre' onclick='javascript:FindInculpado(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
          $html .= "</tr>";
          $html .= "</table>";
          return $html;
         */
    }

    /* ---------------------------------------MODULO DE AUDITORIA 19/08/2010---------------- CREADO POR JESUX -------------------------------------------------------- */

    function panel_auditoria() {
        $html = "<br /><div id='ViCss' style='width:1120px;'>";
        $html .= "<div id='Filtrosx'>" . self::FiltrosAuditoria() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='ShowFiltrosA'></div><div id='Busquedas'></div></div>";
        $html .= "<div id='ListadosInternosx'>" . self::AuditoriaListado() . "</div>";
        $html .= "</div>";
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function FiltrosAuditoria() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:left; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table width='1100px' border='0' cellpadding='0' cellspacing='0'><tr>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>PROCEDENCIA:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(1)' ></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>NOMBRES:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(7)' ></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>USUARIOS&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(2)'></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>ID&nbsp;(S)&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(3)' ></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>FECHA&nbsp;EVENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(4)' ></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:50px;'>EVENTOS&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(5)' ></td>";
        $html .= "<td align='left' style='border: 0px solid #CCCCCC;border-collapse: collapse;width:40px;'>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:FiltrosAuditoria(6)' ></td>";
        $html .= "<td><input type='hidden' name='FiltroChoice' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindAuditoria();' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function ShowFiltrosAuditoria($FiltroChoice) {
        switch ($FiltroChoice) {
            case 1:
                $html = self::SelectProcedencias();
                break;
            case 2:
                $html = self::Usuarios();
                break;
            case 3:
                $html = "<table border='0' cellpadding='2' cellspacing='2'><tr>";
                $html .= "<td>ID&nbsp;EVENTO&nbsp;:&nbsp;</td>";
                $html .= "<td><input class='InputText' type='text' name='IdEvento' tabindex='3' id='IdEvento' tabindex='1' onblur='ClearSpace(this);' onFocus='ClearID(this.id);' size='40'/></td>";
                $html .= "<td>ID&nbsp;SERVER&nbsp;:&nbsp;</td>";
                $html .= "<td><input class='InputText' type='text' name='IdServer' tabindex='3' id='IdServer' tabindex='1' onblur='ClearSpace(this);'onFocus='ClearID(this.id);' size='40'/></td>";
                $html .= "<td>ID&nbsp;LOCAL&nbsp;:&nbsp;</td>";
                $html .= "<td><input class='InputText' type='text' name='IdLocal' tabindex='3' id='IdLocal' tabindex='1' onblur='ClearSpace(this);' onFocus='ClearID(this.id);' size='40'/></td>";
                $html .= "</tr></table>";
                break;
            case 4:
                $html = "<table border='0' cellpadding='0' cellspacing='0'><tr>";
                $html .= "<td align='center' style='border-collapse: collapse;width:50px;'>INICIO : </td>";
                $html .= "<td><input class='InputText' type='text' name='IniSolici' id='IniEvento' readonly='true' /> &nbsp;<img src='../Img/img.gif' onclick='javascript:displayCalendar(document.bigform.IniSolici,this)' cursor:pointer' /></td>";
                $html .= "<td align='center' style='border-collapse: collapse;width:50px;'>FINAL : </td>";
                $html .= "<td><input class='InputText' type='text' name='FinSolici' id='FinEvento' readonly='true' /> &nbsp;<img src='../Img/img.gif' onclick='javascript:displayCalendar(document.bigform.FinSolici,this)' cursor:pointer' /></td>";
                $html .= "</tr></table>";
                break;
            case 5:
                $html = self::Axion();
                break;
            case 7:
                $html .= "<table border='0' cellpadding='2' cellspacing='2'  style='width:700px;overflow:hidden'><tr>";
                $html .= "<td>PATERNO </td>";
                $html .= "<td><input class='InputText' type='text' name='PatEmite' id='PatEmite' tabindex='1'  onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
                $html .= "<td>MATERNO </td>";
                $html .= "<td><input class='InputText' type='text' name='MatEmite' id='MatEmite' tabindex='2'  onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
                $html .= "<td>NOMBRE </td>";
                $html .= "<td><input class='InputText' type='text' name='NomEmite' id='NomEmite' tabindex='3' onblur='ClearSpace(this);ConvertMayuscula(this);' style='width:120px' /></td>";
                $html .= "</table>";
                break;
            default :
                $html = "";
                break;
        }
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function Usuarios() {
        $html = "<select name='ListUsuarios' id='ListUsuarios' >";
        $html.="<option value='0'> [ Seleccione Usuario ]</option>";
        $sql = "SELECT idUsuario,usu_logi,CONCAT(usu_pate,' ',usu_mate,', ',usu_nomb) AS nombres FROM Usuario WHERE usu_flag='1'";
        $query = parent::Query($sql);

        while ($row = parent::ResultAssoc($query)) {
            $idUsuario = $row['idUsuario'];
            $nombres = $row['nombres'];
            $idUsuario = str_pad($idUsuario, 2, "0", STR_PAD_LEFT);
            $html .="<option value='$idUsuario'>$nombres</option>";
        }
        $html .="</select>";

        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function Axion() {
        $html = "<select name='ListAxion' id='ListAxion' >";
        $html.="<option value='0'> [ Seleccione Acci&oacute;n ]</option>";
        $sql = "SELECT axion_id,axion_descrip FROM axion ";
        $query = parent::Query($sql);

        while ($row = parent::ResultAssoc($query)) {
            $axion_id = $row['axion_id'];
            $axion_descrip = $row['axion_descrip'];
            $axion_id = str_pad($axion_id, 2, "0", STR_PAD_LEFT);
            $html .="<option value='$axion_id'>$axion_descrip</option>";
        }
        $html .="</select>";

        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function AuditoriaListado($Filtros, $FiltroChoice) {
        switch ($FiltroChoice) {
            case 1;
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario AND a.proc_dir='$Filtros' order by a.audit_id desc";
                break;
            case 2;
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario AND u.idUsuario='$Filtros' order by a.audit_id desc";
                break;
            case 3;
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario $Filtros order by a.audit_id desc";
                break;
            case 4;
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario AND DATE_FORMAT(a.fecha,'%d-%m-%Y') BETWEEN '$Filtros[0]' AND '$Filtros[1]' order by a.audit_id desc";
                break;
            case 5;
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario AND a.axion_id='$Filtros' order by a.audit_id desc";
                break;
            case 7;
                $paterno = $Filtros[0];
                $materno = $Filtros[1];
                $nombres = $Filtros[2];
                $_pagi_sql = "SELECT a.audit_id, CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres, p.DES_DR, a.p_idsol, a.id_solcitud, ax.axion_descrip, a.fecha,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher, ";
                $_pagi_sql.= "CONCAT(pe.p_apepat, ' ', pe.p_apemat, ', ', pe.p_nombres) AS usuario  FROM auditoria a, Usuario u, axion ax, procedencia_direc p, personas pe WHERE ";
                $_pagi_sql.= "a.axion_id = ax.axion_id AND a.proc_dir = p.ID AND a.idUsuario = u.idUsuario AND a.p_idsol=pe.p_idsol AND pe.p_apepat LIKE '%$paterno%' AND pe.p_apemat LIKE '%$materno%' AND pe.p_nombres LIKE '%$nombres%' ORDER BY a.audit_id desc ";
                break;
            default:
                $_pagi_sql = "SELECT a.audit_id,CONCAT(u.usu_pate, ' ', u.usu_mate, ', ', u.usu_nomb) AS nombres,p.DES_DR,a.p_idsol,a.id_solcitud,ax.axion_descrip, a.fecha ,a.nro_voucher,FROM_UNIXTIME(a.fec_voucher,'%Y-%m-%d') fec_voucher ";
                $_pagi_sql.= "FROM auditoria a,Usuario u,axion ax,procedencia_direc p WHERE a.axion_id=ax.axion_id AND a.proc_dir=p.ID AND a.idUsuario=u.idUsuario order by a.audit_id desc";
                break;
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>ID&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;'>USUARIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:70px;'>ID&nbsp;SERVIDOR&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:70px;'>ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>ACCI&Oacute;N&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:150px;'>&nbsp;FECHA</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>&nbsp;NRO&nbsp;VOUCHER</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>&nbsp;FECHA&nbsp;VOUCHER</th>";
        if ($FiltroChoice == 7) {
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:150px;'>&nbsp;SOLICITANTE</th>";
        }
        $html .= "</tr></thead><tbody>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {

                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . $Rows[2] . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . "</td>";
                $html .= "<td align='center'>" . $Rows[4] . "</td>";
                $html .= "<td>" . $Rows[5] . "</td>";
                $html .= "<td align='center'>" . $Rows[6] . "</td>";
                $html .= "<td align='center'>" . $Rows[7] . "</td>";
                $html .= "<td align='center'>" . $Rows[8] . "</td>";
                $html .= "<td>" . $Rows[9] . "</td></tr>";
            }
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6'>" . $_pagi_navegacion . "</td></tr></table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }

    /* ---------------------------------------------------------------------------------------------------------------------------------------------- */

    function listadoCorrelativosLocal($Find) {
        //$html = "<br /><div id='ViCss' style='width:auto;'>";
        $html = "<br /><div id='divFormInsc1' style='width:850px;overflow:hidden'><fieldset id='field'>";
        $html .= "<legend><strong><center> Correlativos X Sucursal </center> </strong></legend>";
        $html .= "<div style='width:700px;overflow:hidden'>";
        $html .= "<div id='FiltrosUsu'>" . $this->FiltrosCorrelativos() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Xprocedencia' style='display: none;'>" . self::SelectProcedencias() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoCorrelativos($Find) . "</div>";
        $html .= "</div>";
        $html .="<div id='nuevo' style='float:left; width:695px;overflow:hidden;padding-left:4px'></div>";
        //$html .="<div style='clear:both'></div>";
        $html .="<div id='datos' style='float:left; width:695px;overflow:hidden;padding-left:4px'></div>";
        //$html .="<div style='clear:both'></div>";
        $html .="<div id='permisos' style='float:left; width:695px;overflow:hidden;padding-left:4px'></div>";
        $html .="<div id='elimina'></div>";
        $html .="<div style='clear:both'></div>";
        $html .= "</fieldset></div>";

        return $html;
    }

    function FiltrosCorrelativos() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<div style='clear:both'></div><div style:'padding-bottom:10px; overflow:hidden'><table border='0' cellpadding='1' cellspacing='1' style='width:700px'><tr>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='local' onclick='javascript:CamposEmision_correlativo(1)' ></td>";
        $html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' style='border:0; width:15px' onclick='javascript:CamposEmision_correlativo(5)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCorrelativo(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table></div><br />";
        return $html;
    }

    function ListadoCorrelativos($Find) {

        //echo "<script>alert(\"$Find[0]\")</script>";

        if ($Find[0] == 'Todo') {
            if ($Find[9] != '' && $Find[9] != 0) {
                $criterio = " AND proc_dir = '$Find[9]' ";
            } else {
                $criterio = " AND proc_dir <> 0 ";
            }
        } /* else {
          $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[0]'";
          //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' ORDER BY p.p_idsol DESC";
          $_pagi_sql = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag FROM personas p , pagos pg WHERE p.tipo_img = pg.num_sec $Admin ORDER BY p.p_idsol DESC";

          $IdUsuario = $Find[0];
          } */


        /* id_local_correlativoint(11) NOT NULL
          proc_dirint(4) NULL
          nro_correlativo_iniint(11) NULL
          nro_correlativo_finint(11) NULL
          cod_userint(11) NULL
         */

        $_pagi_sql = "SELECT * FROM local_correlativo WHERE id_local_correlativo<>0 $criterio ";

        //echo "<script>alert(\"$_pagi_sql\")</script>";

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";

        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once('Paginador.cls.php');

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='padding-left:4px; overflow:hidden; width:696px; padding-top:2px'><table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:696px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px; background:#dfeaee'><strong>ID</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'><strong>PROCEDENCIA</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'><strong>INICIO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'><strong>FINAL</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background:#dfeaee'><strong>A&Ntilde;O</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;background:#dfeaee'><strong>OPCIONES</strong></td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>&nbsp;</td>";
        $html .= "</tr>";

        if ($NumReg >= '1') {
            //_pagi_result = parent::Query($_pagi_sql);
            while ($Rows = parent::ResultArray($_pagi_result)) {
                //procedencia
                $sqlProd = "select * from procedencia_direc where ID = " . $Rows['proc_dir'];
                $query = parent::Query($sqlProd);
                $row = parent::ResultArray($query);

                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                $html .= "<td align='center'>" . strtoupper($row['DES_DR']) . "</td>";
                $html .= "<td align='center'>" . strtoupper($Rows['nro_correlativo_ini']) . "</td>";
                $html .= "<td align='center'>" . strtoupper($Rows['nro_correlativo_fin']) . "</td>";
                $html .= "<td align='center'>" . strtoupper($Rows['anio_correlativo']) . "</td>";

                $html .= "<td align='center'><a href='#' onclick='ver_edit_correlativo(" . $Rows[0] . ");'><img src='../Img/editar_.gif' title='Editar' border='0' nombre='Editar' /></a>  <a href='#' onclick='eliminar_correlativo(" . $Rows[0] . ");'><img src='../Img/eliminar_.gif' border='0' title='Eliminar' nombre='Eliminar' /></a> </td>";
                //$html .= "<td>" . $tipe . "</td>";
                $html .= "</tr>";
            }

            $html .="<tr><td colspan='5' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
            //$html .= "<tr><td colspan='6'>&nbsp;</td></tr>";
            $html .= "<tr><td colspan='5' class='paginac'>" . $_pagi_navegacion . "</td>";
            ///$html .= "<td colspan='3' align='center'>";
            //$html .= "<a href='#' onclick=javascript:popup('popUpDiv'); >Imprimir</a>";
            //$html .= "<input type='button' id='ImprimirTodos' value='Imprimir Listo' onclick=javascript:popup('popUpDiv'); $evento  $estiloopc width:130px;' />";
            //$html .= "</td><td align='center'>";
//            $html .= "<input type='button' id='AnularPositivo' value='Anular' onclick=javascript:DetallePersona(this.id,'','',''); $evento  $estiloopc width:70px;' />";
            //$html .= "</td></tr>";
            $html .= "</tr>";
        } else {
            $html .= "<tr><td colspan='5' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .="<tr><td colspan='5' align='center'>";

        $html .="<div style='text-align:right; padding-right:64px' id='otro'>";
        $html .="<input type='button' value='Nuevo' onclick='ver_nuevo_correlativo();' class='btn_enviar_1' />";
        $html .="</div>";

        $html .="</td></tr></table></div>";
        return $html;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  Combo Select Region OMAR
    function Direcciones_correlativos() {
        //$sql = "SELECT ID,DES_DR FROM procedencia_direc WHERE FLAG = '1' and (ID = ".$_SESSION['sede']." OR ID = ".$_SESSION['parent'].") ORDER BY DES_DR ASC";
        if ($_SESSION['sede'] == 1) {
            $criterj = " and ID = " . $_SESSION['sede'] . " ";
        } else {
            if ($_SESSION['sede'] == 103) {
                // infra
                $criterj = " and (ID = " . $_SESSION['sede'] . " OR ID = '2') ";
            } elseif ($_SESSION['sede'] == 102) {
                // dicscamec
                $criterj = " and (ID = " . $_SESSION['sede'] . " OR ID = '11') ";
            } else {
                $criterj = " and (ID = " . $_SESSION['sede'] . " OR ID = " . $_SESSION['parent'] . ") ";
            }
        }
        $sql = "SELECT ID,DES_DR FROM procedencia_direc WHERE FLAG = '1' $criterj ORDER BY DES_DR ASC";
        //echo "<script>alert(\"$sql\")</script>";
        $query = parent::Query($sql);
        while ($result = parent::ResultArray($query)) {
            $cod[] = $result["ID"];
            $nom[] = $result["DES_DR"];
        }
        return array($cod, $nom);
    }

    //  Select de Procedencias
    function SelectProcedencias_correlativo() {
        $html = "<div style='padding-bottom:10px; overflow:hidden'><table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>Procedencia</td>";
        $html .= "<td><SELECT name='IdDir' id='IdDir' onchange='javascript:Lugares_tramites(this.value);' class='InputText'>";
        $html .= "<option value='0'>[ Direcciones ]</option>";
        $html .= $this->MixSelect_corre('', $this->Direcciones_correlativos());
        $html .= "</td>";
        $html .= "<td>Correlativos Asignados </td>";
        $html .= "<td><div id='IdLugares'><select name='IdLugar' id='IdLugar' class='InputText'>";
        $html .= "<option value='0'>[ Correlativos ]</option>";
        $html .= "</select></div></td>";

        $html .= "<td colspan='2'>Todos <input type='radio' name='todod' id='todod' value='1' />  Faltantes <input type='radio' name='todod' id='todod' value='0' checked /></td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='1'>Paterno</td>";
        $html .= "<td colspan='1'><input type='text' name='paterno' class='InputText' /></td>";
        $html .= "<td colspan='1'>Materno</td>";
        $html .= "<td colspan='1'><input type='text' name='materno' class='InputText' /></td>";
        $html .= "<td colspan='1'>Nombres</td>";
        $html .= "<td colspan='1'><input type='text' name='nombre' class='InputText' /></td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>INICIO : </td>";
        $html .= "<td><input class='InputText' onblur='asignarFecha()' type='text' name='IniSolici' id='IniSolici' readonly='true' /><input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.IniSolici,this);asignarFecha()' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='1' /></td>";
        $html .= "<td>FINAL : </td>";
        //$html .= "<td><input class='InputText' type='text' name='FinSolici' id='FinSolici' readonly='true' /><input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FinSolici,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='2' /></td>";
	$html .= "<td><input class='InputText' type='text' name='FinSolici' id='FinSolici' readonly='true' /></td>";
        $html .= "<td colspan='1'>&nbsp;</td>";
        $html .= "<td colspan='1'>&nbsp;</td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='6'>&nbsp; </td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='2'><a href='#' onclick='limpiar_nombres()'>Limpiar nombres</a> </td>";
        $html .= "<td colspan='2'><a href='#' onclick='limpiar_fechas()'>Limpiar fechas</a> </td>";
        $html .= "<td colspan='2'>&nbsp;</td>";

        $html .= "</tr>";

        $html .= "</table></div>";

        return $html;
    }

    function SelectProcedencias_atend() {
        $html = "<div style='padding-bottom:10px; overflow:hidden'><table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>Procedencia</td>";
        $html .= "<td><SELECT name='IdDir' id='IdDir' class='InputText'>";
        $html .= "<option value='0'>[ Direcciones ]</option>";
        $html .= $this->MixSelect_corre('', $this->Direcciones_correlativos());
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td colspan='6'>&nbsp; </td>";
        $html .= "</tr>";

        $html .= "</table></div>";

        return $html;
    }

    function MixSelect_corre($Id, $arrayDep) {
        $html = "";
        for ($i = 0; $i < sizeof($arrayDep[0]); $i++) {
            if ($Id == $arrayDep[0][$i]) {
                $html .= "<option value='" . $arrayDep[0][$i] . "' selected >" . $arrayDep[1][$i] . "</option>";
            } else {
                $html .= "<option value='" . $arrayDep[0][$i] . "'>" . $arrayDep[1][$i] . "</option>";
            }
        }
        return $html;
    }

    function asigna_correlativo($Find) {
        $html = "<br /><div id='ViCss' style='width:800px;'>";
        $html .= "<div id='Filtros'>" . $this->FiltrosEmision_Correlativo() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Xprocedencia' style='display: none;'>" . self::SelectProcedencias_correlativo() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        //$html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCorrelativo($Find) . "</div>";
        $html .= "<div id='error' style='text-align:center; color:#FF0000; font:bold 14px Arial'></div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .="<div id='sgte' style='color:#ffffff'></div>";
        $html .="<input type='hidden' id='fin' />";
        $html .="<div id='flotante' class='flotante'></div>";
        $html .= "</div>";
        return $html;
    }

    function FiltrosEmision_Correlativo() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='local' NAME='color' onclick='javascript:CamposEmision_correlativo(1)' ></td>";
        $html .= "<td width='500'>&nbsp;</td>";
        $html .= "<td>&nbsp;</td>";
        $html .= "<td>&nbsp;</td>";
        $html .= "<td>&nbsp;</td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        //$html .= "<td>NOMBRES&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='checkbox' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_correlativo(2)'></td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='checkbox' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_correlativo(3)' ></td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='checkbox' NAME='color' id='soli' onclick='javascript:CamposEmision_correlativo(4)' ></td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td>ESTADO&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='checkbox' NAME='color' id='esta' onclick='javascript:CamposEmision_filtrado(6)' ></td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td>TODO&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:ocultar_filtros()' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificado_correlativo(this.name);' $evento  $estiloopc width:100px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    function ListadoEmiteCorrelativo($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];

        $listar = $Find[13];

        //echo "<script>alert(\"$Find[13]\")</script>";

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";

        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $Admin = ($Find[5] == '2') ? " " : " ";
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            //echo "<script>alert(\"$Find[7]\")</script>";
            //echo "<script>alert(\"$Find[8]\")</script>";

            if ($Find[7] != "" && $Find[8] != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioF = "AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59')";
                $Admin = "";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDir = " AND p.proc_dir = '$Find[9]'";
                /* if($dir== 2 || $dir== 11 || $dir== 1){
                  if($dir== 2){
                  $criInfra = $Find[9];
                  $criInfra1 = 103;
                  }elseif($dir== 11){
                  $criInfra = $Find[9];
                  $criInfra1 = 102;
                  }elseif($dir== 1){
                  $criInfra = $Find[9];
                  $criInfra1 = 101;
                  }
                  $criterioDir = " AND (p.proc_dir = '$criInfra' OR p.proc_dir = '$criInfra1')";
                  }else{
                  $criterioDir = " AND p.proc_dir = '$Find[9]'";
                  }
                 */
                if ($dir < 100) {

                    $sqlParent = "select * from procedencia_direc  where id_parent = " . $dir;
                    $queryP = parent::Query($sqlParent);

                    $rowsP = parent::ResultArray($queryP);
                    if ($dir == 1) {
                        $parent = '101';
                    } elseif ($dir == 11) {
                        $parent = '102';
                    } elseif ($dir == 2) {
                        $parent = '103';
                    } else {
                        $parent = $rowsP['ID'];
                    }

                    $criterioDir = " AND (p.proc_dir = '$dir' OR p.proc_dir = '$parent')";
                } else {
                    $criterioDir = " AND p.proc_dir = '$Find[9]'";
                }
            }

            if ($doc != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDoc = " AND p.p_idsol = '$Find[10]'" ;
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }



            if ($Find[11] != "") {
                //$Estados = ($Find[11] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[11]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                /* if($Find[11]!='ATEN'){
                  $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                  }else{
                  $criterioEs = " AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                  }
                 */
            } else {
                $criterioEs = "";
            }

            if ($nombre == '' && $apepat == '' && $apemat == '' && $Find[7] == "" && $Find[8] == "" && $dir == 0 && $doc == '' && $Find[11] == '') {
                //$criterioImp = " and g.flag = 1";
                $criterioImp = " and p.emite = 'ATEND'";
            }
            //$criterioImp = " and g.flag = 1";
            $criterioImp = " and p.emite = 'ATEND'";

            if ($listar == 0) {
                $filtrado = " AND p.p_idsol NOT IN (SELECT p_idsol FROM certificado_correlativo WHERE flag_certificado = 0)";
            } elseif ($listar == 1) {
                $filtrado = " ";
            }

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $criterioImp $Admin $criterioN $criterioF $criterioDir $criterioDoc $criterioEs $filterxelmomento $filtrado ORDER BY p.p_idsol ASC";
        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

            $Estados = "";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '1' $criterioN $criterioF $criterioDir $criterioDoc $Estados $filterxelmomento ORDER BY p.p_idsol ASC";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        //para impresion
        $_pagi_resultImp = $_pagi_sql;
        $_pagi_result1 = parent::Query($_pagi_sql);
        $idd = 0;
        while ($rows = parent::ResultArray($_pagi_result1)) {
            if ($rows['p_idsol'] != 0) {
                $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                $idd++;
            }
        }

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 10;    // Variables Configurables
        //$_pagi_nav_estilo = "";
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>PATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>MATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PAGO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>&nbsp;FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>&nbsp;HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        if ($Column != 'ATEN') {
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>FECHA&nbsp;SOL.</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></th>";
        }
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:10px;'>&nbsp;IDLOCAL&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:170px;'>&nbsp;N.IMPRESO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>&nbsp;A&Ntilde;O IMP.</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;</th>";
        $html .= "</tr></thead><tbody>";

        //echo $_pagi_sql;
        if ($NumReg >= '1') {
            $val = 0;
            while ($Rows = parent::ResultArray($_pagi_result)) {
                $correlativo_p = self::devuelve_correlativo($Rows[0]);

                $correlativo_pE = explode(",", $correlativo_p);
                $correlativo_p = $correlativo_pE[0];

                $aniop = $correlativo_pE[1];
                $procedencia = self::Procedencia($Rows[18]);
                $Antecedente = $Rows[11];
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . " <input type='hidden' value='$Rows[id_solcitud]' name='idsol[]' /> <input type='hidden' value='$Rows[0]' name='ids[]' style='width:60px; text-align:center' /></td>";
                //$html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                //$html .= "<td align='center'>" . $TipPago . "</td>";
                //$html .= "<td align='center'>" . $Rows[3] . "</td>";
                //$html .= "<td align='center'>" . $Rows[12] . "</td>";
                //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                //onclick='ver_pdf_doc($Rows[0], this.href)'
                if ($Rows[13] == '0') {
                    $userP = self::UsuarioDescrip($Rows['cod_user']);
                    //$Imagenes = (($Antecedente == 'PEND1') || ($Antecedente == 'PEND2')) ? "<img src='../Img/info2.gif' style='border:none' />" : "<img src='../Img/alert.gif' style='border:none' />";
                    $Imagenes = "<img src='../Img/info2.gif' style='border:none' />";
                    $html .= "<td align='center'>" . $Rows[3] . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                    $Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                    //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                    $Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                    $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                    //$html .= "<td align='center'>" . $Imprimir . "</td>";
                    //$html .= "<td align='center'></td>";
                    $html .= "<td align='center'>&nbsp;" . $procedencia[1] . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                } else {
                    $procedencia = self::Procedencia($Rows[18]);
                    $html .= "<td align='center'>$Rows[3]</td>";
                    $html .= "<td align='center'>&nbsp;" . $procedencia[1] . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp; </td>";
                    $html .= "";
                }

                $html .= "<td align='center'> <div id='valores$val'><input type='text' name='corre[]' id='corre$val' style='width:52px; text-align:center' maxlength='7' onkeypress='javascript:return valident(event)' value='$correlativo_p' />";

                if ($correlativo_p != "" || $correlativo_p != 0) {

                    $html .= "<a href='javascript:;' title='Editar Nro Impreso' onclick='editar_impreso($val, $Rows[0], $correlativo_p, $aniop , $correlativo_pE[3]);'><img src='../Img/editar_.gif' alt='Editar' style='border:0' /></a>";

                    $html .= "<a href='javascript:;' title='Anular Nro Impreso' onclick='anular_impreso($val, $Rows[0], $correlativo_p);'><img src='../Img/eliminar_.gif' alt='Anular' style='border:0' /></a>";
                } else {
                    $html .= "<img src='../Img/eliminar_.gif' alt='Anular' style='border:0' />";
                }



                if ($correlativo_p != "" || $correlativo_p != 0) {

                    //$html .= "<a href='javascript:;' title='Editar Nro Impreso' onclick='editar_impreso($val, $Rows[0], $correlativo_p);'><img src='../Img/editar_.gif' alt='Editar' style='border:0' /></a>";

                    $html .= "<a href='javascript:;' title='Intercambiar Nro Impreso' onClick='intercambio($correlativo_p);'><img src='../Img/arrow_refresh.png' alt='Intercambiar Nro Impreso' style='border:0' /></a>";
                } else {
                    $html .= "<a href='#' title='Intercambiar Nro Impreso'><img src='../Img/arrow_refresh.png' alt='Intercambiar Nro Impreso' style='border:0' /></a>";
                }


                $html .= "</div> </td>";
                $html .= "<td align='center'>&nbsp;" . $aniop . "&nbsp; </td>";
                $html .= "<td align='center'>&nbsp; </td>";
                $html .= "</tr>";
                $val++;
            }
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6'><div class='paginac'>" . $_pagi_navegacion . "</div></td></tr></table>";
            if ($listar == 0) {
                //$html .= "<table width='98%'><tr><td colspan='9' align='right'><input type='hidden' name='todo' id='todo' value='$val' /><input type='button' value='GENERAR' onclick='generar();' class='btn_enviar_a' /> <input type='button' value='GRABAR' class='btn_enviar_a' name='ok' onclick='actualizar_correlativo(this.name,1);' /></td></tr></table>";
                $html .= "<table width='98%'><tr><td colspan='9' align='right'><input type='hidden' name='todo' id='todo' value='$val' /> <input type='button' value='GRABAR' class='btn_enviar_a' name='ok' onclick='actualizar_correlativo(this.name,1);' /></td></tr></table>";
            }
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
        $html .= "</tbody></table>";
        $html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }

    function devuelve_correlativo($id) {
        $sqlC = "select * from certificado_correlativo where p_idsol = '" . $id . "' AND flag_certificado = '0'";
        $querU = parent::Query($sqlC);
        $NumReg = parent::NumReg($querU);

        if ($NumReg > 0) {
            $rowU = parent::ResultArray($querU);
            $corre = $rowU['certificado_correlativo'] . "," . $rowU['anio_certificado'] . "," . $rowU['id_certificado_correlativo'] . "," . $rowU['id_local_correlativo'];
        } else {
            $corre = '';
        }

        return $corre;
    }

    function devuelve_correlativo_anulado($id) {
        $sqlC = "select * from certificado_correlativo where p_idsol = '" . $id . "' AND flag_certificado = '1'";
        $querU = parent::Query($sqlC);
        $NumReg = parent::NumReg($querU);

        if ($NumReg > 0) {
            $rowU = parent::ResultArray($querU);
            $corre = $rowU['certificado_correlativo'];
            $obs = $rowU['observacion_certificado'];
        } else {
            $corre = '';
            $obs = '';
        }

        $listado['corre'] = $corre;
        $listado['obs'] = $obs;

        return $listado;
    }

    function estadistica_detalle($proce, $fecha) {
        $html = "<br /><div id='ViCss' style='width:1200px;'>";
        //$html .= "<div id='Filtros'>" . $this->FiltrosEmision_Correlativo() . "</div>";
        echo "<script>alert(\"$proce\")</script>";
        $html .= "<div id='detalle_estadistica'>" . $this->Listado_detalle($proce, $fecha) . "</div>";
        $html .= "<div id='error' style='text-align:center; color:#FF0000; font:bold 14px Arial'></div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .="<div id='sgte' style='color:#ffffff'></div>";
        $html .="<input type='hidden' id='fin' />";
        $html .="<input type='text' id='fecha' name='fecha' value='$fecha' />";
        $html .="<input type='text' id='proce' name='proce' value='$proce' />";
        $html .= "</div>";
        return $html;
    }

    function Listado_detalle($proce, $fecha) {

        if ($proce != '' && $fecha != '') {
            //$_pagi_sql = "SELECT *,d.DES_DR FROM personas p , procedencia_direc d WHERE FROM_UNIXTIME(p_fechasol,'%Y-%m-%d') BETWEEN '" . $fecha . "' AND '" . $fecha . "' AND p.proc_dir = d.ID AND p.proc_dir = ".$proce." AND p_apepat!='' ";
            //AND g.flag = '1'
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' AND ";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%Y-%m-%d') BETWEEN '" . $fecha . "' AND '" . $fecha . "' AND p.proc_dir = " . $proce . "  ORDER BY p.p_idsol ASC";

            echo "<script>alert(\"$_pagi_sql\")</script>";
            $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='text' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

            require_once("Paginador.cls.php");

            $html .= "<table id='tablaordenada'>";
            $html .= "<thead><tr>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. PATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>AP. MATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PAGO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>&nbsp;FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>&nbsp;HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "</tr></thead><tbody>";

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);

                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . " </td>";
                $html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                $html .= "<td align='center'>" . $TipPago . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . "</td>";
                $html .= "<td align='center'>" . $Rows[12] . "</td>";

                $html .= "</tr>";
            }

            $html .= "</tbody></table>";
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6'>" . $_pagi_navegacion . "</td></tr></table>";

            return $html;
        }
    }

    function FlistadoBloqueo($Find) {

        $html .= "<br /><div id='divFormInsc' style='width:968px; overflow:hidden'>";
        $html .= "<fieldset id='field'>";
        $html .= "<legend><strong><center> Bloqueo de VO </center> </strong></legend>";
        $html .= "<div id='Filtros'>" . $this->FiltrosEmisionCertifica1Bloqueo() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Procedencia' style='display:none;'>" . $this->SelectProcedencias() . "</div><div id='Busquedas'></div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCerti1Bloqueo($Find) . "</div></fieldset>";
        $html .= "</div>";
        return $html;
    }

    function FiltrosEmisionCertifica1Bloqueo() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1' style='width:794px'>";
        //$html .= "<td>PROCEDENCIA&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmision1(1)' ></td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td>NOMBRES&nbsp;:</td>";
        //$html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(2)'></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<tr>";
        $html .= "<td>Nro.&nbsp;VO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(3)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>DOC. IDENTIDAD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(2)' ></td>";

        $html .= "<td>F.&nbsp;EMISION&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(4)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(5)' ></td>";

        //$html .= "<td>&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoBloqueo(this.name,0);' $evento  $estiloopc width:90px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>COD.OFICINA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(6)' ></td>";
        //$html .= "<td>&nbsp;</td>";

        $html .= "<td>COD.CAJERO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(7)' ></td>";

        $html .= "<td>ESTADO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' style='border:0' NAME='color' onclick='javascript:CamposEmisionBloqueo(8)' ></td>";

        $html .= "</tr>";

        $html .= "</table><br />";
        return $html;
    }

    function ListadoEmiteCerti1Bloqueo($Find) {

        //echo "<script>alert(\"$Find[0]\")</script>";

        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";
            $criterio = " AND num_doc = '$Find[1]'";
            $IdUsuario = $Find[4];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $criterio = " AND fech >= '$Ini' AND fech <= '$Fin' ";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Procede') {
            $Admin = ($Find[4] == '2') ? "" : "AND p.cod_user = '$Find[3]'";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Doc') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            $criterio = " AND num_sec = '$Find[1]' ";
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Estado') {
            $Admin = ($Find[3] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            //$Estados = ($Find[1] != "ATEN") ? "AND g.flag = '0' AND p.emite = '$Find[1]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
            //$_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin  $Estados  ORDER BY p.p_idsol DESC";
            $criterio = " AND cod_ofi = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        } elseif ($Find[0] == 'tipoD') {
            $criterio = " AND cod_caj = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        } elseif ($Find[0] == 'estadoD') {

            if ($Find[1] == 1) {
                $criterio = " AND id IN (SELECT id_voucher FROM bloqueos_vouchers)";
            } else {
                $criterio = " AND id NOT IN (SELECT id_voucher FROM bloqueos_vouchers)";
            }

            //$criterio = " AND cod_caj = '$Find[1]'";
            $IdUsuario = $Find[2];
            $Column = $Find[1];
        }

        //$_pagi_sql = "SELECT p.tipo_img, p.p_nombres, p.p_apepat, p.p_apemat, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS fecha, FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS hora, pg.mpt_sol AS importe, pg.flag , pg.fech, pg.hor FROM personas p , pagos pg WHERE p.tipo_img = pg.num_sec $criterio $Admin GROUP BY p.tipo_img ORDER BY p.p_fechasol DESC";
        $_pagi_sql = "SELECT * FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas) $criterio ORDER BY fech DESC";
        //$sqlP = "select * from pagos where num_sec = 1 or num_sec = 3 and hdf dklflfkkk dfkldffgf fd fd fdf";
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        //para impresion
        $_pagi_resultImp = $_pagi_sql;
        $_pagi_result1 = parent::Query($_pagi_sql);
        $idd = 0;
        while ($rows = parent::ResultArray($_pagi_result1)) {
            if ($rows['id'] != 0) {
                $idSol.= ( $idd == 0 ? '' : ',') . $rows['id'];
                $idd++;
            }
        }

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables

        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table  style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";

        require_once('Paginador.cls.php');

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='padding-left:7px'><table  style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:790px;'><tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:54px;background: #dfeaee;'><strong>ID</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong> NroVO</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong> DOC. IDENT.</strong></td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:240px;background: #dfeaee'><strong>NOMBRES</strong></td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>FECHA&nbsp;SOL.</strong></td>";
        //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>HORA&nbsp;SOL.</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>FECHA&nbsp;EMISION</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>HORA&nbsp;EMISION</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>CODIGO&nbsp;OFICINA</strong></td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;background: #dfeaee'>&nbsp;<strong>CODIGO&nbsp;CAJERO</strong></td>";

        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px;background: #dfeaee'>&nbsp;<strong>IMPORTE</strong></td>";

        if ($Column != 'ATEN') {
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:10px;background: #dfeaee'><strong>BLOQUEADO</strong></td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'></td>";
        }
        $html .= "</tr>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {

                $estadoo = $this->verBloqueo($Rows[0]);

                $importe = (int) $Rows['mpt_sol'];
                $importe1 = substr($importe, 0, 2);
                $importe2 = substr($importe, 2, 2);
                if ($importe2 < 0 || $importe2 == "") {
                    $importe2 = "00";
                }

                $nuevoImporte = $importe1 . "." . $importe2;


                $subtotal += $nuevoImporte;

                $Antecedente = $Rows[11];
                $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";

                $html .= "<td align='center'>" . strtoupper($Rows['num_sec']) . "</td>";
                $html .= "<td align='center'>" . $Rows['num_doc'] . "</td>";
                //$html .= "<td>" . strtoupper($Rows['p_nombres']) . "</td>";
                //$html .= "<td align='center'>" . $Rows['fecha'] . "</td>";
                //$html .= "<td align='center'>" . $Rows['hora'] . "</td>";

                $html .= "<td align='center'>" . $Rows['fech'] . "</td>";
                $html .= "<td align='center'>" . $Rows['hor'] . "</td>";

                $html .= "<td align='center'>" . $Rows['cod_ofi'] . "</td>";
                $html .= "<td align='center'>" . $Rows['cod_caj'] . "</td>";

                $html .= "<td align='center'>" . $nuevoImporte . "</td>";

                $estado = $estadoo == 0 ? "No" : " Si";

                $html .= "<td align='center'>" . $estado . "</td>";

                $html .= "</tr>";
            }

            $sqlNoVou = "SELECT COUNT(*) AS cantidadNO FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas)";
            $queryNoVou = parent::Query($sqlNoVou);
            $rowNoVou = parent::ResultArray($queryNoVou);

            $sqlS = "SELECT count(*) as tot FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas) AND id NOT IN (SELECT id_voucher FROM bloqueos_vouchers) ORDER BY fech DESC";
            $queryS = parent::Query($sqlS);
            $rowS = parent::ResultArray($queryS);

            $sqlB = "SELECT count(*) as tot FROM pagos WHERE num_sec NOT IN (SELECT tipo_img FROM personas) AND id IN (SELECT id_voucher FROM bloqueos_vouchers) ORDER BY fech DESC";
            $queryB = parent::Query($sqlB);
            $rowB = parent::ResultArray($queryB);

            $html .="<tr><td colspan='9' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
            $html .= "<tr><td colspan='9' class='paginac'>" . $_pagi_navegacion . "</td>";
            $html .= "<tr><td>&nbsp;</td><td colspan='7' style='border-top:#333 1px solid; height:1px; overflow:hidden'></td><td>&nbsp;</td>";

            $html .= "<tr><td colspan='9' style='text-align:center'><strong>Cuadro de Estadisticas</strong></td>";

            $html .= "<tr><td colspan='9' style='text-align:left'>";
            $html .= "<div style='width:334px; float:left; overflow:hidden; padding-left:5px'>";
            $html .= "<div class='personas1'><strong>Nro de Voucher Sin Usar:</strong></div><div class='totall' align='left'>" . number_format($rowNoVou['cantidadNO']) . "</div>";
            $html .= "<div class='personas1'><strong>Nro de Voucher Bloqueados:</strong></div><div class='totall' align='left'>" . number_format($rowB['tot']) . "</div>";
            $html .= "<div class='personas1'><strong>Nro de Voucher Sin Bloquear:</strong></div><div class='totall' align='left'>" . number_format($rowS['tot']) . "</div>";
            $html .= "</div>";

            $html .= "</tr>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }

        //para obtener el total Actual
        $html .="<tr><td colspan='9'>";
        $html .= "<div style='width:auto; float:left; overflow:hidden'>";
        $html .= "<div class='clear'></div>";
        $html .= "</div>";
        $html .="</td></tr>";
        $html .="<tr><td colspan='9'><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
        $html .= "</table>";
        $html .= "</div>";

        return $html;
    }

    function verBloqueo($id) {
        $sql = "select * from bloqueos_vouchers where id_voucher = " . $id . "";
        $query = parent::Query($sql);
        $nroo = parent::NumReg($query);

        if ($nroo > 0) {
            $resultado = 1;
        } else {
            $resultado = 0;
        }

        return $resultado;
    }

    function FormInscSolToma($sede, $p_idsol, $IdUsuario) {
        if ($p_idsol == 0) {
            $html = "<div id='divFormInsc'>";
            $html .= "<center><div id='divButonGraba' class='oculto'></div></center>";
            $html .= "<br><fieldset>";
            $html .= "<legend><strong><center>INSCRIPCI&Oacute;N DE SOLICITUDES</center></strong></legend>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";

            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";
            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left' scope='row' style='width:90px; overflow:hidden'>APEL.&nbsp;PATERNO&nbsp;:</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apepat' id='apepat' class='textos' size='25' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left' scope='row' style='width:80px; overflow:hidden;'>APEL.&nbsp;MATERNO&nbsp;:</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apemat' id='apemat' class='textos' size='25'  onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left' scope='row'>NOMBRES :</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='nombres' id='nombres' class='textos' size='25' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";


            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left' scope='row' valign='middle'>DOCUMENTO :</th>";
            $html .= "<td>";
            $html .= self::SelecDoc("DNI");
            $html .= "</td>";
            //<input type='button' name='reniec' value='Validar DNI' onclick='javascript:AbrirReniec()'/>
            $html .= "</tr>";

            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left'><strong>NRO. DOC :</strong></th>";
            $html .= "<td id='numeroDigito'><input type='text' id='NumDoc' class='textos' name='NumDoc' maxlength='8'  onkeypress='javascript:return valident(event)' ></td>";
            $html .= "</tr>";

            $html .= "<tr>";
            //$html .= "<td style='width:10px; overflow:hidden'></td>";
            $html .= "<th align='left'><strong>FEC. NAC. :</strong></th>";

            $datex = "";
            $html .= "<td><div style='width:auto;padding-top:4px; overflow:hidden; float:left'><input class='InputText' type='text' name='fecnac' id='fecnac' maxlength='10' size='11'  value='$datex' onKeyUp = 'this.value=formateafecha(this.value);' /></div>";
            $html .= " <div style='width:40px;padding-top:4px; overflow:hidden; float:left; padding-left:4px'><a href='#' onclick='displayCalendar(document.bigform.fecnac,this)'><img src='../Img/img.gif' border='0' style='border:#FF0000 1px solid' /></a></div></td>";
            $html .= "</td>";
            
            $html .= "</tr>";

	    //$html .= "<tr>";
            //$html .= "<th align='left'>Foto:</th>";
            //$html .= "<td bgcolor='#DFEAEE' colspan='2'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
            //$html .= self::Foto();
            //$html .= "</td>";
            //$html .= "</tr>";


            $html .= "</table>";
            $html .= "</div>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";

            $html .= "<tr>";

            $html .= "<th align='left' valign='middle'>COMPROBANTES :</th><td>";
            $html .= self::num_tipo_pago("VOUCHER");
            $max = self::NumDigXDoc("VOUCHER");
            $html .= "<div id='TipoRecibo' style='width:60px; float:left; padding-top:3px'><input type='text' name='NumRecibo' maxlength='$max' id='NumRecibo' size='13'  onkeypress='javascript:return valident(event)' /> </div>";
            $html .= "</td>";
            //$html .= "<td>";
            //$html .="</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<th align='left' scope='row'>FECHA&nbsp;COMPROBANTE&nbsp;:</th>";
            $datex = date('d-m-Y');
            $html .= "<td><div style='width:auto;padding-top:4px; overflow:hidden; float:left'><input class='InputText' type='text' name='FechaVoucher' id='FechaVoucher' maxlength='10' size='11'  value='$datex' onKeyUp = 'this.value=formateafecha(this.value);' /></div>";
            $html .= " <div style='width:40px;padding-top:4px; overflow:hidden; float:left; padding-left:4px'><a href='#' onclick='displayCalendar(document.bigform.FechaVoucher,this)'><img src='../Img/img.gif' border='0' style='border:#FF0000 1px solid' /></a></div></td>";
            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<th align='left'>MOTIVO&nbsp;SOLICITUD&nbsp;:</th>";
            $html .= "<td>";
            $html .= self::TipoTramite('0');
            $html .= "</td>";
            $html .= "</tr>";

            $html .= "<tr>";
            $html .= "<th align='left' valign='middle'>OBSERVACIONES&nbsp;:</th>";
            $html .= "<td colspan='2'>";
            $html .= "<textarea name='tobserva' rows='3' style='width:200px; overflow:hidden; font:normal 12px Arial'></textarea>";
            $html .= "</td>";
            $html .= "<div id='mensaje' ></div></td>";
            $html .= "</tr>";

            $html .= "</table>";
            $html .= "</div>";

            $html .= "<div style='clear:both; padding-top:15px'></div>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='780px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th>&iquest;HOMONIMIA?</th>";
            $html .= "<td bgcolor='#DFEAEE'><input type='radio' name='RadioGroup1' style='border:0' value='rsi' id='rsi'  onclick=javascript:mostrarhommonimia();llenaAno(document.bigform.ano);  />";
            $html .= "Si";
            $html .= "<input type='radio' name='RadioGroup1' value='rno' id='rno' style='border:0' CHECKED  onclick=javascript:ocultarhommonimia() />";
            $html .= "No</td></tr>";
            $html .= "</table>";
            $html .= "</div>";

            //$html .= "<div style='clear:both; padding-top:15px'></div>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='780px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='left'>FOTO&nbsp;:</th>";
            $html .= "<td bgcolor='#DFEAEE' colspan='2'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
            $html .= self::Foto();
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";

            $html .= "<div style='clear:both; padding-top:15px'></div>";
        } else {
            $sql = "SELECT p_idsol, p_apepat,p_apemat , p_nombres,p_tipdocu, p_numdocu ,p_tipo,tipo_pago,tipo_img,FROM_UNIXTIME(fec_pago,'%d-%m-%Y') as FechaPago,observacion  FROM personas  where p_idsol ='$p_idsol'  ";
            $resul = parent::Query($sql);
            $Row = parent::ResultAssoc($resul);
            $sql2 = "SELECT g.foto FROM generado_solicitud g where id_generado='$p_idsol'";
            $resul2 = parent::Query($sql2);
            $Row2 = parent::ResultAssoc($resul2);
            $html = "<div id='divFormInsc'>";
            $html .= "<input type='hidden' name='IdsInternos' value='$p_idsol'/>";
            $html .= "<center><div  id='divButonGraba' class='oculto'></div></center>";
            $html .= "<br><fieldset>";
            $html .= "<legend><strong><center>INSCRIPCI&Oacute;N DE SOLICITUDES</center> </strong></legend>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";

            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>APEL.&nbsp;PATERNO&nbsp;:</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apepat' class='textos' id='apepat' size='30'  value='" . $Row['p_apepat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>APEL.&nbsp;MATERNO&nbsp;:</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apemat' class='textos' id='apemat' size='30'  value='" . $Row['p_apemat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>NOMBRES&nbsp;:</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='nombres' class='textos' id='nombres' size='30' value='" . $Row['p_nombres'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";


            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>DOCUMENTO&nbsp;:</th>";
            $html .= "<td>";
            $html .= self::SelecDoc($Row['p_tipdocu']);
            $html .= "&nbsp;<input type='button' name='reniec' value='Validar DNI' onclick='javascript:AbrirReniec()'/></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left'><strong>NRO.&nbsp;DOC&nbsp;:</strong></th>";
            $html .= "<td id='numeroDigito'><input type='text' class='textos' id='NumDoc' name='NumDoc' maxlength='8'  value='" . $Row['p_numdocu'] . "' onkeypress='javascript:return valident(event)' ></td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";


            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='left'>TIPO&nbsp;DE&nbsp;PAGO&nbsp;:</th><td>";
            $html .= self::num_tipo_pago($Row['tipo_pago']);
            $max = self::NumDigXDoc($Row['tipo_pago']);
            //$html .= "</td>";
            //$html .= "</tr>";
            //$html .= "<tr>";
            //$html .= "<td>";
            $html .= "<div id='TipoRecibo' style='width:60px; float:left; padding-top:3px'><input type='text' name='NumRecibo' maxlength='$max' id='NumRecibo' size='13'  value='" . $Row['tipo_img'] . "' onkeypress='javascript:return valident(event)' /> </div></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left' scope='row'>FECHA&nbsp;COMPROBANTE&nbsp;:</th>";
            $html .= "<td><div style='width:auto;padding-top:4px; overflow:hidden; float:left'><input class='InputText' type='text' name='FechaVoucher' id='FechaVoucher' maxlength='10' size='10'  value='" . $Row['FechaPago'] . "' onKeyUp = 'this.value=formateafecha(this.value);' /></div>";
            $html .= "<div style='width:40px;padding-top:4px; overflow:hidden; float:left; padding-left:4px'><a href='#' onclick='displayCalendar(document.bigform.FechaVoucher,this)'><img src='../Img/img.gif' border='0' style='border:#FF0000 1px solid' /></a></div></td>";
            //$html .= "<input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FechaVoucher,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='-1' /></td>";
            //$html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left'>MOTIVO&nbsp;SOLICITUD&nbsp;:</th>";
            $html .= "<td>";
            $html .= self::TipoTramite($Row['p_tipo']);
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left' valign='middle'>OBSERVACIONES&nbsp;:</th>";
            $html .= "<td colspan='2'>";
            $html .= "<textarea name='tobserva' rows='3' style='width:200px; overflow:auto'>" . $Row['observacion'] . "</textarea>";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<div style='clear:both; padding-top:15px'></div>";
            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='380px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='left'>FOTO&nbsp;:</th>";
            $html .= "<td bgcolor='#DFEAEE'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
            $html .= self::Foto();
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";
        }
        $html .= "<div><table border='0' id='homonimiafrom'  border='0' style='width:800px; overflow:hidden'  cellpadding='0' cellspacing='0'>";
        $html .= "<tr>";
        $html .= "<th align='left' scope='row' width='90px' style='width:90px'>Fecha Nacimiento:</th>";
        $html .= "<td>";
        $html .= "<input type='hidden' name='ocultoFechaActual' value='" . date('j/m/Y') . "'>";


        $html .= "<table width='200px' style='width:200px; overflow:hidden'>";
        $html .= "<tr>";
        $html .= "<th>A&ntilde;o</th>";
        $html .= "<th>Mes</th>";
        $html .= "<th>Dia</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td><select name='ano' id='ano' onChange=javascript:cambia(0) >";
        $html .= "</select></td>";
        $html .= "<td><select name='mes'  id='mes' onChange=javascript:cambia(1)  >";
        $html .= "</select></td>";
        $html .= "<td><select name='dia' id='dia' onChange=javascript:cambia(2)  >";
        $html .= "</select></td>";
        $html .= "</tr>";
        $html .= "</table></td>";

        $html .= "</tr>";
        $html .= "<tr >";
        $html .= "<input name='fec_nac' type='hidden' disabled size='14' maxlength='12'>";
        $html .= "<input name='fec_nacx' type='hidden' disabled size='14' maxlength='12'></td>";
        $html .= "<th align='left' scope='row'>Edad</th>";
        $html .= "<td><input name='edad' type='text' id='edad' onkeypress=javascript:return valident(event) size='6' maxlength='2'  readonly='readonly' /></td>";
        $html .= "<th align='left'>Sexo</th>";
        $html .= "<td colspan='3'><select name='sexo' id='sexo' class='dni'>";
        $html .= "<option value='00' selected='selected'>--Seleccione--</option>";
        $html .= "<option value='xy'>Masculino</option>";
        $html .= "<option value='yy'>Femenino</option>";
        $html .= "</select></td>";
        $html .= "<th align='left'   scope='row'> Nacimiento:</th>";
        $html .= "<td colspan='2'>";
        $html .= self::ContenidoMuestraDepartamentos();
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<th align='left' scope='row'>Domicilio </th>";
        $html .= "<td colspan='2'>";
        $html .= "<input name='domicilio' type='text' id='domicilio' size='30' maxlength='200' />";
        $html .= "</td>";

        $html .= "<tr>";
        $html .= "<th align='left' scope='row'>Instrucci&oacute;n</th>";
        $html .= "<td>";
        $html .= self::ContenidoGradoInsx();
        $html .= "</td>";
        $html .= "<th align='left' scope='row'>Profesi&oacute;n";
        //$html .= "";
        $html .= "</th>";
        $html .= "<td colspan='3'>";
        $html .= "<input name='pro_ocup type='text' id='pro_ocup' size='22' maxlength='100' />";
        $html .= "</td>";
        $html .= "<th align='left' scope='row'>E. Civil</th>";
        $html .= "<td colspan='3'>";
        $html .= self::ContenidoEstaCivil();
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<th align='left' scope='row'>Nacionalidad</th>";
        $html .= "<td>";
        $html .= self::ContenidoMuestraNacionalidad();
        $html .= "</td>";
        $html .= "<th align='left' scope='row'>Padre (Ape. y Nomb.)</th>";
        $html .= "<td colspan='3'>";
        $html .= "<input name='nom_pad' type='text' id='nom_pad' size='22' maxlength='230'  />";
        $html .= "</td>";
        $html .= "<th align='left' scope='row'>Madre (Ape. y Nomb.)</th>";
        $html .= "<td colspan='3'><em>";
        $html .= "<input name='nom_mad' type='text' id='nom_mad' size='22' maxlength='230'  />";
        $html .= "</em></td>";
        $html .= "</tr>";
        $html .= "</table>";
        $html .= "</fieldset>";
        //$html .= "</div>";
        $html .= "</div>";

        $html .= "<div style='clear:both'></div>";
        $html .= "<div id='EditionWindow'>" . self::CertificadoEdit($IdUsuario) . "</div>";
        return $html;
    }

    function FormInscSolToma1($sede, $p_idsol, $IdUsuario) {

        if ($p_idsol != 0) {
            $sql = "SELECT p_idsol, p_apepat,p_apemat , p_nombres,p_tipdocu, p_numdocu ,p_tipo,tipo_pago,tipo_img,FROM_UNIXTIME(fec_pago,'%d-%m-%Y') as FechaPago,observacion, emite  FROM personas  where p_idsol ='$p_idsol'  ";
            $resul = parent::Query($sql);
            $Row = parent::ResultAssoc($resul);
            $sql2 = "SELECT g.foto FROM generado_solicitud g where id_generado='$p_idsol'";
            $resul2 = parent::Query($sql2);
            $Row2 = parent::ResultAssoc($resul2);
            $html = "<div id='divFormInsc'>";
            $html .= "<input type='hidden' name='IdsInternos' value='$p_idsol'/>";
            $html .= "<input type='hidden' name='emite' value='" . $Row['emite'] . "'/>";
            $html .= "<center><div  id='divButonGraba' class='oculto'></div></center>";
            $html .= "<br><fieldset>";
            $html .= "<legend><strong><center> Inscripci&oacute;n de Solicitudes </center> </strong></legend>";

            $html .= "<div style='width:400px; float:left; overflow:hidden'>";

            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>Apel. Paterno </th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apepat' class='textos' id='apepat' size='30'  value='" . $Row['p_apepat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>Apel. Materno </th>";
            $html .= "<td>";
            $html .= "<input type='text' name='apemat' class='textos' id='apemat' size='30'  value='" . $Row['p_apemat'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>Nombres</th>";
            $html .= "<td>";
            $html .= "<input type='text' name='nombres' class='textos' id='nombres' size='30' value='" . $Row['p_nombres'] . "' onBlur='javascript:buscarGuionTres(this);compruebaEspacio(this);pasarMayusculas(this)'  onKeyPress='javascript:return keyRestrict(event)' />";
            $html .= "</td>";
            $html .= "</tr>";


            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left' scope='row'>Documento </th>";
            $html .= "<td>";
            $html .= self::SelecDoc($Row['p_tipdocu']);
            //<input type='button' name='reniec' value='Validar DNI' onclick='javascript:AbrirReniec()'/>
            $html .= "&nbsp;</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<td>&nbsp;</td>";
            $html .= "<th align='left'><strong>Nro. Doc</strong></th>";
            $html .= "<td id='numeroDigito'><input type='text' class='textos' id='NumDoc' name='NumDoc' maxlength='8'  value='" . $Row['p_numdocu'] . "' onkeypress='javascript:return valident(event)' ></td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";


            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='380px' cellpadding='2' cellspacing='2' height='205px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='left'>Comprobante</th><td>";
            $html .= self::num_tipo_pago($Row['tipo_pago']);
            $max = self::NumDigXDoc($Row['tipo_pago']);
            //$html .= "</td>";
            //$html .= "</tr>";
            //$html .= "<tr>";
            //$html .= "<td>";
            $html .= "<div id='TipoRecibo' style='width:60px; float:left; padding-top:3px'><input type='text' name='NumRecibo' maxlength='$max' id='NumRecibo' size='13'  value='" . $Row['tipo_img'] . "' onkeypress='javascript:return valident(event)' /> </div></td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left' scope='row'>Fecha Comprobante: </th>";
            $html .= "<td><div style='width:auto;padding-top:4px; overflow:hidden; float:left'><input class='InputText' type='text' name='FechaVoucher' id='FechaVoucher' maxlength='10' size='10'  value='" . $Row['FechaPago'] . "' onKeyUp = 'this.value=formateafecha(this.value);' /></div>";
            $html .= "<div style='width:40px;padding-top:4px; overflow:hidden; float:left; padding-left:4px'><a href='#' onclick='displayCalendar(document.bigform.FechaVoucher,this)'><img src='../Img/img.gif' border='0' style='border:#FF0000 1px solid' /></a></div></td>";
            //$html .= "<input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.FechaVoucher,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='-1' /></td>";
            //$html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left'>Motivo Solicitud </th>";
            $html .= "<td>";
            $html .= self::TipoTramite($Row['p_tipo']);
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "<tr>";
            $html .= "<th align='left' valign='middle'>Observaciones </th>";
            $html .= "<td colspan='2'>";
            $html .= "<textarea name='tobserva' rows='3' style='width:200px; overflow:auto'>" . $Row['observacion'] . "</textarea>";
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";
            $html .= "<div style='clear:both; padding-top:15px'></div>";

            //if ($Row2['foto'] == NULL) {
            $html .= "<div style='width:400px; float:left; overflow:hidden'>";
            $html .= "<table width='380px' border='0' align='left'>";
            $html .= "<tr>";
            $html .= "<th align='left'>Foto:</th>";
            $html .= "<td bgcolor='#DFEAEE'><input type='hidden' name='ImgUrl' id='ImgUrl' />";
            $html .= self::Foto();
            $html .= "</td>";
            $html .= "</tr>";
            $html .= "</table>";
            $html .= "</div>";
            //}
            $html .= "<div style='width:120px; float:left; overflow:hidden'>";
            $html .= "<img src='../Img/certificados/$Row2[foto]' title='foto' alt='foto' width='80' height='100' />";
            $html .= "</div>";

            $html .= "<div style='clear:both; padding-top:15px'></div>";

            $html .= "<td colspan='5' align='right'><input type='button' value='Guardar' onclick='validarEdit_web();' /></td>";
        }
        $html .= "<table width='1050px' border='0' id='homonimiafrom' border='0' style='display:none' cellpadding='0' cellspacing='0'>";
        $html .= "<tr>";
        $html .= "<th align='left' scope='row'>Fecha Nacimiento:</th>";
        $html .= "<td>";
        $html .= "<input type='hidden' name='ocultoFechaActual' value='" . date('j/m/Y') . "'>";


        $html .= "<table>";
        $html .= "<tr>";
        $html .= "<th>A&ntilde;o</th>";
        $html .= "<th>Mes</th>";
        $html .= "<th>Dia</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td><select name='ano' id='ano' onChange=javascript:cambia(0) >";
        $html .= "</select></td>";
        $html .= "<td><select name='mes'  id='mes' onChange=javascript:cambia(1)  >";
        $html .= "</select></td>";
        $html .= "<td><select name='dia' id='dia' onChange=javascript:cambia(2)  >";
        $html .= "</select></td>";
        $html .= "</tr>";
        $html .= "</table>";

        $html .= "</tr>";
        $html .= "<tr >";
        $html .= "<input name='fec_nac' type='hidden' disabled size='14' maxlength='12'>";
        $html .= "<input name='fec_nacx' type='hidden' disabled size='14' maxlength='12'></td>";
        $html .= "<th align='right' scope='row'>Edad</th>";
        $html .= "<td><input name='edad' type='text' id='edad' onkeypress=javascript:return valident(event) size='6' maxlength='2'  readonly='readonly' /></td>";
        $html .= "<th align='right'>Sexo</th>";
        $html .= "<td colspan='3'><select name='sexo' id='sexo' class='dni'>";
        $html .= "<option value='00' selected='selected'>--Seleccione--</option>";
        $html .= "<option value='xy'>Masculino</option>";
        $html .= "<option value='yy'>Femenino</option>";
        $html .= "</select></td>";
        $html .= "<th align='right'   scope='row'> Lugar Nacimiento:</th>";
        $html .= "<td colspan='2'>";
        $html .= self::ContenidoMuestraDepartamentos();
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<th align='right' scope='row'>Domicilio </th>";
        $html .= "<td colspan='2'><em>";
        $html .= "<input name='domicilio' type='text' id='domicilio' size='30' maxlength='200' />";
        $html .= "</em></td>";

        $html .= "<tr>";
        $html .= "<th align='right' scope='row'>Grado de Instrucci&oacute;n</th>";
        $html .= "<td>";
        $html .= self::ContenidoGradoInsx();
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Profesi&oacute;n u ";
        $html .= "Ocupaci&oacute;n";
        $html .= "</th>";
        $html .= "<td colspan='3'><em>";
        $html .= "<input name='pro_ocup type='text' id='pro_ocup' size='30' maxlength='100' />";
        $html .= "</em></td>";
        $html .= "<th align='right' scope='row'>Estado Civil</th>";
        $html .= "<td colspan='3'><em>";
        $html .= self::ContenidoEstaCivil();
        $html .= "</em></td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<th align='right' scope='row'>Nacionalidad</th>";
        $html .= "<td>";
        $html .= self::ContenidoMuestraNacionalidad();
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Padre <em>(Ape. y Nomb.)</em></th>";
        $html .= "<td colspan='3'>";
        $html .= "<input name='nom_pad' type='text' id='nom_pad' size='30' maxlength='230'  />";
        $html .= "</td>";
        $html .= "<th align='right' scope='row'>Madre <em>(Ape. y Nomb.)</em></th>";
        $html .= "<td colspan='3'><em>";
        $html .= "<input name='nom_mad' type='text' id='nom_mad' size='30' maxlength='230'  />";
        $html .= "</em></td>";
        $html .= "</tr>";
        $html .= "</table>";
        //$html .= "<div id='EditionWindow'></div>";
        $html .= "</div>";
        $html .= "<div id='EditionWindow'>" . self::CertificadoEdit1($IdUsuario) . "</div>";
        return $html;
    }

    function CertificadoEdit1($Find) {
        $html = "<br /><fieldset>";
        /* ---------------------------------------------------  MARCO  OBSERVAR  ------------------------------------------------- */
        $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/mainpopup_web.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";
        /* ---------------------------------------------------  MARCO  ELIMINAR  ------------------------------------------------- */
        $html .= "<div id='popupdelete' style='display:none;width:750px;height:415px;'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
        $html .= "<tr bgcolor='blue'>";
        $html .= "<td align='right'><a href='#' onclick=javascript:popup('popupdelete');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
        $html .= "</tr><tr><td>";
        $html .= "<iframe src='../main/popupdelete.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
        $html .= "</iframe></td></tr></table>";
        $html .= "</div>";
        /* ------------------------------------------------------------------------------------------------------------------------------ */


        $html .= "<legend><strong><center> B&uacute;squeda de Solicitudes WEB Ingresadas</center> </strong></legend>";
        $html .="<div id='ViCss' style='width:840px;overflow:hidden;background:#CEF4FA'><input type='hidden' name='IdsInternos' />";
        $html .= "<div id='Filtros'>" . $this->FiltrosEdit1() . "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Xprocedencia' style='display:none;'>";
        $html .=$this->SelectProcedencias() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='Busquedas1' style='display:none;'>" . $this->dniApoderado() . "</div>";
        $html .= "</div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEditCerti11($Find) . "</div>";

        $html .= "</div>";
        return $html;
    }

    function FiltrosEdit1() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html = "<table border='0' cellpadding='1' cellspacing='1'><tr>";
        $html .= "<td >CODIGO DE IMPRESION&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(5)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";
        $html .= "<td>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='<< Buscar >>' onclick='javascript:FindCertificadoEditWEB(this.name);' $evento  $estiloopc width:90px;' tabindex='4'>";
        $html .= "</td>";
        $html .= "</tr>";
        $html .= "<tr><td>Dni Apoderado :</td> <td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(7)' ></td>";

        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $html .= "<td>ATEND :</td> <td><INPUT TYPE='RADIO' NAME='color' onclick='javascript:CamposEmisionWEB(8)' ></td>";

        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    function ListadoEditCerti11($Find) {
        if ($Find[0] == 'Nombres') {
            //$Admin = "AND p.id_local = '$Find[6]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=0";
            $proce = "AND p.proc_dir='$Find[6]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_nombres LIKE '$Find[3]%' AND p.p_apepat LIKE '$Find[1]%' AND p.p_apemat LIKE '$Find[2]%' $Admin $proce ORDER BY p.p_apepat ASC";
            $IdUsuario = $Find[4];
        } elseif ($Find[0] == 'Fechas') {
            $Ini = $this->FechaMysql($Find[1]);
            $Fin = $this->FechaMysql($Find[2]);
            //$Admin = "AND p.id_local = '$Find[5]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=0";
            $proce = "AND p.proc_dir='$Find[5]'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59') $Admin $proce ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Procede') {
            //$Admin = "AND p.id_local = '$Find[2]'";
            $codImp = " AND p.id_ofic = '$Find[1]'";
            //AND p.proc_lug = '$Find[2]'
            $proce = "AND p.proc_dir='$Find[5]'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $codImp  $Admin $proce ORDER BY p.p_fechasol ASC";
            $IdUsuario = $Find[3];
        } elseif ($Find[0] == 'Doc') {
            //$Admin = "AND p.id_local = '$Find[4]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=0";
            $proce = "AND p.proc_dir='$Find[4]'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' AND p.p_idsol = '$Find[1]' $Admin $proce ORDER BY p.p_idsol ASC";
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Tram') {
            //$Admin = "AND p.id_local = '$Find[4]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=0";
            $proce = "AND p.proc_dir='$Find[4]'";
            $crit = "AND p.foto = " . $Find[1];

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_apepat,' ',p.p_apemat,' ',p.p_nombres) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $proce $crit ORDER BY p.p_idsol ASC";
            $IdUsuario = $Find[2];
        } elseif ($Find[0] == 'Todo') {
            //$Admin = "AND p.id_local = '$Find[3]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=0";
            //$Admin = "";
            $proce = "AND p.proc_dir='$Find[3]'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $proce ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[0];
        }elseif ($Find[0] == 'atend') {
            //$Admin = "AND p.id_local = '$Find[3]'";
            //AND g.flag=0
            $Admin = " AND p.id_local>100 AND g.flag=1";
            //$Admin = "";
            $proce = "AND p.proc_dir='$Find[3]'";

            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $proce ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[0];
        } else {
            //echo "<script>alert(\"$Find[0]\")</script>";
            //echo "<script>alert(\"$Find[1]\")</script>";
            //echo "<script>alert(\"$Find[2]\")</script>";
            //AND p.proc_dir = '$Find[2]'
            //AND p.cod_user = '$Find[0]'

            //AND g.flag=0
            if ($Find[0] == "") {
                $Admin = " AND p.id_local>100 AND g.flag=0";
            } else {
                //AND g.flag=0
                $Admin = " AND p.id_local>100 AND g.flag=0";
            }
            $proce = "AND p.proc_dir='$Find[2]'";
            //$Admin = ($Find[1] == '2') ? "AND p.proc_dir='$Find[2]'" : "AND p.cod_user = '$Find[0]'";
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull, FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,FROM_UNIXTIME(p.p_fechasol,'%h:%i:%s') AS Hora,g.foto,p.p_apepat,p.p_apemat, p.p_nombres, p.emite, p.proc_dir FROM personas p LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  p.p_idsol = g.id_generado AND p.f_val = '0' $Admin $proce ORDER BY p.p_idsol DESC";
            $IdUsuario = $Find[0];
        }

        //echo "<script>alert(\"$Find[3]\")</script>";
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 10;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:220px;'>AP. PATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:220px;'>AP. MATERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:220px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px;'>F.&nbsp;SOLICITUD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>PROCED.</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ESTADO&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>OPC.</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;</th>";
        $html .= "</tr></thead><tbody>";
        $contador = 0;


        $idd = 0;
        

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $idSol.= ( $idd == 0 ? '' : ',') . $Rows['p_idsol'];
                $idd++;
                $contador++;
                $Antecedente = $Rows['emite'];
                $procedencia = self::Procedencia($Rows['proc_dir']);
                if ($procedencia[1] == 'INFRAESTRUCTURA(CAJ - WEB)') {
                    $proced = "INFRA";
                } elseif ($procedencia[1] == 'DICSCAMEC(CAJ - WEB)') {
                    $proced = "DICSCAMEC";
                } elseif ($procedencia[1] == 'SEDE CENTRAL') {
                    $proced = "S. CENTRAL";
                } else {
                    $proced = $procedencia[1];
                }
                //$ValidaPago = $this->ValidarPago($Rows[7], $Rows[8], $Rows[9]);
                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none'>";
                $html .= "<td><a href='#' onclick=javascript:MantenimientoDatosEdit1('Editar','$Rows[0]'); title='Editar' >" . $Rows[0] . "</a></td>";
                $html .= "<td>" . $Rows[1] . "</td>";
                $html .= "<td>" . strtoupper($Rows[13]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                $html .= "<td>" . strtoupper($Rows[15]) . "</td>";

                //$html .= "<td align='center'>" . $ValidaPago[0] . "</td>";
                $html .= "<td align='center'>" . $Rows[3] . "</td>";
                $Anti = $this->ResultPersonal($Rows[4], $Rows[0]);
                //$Anticucho = (($Anti == "SI") AND ($ValidaPago[1] == "NO")) ? "BUSCA" : $Anti ;
                $html .= "<td align='center'>" . $Rows[11] . "</td>"; //HORA
                //$Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' /><img src='../Img/negro.gif' style='border:none' /></a>";
                /* if ($Rows[12] == NULL) {
                  $html .= "<td align='center'></td>"; //FOTO
                  } else { */
                $html .= "<td align='center'>" . $proced . "</td>"; //FOTO
                //}
                $Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDFWeb('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                $Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;


                //$html .= "<td align='center'>" . $Anticucho . "</td>";
                $html .= "<td align='center'>" . $Rows[16] . "</td>";
                $html .= "<td align='center'>";
                //<a href='#' onclick=javascript:MantenimientoDatosEdit1('Editar','$Rows[0]'); title='Editar' ><img src='../Img/edi.gif' border='0' /></a>&nbsp;
                //if($Rows['emite']=='LISTO'){
                //$html .= "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' title='Imprimir'><img src='../Img/negro.gif' title='Imprimir' border='0' /></a> ".$Rows['emite'];
                $html .= $Imprimir;
                //}else{
                //$html .= "$Rows['emite']";
                //}
                $html .= "</td>";

                $html .= "<td align='center'>";
                if ($Rows['foto'] != "") {
                    //$html .= "<img src='../Img/Img/user.png' border='0' />";
                    $html .= "<img src='../Img/certificados/$Rows[foto]' border='0' width='24' height='30' />";
                } else {
                    $html .= "&nbsp;";
                }
                $html .= "</td>";


                $html .= "<td align='center'>&nbsp;</td>";

                $html .= "</tr>";
            }
            $html .= "<table><tr><td colspan='4' align='right'><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /> <input type='hidden' name='NumRegistro' value='$contador' /></td></tr>";
            $html .= "<tr><td colspan='7'>" . $_pagi_navegacion . "</td></tr></table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .= "</tbody></table>";
        return $html;
    }

    function GbSolicitudEditada_web($_POST, $Ubica, $user) {
        //$IdsInternos = $_POST["IdsInternos"];
	$html = '';
        $IdsInternos = $_POST["IdsInternos"];
        //echo "<script>".$IdsInternos."</script>";
        $apepat = mysql_real_escape_string($_POST["apepat"]);
        $email = mysql_real_escape_string($_POST["email"]);
        $apemat = mysql_real_escape_string($_POST["apemat"]);
        $nomb = mysql_real_escape_string($_POST["nombres"]);
        $tipdocu = mysql_real_escape_string($_POST["tipdocu"]);
        $tipdocu = self::BuscaTipodoc($tipdocu);
        $numdocu = mysql_real_escape_string($_POST["NumDoc"]);
///TIPO DE PAGO
        $tipoPago = mysql_real_escape_string($_POST["num_tipo_pago"]);
        $tobserva = preg_replace("/[\n|\r|\n\r]/i", " ", $_POST["tobserva"]);
        $ValDocPago = explode('.', $tipoPago);
        $tipoPago = $ValDocPago['0'];
        $procedencia_direc = $Ubica;
        //$procedencia_direc=mysql_real_escape_string($_POST["procedencia_direc"]);
        $procedencia_lugar = mysql_real_escape_string($_POST["localidadp"]);
        $emite = mysql_real_escape_string($_POST["emite"]);

        $fec_pago = self::FechaMysql($_POST["FechaVoucher"]);
        $fec_pago_unix = strtotime($fec_pago);
        $tbanco = $_POST["NumRecibo"];
        $monto = '0.00';


        $num_doc = $numdocu;
        $xva = $user;
        $id_local = $Ubica;

        $desc = $_POST["TipoTramite"];
        $FotoTupa = $_POST["ImgUrl"];
        //echo "<script>alert(\"$des\")</script>";

        $arraySolicitaIdDesc = self::muestra_solicita_cod($desc);

        for ($i = 0; $i < sizeof($arraySolicitaIdDesc[0]); $i++) {
            //echo "<option value='".$arrayDocu[0][$i]."'>".$arrayDocu[1][$i]."</option>";
            $tiposol = $arraySolicitaIdDesc[0][$i];
            $desc = $arraySolicitaIdDesc[1][$i];
        }

////////////////////////////////////////////////////////$test=Contenido::ContenidoValida($tbanco,$fec_pago); modificado x 10 dias
//  $tasa="29.44";//tasa por tupa de antecedentes judiciales a nivel nacional
//no c creo una tabla mantenimiento por motivos de tiempo
        $tasa = "20.27";
        $test[0] = "0000000002027";
        $test[1] = "0";
        $decimal = ($test[0] / 100);
        $valorvoucher = self::ContenidoValidaSedeCentral($tbanco, $fec_pago_unix, $tipoPago);
        $num = self::ContenidoOptienID();
        $idsol = sprintf('%06s', ($num + 1));

        switch (TRUE) {
            case ($tipoPago == 'VOUCHER' && $valorvoucher[0] == 0):

                //verificando

                $sqlV = "select * from personas where p_idsol = " . $IdsInternos;
                $queryV = parent::Query($sqlV);
                $rowV = parent::ResultArray($queryV);

                if ($rowV['p_apepat'] == $apepat && $rowV['p_apemat'] == $apemat && $rowV['p_nombres'] == $nomb && $rowV['tipo_img'] == $tbanco && $rowV['fec_pago'] == $fec_pago_unix && $rowV['p_numdocu'] == $num_doc) {
                    $estadoE = $emite;
                } else {
                    $estadoE = "BUSCA";
                }

                $sql = "UPDATE personas p SET ";
                $sql.="p_apepat='$apepat'";
                $sql.=",p_apemat='$apemat'";
                $sql.=",p_nombres='$nomb'";
                $sql.=",p_tipdocu='$tipdocu'";
                $sql.=",p_numdocu='$num_doc'";

                $sql.=",p_tipo='$tiposol'";
                $sql.=",p_desc='$desc'";

                $sql.=", tipo_img='$tbanco'";
                $sql.=",observacion='$tobserva'";
                $sql.=",fec_pago='$fec_pago_unix'";
                $sql.=",tipo_pago='$tipoPago'";
                //$sql.=",proc_dir='$procedencia_direc'";
                $sql.=",proc_lug='$procedencia_lugar'";
                $sql.=",buscador='0'";
                $sql.=",migrado='0'"; //para q sea enviado nuevamente
                $sql.=",emite='$estadoE'";
                $sql.=" WHERE p_idsol='$IdsInternos' ";
                $updateI = parent::Query($sql);
                if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                    self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else if ($FotoTupa) {
                    $idFot = explode("/", $_POST['ImgUrl']);
                    //$test=$idFot['6'];
                    //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                    //echo "<script>alert(\"$idFot[6]\")</script>";
                    //echo "<script>alert(\"$idFot[7]\")</script>";

                    self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else {
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                }

                $updaI = parent::Query($sqlI);

                if ($estadoE == 'LISTO') {
                    $queryUsu = self::CreaCertificadoPDF('', $IdsInternos);
                    $sqlBuscador = "UPDATE personas SET buscador = '1' where p_idsol = " . $IdsInternos;
                    $queryBus = parent::Query($sqlBuscador);
                }

                if ($updateI) {
                    $html = "Edici&oacute;n Grabada con Exito ";
                } else {
                    $html = "No se Grabo:Contacte su Admin ";
                }
                return $html;
                break;
            case ($tipoPago != 'VOUCHER' && $valorvoucher[0] == 0):

                //verificando

                $sqlV = "select * from personas where p_idsol = " . $IdsInternos;
                $queryV = parent::Query($sqlV);
                $rowV = parent::ResultArray($queryV);

                if ($rowV['p_apepat'] == $apepat && $rowV['p_apemat'] == $apemat && $rowV['p_nombres'] == $nomb && $rowV['tipo_img'] == $tbanco && $rowV['fec_pago'] == $fec_pago_unix && $rowV['p_numdocu'] == $num_doc) {
                    $estadoE = $emite;
                } else {
                    $estadoE = "BUSCA";
                }

                $sql = "UPDATE personas p SET ";
                $sql.="p_apepat='$apepat'";
                $sql.=",p_apemat='$apemat'";
                $sql.=",p_nombres='$nomb'";
                $sql.=",p_tipdocu='$tipdocu'";
                $sql.=",p_numdocu='$num_doc'";
                $sql.=",p_tipo='$tiposol'";
                $sql.=",p_desc='$desc'";
                $sql.=", tipo_img='$tbanco'";
                $sql.=",observacion='$tobserva'";
                $sql.=",fec_pago='$fec_pago_unix'";
                $sql.=",tipo_pago='$tipoPago'";
                //$sql.=",proc_dir='$procedencia_direc'";
                $sql.=",proc_lug='$procedencia_lugar'";
                $sql.=",buscador='0'";
                $sql.=",migrado='0'"; //para q sea enviado nuevamente
                $sql.=",emite='$estadoE'";
                $sql.=" WHERE p_idsol='$IdsInternos' ";
                $updateII = parent::Query($sql);
                if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                    self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else if ($FotoTupa) {
                    $idFot = explode("/", $_POST['ImgUrl']);
                    //$test=$idFot['6'];
                    //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                    //echo "<script>alert(\"$idFot[6]\")</script>";
                    //echo "<script>alert(\"$idFot[7]\")</script>";

                    self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                } else {
                    $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                }

                $updaI = parent::Query($sqlI);
                if ($estadoE == 'LISTO') {
                    $queryUsu = self::CreaCertificadoPDF('', $IdsInternos);
                    $sqlBuscador = "UPDATE personas SET buscador = '1' where p_idsol = " . $IdsInternos;
                    $queryBus = parent::Query($sqlBuscador);
                }

                if ($updateII) {
                    $html = "Edici&oacute;n Grabada con Exito ";
                } else {
                    $html = "No se Grabo:Contacte su Admin ";
                }
                return $html;
                break;


            case($valorvoucher[0] == 1):
                $Row = self::DatosRegSolicitud($IdsInternos);
		$sqlError   = "SELECT count(p_idsol) as numero, p_idsol  FROM personas  where tipo_img='".$tbanco."' and fec_pago='".$fec_pago_unix."' and tipo_pago='VOUCHER' group by p_idsol ";
                $queryError = parent::Query($sqlError);
                $nroReg = parent::NumReg($queryError);

                //if ($Row['tipo_img'] == $tbanco) {
		if ($nroReg==0 || $nroReg==1) {

                    //verificando

                    $sqlV = "select * from personas where p_idsol = " . $IdsInternos;
                    $queryV = parent::Query($sqlV);
                    $rowV = parent::ResultArray($queryV);

                    if ($rowV['p_apepat'] == $apepat && $rowV['p_apemat'] == $apemat && $rowV['p_nombres'] == $nomb && $rowV['tipo_img'] == $tbanco && $rowV['fec_pago'] == $fec_pago_unix && $rowV['p_numdocu'] == $num_doc) {
                        $estadoE = $emite;
                    } else {
                        $estadoE = "BUSCA";
                    }

                    $sql = "UPDATE personas p SET ";
                    $sql.="p_apepat='$apepat'";
                    $sql.=",p_apemat='$apemat'";
                    $sql.=",p_nombres='$nomb'";
                    $sql.=",p_tipdocu='$tipdocu'";
                    $sql.=",p_numdocu='$num_doc'";
                    $sql.=",p_tipo='$tiposol'";
                    $sql.=",p_desc='$desc'";
                    $sql.=", tipo_img='$tbanco'";
                    $sql.=",observacion='$tobserva'";
                    $sql.=",fec_pago='$fec_pago_unix'";
                    $sql.=",tipo_pago='$tipoPago'";
                    //$sql.=",proc_dir='$procedencia_direc'";
                    $sql.=",proc_lug='$procedencia_lugar'";
                    $sql.=",buscador='0'";
                    $sql.=",migrado='0'"; //para q sea enviado nuevamente
                    $sql.=",emite='$estadoE'";
                    $sql.=" WHERE p_idsol='$IdsInternos' ";
                    $updateIII = parent::Query($sql);
                    if (file_exists("../Img/Foto_Previa/pilar_00001.JPG")) {
                        self::MoverArchivo("../Img/Foto_Previa/pilar_00001.JPG", "../Img/certificados/$IdsInternos.JPG");
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.JPG',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    } else if ($FotoTupa) {
                        $idFot = explode("/", $_POST['ImgUrl']);
                        //$test=$idFot['6'];
                        //0--http://1--//2--192.168.1.35//3--sip_pilar//4--Img//5--Foto_Previa//6--20100214172137.jpg
                        //echo "<script>alert(\"$idFot[6]\")</script>";
                        //echo "<script>alert(\"$idFot[7]\")</script>";

                        self::MoverArchivo("../Img/Foto_Previa/" . $idFot['6'], "../Img/certificados/$IdsInternos.jpg");
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',foto='$IdsInternos.jpg',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    } else {
                        $sqlI = "UPDATE generado_solicitud g SET flag='0',batch_solici=NULL  WHERE id_generado='$IdsInternos'";
                    }

                    $updaI = parent::Query($sqlI);
                    if ($estadoE == 'LISTO') {
                        $queryUsu = self::CreaCertificadoPDF('', $IdsInternos);
                        $sqlBuscador = "UPDATE personas SET buscador = '1' where p_idsol = " . $IdsInternos;
                        $queryBus = parent::Query($sqlBuscador);
                    }

                    if ($updateIII) {
                        $html = "Edici&oacute;n Grabada con Exito ";
                    } else {
                        $html = "No se Grabo:Contacte su Admin ";
                    }
                } else {
                    //$html = "ahahahaha";
                    $html = "<i>Error: Para: $fec_pago Existe un $tipoPago  Asignado  a " . $valorvoucher[1] . " -- " . $tbanco . " -- " . $Row['tipo_img'] . "</i>";
                }
                break;


            default:
                $html = "default";
                break;
        }


        return $html;
    }

    //  Funcion para Mostrar las Solicitudes y Emision de Documentos historico
    function CertificadoSolicitud_historico($Find) {
        $html = "<br /><div id='ViCss' style='width:840px;'>";
        $html .= "<div id='Filtros3'>" . $this->FiltrosEmisionCertifica_historico() . "</div>";
        $html .= "<div id='ContainerRegistro2'><div id='Xprocedencia' style='display: none;'>" . self::SelectProcedencias() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        $html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div>";
        $html .= "<div id='capaoficio' style='display: none;'>" . self::nrooficio() . "</div></div>";

        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCerti_historico($Find) . "</div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .= "</div>";
        return $html;
    }

    //  Funcion para Mostrar las Solicitudes y Emision de Documentos historico
    function atendidos($Find) {
        $html = "<br /><div id='ViCss' style='width:840px;'>";
        $html .= "<div id='Filtros3'>" . $this->atendido_historico() . "</div>";
        $html .= "<div id='ContainerRegistro2'><div id='Xprocedencia' style='display: none;'>" . self::SelectProcedencias() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        $html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div>";
        $html .= "<div id='capaoficio' style='display: none;'>" . self::nrooficio() . "</div></div>";
        $html .= "<div id='impresionj' style='display: none;'>" . self::nroImpresion() . "</div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoEmiteCerti_atendido($Find) . "</div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .= "</div>";
        return $html;
    }

    function atendido_historico() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='local' NAME='color' onclick='javascript:CamposEmision_atendido(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_atendido(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_atendido(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='soli' onclick='javascript:CamposEmision_atendido(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificado_atendido(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>CERT. WEB&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='web' name='web' onclick='javascript:CamposEmision_atendido(6)' > <input type='hidden' name='como' id='como' value='0' /></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        //$html .= "<td>&nbsp;&nbsp;</td>";
        //$html .= "<td>&nbsp;</td>";
        //$html .= "<td></td>";
        //$html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;OFICIO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='oficio' onclick='javascript:CamposEmision_atendido(7)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>COD. IMPRES.:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='impre' id='impre' onclick='javascript:ocultar_filtros_otro()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:ocultar_filtrosAt()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /><input type='hidden' id='esta' /></td>";



        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    function ListadoEmiteCerti_atendido($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];
        $nroImp = $Find[14];
        $como = $Find[15];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";

        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            //echo "<script>alert(\"$Find[7]\")</script>";
            //echo "<script>alert(\"$Find[8]\")</script>";

            if ($Find[7] != "" && $Find[8] != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioF = "AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59')";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDir = " AND p.proc_dir = '$Find[9]'";
                if ($dir == 2 || $dir == 11 || $dir == 1) {
                    if ($dir == 2) {
                        $criInfra = $Find[9];
                        $criInfra1 = 103;
                        $criterioDir = " AND (p.proc_dir = '$criInfra' OR p.proc_dir = '$criInfra1')";
                    } elseif ($dir == 11) {
                        $criInfra = $Find[9];
                        $criInfra1 = 102;
                        $criterioDir = " AND (p.proc_dir = '$criInfra' OR p.proc_dir = '$criInfra1')";
                    } elseif ($dir == 1) {
                        $criInfra = $Find[9];
                        $criInfra1 = 101;
                        //$criterioDir = " AND (p.proc_dir = '$criInfra' OR p.proc_dir = '$criInfra1')";
                        $criterioDir = " AND p.proc_dir = '$Find[9]'";
                    }
                    //$criterioDir = " AND (p.proc_dir = '$criInfra' OR p.proc_dir = '$criInfra1')";
                } else {
                    $criterioDir = " AND p.proc_dir = '$Find[9]'";
                }
            }

            if ($doc != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDoc = " AND p.p_idsol = '$Find[10]'" ;
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }


            if ($Find[11] != "") {
                //$Estados = ($Find[11] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[11]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $Admin = "";

                if ($Find[11] != 'ATEND') {
                    $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                } else {
                    $criterioEs = " AND p.emite = '$Find[11]' ";
                }
            } else {
                $criterioEs = "";
            }

            if ($nombre == '' && $apepat == '' && $apemat == '' && $Find[7] == "" && $Find[8] == "" && $dir == 0 && $doc == '' && $Find[11] == '') {
                //$criterioImp = " and g.flag = 0";
            }

            if ($nroOfi != "") {
                $criterioofi = " AND g.oficio_solici  LIKE  '%$nroOfi%' ";
            }

            if ($nroImp) {
                $criterioCodImpre = " AND p.id_ofic  LIKE  '%$nroImp%' ";
            } else {
                $criterioCodImpre = "";
            }

            if ($como != 0) {
                $criteriocomo = " and p.proc_dir>100 ";
            } else {
                $criteriocomo = "";
            }
            //AND p.f_val = '0'
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud , g.oficio_solici FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado $criteriocomo $criterioImp $Admin $criterioN $criterioF $criterioDir $criterioDoc $criterioEs $criterioofi $criterioCodImpre  $filterxelmomento AND (p.emite = 'ATEND' OR p.emite = 'POS-A') ORDER BY p.p_idsol DESC";

            //echo "<script>alert(\"$_pagi_sql\")</script>";


            $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            //para impresion
            $_pagi_resultImp = $_pagi_sql;
            $_pagi_result1 = parent::Query($_pagi_sql);
            $idd = 0;
            while ($rows = parent::ResultArray($_pagi_result1)) {
                if ($rows['p_idsol'] != 0) {
                    $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                    $idd++;
                }
            }
            //}
            //$_SESSION['sql'] = $idSol;
            //echo "<script>alert(\"$idSol\")</script>";
            //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
            //$html .= $this->FormImprimirPdfsFull();
            /* ---------------------------------------------------  MARCO  OBSERVAR  ------------------------------------------------- */
            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ---------------------------------------------------  MARCO  ELIMINAR  ------------------------------------------------- */
            $html .= "<div id='popupdelete' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popupdelete');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/popupdelete.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ------------------------------------------------------------------------------------------------------------------------------ */
            require_once("Paginador.cls.php");

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<div style='width: 820px ; overflow-x:scroll'>";
            $html .= "<table id='tablaordenada'>";
            $html .= "<thead><tr>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>MATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;DOC IDEN</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";


            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>ESTADO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;Nro&nbsp;OFI&nbsp;&nbsp;</th>";


            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "</tr></thead><tbody>";

            if ($NumReg >= '1') {

                while ($Rows = parent::ResultArray($_pagi_result)) {
                    $procedencia = self::Procedencia($Rows[18]);
                    $Antecedente = $Rows[11];
                    $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                    $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                    $html .= "<td align='center'>" . $Rows[0] . "</td>";
                    //$html .= "<td>" . $Rows[1] . "</td>";

                    $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                    //$html .= "<td align='center'>" . $TipPago . "</td>";
                    $html .= "<td align='center'>" . $Rows['Docs'] . "</td>";

                    $html .= "<td align='center'>" . $Rows[3] . "</td>";
                    $html .= "<td align='center'>" . $Rows[12] . "</td>";

                    //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                    //onclick='ver_pdf_doc($Rows[0], this.href)'
                    //if ($Rows[13] == '0') {
                    $userP = self::UsuarioDescrip($Rows['cod_user']);
                    $titleimg = self::TitleDescrip($Antecedente);
                    //$Imagenes = (($Antecedente == 'PEND1') || ($Antecedente == 'PEND2')) ? "<img src='../Img/info2.gif' style='border:none' />" : "<img src='../Img/alert.gif' style='border:none' />";
                    if ($Antecedente == 'ATEND') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/accept.png' style='border:none' title='$titleimg'/>";
                    } else if ($Antecedente == 'VOUCH') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_red.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'POSIT' || $Antecedente == 'COINC') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/user_add.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'BUSCA') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_find.png' style='border:none' title='$titleimg'  />";
                    } else if ($Antecedente == 'OBSV') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_magnify.png' style='border:none' title='$titleimg'  />";
                    } else {
                        $Imagenes = "<img id='leyendaimg' src='../Img/info2.gif' style='border:none' title='$titleimg'/>";
                    }
                    $html .= "<td align='center'>" . $Antecedente . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                    //$Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                    $Pdf = "<img src='../Img/negro.gif' style='border:none' />";
                    //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                    //$Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                    $Detalle = "" . $Imagenes . "";
                    $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                    $html .= "<td align='center'>" . $Imprimir . "</td>";
                    //$html .= "<td align='center'></td>";
                    if ($procedencia[1] == 'INFRAESTRUCTURA') {
                        $proced = "INFRA";
                    } else {
                        $proced = $procedencia[1];
                    }
                    $html .= "<td align='center'>&nbsp;" . $proced . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19]." -  ".$Rows['proc_dir']."--".$dir."&nbsp;</td>";
                    //generado_solicitud
                    $html .= "<td align='center'>" . str_replace("OFICIO-", "", $Rows['oficio_solici']) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "<table>";
                $html .= "<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
                $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
                $html .= "</table>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
            }

            $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
            $html .= "</tbody></table>";
            $html .= "</div>";
            $html .= "<div id='DetalleSolicitante'></div>";

            //return $html;
        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            // AND g.flag = '0'
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

            $Estados = "";
            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

            /*
              $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud, g.oficio_solici FROM personas p ";
              $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
              $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '1' $Admin $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento ORDER BY p.p_idsol DESC";
             */
            //$_pagi_sql = "";
            $html .= "<table width='100%'><tr><td colspan='9' align='center'><font color='red'><b> Ingrese su criterio de B&uacute;queda !</b></font></td></tr></table>";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        // if ($Find[0] == 'Todo') {
        return $html;
    }

    /*  Formulario de Busqueda para la Emision y Certificadio historico      */

    function FiltrosEmisionCertifica_historico() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='local' NAME='color' onclick='javascript:CamposEmision_filtrado(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_filtrado(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_filtrado(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='soli' onclick='javascript:CamposEmision_filtrado(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificado_historico(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>ESTADO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='esta' onclick='javascript:CamposEmision_filtrado(6)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;OFICIO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='oficio' onclick='javascript:CamposEmision_filtrado(7)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:ocultar_filtros()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";

        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }

    function ListadoEmiteCerti_historico($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";

        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            //echo "<script>alert(\"$Find[7]\")</script>";
            //echo "<script>alert(\"$Find[8]\")</script>";

            if ($Find[7] != "" && $Find[8] != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioF = "AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59')";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $criterioDir = " AND p.proc_dir = '$Find[9]'";
            }

            if ($doc != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDoc = " AND p.p_idsol = '$Find[10]'" ;
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }


            if ($Find[11] != "") {
                //$Estados = ($Find[11] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[11]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[11] != 'ATEND') {
                    $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                } else {
                    $criterioEs = " AND p.emite = '$Find[11]' ";
                }
            } else {
                $criterioEs = "";
            }

            if ($nombre == '' && $apepat == '' && $apemat == '' && $Find[7] == "" && $Find[8] == "" && $dir == 0 && $doc == '' && $Find[11] == '') {
                $criterioImp = " and g.flag = 0";
            }

            if ($nroOfi != "") {
                $criterioofi = " AND g.oficio_solici  LIKE  '%$nroOfi%' ";
            }
            //AND p.f_val = '0'
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud , g.oficio_solici FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '1' $criterioImp $Admin $criterioN $criterioF $criterioDir $criterioDoc $criterioEs $criterioofi $filterxelmomento ORDER BY p.p_idsol DESC";




            $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            //para impresion
            $_pagi_resultImp = $_pagi_sql;
            $_pagi_result1 = parent::Query($_pagi_sql);
            $idd = 0;
            while ($rows = parent::ResultArray($_pagi_result1)) {
                if ($rows['p_idsol'] != 0) {
                    $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                    $idd++;
                }
            }
            //}
            //$_SESSION['sql'] = $idSol;
            //echo "<script>alert(\"$idSol\")</script>";
            //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
            //$html .= $this->FormImprimirPdfsFull();
            /* ---------------------------------------------------  MARCO  OBSERVAR  ------------------------------------------------- */
            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ---------------------------------------------------  MARCO  ELIMINAR  ------------------------------------------------- */
            $html .= "<div id='popupdelete' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popupdelete');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/popupdelete.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ------------------------------------------------------------------------------------------------------------------------------ */
            require_once("Paginador.cls.php");

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<div style='width: 820px ; overflow-x:scroll'>";
            $html .= "<table id='tablaordenada'>";
            $html .= "<thead><tr>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>MATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;DOC IDEN</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";


            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>ESTADO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;Nro&nbsp;OFI&nbsp;&nbsp;</th>";


            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "</tr></thead><tbody>";

            if ($NumReg >= '1') {

                while ($Rows = parent::ResultArray($_pagi_result)) {
                    $procedencia = self::Procedencia($Rows[18]);
                    $Antecedente = $Rows[11];
                    $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                    $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                    $html .= "<td align='center'>" . $Rows[0] . "</td>";
                    //$html .= "<td>" . $Rows[1] . "</td>";

                    $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                    //$html .= "<td align='center'>" . $TipPago . "</td>";
                    $html .= "<td align='center'>" . $Rows['Docs'] . "</td>";

                    $html .= "<td align='center'>" . $Rows[3] . "</td>";
                    $html .= "<td align='center'>" . $Rows[12] . "</td>";

                    //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                    //onclick='ver_pdf_doc($Rows[0], this.href)'
                    //if ($Rows[13] == '0') {
                    $userP = self::UsuarioDescrip($Rows['cod_user']);
                    $titleimg = self::TitleDescrip($Antecedente);
                    //$Imagenes = (($Antecedente == 'PEND1') || ($Antecedente == 'PEND2')) ? "<img src='../Img/info2.gif' style='border:none' />" : "<img src='../Img/alert.gif' style='border:none' />";
                    if ($Antecedente == 'ATEND') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/accept.png' style='border:none' title='$titleimg'/>";
                    } else if ($Antecedente == 'VOUCH') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_red.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'POSIT' || $Antecedente == 'COINC') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/user_add.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'BUSCA') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_find.png' style='border:none' title='$titleimg'  />";
                    } else if ($Antecedente == 'OBSV') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_magnify.png' style='border:none' title='$titleimg'  />";
                    } else {
                        $Imagenes = "<img id='leyendaimg' src='../Img/info2.gif' style='border:none' title='$titleimg'/>";
                    }
                    $html .= "<td align='center'>" . $Antecedente . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                    //$Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                    $Pdf = "<img src='../Img/negro.gif' style='border:none' />";
                    //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                    //$Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                    $Detalle = "" . $Imagenes . "";
                    $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                    $html .= "<td align='center'>" . $Imprimir . "</td>";
                    //$html .= "<td align='center'></td>";
                    if ($procedencia[1] == 'INFRAESTRUCTURA') {
                        $proced = "INFRA";
                    } else {
                        $proced = $procedencia[1];
                    }
                    $html .= "<td align='center'>&nbsp;" . $proced . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                    //generado_solicitud
                    $html .= "<td align='center'>" . str_replace("OFICIO-", "", $Rows['oficio_solici']) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "<table>";
                $html .= "<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
                $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
                $html .= "</table>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
            }

            $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
            $html .= "</tbody></table>";
            $html .= "</div>";
            $html .= "<div id='DetalleSolicitante'></div>";

            //return $html;
        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            // AND g.flag = '0'
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

            $Estados = "";
            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

            /*
              $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud, g.oficio_solici FROM personas p ";
              $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
              $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '1' $Admin $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento ORDER BY p.p_idsol DESC";
             */
            //$_pagi_sql = "";
            $html .= "<table width='100%'><tr><td colspan='9' align='center'><font color='red'><b> Ingrese su criterio de B&uacute;queda !</b></font></td></tr></table>";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        // if ($Find[0] == 'Todo') {
        return $html;
    }

    /*  Formulario de Busqueda para la Emision y Certificadio historico      

    function FiltrosEmisionCertifica_historico() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>PROCEDENCIA&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='local' NAME='color' onclick='javascript:CamposEmision_filtrado(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_filtrado(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_filtrado(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>FECHA&nbsp;SOLICITUD&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='soli' onclick='javascript:CamposEmision_filtrado(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificado_historico(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";

        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>ESTADO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='esta' onclick='javascript:CamposEmision_filtrado(6)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;OFICIO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='checkbox' NAME='color' id='oficio' onclick='javascript:CamposEmision_filtrado(7)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:ocultar_filtros()' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";

        $html .= "</tr>";
        $html .= "</table>";
        return $html;
    }
     */
    function ListadoEmiteCerti_historico_1111($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";

        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            //echo "<script>alert(\"$Find[7]\")</script>";
            //echo "<script>alert(\"$Find[8]\")</script>";

            if ($Find[7] != "" && $Find[8] != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
                $criterioF = "AND p.p_fechasol >= UNIX_TIMESTAMP('$Ini 00:00:00') AND p.p_fechasol <= UNIX_TIMESTAMP('$Fin 23:59:59')";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $criterioDir = " AND p.proc_dir = '$Find[9]'";
            }

            if ($doc != "") {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$criterioDoc = " AND p.p_idsol = '$Find[10]'" ;
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }


            if ($Find[11] != "") {
                //$Estados = ($Find[11] != "ATEN") ? " AND g.flag = '0' AND p.emite = '$Find[11]'" : "AND g.flag = '1' AND DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= DATE_FORMAT(g.fecha,'%Y-%m-%d')";
                //$Admin = "AND p.cod_user = '$Find[4]'";
                $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

                if ($Find[11] != 'ATEND') {
                    $criterioEs = " AND g.flag = '0' AND p.emite = '$Find[11]'";
                } else {
                    $criterioEs = " AND p.emite = '$Find[11]' ";
                }
            } else {
                $criterioEs = "";
            }

            if ($nombre == '' && $apepat == '' && $apemat == '' && $Find[7] == "" && $Find[8] == "" && $dir == 0 && $doc == '' && $Find[11] == '') {
                $criterioImp = " and g.flag = 0";
            }

            if ($nroOfi != "") {
                $criterioofi = " AND g.oficio_solici  LIKE  '%$nroOfi%' ";
            }
            //AND p.f_val = '0'
            $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
            $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud , g.oficio_solici FROM personas p ";
            $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
            $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '1' $criterioImp $Admin $criterioN $criterioF $criterioDir $criterioDoc $criterioEs $criterioofi $filterxelmomento ORDER BY p.p_idsol DESC";




            $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            //para impresion
            $_pagi_resultImp = $_pagi_sql;
            $_pagi_result1 = parent::Query($_pagi_sql);
            $idd = 0;
            while ($rows = parent::ResultArray($_pagi_result1)) {
                if ($rows['p_idsol'] != 0) {
                    $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                    $idd++;
                }
            }
            //}
            //$_SESSION['sql'] = $idSol;
            //echo "<script>alert(\"$idSol\")</script>";
            //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
            //$html .= $this->FormImprimirPdfsFull();
            /* ---------------------------------------------------  MARCO  OBSERVAR  ------------------------------------------------- */
            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ---------------------------------------------------  MARCO  ELIMINAR  ------------------------------------------------- */
            $html .= "<div id='popupdelete' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:750px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popupdelete');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/popupdelete.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";
            /* ------------------------------------------------------------------------------------------------------------------------------ */
            require_once("Paginador.cls.php");

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<div style='width: 820px ; overflow-x:scroll'>";
            $html .= "<table id='tablaordenada'>";
            $html .= "<thead><tr>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Nro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>MATERNO&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>NOMBRES&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;DOC IDEN</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:60px;'>FECHA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>HORA&nbsp;SOL.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";


            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:40px;'>ESTADO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;PROCEDENCIA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;ID&nbsp;LOCAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;Nro&nbsp;OFI&nbsp;&nbsp;</th>";


            //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

            $html .= "</tr></thead><tbody>";

            if ($NumReg >= '1') {

                while ($Rows = parent::ResultArray($_pagi_result)) {
                    $procedencia = self::Procedencia($Rows[18]);
                    $Antecedente = $Rows[11];
                    $TipPago = ($Rows[9] == 'RECIBO-TESORERIA') ? 'TE' : substr($Rows[9], 0, 2);
                    $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                    $html .= "<td align='center'>" . $Rows[0] . "</td>";
                    //$html .= "<td>" . $Rows[1] . "</td>";

                    $html .= "<td>" . strtoupper($Rows[14]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[15]) . "</td>";
                    $html .= "<td>" . strtoupper($Rows[16]) . "</td>";

                    //$html .= "<td align='center'>" . $TipPago . "</td>";
                    $html .= "<td align='center'>" . $Rows['Docs'] . "</td>";

                    $html .= "<td align='center'>" . $Rows[3] . "</td>";
                    $html .= "<td align='center'>" . $Rows[12] . "</td>";

                    //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                    //onclick='ver_pdf_doc($Rows[0], this.href)'
                    //if ($Rows[13] == '0') {
                    $userP = self::UsuarioDescrip($Rows['cod_user']);
                    $titleimg = self::TitleDescrip($Antecedente);
                    //$Imagenes = (($Antecedente == 'PEND1') || ($Antecedente == 'PEND2')) ? "<img src='../Img/info2.gif' style='border:none' />" : "<img src='../Img/alert.gif' style='border:none' />";
                    if ($Antecedente == 'ATEND') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/accept.png' style='border:none' title='$titleimg'/>";
                    } else if ($Antecedente == 'VOUCH') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_red.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'POSIT' || $Antecedente == 'COINC') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/user_add.png' style='border:none' title='$titleimg' />";
                    } else if ($Antecedente == 'BUSCA') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_find.png' style='border:none' title='$titleimg'  />";
                    } else if ($Antecedente == 'OBSV') {
                        $Imagenes = "<img id='leyendaimg' src='../Img/Img/page_white_magnify.png' style='border:none' title='$titleimg'  />";
                    } else {
                        $Imagenes = "<img id='leyendaimg' src='../Img/info2.gif' style='border:none' title='$titleimg'/>";
                    }
                    $html .= "<td align='center'>" . $Antecedente . "<input type='hidden' size='5' name='$Rows[0]' value='" . $Antecedente . "' /></td>";
                    //$Pdf = "<a href='../pdf/certificados/" . $Rows[0] . ".pdf' target='_blank' onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href); ><img src='../Img/negro.gif' style='border:none' /></a>";
                    $Pdf = "<img src='../Img/negro.gif' style='border:none' />";
                    //$Pdf = "<a href='crear-pdf.php?id=$Rows[0]' onclick='ver_pdf_doc($Rows[0]);'><img src='../Img/negro.gif' style='border:none' /></a>";
                    //$Detalle = "<a href='#' onclick=javascript:DetallePersona('Detalles','$Rows[0]','$Rows[7]','$Rows[8]');>" . $Imagenes . "</a>";
                    $Detalle = "" . $Imagenes . "";
                    $Imprimir = (($Antecedente == 'LISTO') || ($Antecedente == 'SI')) ? $Pdf : $Detalle;
                    $html .= "<td align='center'>" . $Imprimir . "</td>";
                    //$html .= "<td align='center'></td>";
                    if ($procedencia[1] == 'INFRAESTRUCTURA') {
                        $proced = "INFRA";
                    } else {
                        $proced = $procedencia[1];
                    }
                    $html .= "<td align='center'>&nbsp;" . $proced . "&nbsp;</td>";
                    $html .= "<td align='center'>&nbsp;" . $Rows[19] . "&nbsp;</td>";
                    //generado_solicitud
                    $html .= "<td align='center'>" . str_replace("OFICIO-", "", $Rows['oficio_solici']) . "</td>";
                    $html .= "</tr>";
                }
                $html .= "<table>";
                $html .= "<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
                $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
                $html .= "</table>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
            }

            $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
            $html .= "</tbody></table>";
            $html .= "</div>";
            $html .= "<div id='DetalleSolicitante'></div>";

            //return $html; $sql = "select * from personas where id_persona = 5";
        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            // AND g.flag = '0'
            $Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";

            $Estados = "";
            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 15;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";

            /*
              $_pagi_sql = "SELECT  p.p_idsol, CONCAT(p.p_tipdocu,': ',p.p_numdocu) AS Docs, CONCAT(p.p_nombres,' ',p.p_apepat,' ',p.p_apemat) AS NombreFull,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%d-%m-%Y') AS Fecha, p.buscador, d.DES_DR, l.DESC, p.fec_pago, p.tipo_img, p.tipo_pago,p.cod_user,p.emite,";
              $_pagi_sql.= " FROM_UNIXTIME(p.p_fechasol,'%H:%i:%s') AS Hora,g.flag,p.p_apepat,p.p_apemat, p.p_nombres, p.estado, p.proc_dir, p.id_solcitud, g.oficio_solici FROM personas p ";
              $_pagi_sql.= " LEFT JOIN procedencia_direc d ON p.proc_dir = d.id LEFT JOIN procedencia_lugar l ON p.proc_lug = l.ID, generado_solicitud g  WHERE  ";
              $_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '1' $Admin $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento ORDER BY p.p_idsol DESC";
             */
            //$_pagi_sql = "";
            $html .= "<table width='100%'><tr><td colspan='9' align='center'><font color='red'><b> Ingrese su criterio de B&uacute;queda !</b></font></td></tr></table>";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";
        // if ($Find[0] == 'Todo') {
        return $html;
    }

    function RegistrarPagos($pagos) {

        foreach ($pagos as $key => $valor) {
//            $num=self::ContenidoOptienID();
//            $idsol=sprintf('%06s',($num+1)); ResultAntecedentesWS($idsol);
            //$valor['p_apepat']

            $f22_CTRIBUTO = $valor['BN02-CTRIBUTO'];
            $f22_TDOCUM = $valor['BN02-TDOCUM'];
            $f22_NDOCUM = $valor['BN02-NDOCUM'];
            $f22_STOTAL = $valor['BN02-STOTAL'];
            $f22_FMOVIM = $valor['BN02-FMOVIM'];
            $f22_HMOVIM = $valor['BN02-HMOVIM'];
            $f22_CAGENCIA = $valor['BN02-CAGENCIA'];
            $f22_CCAJERO = $valor['BN02-CCAJERO'];
            $f22_CJUZJADO = $valor['BN02-CJUZGADO'];
            $f22_DIGCHK = $valor['BN02-DIGCHK'];
            $f22_NUMSEC = $valor['BN02-NUMSEC'];

            $f22_NPAGOS = $valor['BN02-CTRIBUTO'];
            $valorTotal = substr($f22_STOTAL, 11);
            $f22_DATA1 = "";
            $f22_DATA2 = "";
            $f22_DATA3 = "";
            $f22_DATA4 = "";
            $cod_trb = str_pad($f22_CTRIBUTO, 5, "0", STR_PAD_LEFT);
            $tp_doc = $valor['BN02-CTRIBUTO'];
            $num_doc = $valor['BN02-CTRIBUTO'];
            $filter01 = $valor['BN02-CTRIBUTO'];
            $nro_reg = 0;
            $mpt_sol = $f22_STOTAL;
            $fech_f22_FMOVIM = $valor['BN02-CTRIBUTO'];
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
            $filter2 = $valor['BN02-CTRIBUTO'];
            $cod_ofi = str_pad($valor['BN02-CTRIBUTO'], 4, "0", STR_PAD_LEFT);
            $cod_caj = str_pad($valor['BN02-CTRIBUTO'], 4, "0", STR_PAD_LEFT);
            $filter3 = $f22_DATA3;
            $filter4 = $f22_DATA4;
            $llave = "$fech2$hor2$cod_ofi$cod_caj";

            if ($valor['BN02-BESTADO'] == 0) {
                $Query1 = "SELECT * FROM pagos_BN where llave='$llave' and num_sec='$num_sec'";
                $row1 = parent::Query($Query1);
                $cantidad = parent::NumReg($row1);
                if ($cantidad == 0) {
                    $sql = "INSERT INTO `pagos_BN`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
         VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
                    $retornasql = parent::Query($sql);

                    $idJ = mysql_insert_id();

                    if ($retornasql) {
                        $valor['COD-RETORNO'] = '0000'; //Se inserto OK
                        $valor['DES-RETORNO'] = 'Se inserto OK';

                        $sqlUPD = "UPDATE pagos_BN SET filter01 = '" . $valor['COD-RETORNO'] . " - " . $valor['DES-RETORNO'] . "' WHERE id = " . $idJ;
                        $querysql = parent::Query($sqlUPD);

                        $okinsert++;
                    } else {
                        $valor['COD-RETORNO'] = '0001'; //No se inserto
                        $valor['DES-RETORNO'] = 'No se inserto';
                    }
                } else {
                    //echo $error = "Ya Existe:$llave  -> $num_sec<br>";
                    $sql = "INSERT INTO `pagos_rechazados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
         VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
                    $retornasql = parent::Query($sql);

                    $valor['COD-RETORNO'] = '0001'; //No se inserto
                    $valor['DES-RETORNO'] = 'No se inserto';
                }
            } else {
                $sqlEst = "INSERT INTO `pagos_estornados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
 VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
                $retornasql = parent::Query($sqlEst);

                $valor['COD-RETORNO'] = '0001'; //No se inserto
                $valor['DES-RETORNO'] = 'No se inserto';
            }
        }

        return $valor;
    }

    function RegistrarPagosE($pagos) {

        foreach ($pagos as $key => $valor) {
//            $num=self::ContenidoOptienID();
//            $idsol=sprintf('%06s',($num+1)); ResultAntecedentesWS($idsol);
            //$valor['p_apepat']

            $f22_CTRIBUTO = $valor['BN02-CTRIBUTO'];
            $f22_TDOCUM = $valor['BN02-TDOCUM'];
            $f22_NDOCUM = $valor['BN02-NDOCUM'];
            $f22_STOTAL = $valor['BN02-STOTAL'];
            $f22_FMOVIM = $valor['BN02-FMOVIM'];
            $f22_HMOVIM = $valor['BN02-HMOVIM'];
            $f22_CAGENCIA = $valor['BN02-CAGENCIA'];
            $f22_CCAJERO = $valor['BN02-CCAJERO'];
            $f22_CJUZJADO = $valor['BN02-CJUZGADO'];
            $f22_DIGCHK = $valor['BN02-DIGCHK'];
            $f22_NUMSEC = $valor['BN02-NUMSEC'];

            $f22_NPAGOS = $valor['BN02-CTRIBUTO'];
            $valorTotal = substr($f22_STOTAL, 11);
            $f22_DATA1 = "";
            $f22_DATA2 = "";
            $f22_DATA3 = "";
            $f22_DATA4 = "";
            $cod_trb = str_pad($f22_CTRIBUTO, 5, "0", STR_PAD_LEFT);
            $tp_doc = $valor['BN02-CTRIBUTO'];
            $num_doc = $valor['BN02-CTRIBUTO'];
            $filter01 = $valor['BN02-CTRIBUTO'];
            $nro_reg = 0;
            $mpt_sol = $f22_STOTAL;
            $fech_f22_FMOVIM = $valor['BN02-CTRIBUTO'];
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
            $filter2 = $valor['BN02-CTRIBUTO'];
            $cod_ofi = str_pad($valor['BN02-CTRIBUTO'], 4, "0", STR_PAD_LEFT);
            $cod_caj = str_pad($valor['BN02-CTRIBUTO'], 4, "0", STR_PAD_LEFT);
            $filter3 = $f22_DATA3;
            $filter4 = $f22_DATA4;
            $llave = "$fech2$hor2$cod_ofi$cod_caj";


            $sql = "INSERT INTO `pagos_estornados`  (`id` , `llave` ,  `cod_trb` ,  `tp_doc` ,  `num_doc` ,  `filter01` ,  `nro_reg` , `mpt_sol`,  `fech` ,  `num_sec`,  `hor`,  `filter2` ,  `cod_ofi` ,  `cod_caj` ,  `filter3` ,  `filter4` )
            VALUES ('','$llave','$cod_trb','$tp_doc','$num_doc','$filter01','$nro_reg','$mpt_sol','$fech','$num_sec','$hor','$filter2','$cod_ofi','$cod_caj','$filter3','$filter4')";
            $retornasql = parent::Query($sql);

            //$okinsert++;
            if ($retornasql) {
                $valor['COD-RETORNO'] = '0000'; //Se inserto OK
                $valor['DES-RETORNO'] = 'Se inserto OK';

                $okinsert++;
            } else {
                $valor['COD-RETORNO'] = '0001'; //No se inserto
                $valor['DES-RETORNO'] = 'No se inserto';
            }
        }
        return $valor;
    }

    function nroImpresion() {
        $html = "<table border='0' cellpadding='2' cellspacing='2'>";
        $html .= "<tr><td colspan='2'><strong>Ingrese Codigo Impresion :</strong></td></tr>";
        $html .= "<tr>";
        $html .= "<td>N&ordm;&nbsp;:</td>";
        $html .= "<td><input class='InputText' type='text' name='codImpre' tabindex='3' id='codImpre' tabindex='1' onblur='ClearSpace(this);' size='40'/></td>";
        $html .= "</tr></table>";

        return $html;
    }

    function FlistadoServer1($Find) {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html = "<br /><div id='divFormInsc' style='width:968px;overflow:hidden'>";
        $html .= "<br><fieldset id='field'>";
        $html .= "<legend><strong><center> B&uacute;squeda de Internos </center> </strong></legend>";
        $html .= "<br /><div id='Filtros'>" . $this->FiltrosInculpados() . "";
        $html .= "<table border='0' cellpadding='2' cellspacing='2'  style='width:700px;overflow:hidden'><tr>";
        $html .= "<td>PATERNO </td>";
        $html .= "<td><input class='InputText' type='text' name='PatEmite' id='PatEmite' tabindex='1'  onblur='ClearSpace(this);ConvertMayuscula(this);' onKeyPress='return validacaracter(event)' style='width:120px' /></td>";
        $html .= "<td>MATERNO </td>";
        $html .= "<td><input class='InputText' type='text' name='MatEmite' id='MatEmite' tabindex='2'  onblur='ClearSpace(this);ConvertMayuscula(this);' onKeyPress='return validacaracter(event)' style='width:120px' /></td>";
        $html .= "<td>NOMBRE </td>";
        $html .= "<td><input class='InputText' type='text' name='NomEmite' id='NomEmite' tabindex='3' onblur='ClearSpace(this);ConvertMayuscula(this);' onKeyPress='return validacaracter(event)' style='width:120px' /></td>";
        $html .= "<td><input type='hidden' name='CajaEmision' value='2' /><input type='button' id='BuscaEmision' value='<< Buscar >>' name='EmiteNombre' onclick='javascript:FindInculpado1(this.name);' $evento  $estiloopc width:94px;' tabindex='4'></td>";

        /* $html .= "<tr>";
          $html .= "<td>PENAL </td>";
          $html .= "<td><select name='cbopenal' style='width:200px'>" . $this->listadoPenalesP() . "</select></td>";
          $html .= "<td>ESTADO </td>";
          $html .= "<td colspan='3'>ACTIVO <input class='InputText' type='radio' name='estado' id='estado' tabindex='2' value='1' style='border:0' />   INACTIVO <input class='InputText' type='radio' name='estado' style='border:0' id='estado' tabindex='3' value='2' /> TODOS <input class='InputText' type='radio' style='border:0' name='estado' id='estado' tabindex='3' checked='checked' value='3' /></td>";
          $html .= "<tr>";
         */
        $html .= "<tr>";
        $html .= "<td colspan='7'>Caracteres permitidos : ? *</td>";
        $html .= "<tr>";
        //$html .= "<tr>";
        //$html .= "<td colspan='7'>Nota: Los comodines ? ejecutan una b&uacute;squeda m&aacute;s precisa y los comodines % todas las coincidencias.</td>";
        //$html .= "<tr>";
        $html .= "</tr></table>";
        $html .= "</div>";
        $html .= "<div id='ContainerRegistro'><div id='Busquedas'></div></div>";
        $html .= "<div style='clear:both'></div><br /><div id='ListadosInternos'>" . $this->ListadoInculpado1($Find) . "</div>";
        //$html .= "<fieldset>";
        $html .= "</fieldset></div>";
        return $html;
    }

    function ListadoInculpado1($Find, $orden=0, $ordenm=0) {

        //echo "<script>alert(\"$Find[0]\")</script>";
        $criterio = "";
        if ($Find[0] == 'Nombres') {
            $Admin = ($Find[5] == '2') ? "" : "AND p.cod_user = '$Find[4]'";

            $penal = $Find[6] != 0 ? " AND PPO.COD_PENAL = " . $Find[6] . "" : "";

            $activo = $Find[7];
            if ($activo == 1) {
                $crity = " AND PPO.IND_ESTADO = 1 ";
            } elseif ($activo == 2) {
                $crity = " AND PPO.IND_ESTADO = 0 ";
            } else {
                $crity = "";
            }

            $paterno1 = str_replace("*", "%", $Find[1]);
            $materno1 = str_replace("*", "%", $Find[2]);
            $nombres1 = str_replace("*", "%", $Find[3]);

            $paterno = str_replace("?", "_", $paterno1);
            $materno = str_replace("?", "_", $materno1);
            $nombres = str_replace("?", "_", $nombres1);


            $criterio = "AND TRIM(IIMA.DES_APE_PATERNO) LIKE '$paterno%' AND TRIM(IIMA.DES_APE_MATERNO) LIKE '$materno%' AND TRIM(IIMA.DES_NOMBRES) LIKE '$nombres%'";
            $IdUsuario = $Find[4];

            $criterioOrden = "";

            //apellido paterno
            if ($orden == 1) {
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO ASC";
                $orden = 2;
            } elseif ($orden > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_PATERNO DESC";
                $orden = 1;
            }

            if ($ordenm == 1) { //apellido materno
                $title = "Descendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO ASC";
                $ordenm = 2;
            } elseif ($ordenm > 1) {
                $title = "Ascendente";
                $criterioOrden = "ORDER BY  IINM.DES_APE_MATERNO DESC";
                $ordenm = 1;
            }

            if ($Find[1] != "") {
                $critApepat = "AND MATCH(IIMA.DES_APE_PATERNO) AGAINST ('" . $Find[1] . "*' IN BOOLEAN MODE)";
            } else {
                $critApepat = "";
            }

            if ($Find[2] != "") {
                $critApemat = "AND MATCH(IIMA.DES_APE_MATERNO) AGAINST ('" . $Find[2] . "*' IN BOOLEAN MODE)";
            } else {
                $critApemat = "";
            }

            if ($Find[3] != "") {
                $critNombre = "AND MATCH(IIMA.DES_NOMBRES) AGAINST ('" . $Find[3] . "*' IN BOOLEAN MODE)";
            } else {
                $critNombre = "";
            }
            //$_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre ,
            //IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,DATE_FORMAT(IIMO.FEC_INGRESO, '%d-%m-%Y') as fecha ,DATE_FORMAT(IIMO.FEC_SALIDA, '%d-%m-%Y') AS fechas ,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
            //, (PPO.IND_ESTADO) AS estadoa FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN
            //sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON
            //IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE IIMO.COD_INCULPADO<>0 $criterio $penal $crity GROUP BY IIMO.COD_INCULPADO $criterioOrden
            //";
            //busqueda exacta
            /* $_pagi_sql1 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,
              TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre,
              IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS 'PENAL'
              FROM sip_omar.IDE_INCULPADO_MAE IIMA LEFT JOIN sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO LEFT JOIN
              sip_omar.PEN_POBLACION_PENAL  PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
              IIMA.COD_INCULPADO <> 0
              $critApepat
              $critApemat
              $critNombre
              $crity
              $penal
              AND IIMO.COD_SECUENCIAL=(SELECT MAX(A.COD_SECUENCIAL)
              FROM sip_omar.IDE_INCULPADO_MOV A WHERE A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1')";
             */
            /*$_pagi_sql1 = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,  TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre,
 IIMA.COD_TT1_SEXO,IIMA.FEC_NACIMIENTO,
 IIMA.DES_LUGAR_NAC FROM sip_omar.IDE_INCULPADO_MAE IIMA WHERE IIMA.COD_INCULPADO <> 0 $criterio";
		
		$_pagi_result = parent::Query($_pagi_sql1);
            	$NumReg1 = parent::NumReg($_pagi_result);
	    

		*/
	    $criterio1 = "AND TRIM(IINM.DES_APE_PATERNO) LIKE '$paterno%' AND TRIM(IINM.DES_APE_MATERNO) LIKE '$materno%' AND TRIM(IINM.DES_NOMBRES) LIKE '$nombres%'";

            $_pagi_sql = "SELECT STRAIGHT_JOIN IIMA.COD_INCULPADO,CONCAT(TRIM(IIMA.DES_APE_PATERNO),' ',TRIM(IIMA.DES_APE_MATERNO),' ',TRIM(IIMA.DES_NOMBRES)) AS FULLNAME,  TRIM(IIMA.DES_APE_PATERNO) as pate, TRIM(IIMA.DES_APE_MATERNO) as mate, TRIM(IIMA.DES_NOMBRES) as nombre
                            FROM sip_omar.IDE_INCULPADO_MAE IIMA WHERE IIMA.COD_INCULPADO <> 0 $criterio

            UNION (

                        SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,  TRIM(IINM.DES_APE_PATERNO) as pate, TRIM(IINM.DES_APE_MATERNO) as mate, TRIM(IINM.DES_NOMBRES) as nombre
                        FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM  INNER JOIN
                        sip_omar.IDE_INCULPADO_MOV IIMO ON IINM.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
                        IIMO.COD_INCULPADO <> 0 $criterio1 GROUP BY IINM.COD_INCULPADO

            )
            ";
	    
	    $_pagi_result = parent::Query($_pagi_sql);
            $NumReg = parent::NumReg($_pagi_result);

            
		/*
            if ($NumReg1 == 0) {
                $_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,
                     IIMA.COD_TT1_SEXO,PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO,IIMA.FEC_NACIMIENTO, IIMA.DES_LUGAR_NAC, PPO.COD_PENAL AS
                     'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM INNER JOIN sip_omar.IDE_INCULPADO_MAE IIMA ON IINM.COD_INCULPADO=IIMA.COD_INCULPADO INNER JOIN
                     sip_omar.IDE_INCULPADO_MOV IIMO ON IIMA.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON
                     IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE

                     IIMO.COD_INCULPADO <> 0
                     $critApepat
                     $critApemat
                     $critNombre
                     $crity
                     $penal
                     AND IIMO.COD_SECUENCIAL= (SELECT MAX(A.COD_SECUENCIAL) FROM sip_omar.IDE_INCULPADO_MOV A WHERE
                     A.COD_INCULPADO = IIMO.COD_INCULPADO AND A.IND_ESTADO = '1') ";
		
		$criterio1 = "AND TRIM(IINM.DES_APE_PATERNO) LIKE '$paterno%' AND TRIM(IINM.DES_APE_MATERNO) LIKE '$materno%' AND TRIM(IINM.DES_NOMBRES) LIKE '$nombres%'";

		$_pagi_sql = "SELECT STRAIGHT_JOIN IIMO.COD_INCULPADO,CONCAT(TRIM(IINM.DES_APE_PATERNO),' ',TRIM(IINM.DES_APE_MATERNO),' ',TRIM(IINM.DES_NOMBRES)) AS FULLNAME,  TRIM(IINM.DES_APE_PATERNO) as pate, TRIM(IINM.DES_APE_MATERNO) as mate, TRIM(IINM.DES_NOMBRES) as nombre, PPO.COD_PENAL,IIMO.IND_ESTADO,IIMO.COD_SECUENCIAL,IIMO.FEC_INGRESO, PPO.COD_PENAL AS 'PENAL' FROM sip_omar.IDE_INCULPADO_NOMBRES_MOV IINM  INNER JOIN
 sip_omar.IDE_INCULPADO_MOV IIMO ON IINM.COD_INCULPADO=IIMO.COD_INCULPADO INNER JOIN sip_omar.PEN_POBLACION_PENAL PPO ON IIMO.COD_INCULPADO=PPO.COD_INCULPADO WHERE
 IIMO.COD_INCULPADO <> 0 $criterio1 GROUP BY IINM.COD_INCULPADO";

            } else {
                $_pagi_sql = $_pagi_sql1;
            }
		*/
            //echo "<script>alert(\"$_pagi_sql\")</script>";
            //echo "<script>alert(\"$_pagi_sql\")</script>";
            $_pagi_result = parent::Query($_pagi_sql);
	    $NumReg = parent::NumReg($_pagi_result);
            $_pagi_conteo_alternativo = true;
            $_pagi_cuantos = 20;
            $_pagi_nav_num_enlaces = 11;    // Variables Configurables
            //$_pagi_nav_estilo = "";

            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

            $html = "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
            $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
            $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
            $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
            //$html .= "<textarea>$_pagi_sql</textarea>";

            $html .= "<div id='popUpDiv' style='display:none;width:750px;height:415px;'>";
            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:760px;'>";
            $html .= "<tr bgcolor='blue'>";
            $html .= "<td align='right'><a href='#' onclick=javascript:popup('popUpDiv');><img src='../Img/divcerrar.gif' style='border:none' alt='Cerrar' /></a></td>";
            $html .= "</tr><tr><td>";
            $html .= "<iframe src='../main/mainpopup.php' marginwidth='1' marginheight='1' scrolling='auto' frameborder='0' width='740px' height='395px' >";
            $html .= "</iframe></td></tr></table>";
            $html .= "</div>";

            require_once('Paginador.cls.php');

            $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
            $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
            $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

            $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px; overflow:hidden'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            //$html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($orden,0);' title='$title'>AP. PATERNO</a></td>";
            //$html .= "<td align='center' class='ordenpat'><a href='#' onclick='javascript:FindInternoB($ordenm,1);' title='$title'>AP. MATERNO</a></td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>AP. PATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>AP. MATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>NOMBRES</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>PENAL</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>FECHA INGRESO</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>INFORMACION</td>";
            $html .= "</tr>";

            if ($NumReg >= '1') {
                //_pagi_result = parent::Query($_pagi_sql);
                while ($Rows = parent::ResultArray($_pagi_result)) {

                    /*
                      $sqlPenalj   = "select * from sip_omar.PEN_POBLACION_PENAL where COD_INCULPADO = ".$Rows[0];
                      $queryPenalj = parent::Query($sqlPenalj);
                      $rowj         = parent::ResultArray($queryPenalj);


                      $sqlPenal = "SELECT ABREV_DES_PENAL penal FROM sip_omar.PEN_PENAL_MAE WHERE COD_PENAL = " . $rowj['COD_PENAL'];
                      $queryPenal = parent::Query($sqlPenal);
                      $row = parent::ResultArray($queryPenal);

                      $sqlPenalT = "SELECT FEC_INICIO, FEC_FINAL FROM sip_omar.IDE_INCULPADO_MOV WHERE COD_INCULPADO = " . $Rows[0];
                      $queryPenalT = parent::Query($sqlPenalT);
                      $rowT = parent::ResultArray($queryPenalT);
                     */
                    $estadop = $rowj['IND_ESTADO'] != 1 ? 'Inactivo' : 'Activo';
                    $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                    //$html .= "<td align='center'>" . $Rows[0] . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['pate']) . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['mate']) . "</td>";
                    $html .= "<td style='width:180px;'>" . strtoupper($Rows['nombre']) . "</td>";
                    //$html .= "<td style='width:180px;'>" . ucwords($row[0]) . "</td>";
                    //$html .= "<td style='width:180px;'>" . strtoupper($rowT['FEC_INICIO']) . "</td>";
                    //$html .= "<td style='width:180px'>" . strtoupper($rowT['FEC_FINAL']) . "</td>";
                    $html .= "<td style='width:180px' align='center'><input type='checkbox' value = '$Rows[0]' id = 'arrayInculpado' name = 'arrayInculpado[]' /> </td>";
                    $html .= "</tr>";
                }

                //$html .="<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td></tr>";
                //$html .= "<tr><td colspan='6'>&nbsp;</td>";
                $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
                $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
                $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
                $html .= "<tr><td colspan='7' align='right'><input type='button' value='Ver Informacion' onclick='ver_info();' id='infom' $estiloopc $evento /></td>";

                $html .= "<tr><td colspan='7'><div id='info'></div></td>";

                $html .= "<tr><td colspan='7'>" . $_pagi_navegacion . "</td>";
                //$html .= "<td colspan='3' align='center'>";
                //$html .= "</td>";
                $html .= "</tr>";
            } else {
                $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos no encontrados!</b></font></td></tr>";
            }
            //$html .="<tr><td colspan='9' align='left'>";
            //$html .="<div id='datos'>";
            //$html .="</div>";
            //$html .="</td></tr></table>";
            $html .="</table>";
        } else {
            $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];


            $html = "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px;'><tr>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>Id</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>AP. PATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>AP. MATERNO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>NOMBRES</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>PENAL</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>FECHA INGRESO</td>";
            //$html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>FECHA EGRESO</td>";
            $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px;background:#dfeaee'>INFORMACION</td>";
            $html .= "</tr>";
            $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
            $html .="</table>";
        }
        return $html;
    }

    function FlistadoEstadisticas($Find) {

        $criterioFecha = "";
        if($Find[0] == 'fechas') {
            $fechaI =  $Find[1];
            $fechaF =  $Find[2];

            $arrayF = explode("-",$fechaI);
            $fechaI = $arrayF[2]."-".$arrayF[1]."-".$arrayF[0];

            $arrayFf = explode("-",$fechaF);
            $fechaF = $arrayFf[2]."-".$arrayFf[1]."-".$arrayFf[0];

            $criterioFecha = "AND (FROM_UNIXTIME(p.p_fechasol, '%Y-%m-%d') >= '".$fechaI."'  AND FROM_UNIXTIME(p.p_fechasol, '%Y-%m-%d') <= '".$fechaF."')";

        }
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        
        $anio = date('Y');
        $html .= "<br /><div id='divFormInsc' style='width:968px; overflow:hidden'>";
        $html .= "<fieldset id='field'>";
        $html .= "<legend><strong><center> Reporte Estadistico X Sucursal</center> </strong></legend>";
        $html .= "<div id='ListadosInternos'>";
        $html .= "<table style='border: 1px solid #CCCCCC;border-collapse: collapse;line-height: 15px; font-size:10px; width:800px; overflow:hidden'>";

        $html .= "<tr>";
        $html .= "<td>Fecha Inicio</td>";
        $html .= "<td><input type='text' name='fecha1' /> <input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.fecha1,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='1' /></td>";
        $html .= "<td>Fecha Final</td>";
        $html .= "<td><input type='text' name='fecha2' /> <input type='button' value='&#8225;&#8225;' onclick='javascript:displayCalendar(document.bigform.fecha2,this)' onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff'; onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';  style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;width:40px;' tabindex='1' /></td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan='4' align='right'><input type='hidden' name='CajaEmision' value='2' /><input type='button' id='BuscaEmision' value='<< Buscar >>' name='EmiteNombre' onclick='javascript:FindEstadistica(this.name);' $evento  $estiloopc width:94px;' tabindex='4'></td>";
        $html .= "</tr>";
        
        $html .= "<tr>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:180px; background:#dfeaee'>SUCURSAL</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px; background:#dfeaee'>Certif. Registrados</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px; background:#dfeaee'>Certif. Atendidos.</td>";
        $html .= "<td align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:80px; background:#dfeaee'>Certif. x Atender.</td>";
        $html .= "</tr>";

        /*$sqlest = "SELECT p.proc_dir, s.DES_DR, COUNT(p.proc_dir) AS emitidos, c.atendidos, a.poraten FROM personas p, sedes s, catendidos c, poratender a WHERE
        p.proc_dir = s.id AND c.proc_dir = p.proc_dir AND a.proc_dir = p.proc_dir AND p.f_val = '0'
        GROUP BY s.DES_DR";
        */
        $sqlest = "SELECT p.proc_dir, s.DES_DR, COUNT(p.proc_dir) AS emitidos FROM personas p, sedes s  WHERE
	p.proc_dir = s.id AND p.f_val = '0' $criterioFecha
	GROUP BY s.DES_DR";
       // echo "<script>alert(\"$sqlest\")</script>";
        $_pagi_result = parent::Query($sqlest);
        $NumReg = parent::NumReg($_pagi_result);

        if ($NumReg > 0) {
            while ($Rows = parent::ResultArray($_pagi_result)) {
                
                if($fechaI!='' && $fechaF!=''){
                    $criterioFechaj = "AND (FROM_UNIXTIME(pe.p_fechasol, '%Y-%m-%d') >= '".$fechaI."'  AND FROM_UNIXTIME(pe.p_fechasol, '%Y-%m-%d') <= '".$fechaF."')";
                    $criterioFechajj = "AND (FROM_UNIXTIME(pr.p_fechasol, '%Y-%m-%d') >= '".$fechaI."'  AND FROM_UNIXTIME(pr.p_fechasol, '%Y-%m-%d') <= '".$fechaF."')";
                }
                $sqlAte = "SELECT
                    `pe`.`proc_dir`   AS `proc_dir`,
                    COUNT(`pe`.`proc_dir`) AS `atendidos`,
                    `pe`.`p_fechasol` AS `fech`
                    FROM (`personas` `pe`
                    JOIN `sedes` `s`)
                    WHERE ((`pe`.`proc_dir` = `s`.`id`)
                    AND (`pe`.`f_val` = '0')
                    $criterioFechaj 
                    AND pe.proc_dir = ".$Rows[0]." 
                    AND ((`pe`.`emite` = 'ATEND')
                    OR (`pe`.`emite` = 'POS-A')
                    OR (`pe`.`emite` = 'INVAL')))
                    GROUP BY `s`.`DES_DR` ";
                 $query = parent::Query($sqlAte);
                 $RowsA = parent::ResultArray($query);

                 $sqlPAte = "SELECT
                      `pr`.`proc_dir`   AS `proc_dir`,
                      COUNT(`pr`.`proc_dir`) AS `poraten`,
                      `pr`.`p_fechasol` AS `fecha`
                    FROM (`personas` `pr`
                       JOIN `sedes` `s`)
                    WHERE ((`pr`.`proc_dir` = `s`.`id`)
                           AND (`pr`.`f_val` = '0')
                           $criterioFechajj
                 AND pr.proc_dir = ".$Rows[0]."
                    AND ((`pr`.`emite` = 'LISTO')
                         OR (`pr`.`emite` = 'VOUCH')
                         OR (`pr`.`emite` = 'BUSCA')
                         OR (`pr`.`emite` = 'POSIT')
                         OR (`pr`.`emite` = 'COINC')
                         OR (`pr`.`emite` = 'OBSV')))
                    GROUP BY `s`.`DES_DR`";
                 $queryP = parent::Query($sqlPAte);
                 $RowsPA = parent::ResultArray($queryP);
                 
                $html .= "<tr onMouseOver=this.style.background='#CCFF33' onMouseOut=this.style.background='none' >";
                $html .= "<td style='width:180px;'><a href='javascript:;' onclick='ver_detalle_sede($Rows[0], $anio)'>" . strtoupper($Rows['DES_DR']) . "</a></td>";
                $html .= "<td style='width:80px;text-align:center'>" . strtoupper($Rows['emitidos']) . "</td>";
                $html .= "<td style='width:80px;text-align:center'>" . strtoupper($RowsA['atendidos']) . "</td>";
                $html .= "<td style='width:80px;text-align:center'>" . strtoupper($RowsPA['poraten']) . "</td>";
                $html .= "</tr>";
            }
        }

        $html .= "</table>";

        $html .= "</div>";

        $html .= "<div id='detallado'></div>";
        //$html .= "</div>";

        $html .= "</fieldset>";
        $html .= "</div>";
        return $html;
    }

    function edad($stamp){
	$c = date("Y",$stamp);
	$b = date("m",$stamp);
	$a = date("d",$stamp);

	$anos = date("Y")-$c;

	if(date("m")-$b > 0){

	}elseif(date("m")-$b == 0){

		if(date("d")-$a < 0){

			$anos = $anos-1;

		}

	}else{

		$anos = $anos - 1;

	}

	return $anos;
    }


    //  Funcion para Mostrar las Solicitudes y Emision de Documentos
    function historialEstados($Find) {
        $html = "<br /><div id='ViCss' style='width:840px;'>";
        $html .= "<div id='Filtros3'>" . $this->FiltrosHistorialEstado() . "</div>";
        $html .= "<div id='ContainerRegistro2'><div id='Xprocedencia' style='display: none;'>" . self::Usuarios() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        $html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div>";
        $html .= "<div id='capaoficio' style='display: none;'>" . self::nrooficio() . "</div>";
        $html .= "<div id='impresionj' style='display: none;'>" . self::nroImpresion() . "</div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoHistorialEstados($Find) . "</div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .= "</div>";
        return $html;
    }

    function ListadoHistorialEstados($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];

        $codImpre = $Find[14];
        $cweb = $Find[15];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";

        $diaj  = date('d');
        $mesj  = date('m');
        $anioj = date('Y');
        $diferenciaj = mktime(00,00,00,$mesj,$diaj-3,$anioj);
        $fechaj      =  date('Y-m-d',$diferenciaj);


        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {
                
                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                $criterioDir = " AND po.idUsuario = '$Find[9]'";
            }

            if ($doc != "") {
                
                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }

            $_pagi_sql = "SELECT po.id, po.p_idsol, po.idUsuario, po.texto_observado, (po.fecha_registro) AS fecha FROM personas_observadas po, personas p WHERE po.id <> 0 AND po.p_idsol = p.p_idsol $criterioN $criterioDoc $criterioDir ORDER BY po.id DESC";

        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            //$Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            //$Estados = "";
            //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";

            $_pagi_sql = "SELECT po.id, po.p_idsol, po.idUsuario, po.texto_observado, DATE_FORMAT(po.fecha_registro, '%d-%m-%Y %H:%i:%s') AS fecha FROM
personas_observadas po, personas p WHERE po.id <> 0 AND po.p_idsol = p.p_idsol
ORDER BY po.id DESC";
            //$_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento $menosVouch and p.emite != 'ATEND' and p.emite != 'POS-A' ORDER BY p.p_idsol DESC";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        //para impresion
        $_pagi_resultImp = $_pagi_sql;
        $_pagi_result1 = parent::Query($_pagi_sql);
        $idd = 0;
        while ($rows = parent::ResultArray($_pagi_result1)) {
            if ($rows['p_idsol'] != 0) {
                $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                $idd++;
            }
        }
        //$_SESSION['sql'] = $idSol;
        //echo "<script>alert(\"$idSol\")</script>";
        //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();
        
        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='width: 820px ; overflow-x:scroll'>";
        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:100px;'>NRO SERVER</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>PERSONA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>USUARIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:200px;'>&nbsp;ESTADOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:160px;'>FECHA&nbsp;Y.&nbsp;HORA&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        
        
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
      


        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "</tr></thead><tbody>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $procedencia = self::Procedencia($Rows[18]);
                $Antecedente = $Rows[11];
                $userP = self::UsuarioDescripCompleto($Rows['idUsuario']);
                $persP = self::personasNombre($Rows['p_idsol']);
		
		$sinnada = str_replace("/","-",$Rows['texto_observado']);
		$sinnada = str_replace("."," ",$sinnada);
		$sinnada = str_replace("_"," ",$sinnada);
		$sinnada = str_replace("º"," ",$sinnada);
		$sinnada = str_replace("'"," ",$sinnada);
		$sinnada = str_replace('"'," ",$sinnada);
                //personasNombre

                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                //$html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows['p_idsol']) . "</td>";
                $html .= "<td>" . strtoupper($persP) . "</td>";
                $html .= "<td>" . strtoupper($userP) . "</td>";
                $html .= "<td>" . strtoupper(substr($sinnada,0,100)) . "</td>";

                //$html .= "<td align='center'>" . $TipPago . "</td>";
                $html .= "<td align='center'>" . $Rows['fecha'] . "</td>";
                $html .= "<td align='center'>&nbsp;</td>";

                //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                //onclick='ver_pdf_doc($Rows[0], this.href)'
                //if ($Rows[13] == '0') {
                
                
                
               
                $html .= "</tr>";
            }
            $html .= "<table>";
            $html .= "<tr><td colspan='4' align='right'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
            $html .= "</table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
        $html .= "</tbody></table>";
        $html .= "</div>";
        $html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }


    function FiltrosHistorialEstado() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>USUARIOS&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' id='local' NAME='color' onclick='javascript:CamposEmision_filtradojj(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_filtradojj(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_filtradojj(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:CamposEmision_filtradojj(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificadoEstado(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";
        
        $html .= "</tr>";

        //$html .= "<tr>";
        //$html .= "</tr>";

        

        $html .= "</table>";
        return $html;
    }
    
    function personasNombre($idUsuario) {
        $sql = "SELECT p_idsol, CONCAT(p_apepat,' ',p_apemat,' ',p_nombres) AS nombre FROM personas WHERE p_idsol = '$idUsuario';";
        $query = parent::Query($sql);
        $Row = parent::ResultAssoc($query);
        $idUsuario = $Row['p_idsol'];
        $usu_logi = $Row['nombre'];
        return $usu_logi;
    }
    
    function historialLog($Find) {
        $html = "<br /><div id='ViCss' style='width:840px;'>";
        //$html .= "<div id='Filtros3'>" . $this->FiltrosHistorialLog() . "</div>";
        $html .= "<div id='ContainerRegistro2'><div id='Xprocedencia' style='display: none;'>" . self::Usuarios() . "</div><div id='Busquedas'></div>";
        $html .= "<div id='nombrescj' style='display: none;'>" . self::busquedaNombres() . "</div>";
        $html .= "<div id='nrodocj' style='display: none;'>" . self::nrodocu() . "</div>";
        $html .= "<div id='fechass' style='display: none;'>" . self::fechas_filtros() . "</div>";
        $html .= "<div id='estadoss' style='display: none;'>" . self::estados() . "</div>";
        $html .= "<div id='capaoficio' style='display: none;'>" . self::nrooficio() . "</div>";
        $html .= "<div id='impresionj' style='display: none;'>" . self::nroImpresion() . "</div></div>";
        $html .= "<div id='ListadosInternos'>" . $this->ListadoHistorialLog($Find) . "</div>";
        $html .="<input type='hidden' id='nombreestado' name='nombreestado' />";
        $html .= "</div>";
        return $html;
    }

    function FiltrosHistorialLog() {
        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;float:right; ";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";
        $html .= "<table border='0' cellpadding='1' cellspacing='1'>";

        $html .= "<tr>";
        $html .= "<td>&nbsp;&nbsp;</td>";
        $html .= "<td>USUARIOS&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' id='local' NAME='color' onclick='javascript:CamposEmision_filtradojj(1)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>NOMBRES&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' id='nombcheck' NAME='color' onclick='javascript:CamposEmision_filtradojj(2)'></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>Nro.&nbsp;DOCUMENTO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='radio' NAME='color' id='nrodoc' onclick='javascript:CamposEmision_filtradojj(3)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td>TODO&nbsp;:</td>";
        $html .= "<td><INPUT TYPE='RADIO' NAME='color' id='color' onclick='javascript:CamposEmision_filtradojj(4)' ></td>";
        $html .= "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $html .= "<td><input type='hidden' name='CajaEmision' /></td>";

        $html .= "<td style='width:120px; text-align:right'>";
        $html .= "<input type='button' disabled='true' id='BuscaEmision' value='&laquo;Buscar&raquo;' onclick='javascript:FindCertificadoEstado(this.name);' $evento  $estiloopc width:60px;' tabindex='4'>";
        $html .= "</td>";

        $html .= "</tr>";

        //$html .= "<tr>";
        //$html .= "</tr>";



        $html .= "</table>";
        return $html;
    }

    function ListadoHistorialLog($Find) {

        $filterxelmomento = " AND p.p_nombres != '' AND p.p_apepat != '' AND p.p_apemat !='' ";
        $nombre = $Find[3];
        $apepat = $Find[1];
        $apemat = $Find[2];

        $Ini = $this->FechaMysql($Find[7]);
        $Fin = $this->FechaMysql($Find[8]);

        $dir = $Find[9];
        $doc = $Find[10];
        $nroOfi = $Find[13];

        $codImpre = $Find[14];
        $cweb = $Find[15];

        $criterioN = "";
        $criterioF = "";
        $criterioDir = "";
        $criterioDoc = "";
        $criterioofi = "";

        $diaj  = date('d');
        $mesj  = date('m');
        $anioj = date('Y');
        $diferenciaj = mktime(00,00,00,$mesj,$diaj-3,$anioj);
        $fechaj      =  date('Y-m-d',$diferenciaj);
        $fechadia = date('Y-m-d');
        //$fechadia = '2011-12-12';

        if ($Find[0] == 'Todo') {
            if ($nombre != '' || $apepat != '' || $apemat != '') {

                $criterioN = "AND p.p_nombres LIKE '$nombre%' AND p.p_apepat LIKE '$apepat%' AND p.p_apemat LIKE '$apemat%'";
            }

            if ($dir != 0) {
                //$Admin = "AND p.cod_user = '$Find[4]'";
                //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";
                $criterioDir = " AND po.idUsuario = '$Find[9]'";
            }

            if ($doc != "") {

                if ($Find[12] != "") {
                    $criterioDoc = " AND p.id_solcitud = '$Find[12]'";
                } else {
                    $criterioDoc = " AND p.p_idsol = '$Find[10]'";
                }
                //AND p.id_solcitud = '$Find[5]'
            }

            $_pagi_sql = "SELECT po.id, po.p_idsol, po.idUsuario, po.texto_observado, (po.fecha_registro) AS fecha FROM personas_observadas po, personas p WHERE po.id <> 0 AND po.p_idsol = p.p_idsol $criterioN $criterioDoc $criterioDir ORDER BY po.id DESC";

        } else {
            //$Admin = "AND p.cod_user = '$Find[0]'";
            //$Admin = ($Find[1] == '2') ? "" : "AND p.cod_user = '$Find[2]'";
            //$Estados = "";
            //$menosVouch = " AND p.p_idsol not in (select pp.p_idsol from personas pp where pp.emite = 'VOUCH' AND FROM_UNIXTIME(pp.p_fechasol, '%Y-%m-%d') <= '".$fechaj."') ";

            $_pagi_sql = "SELECT * FROM log WHERE axion LIKE '%Fin Web Service BN%' AND
 DATE_FORMAT(fec_hor, '%Y-%m-%d') = '".$fechadia."' ORDER BY id_log DESC";
            //$_pagi_sql.= " p.p_idsol = g.id_generado AND p.f_val = '0' $Admin AND g.flag = '0' $criterioN $criterioF $criterioDir $criterioDoc $Estados $criterioofi $filterxelmomento $menosVouch and p.emite != 'ATEND' and p.emite != 'POS-A' ORDER BY p.p_idsol DESC";
        }

        //echo "<script>alert(\"$_pagi_sql\")</script>";
        //$_SESSION['sql'] = $_pagi_sql;
        //global $ssql_excel;
        //$_SESSION['sqlexcel'] = $_pagi_sql;
        //$ssql_excel = $_pagi_sql;
        //echo "<script>alert(\"$_pagi_sql\")</script>";

        $_pagi_result = parent::Query($_pagi_sql);
        $NumReg = parent::NumReg($_pagi_result);

        //para impresion
        $_pagi_resultImp = $_pagi_sql;
        $_pagi_result1 = parent::Query($_pagi_sql);
        $idd = 0;
        while ($rows = parent::ResultArray($_pagi_result1)) {
            if ($rows['p_idsol'] != 0) {
                $idSol.= ( $idd == 0 ? '' : ',') . $rows['p_idsol'];
                $idd++;
            }
        }
        //$_SESSION['sql'] = $idSol;
        //echo "<script>alert(\"$idSol\")</script>";
        //$html = "<input type ='hidden' name='consulta' value='$_pagi_sql' />";

        $_pagi_conteo_alternativo = true;
        $_pagi_cuantos = 15;
        $_pagi_nav_num_enlaces = 11;    // Variables Configurables
        //$_pagi_nav_estilo = "";
        $_pagi_actual = (!isset($_REQUEST['_pagi_actual'])) ? 1 : $_REQUEST['_pagi_actual'];

        $html .= "<INPUT TYPE='hidden' name='_pagi_actual' id='_pagi_actual' value='$_pagi_actual'>";
        $html .= "<INPUT TYPE='hidden' name='_pagi_enlace' id='_pagi_enlace' value='" . $_SERVER['PHP_SELF'] . "'>";
        $html .= "<INPUT TYPE='hidden' name='IdImpresos' id='IdImpresos' />";
        $html .= "<INPUT TYPE='hidden' name='FechaPagoDetalle' id='FechaPagoDetalle' />";
        $html .= "<INPUT TYPE='hidden' name='NumPagoDetalle' id='NumPagoDetalle' />";
        //$html .= $this->FormImprimirPdfsFull();

        require_once("Paginador.cls.php");

        $estiloopc = "style='border:1px #666666 solid; background-color:#E6E6E6; font-size:11px; cursor:pointer;";
        $evento = "onMouseover=this.style.backgroundColor='#666666';this.style.color='#ffffff';";
        $evento .= " onMouseout=this.style.backgroundColor='#E6E6E6';this.style.color='#000000';";

        $html .= "<div style='width: 820px ; overflow-x:scroll'>";
        $html .= "<table id='tablaordenada'>";
        $html .= "<thead><tr>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:50px;'>ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:110px;'>DOCUMENTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";
        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:450px;'>DESCRIPCION</th>";

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:120px;'>FECHA Y HORA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        

        $html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";
        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;&nbsp;</th>";



        //$html .= "<th align='center' style='border: 1px solid #CCCCCC;border-collapse: collapse;width:20px;'>&nbsp;OFICIO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>";

        $html .= "</tr></thead><tbody>";

        if ($NumReg >= '1') {

            while ($Rows = parent::ResultArray($_pagi_result)) {
                $procedencia = self::Procedencia($Rows[18]);
                $Antecedente = $Rows[11];
                $userP = self::UsuarioDescripCompleto($Rows['idUsuario']);
                $persP = self::personasNombre($Rows['p_idsol']);
                //personasNombre

                $html .= "<tr onMouseOver=this.style.background='#b4d4eb' onMouseOut=this.style.background='none' >";
                $html .= "<td align='center'>" . $Rows[0] . "</td>";
                //$html .= "<td>" . $Rows[1] . "</td>";

                $html .= "<td>" . strtoupper($Rows['axion']) . "</td>";
                $html .= "<td>" . strtoupper($Rows['fec_hor']) . "</td>";
                
                $html .= "<td align='center'>&nbsp;</td>";

                //onclick=javascript:ImpresosPDF('" . $Rows[0] . "',this.href);
                //onclick='ver_pdf_doc($Rows[0], this.href)'
                //if ($Rows[13] == '0') {




                $html .= "</tr>";
            }
            $html .= "<table width='100%'>";
            $html .= "<tr><td colspan='4' align='left'><input type='hidden' name='NumRegistro' id='NumRegistro' value='$contador' /></td><td colspan='6' class='paginac'>" . $_pagi_navegacion . "</td></tr>";
            $html .= "<tr><td colspan='10'>&nbsp;</td></tr>";
            $html .= "<tr><td colspan='10' align='right'><input type='button' value='Enviar Mensaje' onclick='javascript:enviar_email();' /> </td></tr>";
            $html .= "</table>";
        } else {
            $html .= "<tr><td colspan='9' align='center'><font color='red'><b>Datos No Encontrados !</b></font></td></tr>";
        }
        $html .="<tr><td><input type='hidden' value='$idSol' name='idsoli' id='idsoli' /><input type='hidden' value='$Find[11]' name='estadoAtendido' /></td></tr>";
        $html .= "</tbody></table>";
        $html .= "</div>";
        $html .= "<div id='DetalleSolicitante'></div>";
        return $html;
    }
    
    function EjecutarPendientes() {
        $mes = date('m');
        $year = date('Y');
        $dia = date('d');

        $fechaActual = mktime(0,0,0, $mes, $dia - 1, $year);
        $fechaA =  date('Y-m-d',$fechaActual);

        $KryRob = parent::Query("UPDATE personas SET buscador = '1' WHERE buscador = 0 AND proc_dir > 100 AND FROM_UNIXTIME(p_fechasol, '%Y-%m-%d') = '".$fechaA."' "); //ajol
        
    }

        
}

?>
