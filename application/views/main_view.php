<?php
if ($this->session->userdata('logeado') == TRUE) {
    ?>
	<?php
	$this->load->view('estructura/header');
	?>
    <body>
        <div id="window" align="center">
            <div id="pagina">
                <?php
                $this->load->view('estructura/top');
                $this->load->view('estructura/sub_header');
                ?>
                <div id="menu"><?php $this->load->view('estructura/menu_view'); ?></div>
                <div id="main"><?php $this->load->view('welcome_view'); ?></div>
                <?php if (isset($title) && isset($msg)) { ?>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            boxmensajes('<?= $title ?>', '<?= $msg ?>')
                        });
                    </script>
                <?php } ?>
                <?php //$this->load->view('estructura/footer'); ?>
            </div>
        </div>
    </body>
    <div id="blocker"></div>
    <div id="loading"><img src="<?= $this->config->item('images') ?>/s_cargando.gif" /></div>
    <div id="oculto"></div>
    <?php
} else {
    redirect('login', 'refresh');
}
?>

