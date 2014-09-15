<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css') ?>/styles.css" media="screen"/>
<link type="text/css" href="<?= $this->config->item('js') ?>/carousel/css/jcarousel.css" rel="stylesheet" charset="utf-8" />
<link type="text/css" href="<?= $this->config->item('js') ?>/token/css/token-input-advanced.css" rel="stylesheet" charset="utf-8" />

<script type="text/javascript" language="javascript" src="<?= $this->config->item('js') ?>/data/json_prodis.json"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/jquery-1.8.2.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->config->item('js') ?>/fjs.extras.js" charset="utf-8"></script><script type="text/javascript" src="<?= $this->config->item('js') ?>/carousel/jquery.jcarousel.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/token/jquery.tokeninput.min.js" charset="utf-8"></script>

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

<div id="contenedor" class="ins"><div class="izq">
  <div class="bkg">

  	  <div class="bkgi">

      	<div class="ica">Encuentra tu inmueble aqu&iacute;:</div>

      	<div class="bim">

        <form id="frmBus" name="frmBus" method="post" action="<?= $this->config->item('base_url') ?>/home/busqueda_inmuebles/">

          <div class="mad">
		  <div class="slef">
		  <select name="cboope" class="txt"><option value="1">Compra</option><option value="2">Alquiler</option></select>
		  </div>
		  <div class="flef">
		  <select name="cbotip" class="txt">
		  <option value="">[Todos los tipos]</option>
		  <option value="1">Casa</option>
		  <option value="2">Departamento</option>
		  <option value="3">Habitaci&oacute;n</option>
		  <option value="4">Terreno</option>
		  </select>
		  </div>
		  <div class="flot"></div>
		  </div>

          <div class="mad"><div class="bus"><input type="text" name="txtbus" id="txtbus" /></div></div>

          <div class="mad"><div class="lbl">N de dormitorios</div><div class="flef"><select name="cbodor" class="txt"><option value="0">0 o m&aacute;s</option><option value="1">1 o m&aacute;s</option><option value="2">2 o m&aacute;s</option><option value="3">3 o m&aacute;s</option><option value="4">4 o m&aacute;s</option><option value="5">5 o m&aacute;s</option></select></div><div class="flot"></div></div>

          <div class="lbl">Desde</div><div class="lbl">S/. </div><div class="slef"><input type="text" name="txtpmn" id="txtpmn" maxlength="8" class="txt" /></div>

          <div class="lbl">-</div>

          <div class="lbl">hasta</div><div class="lbl">S/. </div><div class="flef"><input type="text" name="txtpmx" id="txtpmx" maxlength="8" class="txt" /></div>

          <div class="btn btx"><span><input type="submit" id="btnbus" value="Buscar" /></span></div>

          <div class="flot"></div>

        </form>

        </div>

      </div>

  	</div>
  
  <?php
  foreach($listado as $row){
  ?>
  	<div class="itm">
  <div class="tit">
  <h3><a href="<?= $this->config->item('base_url') ?>/home/detalle_inmueble/<?=$row['id']?>"><?=$row['nombre']?></a></h3></div>
  <div class="slef"><a href="<?= $this->config->item('base_url') ?>/home/detalle_inmueble/<?=$row['id']?>"><img src="<?= $this->config->item('images') ?>/inmuebles/<?=$row['imagen']?>" width="130" height="94" alt="<?=$row['nombre']?>" /></a></div>
  <div class="det">
  <div class="chi"><?=$row['fechainicio']?></div>
  <div class="bld"></div>
  <div>NUEVO</div>
  <div class="bld">AT <?=$row['areatotalmin']?> m² AC <?=$row['areaconstruidamin']?> m²</div>
  <div class="chi"><?=$row['adicionales']?></div></div><div class="flot"></div><div class="dpr"><div class="pre">S/. <?=$row['preciomin']?></div><div class="prs mod">$. <?=$row['preciodolaresmin']?></div><div class="btn btx"><span><a href="<?= $this->config->item('base_url') ?>/home/detalle_inmueble/<?=$row['id']?>">Ver m&aacute;s</a></span></div></div></div>
  <?php
  }
  ?>
</div>
<div class="der">
  <div class="mod"><img src="<?= $this->config->item('images') ?>/img1.jpg" /> </div>
  <!--<div class="mod">
    <div class="bkm">
      <div class="bkmi">
      	<div class="ici">B&uacute;squeda avanzada</div>
        
      </div>
    </div>
  </div>
  <div class="mod">
  	[Zona del tabber]
  </div>-->
  <div class="mod"><img src="<?= $this->config->item('images') ?>/img4.jpg" /></div>
</div>
<div class="flot"></div>
</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
