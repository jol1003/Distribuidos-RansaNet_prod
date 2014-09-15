<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css') ?>/styles.css" media="screen"/>
<link type="text/css" href="<?= $this->config->item('js') ?>/carousel/css/jcarousel.css" rel="stylesheet" charset="utf-8" />
<link type="text/css" href="<?= $this->config->item('js') ?>/token/css/token-input-advanced.css" rel="stylesheet" charset="utf-8" />

<script type="text/javascript" language="javascript" src="<?= $this->config->item('js') ?>/data/json_prodis.json"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/jquery-1.8.2.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->config->item('js') ?>/fjs.extras.js" charset="utf-8"></script><script type="text/javascript" src="http://localhost/midepa/assets/js/carousel/jquery.jcarousel.js" charset="utf-8"></script>
<script type="text/javascript" src="http://localhost/midepa/assets/js/token/jquery.tokeninput.min.js" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">var c=0,sw=0;function mycarousel_initCallback(carousel){carousel.stopAuto();};
function mycarousel_itemFirstInCallback(carousel,item,idx,state){if(state=="init"||sw==1){}else{$(".jcarousel-control a").attr("class","");c++;if(c==3){c=0;};$(".jcarousel-control").find("a").eq(c).attr("class","sel");};sw=0;}; 
$(document).ready(function(){$("#mycarousel").jcarousel({auto:3,initCallback:mycarousel_initCallback,itemFirstInCallback:mycarousel_itemFirstInCallback,wrap:"circular",buttonNextHTML:null,buttonPrevHTML:null});

$("#txtbus").tokenInput([{"n":"CHACHAPOYAS-CHACHAPOYAS","i":"1"},{"n":"CHACHAPOYAS-ASUNCION","i":"2"},{"n":"CHACHAPOYAS-BALSAS","i":"3"},{"n":"CHACHAPOYAS-CHETO","i":"4"},{"n":"CHACHAPOYAS-CHILIQUIN","i":"5"},{"n":"CHACHAPOYAS-CHUQUIBAMBA","i":"6"},{"n":"CHACHAPOYAS-GRANADA","i":"7"},{"n":"CHACHAPOYAS-HUANCAS","i":"8"},{"n":"CHACHAPOYAS-LA JALCA","i":"9"},{"n":"CHACHAPOYAS-LEIMEBAMBA","i":"10"},{"n":"CHACHAPOYAS-LEVANTO","i":"11"},{"n":"CHACHAPOYAS-MAGDALENA","i":"12"},{"n":"CHACHAPOYAS-MARISCAL CASTILLA","i":"13"},{"n":"CHACHAPOYAS-MOLINOPAMPA","i":"14"},{"n":"CHACHAPOYAS-MONTEVIDEO","i":"15"},{"n":"CHACHAPOYAS-OLLEROS","i":"16"},{"n":"CHACHAPOYAS-QUINJALCA","i":"17"},{"n":"CHACHAPOYAS-SAN FRANCISCO DE DAGUAS","i":"18"},{"n":"CHACHAPOYAS-SAN ISIDRO DE MAINO","i":"19"},{"n":"CHACHAPOYAS-SOLOCO","i":"20"},{"n":"CHACHAPOYAS-SONCHE","i":"21"},{"n":"LIMA-LIMA","i":"22"},{"n":"LIMA-ANCON","i":"23"},{"n":"LIMA-ATE","i":"24"},{"n":"LIMA-BARRANCO","i":"25"},{"n":"LIMA-BRE\u00d1A","i":"26"},{"n":"LIMA-CARABAYLLO","i":"27"},{"n":"LIMA-CHACLACAYO","i":"28"},{"n":"LIMA-CHORRILLOS","i":"29"},{"n":"LIMA-CIENEGUILLA","i":"30"},{"n":"LIMA-COMAS","i":"31"},{"n":"LIMA-EL AGUSTINO","i":"32"},{"n":"LIMA-INDEPENDENCIA","i":"33"},{"n":"LIMA-JESUS MARIA","i":"34"},{"n":"LIMA-LA MOLINA","i":"35"},{"n":"LIMA-LA VICTORIA","i":"36"},{"n":"LIMA-LINCE","i":"37"},{"n":"LIMA-LOS OLIVOS","i":"38"},{"n":"LIMA-LURIGANCHO","i":"39"},{"n":"LIMA-LURIN","i":"40"},{"n":"LIMA-MAGDALENA DEL MAR","i":"41"},{"n":"LIMA-PUEBLO LIBRE","i":"42"},{"n":"LIMA-MIRAFLORES","i":"43"},{"n":"LIMA-PACHACAMAC","i":"44"},{"n":"LIMA-PUCUSANA","i":"45"},{"n":"LIMA-PUENTE PIEDRA","i":"46"},{"n":"LIMA-PUNTA HERMOSA","i":"47"},{"n":"LIMA-PUNTA NEGRA","i":"48"},{"n":"LIMA-RIMAC","i":"49"},{"n":"LIMA-SAN BARTOLO","i":"50"},{"n":"LIMA-SAN BORJA","i":"51"},{"n":"LIMA-SAN ISIDRO","i":"52"},{"n":"LIMA-SAN JUAN DE LURIGANCHO","i":"53"},{"n":"LIMA-SAN JUAN DE MIRAFLORES","i":"54"},{"n":"LIMA-SAN LUIS","i":"55"},{"n":"LIMA-SAN MARTIN DE PORRES","i":"56"},{"n":"LIMA-SAN MIGUEL","i":"57"},{"n":"LIMA-SANTA ANITA","i":"58"},{"n":"LIMA-SANTA MARIA DEL MAR","i":"59"},{"n":"LIMA-SANTA ROSA","i":"60"},{"n":"LIMA-SANTIAGO DE SURCO","i":"61"},{"n":"LIMA-SURQUILLO","i":"64"},{"n":"LIMA-VILLA EL SALVADOR","i":"65"},{"n":"LIMA-VILLA MARIA DEL TRIUNFO","i":"66"}],{theme:"advanced",tokenLimit:3,resultsLimit:50});});</script>

