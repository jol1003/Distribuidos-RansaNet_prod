<div class="busqueda">
    <!----- principio vista admin  -->
    <form method="post" name="opciones" id="opciones">
        <fieldset>
            <table border='0' cellpadding='1' cellspacing='1'>
                <tr>
                    <td>N&deg;&nbsp;SOLICITUD&nbsp;:</td><td><input type="checkbox" id="solicitud" name="solicitud" onclick="opciones_check(this.id)"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <?php if ($this->session->userdata('perfil') == "1" ||$this->session->userdata('perfil') == '3') { ?>
                        <td>ENTIDAD&nbsp;:</td><td><input type="checkbox" id="entidad" name="entidad" onclick="opciones_check(this.id)"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <? } ?>
                    <td>DATOS&nbsp;&nbsp;DEL&nbsp;INTERNO&nbsp;:</td><td><input type="checkbox" id="interno" name="interno" onclick="opciones_check(this.id)"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>N&deg;&nbsp;EXPEDIENTE&nbsp;:</td><td><input type="checkbox" id="exp" name="exp" onclick="opciones_check(this.id)"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>FECHA&nbsp;DE&nbsp;SOLICITUD&nbsp;:</td><td><input type="checkbox" id="fec_sol" name="fec_sol" onclick="opciones_check(this.id)"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td>
                        <div class="buttons">
                            <a class="regular search" href="#" id="search_ubicacion" onclick="find_ubica('<?= $controller ?>','<?= $tipo ?>')">Buscar</a>
                        </div> 
                    </td>
                </tr>
            </table>
        </fieldset>
        <!----- fin vista admin  -->
        <div id="DIVsolicitud"></div>
        <div id="DIVentidad"></div>
        <div id="DIVinterno"></div>
        <div id="DIVexp"></div> 
        <div id="DIVfec_sol"></div> 
    </form>
</div>