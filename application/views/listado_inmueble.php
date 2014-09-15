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
</script>

<title>Documento sin t&iacute;tulo</title>
</head>

<body>
<?php
$this->load->view('estructura/top');
?>

<div id="contenedor" class="ins"><div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<div class="mod">
<div class="icg flef">Mi cuenta</div>
<div class="btn frig"><span><a href="<?=$this->config->item('base_url')?>/home/crearpublicacion/">Registrar inmueble</a></span></div>
<div class="flot"></div></div>

<div class="izn">
<div class="bkm mcu">
<div class="bkmi">
<ul>
<li>
<li>
<a href="<?= $this->config->item('base_url') ?>/home/micuenta" class="sel">Mi perfil</a>
</li>
<li><a href="<?= $this->config->item('base_url') ?>/home/listadoInmueble">Mis publicaciones</a></li>
<li><a href="<?= $this->config->item('base_url') ?>">Cambiar contrase&ntilde;a</a></li>

</ul>
</div>
</div>
</div><div class="den">
  <div class="sub">Mis publicaciones </div>
  <div class="frc frn">
		<!--inicio-->
		
		<?php
		foreach($listado as $row){
			$antig = $row['antiguedad'];
			if($antig==1){
				$ant = "EN CONSTRUCCION";
			}elseif($antig==2){
				$ant = "USADO";
			}else{
				$ant = "NUEVO";
			}
		?>
		<div class="itm">
	<div class="tit"><h3><a href="http://localhost/midepa/inmueble-detalle/1/depa"><?=$row['nombre']?></a></h3></div>
	<div class="slef"><a href="<?= $this->config->item('base_url') ?>/home/detalle_inmueble/<?=$row['id']?>"><img src="<?= $this->config->item('images') ?>/inmuebles/<?=$row['imagen']?>" width="130" height="94" alt="depa" /></a></div>
	<div class="det">
	<!--<div class="chi">18-Nov-2012 </div>-->
	<div class="bld"></div>
	<div><?=$ant?> </div>
	<div class="bld">AT <?=$row['areatotalmin']?> m² AC <?=$row['areaconstruidamin']?> m² </div>
						<div class="chi"><?=$row['urlnombre']?> </div>
				<div class="chi"><?=$row['fechainicio']?> - <?=$row['fechafin']?></div>
			<div class="chi"><a href="javascript:;"><strong>Renovar publicaci&oacute;n</strong></a></div>
		</div>
	<div class="flot"></div>
	<div class="dpr">
	<div class="pre">S/. <?=$row['preciomin']?></div>
	<div class="prs mod">$. <?=$row['preciodolaresmin']?></div>
	<div class="btn btx"><span><a href="<?= $this->config->item('base_url') ?>/home/editarpublicacion/<?=$row['id']?>">Editar</a></span></div>
	</div>
	</div>
	<?php
	}
	?>
	<!--fin-->
	
	  	<!--<div class="pagination"><span class="current">1</span><a href="http://localhost/midepa/micuenta-mispublicaciones/page/1/" class="next">Siguiente &#187;</a></div>-->
  </div>
</div>
<div class="flot"></div>
</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