<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php
$this->load->view('estructura/top');
?>

<div id="contenedor" class="ins">
<div class="ubi"><a href="<?= $this->config->item('base_url') ?>/home/">Inicio</a> | Reg&iacute;strate</div>

<div class="its">


  <div class="mod"><div class="ich">Ventajas de colocar un anuncio</div></div>

  <div class="mod">Registr&aacute;ndote como usuario particular podr&aacute;s acceder a estos servicios Gratis Solo tienes que llenar este formulario y Listo !!!</div>

  <div class="mod">Abrir una cuenta de Usuario Particular Te permite publicar anuncios, subir fotos, revisar solicitudes u ofertas de personas interesadas en tu anuncio, ver tu lista de anuncios publicados, recibir alertas de expiraci&oacute;n de tus anuncios, publicar tus datos de contacto, localizar tu propiedad en un mapa para su f&aacute;cil ubicacion por parte de los interesados etc.</div>

  
  <div class="frr">

  <form method="post" id="frmReg" action="<?= $this->config->item('base_url') ?>/home/insertarregistro" enctype="multipart/form-data">

    <div class="mod"><div class="lbl">Tipo de registro</div><div class="flef"><select name="cboreg" id="cboreg" class="txt txg"><option value="1" selected="selected">Persona natural</option><option value="2">Empresa</option></select></div><div class="flot"></div></div>

    <div id="dvreg">

  	  <div class="izql">

      	<div class="mod"><div class="lbl">Nombres</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>

        <div class="mod"><div class="lbl">DNI</div><div class="flef"><input type="text" name="txtdni" id="txtdni" class="txt" maxlength="8" /></div><div class="flot"></div></div>

        <div class="mod"><div class="lbl">Contrase&ntilde;a</div><div class="flef"><input type="password" name="txtpwd" id="txtpwd" class="txt txg" /></div><div class="flot"></div></div>

  	  </div>

  	  <div class="izql izqln">

  	  	<div class="mod"><div class="lbl">Apellidos</div><div class="flef"><input type="text" name="txtape" id="txtape" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>

        <div class="mod"><div class="lbl">Email</div><div class="flef"><input type="text" name="txtmai" id="txtmai" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>

        <div class="mod"><div class="lbl">Repetir contrase&ntilde;a</div><div class="flef"><input type="password" name="txtpwr" id="txtpwr" class="txt txg" /></div><div class="flot"></div></div>

  	  </div>

   	  <div class="flot"></div>

    </div>

    <div class="its"><div class="slef"><input type="checkbox" name="chkbol" value="1" /></div><div class="zlef">Deseo recibir informaci&oacute;n adicional vía email</div><div class="slef"><input type="checkbox" name="chkter" id="chkter" value="1" /></div><div class="flef">Acepto los t&eacute;rminos y condiciones de uso (<a href="">Ver t&eacute;rminos aqui</a>)</div><div class="flot"></div></div>

    <div class="btn btx"><span><input type="submit" name="btnok" value="Enviar" /></span></div>

  </form>

  </div>


</div>

</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
