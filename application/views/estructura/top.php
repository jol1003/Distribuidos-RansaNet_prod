<div id="fb-root"></div>
<script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id)) return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/es_ES/all.js#xfbml=1";fjs.parentNode.insertBefore(js,fjs);}(document,'script','facebook-jssdk'));</script>


<div id="superior">
<div class="sup">
<div class="ins">
<div class="flef">
<span class="rss"><a href="#">RSS</a></span>
<span class="gnr"><a href="#">2urpi</a></span>
<span class="fbk"><a href="https://www.facebook.com/pages/Midepape/125879437565772" target="_blank">Facebook</a></span>
<span class="twt"><a href="https://twitter.com/MiDepape" target="_blank">Twitter</a></span>
<span class="gog"><a href="https://plus.google.com/u/0/104823439872423969978/" target="_blank">Google+</a></span></div>

<?php
if($this->session->userdata('id_user')){
?>
	<div class="frig"><a href="<?= $this->config->item('base_url') ?>/home/micuenta"><?=$this->session->userdata('usuario');?> (mi cuenta)</a> | <a href="<?= site_url('home/logout') ?>">Cerrar sesi&oacute;n</a></div>
<?php
}else{
?>
	<div class="ini"><span><a href="<?= $this->config->item('base_url') ?>/home/login">Iniciar sesi&oacute;n</a></span><a href="<?= $this->config->item('base_url') ?>/home/registro">Reg&iacute;strate</a></div>
<?php
}
?>
</div>
</div>

<div class="cab">
<div class="log"><a href="<?= $this->config->item('base_url') ?>"></a></div>
<div class="frig">
<form method="get" action="<?= $this->config->item('base_url') ?>/home/search" id="cse-search-box">
<div class="but"><input type="text" autocomplete="off" name="q" id="q" class="txt" style="width:270px;" /></div><div class="btn btx"><span><input type="submit" name="sa" id="btnbus" value="Buscar" /><input type="hidden" value="005112579141928768649:sbzfdghrr6u" name="cx"><input type="hidden" name="cof" value="FORID:11" />
<input type="hidden" name="oe" value="UTF-8"></span></div>
</form>
<script type="text/javascript" src="http://www.google.com/coop/cse/brand?form=cse-search-box&lang=es"></script>
</div>
<div class="gra"></div>
</div>
<div class="nav">
<ul class="ins">
<li class="sel"><a href="<?= $this->config->item('base_url') ?>" class="hme">INICIO</a></li>
<li><a href="<?= $this->config->item('base_url') ?>/home/listadoinmuebles/">INMUEBLES</a></li>
<li><a href="#">PROYECTOS</a></li>
<li><a href="#">CONSTRUCTORAS</a></li>
<li><a href="#">AGENTES INMOBILIARIOS</a></li>
<li><a href="#">PUBLICAR ANUNCIO</a></li>
</ul>
</div>
</div>
<div id="contenedor" class="ins">