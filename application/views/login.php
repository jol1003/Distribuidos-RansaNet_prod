<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css') ?>/styles.css" media="screen"/>
<link type="text/css" href="<?= $this->config->item('js') ?>/carousel/css/jcarousel.css" rel="stylesheet" charset="utf-8" />
<link type="text/css" href="<?= $this->config->item('js') ?>/token/css/token-input-advanced.css" rel="stylesheet" charset="utf-8" />

<script type="text/javascript" language="javascript" src="<?= $this->config->item('js') ?>/data/json_prodis.json"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/jquery-1.8.2.min.js" charset="utf-8"></script>

<script type="text/javascript" src="http://localhost/midepa/assets/js/fjs.extras.js" charset="utf-8"></script><script type="text/javascript" src="http://localhost/midepa/assets/js/carousel/jquery.jcarousel.js" charset="utf-8"></script>
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
<div class="ubi"><a href="">Inicio</a> | Login</div>
<div class="its">
  <div class="izql">
  	<div class="mad"><div class="suv">&iquest;A&uacute;n no tienes cuenta en Midepa.pe?</div></div>
  	<div class="mad">Elige una de las 2 alternativas:</div>
    <div class="itg">
  	  <div class="mod">1. Soy un usuario particular y quiero publicar un anuncio.</div>
      <div class="ahv"><a href="http://localhost/midepa/registro/">Reg&iacute;strate gratis</a></div>
    </div>
    <div class="its">
      <div class="mod">2. Soy una empresa inmobiliaria o corredor independiente y quiero publicar mi cartera de inmuebles.</div>
      <div class="ahv"><a href="http://localhost/midepa/registro/">Reg&iacute;strate gratis</a></div>
    </div>
  </div>
  <div class="bkl">
  	<div class="bkli">
      <div class="suv">Puedes acceder automáticamente a Midepa.pe</div>
      <form method="post" action="<?= $this->config->item('base_url') ?>/home/verificar_login">
      	<div class="mod">Correo electr&oacute;nico:</div>
      	<div class="mad"><input type="text" name="txtusr" class="txt" /></div>
      	<div class="mod">Contrase&ntilde;a:</div>
      	<div class="mad"><input type="password" name="txtpwd" class="txt" /></div>
      	<div class="btn btx"><span><input type="submit" name="btnok" value="Entrar"></span></div>
		
		<?php 
		if(isset($msg)){ 
		?>
		<div style="text-align:center; width:400px; overflow:hidden; padding-top:5px; padding-bottom:5px"><span style="color:#FF0000"><strong><?=$msg?></strong> </span></div>
		<?php
		}?>
		
        <div><a href="http://localhost/midepa/recuperar-contrasena/">&iquest;Olvidaste tu contrase&ntilde;a?</a></div>
      </form>
  	</div>
  </div>
  <div class="flot"></div>
</div>
</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
