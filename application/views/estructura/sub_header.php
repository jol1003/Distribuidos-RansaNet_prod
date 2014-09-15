<div id="subheader">
    <div class="clear"></div>
    <div style="padding-left:10px; overflow:hidden; width:auto">
	<div id="divUsuario2" align="left">
        <font color=#000077 ><b><?= utf8_decode($this->rq_model->get_entidad_nombres()) ?>&nbsp;&nbsp;-&nbsp;<?= $this->input->ip_address() ?></b></font>
    </div>
	    
    <div id="divUsuario" align="right">
        <img src="<?= $this->config->item('images') ?>/menu/user.png" title="Cerrar Sesion" style="border: 0px;"/>
        <b>Bienvenido Usuario : </b>
        <font color='#000077'><?= utf8_decode($this->session->userdata('usuario')) ?>&nbsp;&nbsp;&nbsp;&nbsp;</font>        
    </div>
	
	</div>
	<div class="clear"></div>
    <!--<div class="btn-cerrar">
        <table><tr>
                <td><a href="<?= site_url('login/logout') ?>"><img src="<?= $this->config->item('images') ?>/cerrar_sesion.gif" title="Cerrar Sesion" style="border: 0px;"/></a></td>
            </tr>
        </table>
    </div> 
    <div class="clear"></div>-->
</div>


