<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--<meta property="fb:admins" content='100001282887976'/>-->
<!--<link rel="image_src" href="http://informaccion.com/new/midepa/img/logo_soc.jpg" id="image_src" />-->
<meta name="title" content="" />
<meta name="robots" content="index, follow" />
<meta name="googlebot" content="index, follow" />

<link rel="stylesheet" type="text/css" href="<?= $this->config->item('css') ?>/styles.css" media="screen"/>

<script type="text/javascript">var iurl='<?=$this->config->item('base_url')?>/';</script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/jquery-1.8.2.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?= $this->config->item('js') ?>/fjs.extras.js" charset="utf-8"></script>
<script type='text/javascript' src='http://maps.googleapis.com/maps/api/js?key=AIzaSyAlWIW73VRTHIqM2DuTMx1E58KyIE2wylg&sensor=true&#038;ver=3.4.2'></script>
<script type="text/javascript" charset="utf-8">var str='[{"i":"1","n":"AMAZONAS"},{"i":"2","n":"ANCASH"},{"i":"3","n":"APURIMAC"},{"i":"4","n":"AREQUIPA"},{"i":"5","n":"AYACUCHO"},{"i":"6","n":"CAJAMARCA"},{"i":"7","n":"CALLAO"},{"i":"8","n":"CUSCO"},{"i":"9","n":"HUANCAVELICA"},{"i":"10","n":"HUANUCO"},{"i":"11","n":"ICA"},{"i":"12","n":"JUNIN"},{"i":"13","n":"LA LIBERTAD"},{"i":"14","n":"LAMBAYEQUE"},{"i":"15","n":"LIMA"},{"i":"16","n":"LORETO"},{"i":"17","n":"MADRE DE DIOS"},{"i":"18","n":"MOQUEGUA"},{"i":"19","n":"PASCO"},{"i":"20","n":"PIURA"},{"i":"21","n":"PUNO"},{"i":"22","n":"SAN MARTIN"},{"i":"23","n":"TACNA"},{"i":"24","n":"TUMBES"},{"i":"25","n":"UCAYALI"}]';</script>

<script language="javascript" type="text/javascript" src="<?= $this->config->item('js') ?>/js.js"></script><script type="text/javascript" src="<?= $this->config->item('js') ?>/swfobject/swfobject.js" charset="utf-8"></script>




</head>

<body>
<?php
$this->load->view('estructura/top');
?>

<div id="contenedor" class="ins"><script type="text/javascript">
  var lat=null;var lng=null;var map=null;var geocoder=null;var marker=null;var c=0;
  $(document).ready(function(){
	//llenarDep(0);
	$('a[rel="inmmap"]').click(function(){
	  c++;
	  if(c==1){
		initialize();
	  }else{
		codeAddress();
	  }
	});
	llenarDep(15); llenarPro(15,8); llenarDis(8,<?=$listado['distrito_id']?>);
  });
  function initialize(){
	lat=$("#hdnlat").val();lng=$("#hdnlon").val();
	geocoder=new google.maps.Geocoder();
	if(lat!=''&&lng!=''){var latLng=new google.maps.LatLng(lat,lng);}else{var latLng=new google.maps.LatLng(-12.0881394,-77.02748539999999);}
	var myOptions={center:latLng,zoom:15,mapTypeId:google.maps.MapTypeId.ROADMAP};
	map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	marker=new google.maps.Marker({map:map,position:latLng,draggable:true});
	updatePosition(latLng);
  }
  function codeAddress(){
	var address="";
	var dir = $("#txtdir").val();if(dir!=""){address+=dir+" ";}
	var dep=$("#cbodep").val();var pro=$("#cbopro").val();var dis=$("#cbodis").val();
	if(dep!=""){address+=$("#cbodep option:selected").html()+" ";}
	if(pro!=""){address+=$("#cbopro option:selected").html()+" ";}
	if(dis!=""){address+=$("#cbodis option:selected").html()+" ";}
	if(address == "") address = "Lima Peru";
	geocoder.geocode(
	  {'address':address},
	  function(results,status){
		if(status==google.maps.GeocoderStatus.OK){
		  map.setCenter(results[0].geometry.location);
		  marker.setPosition(results[0].geometry.location);
		  updatePosition(results[0].geometry.location);
		  google.maps.event.addListener(marker,'dragend',function(){
			updatePosition(marker.getPosition());
		  });
		}else{
		  alert("No podemos encontrar la direcci&oacute;n, error: "+ status);
		}
	  }
	);
  }
  function updatePosition(latLng){$('#hdnlat').val(latLng.lat());$('#hdnlon').val(latLng.lng());}
</script>
<div class="ubi">Mi cuenta - Crear publicaci&oacute;n</div>
<div class="mod">
<div class="icg flef">Mi cuenta</div>
<div class="btn frig"><span><a href="<?=$this->config->item('base_url')?>/home/crearpublicacion/">Registrar inmueble</a></span></div>
<div class="flot"></div></div>

