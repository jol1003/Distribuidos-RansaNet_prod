<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css') ?>/styles.css" media="screen"/>
<link type="text/css" href="<?= $this->config->item('js') ?>/carousel/css/jcarousel.css" rel="stylesheet" charset="utf-8" />
<link type="text/css" href="<?= $this->config->item('js') ?>/token/css/token-input-advanced.css" rel="stylesheet" charset="utf-8" />

<!--<script type="text/javascript" language="javascript" src="<?= $this->config->item('js') ?>/data/json_prodis.json"></script>-->
<script type="text/javascript" src="<?= $this->config->item('js') ?>/jquery-1.8.2.min.js" charset="utf-8"></script>

<script type="text/javascript" src="<?= $this->config->item('js') ?>/fjs.extras.js" charset="utf-8"></script><script type="text/javascript" src="<?= $this->config->item('js') ?>/carousel/jquery.jcarousel.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/token/jquery.tokeninput.min.js" charset="utf-8"></script>

<script type="text/javascript" charset="utf-8">var c=0,sw=0;function mycarousel_initCallback(carousel){carousel.stopAuto();};
function mycarousel_itemFirstInCallback(carousel,item,idx,state){if(state=="init"||sw==1){}else{$(".jcarousel-control a").attr("class","");c++;if(c==3){c=0;};$(".jcarousel-control").find("a").eq(c).attr("class","sel");};sw=0;}; 
$(document).ready(function(){$("#mycarousel").jcarousel({auto:3,initCallback:mycarousel_initCallback,itemFirstInCallback:mycarousel_itemFirstInCallback,wrap:"circular",buttonNextHTML:null,buttonPrevHTML:null});
</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" charset="utf-8">
$(document).ready(function(){
	var latlng=new google.maps.LatLng(<?=$listado['latitud']?>,<?=$listado['longitud']?>);
	var myOptions={zoom:8,center:latlng,mapTypeId:google.maps.MapTypeId.ROADMAP};
	var map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	var marker=new google.maps.Marker({position:latlng,map:map,title:"Hello World!"});
	$("#mycarousel").jcarousel();
});
</script>







<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php
$this->load->view('estructura/top');
?>

<div id="contenedor" class="ins">
<div class="ubi"><a href="">Inmuebles</a> | <a href="">Otro</a></div>

<div class="ica"><h1><?=$listado['nombre']?></h1></div>

<div class="izq">

  <div class="itg itn">

  	<div id="img" class="img"><img src="http://localhost/midepa/w/imagenes/inmuebles/2.jpg" /></div>

  	<div class="bkc">

  	  <div class="bkci">

      	<div class="bld"><?=$listado['direccion']?></div>

      	<div class="itm"><div class="pre">S/. <?=$listado['preciomin']?></div><div class="prs">$. <?=$listado['preciodolaresmin']?></div></div>

      	<div class="itm chi">Este inmueble ha sido visto 1 veces</div>

      	<div class="itm"><a href="" class="prt">Imprimir</a><!--<a href="" class="env">Enviar</a>--></div>

      	<div class="itm itp"><div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></div>

      	<div class="red"><div class="mod">Compartir:</div><span class="twtg"><a href=""></a></span><span class="fbkg"><a class="fb-share" onClick="window.open('http://www.facebook.com/sharer.php?s=100'+'&p[url]=<?=$this->config->item('base_url')?>/home/detalle_inmueble/<?=$listado['id']?>'+'&p[title]=depa'+'&p[images][0]=http://localhost/midepa/w/imagenes/inmuebles/2.jpg'+'&p[summary]= ','mywindow','width=800,height=500,resizable=yes');" href="javascript:void(0);"></a></span><span class="goog"><a href=""></a></span><span class="gnrg"><a href=""></a></span><span class="maig"><a href=""></a></span><div class="flot"></div></div>

      </div>

  	</div>

  	<div class="flot"></div>
	
	<? if(count($imagen) > 0){?>
	<ul id="mycarousel" class="jcarousel-skin-det">
	<? foreach($imagen as $rowi){?>
		<li><img src="<?= $this->config->item('images') ?>/inmuebles/<?=$rowi['imagen']?>" width="106" height="82" alt="" /></li>
	<? }?>
	</ul>
	<? }?>
	

  </div>

  <div class="itg">
  <div class="suv">Descripci&oacute;n del proyecto</div>
  <div class="mad"><?=$listado['adicionales']?></div>
  <div class="itt">
  <span class="lbd">Tipo de inmueble:</span>
  <span class="flef"><?=$listado['depa']?></span>
  <div class="flot"></div>
  </div>
  
  <div class="itt">
  <span class="lbd">Direcci&oacute;n:</span>
  <span class="flef"><?=$listado['direccion']?></span>
  <div class="flot"></div>
  </div>
  
  <div class="itt">
  <span class="lbd">&Aacute;rea total (m²):</span>
  <span class="flef"><?=$listado['areatotalmin']?></span>
  <div class="flot"></div>
  </div>
  
  <div class="itt">
  <span class="lbd">&Aacute;rea construida (m²):</span>
  <span class="flef"><?=$listado['areaconstruidamin']?></span>
  <div class="flot"></div>
  </div>
  <?php
 if($listado['antiguedad'] != ""){if($listado['antiguedad'] == 1){$nant = "Proyecto en contrucci&oacute;n";}elseif($listado['antiguedad'] == 2){$nant = "Usado";}else{$nant = "Nuevo";}}
  ?>
  <div class="itt">
  <span class="lbd">Antigüedad:</span>
  <span class="flef"><?=$nant ?></span>
  <div class="flot"></div>
  </div>
  
  <div class="itt"><span class="lbd">Precio:</span><span class="flef"><span class="prs">S/. <?=$listado['preciomin']?></span> ($. <?=$listado['preciodolaresmin']?>)</span>
  <div class="flot"></div>
  </div>
  <div class="itt"><span class="lbd">Dormitorios:</span><span class="flef"><?=$listado['dormitoriosmin']?></span><div class="flot"></div>
  </div>
  
  <div class="itt"><span class="lbd">Ba&ntilde;os:</span><span class="flef"><?=$listado['banosmin']?></span><div class="flot"></div></div>
  
  <?php
  if($listado['areacomun']!=''){
  ?>
  <div class="itt">
  <span class="lbd">&Aacute;reas comunes:</span>
  <span class="flef"><?php
  $arrayId = explode(",",$listado['areacomun']);
  $s=0;
  for($f=0;$f<sizeof($arrayId);$f++){
  	//echo $arrayId[$f];
	$ac = $this->home_model->get_inmueble_area_comun($arrayId[$f]);
	$sd = ($s==0?"":", ").utf8_decode($ac['nombre']);
	echo $sd;
	$s++;
  }
  ?>
  </span>
  <div class="flot"></div>
  </div>
  <?php
  }
  ?>
  
  </div>


  <div class="fb-comments its" data-href="<?=$this->config->item('base_url')?>/home/detalle_inmueble/<?=$listado['id']?>" data-num-posts="10" data-width="504"></div>

</div>

<div class="der">

  <div class="mod">
  <div class="bkm">
  <div class="bkmi">
  <div class="mod">
  <div class="icg">Datos del contacto</div>
  <?php
  $usu = $this->home_model->get_usuario($listado['usuario_id']);
  $per = $this->home_model->get_persona($listado['usuario_id']);
  ?>
  <div class="slef"><img src="<?= $this->config->item('images') ?>/fotos/<?=$usu['imagen']?>" width="76" height="76" /></div>
  <div class="dde chi"><?=$per['nombres']." ".$per['apellidos']?></div><div class="flot"></div></div><div class="mod"><div class="ddl">Email:</div><div class="ddd"><?=$usu['email']?></div><div class="flot"></div></div></div></div></div>

  <div class="mod"><div class="ice">Ubicaci&oacute;n en el mapa</div><div id="map_canvas" style="width:310px;height:300px;"></div></div>

  <div class="mod">

  	[Zona del tabber]

  </div>

  <div class="mod"><img src=""<?= $this->config->item('images') ?>/img4.jpg" /></div>

</div>

<div class="flot"></div>

</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