<div class="izn">
<div class="bkm mcu">
<div class="bkmi">
<ul>
<li>
<a href="<?=$this->config->item('base_url')?>/home/micuenta/">Mi perfil</a>
</li>
<li><a href="<?=$this->config->item('base_url')?>/home/listadoInmueble/">Mis publicaciones</a></li>
<li><a href="<?=$this->config->item('base_url')?>/home/micuenta-contrasena/">Cambiar contrase&ntilde;a</a></li>

<!--<li><a href="http://localhost/midepa/pago-publicaciones.php">Ver mi carrito</a></li>-->

</ul>
</div>
</div>
</div><div class="den">
  <div class="tbp">
    <ul class="tabs"><li><a href="#inmdes" class="defaulttab" rel="inmdes"><span>Descripcion del inmueble</span></a></li><li><a href="#inmimg" rel="inmimg"><span>Im&aacute;genes</span></a></li><li><a href="#inmmap" rel="inmmap"><span>Ubicaci&oacute;n en el mapa</span></a></li><div class="flot"></div></ul>
    <form id="frmInm" method="post" action="<?=$this->config->item('base_url')?>/home/insertarInmueble/" enctype="multipart/form-data">
    <div class="tab-content" id="inmdes">
  	  <div class="mod"><div class="lbl">Tipo de registro:</div><div class="flef"><select id="cbocat" name="cbocat" class="txt txg"><option value="1">Inmueble individual</option></select></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de inmueble:</div><div class="flef">
	  <select id="cbotip" name="cbotip" class="txt txg">
	  <option value="">Seleccione</option>
	  <option value="1" <?php if($listado['inmuebletipo_id']==1){?> selected <?php } ?>>Casa</option>
	  <option value="2" <?php if($listado['inmuebletipo_id']==2){?> selected <?php } ?>>Departamento</option>
	  <option value="3" <?php if($listado['inmuebletipo_id']==3){?> selected <?php } ?>>Habitacion</option>
	  <option value="4" <?php if($listado['inmuebletipo_id']==4){?> selected <?php } ?>>Terreno</option>
	  </select>
	  </div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de operaci&oacute;n:</div><div class="flef">
	  <select id="cboope" name="cboope" class="txt txg">
	  <option value="1" <?php if($listado['tipo']==1){?> selected <?php } ?>>Compra</option>
	  <option value="2" <?php if($listado['tipo']==2){?> selected <?php } ?>>Alquiler</option>
	  </select>
	  </div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Nombre:</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="80" value="<?=$listado['nombre']?>" /></div><div class="flot"></div></div>
	  <div class="mod"><div class="lbl">Direcci&oacute;n:</div><div class="flef"><input type="text" name="txtdir" id="txtdir" class="txt txg" maxlength="80" value="<?=$listado['urlnombre']?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="zlef"><select id="cbodep" class="chi"></select></div><div class="zlef"><select id="cbopro" class="chi"><option value="">[PROVINCIA]</option></select></div><div class="flef"><select name="cbodis" id="cbodis" class="chi"><option value="">[DISTRITO]</option></select></div><div class="flot"></div></div>
      <div id="dvinm">
      <div class="mod"><div class="lbl">Area total:</div><div class="flef"><input type="text" name="txtato" id="txtato" class="txt" maxlength="6" value="<?=$listado['areatotalmin']?>" /> m </div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Area construida:</div><div class="flef"><input type="text" name="txtaco" id="txtaco" class="txt" maxlength="6" value="<?=$listado['areaconstruidamin']?>" /> m </div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Antiguedad:</div><div class="flef">
	  <select id="cboant" name="cboant" class="txt txg">
	  <option value="">Seleccione</option>
	  <option value="1" <?php if($listado['antiguedad']==1){ ?> selected <?php } ?>>Proyecto en construcci&oacute;n</option>
	  <option value="2" <?php if($listado['antiguedad']==2){ ?> selected <?php } ?>>Usado</option>
	  <option value="3" <?php if($listado['antiguedad']==3){ ?> selected <?php } ?>>Nuevo</option>
	  </select>
	  </div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Precio:</div><div class="zlef">S/. <input type="text" name="txtpso" id="txtpso" class="txt" maxlength="8" value="<?=$listado['preciomin']?>" /></div><div class="flef">$. <input type="text" name="txtpdo" id="txtpdo" class="txt" maxlength="8" value="<?=$listado['preciodolaresmin']?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de dormitorios:</div><div class="flef"><input type="text" name="txtdor" id="txtdor" class="txt" maxlength="2" value="<?=$listado['dormitoriosmin']?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de ba&ntilde;os:</div><div class="flef"><input type="text" name="txtban" id="txtban" class="txt" maxlength="2" value="<?=$listado['banosmin']?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">&Aacute;reas comunes: <?=$cod = explode(",",$listado['areacomun'])?></div>
	  <div class="flef">
	  <div class="mod">
	  <input type="checkbox" name="chkarc[]" value="1" <? if(in_array(1,$cod)){echo ' checked="checked"';}?> /> Canchita de Futbol</div>
	  <div class="mod"><input type="checkbox" name="chkarc[]" value="2" <? if(in_array(2,$cod)){echo ' checked="checked"';}?> /> Patio</div>
	  <div class="mod"><input type="checkbox" name="chkarc[]" value="3" <? if(in_array(3,$cod)){echo ' checked="checked"';}?> /> Lavander&iacute;a</div>
	  <div class="mod"><input type="checkbox" name="chkarc[]" value="5" <? if(in_array(5,$cod)){echo ' checked="checked"';}?> /> Piscina</div>
	  <div class="mod"><input type="checkbox" name="chkarc[]" value="6" <? if(in_array(6,$cod)){echo ' checked="checked"';}?> /> Gimnasio</div>
	  <div><input type="checkbox" name="chkarc[]" value="7" <? if(in_array(7,$cod)){echo ' checked="checked"';}?> /> Juegos para ni&ntilde;os</div>
	  </div>
	  <div class="flot"></div>
	  </div>
	  <div>
	  <div class="lbl">Datos adicionales Inmuebles:</div>
	  <div class="flef"><textarea name="txtadi" class="tex txg"><?=$listado['adicionales']?></textarea></div>
	  <div class="flot"></div>
	  <div class="lbl">Dias de Publicaci&oacute;n:</div>
	  <div class="flef"><strong>10 dias</strong></div>
	   <div class="btn btx"><span>
      <input type="submit" name="btnok" value="Guardar" />
    	</span></div>
	
	  </div>
      </div>
	  </form>
  	</div>
    <div class="tab-content" id="inmimg">
	  <? //if($_GET["statusimg"] == "ok"){?><!--<div class="god itp">Datos guardados correctamente. Ahora puede agregar mas imagenes</div>--><? //}?>
	  
	  
      <form id="frmInmImg" method="post" action="<?=$this->config->item('base_url')?>/home/subirImagen/" enctype="multipart/form-data">
      <? //if($_GET["status"] == "err"){echo '<div class="err itp">No se puede borrar la unica imagen del inmueble</div>';}?>
      <div class="mod">Selecciona una imagen</div>
      <div class="mad"><div class="slef"><input type="file" name="filimg" id="filimg" class="txt" /></div><div class="btn btm flef"><span><input type="submit" name="btnokimg" value="Guardar imagen" /></span></div><div class="lod flef"><!--<img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" />--><span class="ver">Procesando...</span></div><div class="flot"></div></div>
      <div class="itm itp"><span class="ver">Debes al menos ingresar una imagen.</span> Peso m&aacute;ximo: 1MB en formato jpg, gif o png</div>
      <div class="slef">Elige una imagen como principal haciendo clic en <span class="bld">Foto principal</span> o elim&iacute;nala con </div><div class="del flef"></div><div class="flot"></div>
      <div class="its">
	  <? if(count($imagen) > 0){
	  		$f=0;
	  		foreach($imagen as $row){
			?>
				<div class="gap">
				<?php
				if($f == 0){
				?>
					<img src="<?= $this->config->item('images') ?>/inmuebles/<?=$row['imagen']?>" width="130" height="94" /><span class="roj">Foto principal</span>
				<?php
				}else{
				?>
					<a href="<?=$this->config->item('base_url')?>/home/eliminarimagen/<?=$row['id']?>" class="del"></a><img src="<?= $this->config->item('images') ?>/inmuebles/<?=$row['imagen']?>" width="130" height="94" /><!--<a href="'.URL_PATH.'micuenta-priimagen/?id='.$gal->id.'&inm='.$id.'">Foto </a>-->
				<?php
				}
				?>
				</div>
				<?php
	  			$f++;
			}
		}
		?>
		<input type="hidden" id="inmueble" name="inmueble" value="<?=$listado['id']?>" />
		<div class="flot"></div>
	  
	  </div>
      
  	
    
   
    <div class="lod"><span class="ver">Procesando...</span></div>
    </form>
  </div>
  
  <div class="tab-content" id="inmmap">
      <div id="map_canvas" style="width:400px;height:400px;margin:0px auto;"></div>
      <input type="hidden" id="hdnlat" name="hdnlat" value="" /><input type="hidden" id="hdnlon" name="hdnlon" value="" />
	  <input type="hidden" id="hdnfrmtip" value="add" />
	  <input type="hidden" id="hdnfrmtip" value="add" />
  	</div>
	
</div>
<div class="flot"></div>
</div>

<?php
$this->load->view('estructura/footer');
?>
</body>
</html>
