<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {  

	/**ini
	 * Index Page for this controller. 
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index 
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/ 
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		$data['listado'] = $this->home_model->get_inmuebles();
		$this->load->view('inicio', $data);
	}
	
	public function detalle_inmueble(){
		$id = ($this->uri->segment(3) == "") ? 0 : $this->uri->segment(3);
		//echo $id;
		//exit;
		$data['listado'] = $this->home_model->get_inmuebles_detalle($id);
		$data['imagen'] = $this->home_model->get_inmuebles_imagenes($id);
		//echo $listado['areacomun'] ;
		//exit;
        $this->load->view('detalle-inmueble', $data);
	}
	
	public function registro(){
		$data['listado'] = $this->home_model->get_inmuebles();
		$this->load->view('registro', $data);
	}
	
	public function insertarregistro(){
		$data['listado'] = $this->home_model->insert_usuario($_POST);
		if($data['listado']==1){
			$this->load->view('registro_ok');
		}
	}
	
	public function login(){
		$this->load->view('login');
	}
	
	public function verificar_login(){
		$usr = $this->input->post('txtusr');
		$pwd = $this->input->post('txtpwd');
		
		$verificar = $this->home_model->model_verificar_login($usr, $pwd);
		//echo $verificar;
		//exit;
			if($verificar==1){
				$data['resultado'] = $this->home_model->listarUsuarios($this->session->userdata('id_user'));
				$data['msg'] = "";
				$this->load->view('micuenta' , $data);
			}elseif($verificar==2){
				$data['msg'] = "Usuario no se encuentra activo";
				$this->load->view('login', $data);
			}else{
				$data['msg'] = "Usuario no existe";
				$this->load->view('login', $data);
			}
	}
	
	public function micuenta(){
				$data['resultado'] = $this->home_model->listarUsuarios($this->session->userdata('id_user'));
				$this->load->view('micuenta' , $data);
	}
	
	#################################################################
	public function updateUsuario(){
				$config['upload_path'] = FCPATH.'img/fotos';
				$config['overwrite'] = TRUE;
				$config['allowed_types'] = 'jpg';
				$config['max_size'] = '300';
				$config['max_width'] = '800';
				$config['max_height'] = '600';
				
				$this->load->library('upload', $config);
				
				if (!$this->upload->do_upload('foto', $this->session->userdata('id_user'))) {
					//echo $this->upload->display_errors();
					$data['error'] = $this->upload->display_errors();
					$data['listado'] = $this->home_model->updateUsuario($_POST, $this->session->userdata('id_user'), '');
					$data['resultado'] = $this->home_model->listarUsuarios($this->session->userdata('id_user'));
					$this->load->view('micuenta' , $data);
					//echo "sdfsdfs";
				}else{
					$uploaded = $this->upload->data();
					$data['img'] = base_url() . 'images/' . $uploaded['file_name'];
					
					//echo $uploaded['file_name']; 
					$data['listado'] = $this->home_model->updateUsuario($_POST, $this->session->userdata('id_user'), $uploaded['file_name']);
					$data['resultado'] = $this->home_model->listarUsuarios($this->session->userdata('id_user'));
					$this->load->view('micuenta' , $data);
					   
					//exit;
				}
						
	}
	#################################################################
	
	function logout() { //fin de sesión
        if ($this->session->userdata('logeado') == TRUE) {
            //$this->login_model->log_axion($this->session->userdata('id_user'), 'SALIDA DEL SISTEMA');
            $data = array('id_user' => 0, 'usuario' => 0, 'perfil' => 0, 'logeado' => FALSE, 'cod'=>0);
            $this->session->unset_userdata($data);
            $this->session->sess_destroy();
            //$data['title'] = 'ESPERE';
            //$data['msg'] = 'CERRANDO SESION';
			//$data['cerrar'] = 'cerrar';
            $this->load->view('login', $data);
        } else {
            $this->load->view('login');
        }
    }
	
	###################################################
	function crearpublicacion(){
		$this->load->view('crearpublicacion');
	}
	###################################################
	
	###################################################
	function insertarInmueble(){
		//$this->load->view('insertInmueble');
		$config['upload_path'] = FCPATH.'img/inmuebles';
		$config['overwrite'] = TRUE;
		$config['allowed_types'] = 'jpg';
		$config['max_size'] = '300';
		$config['max_width'] = '800';
		$config['max_height'] = '600';
				
		$this->load->library('upload', $config);
		
		$inmuebleId = $this->home_model->insertInmueble($_POST, $this->session->userdata('id_user'));
		//$this->micuenta();
		
		if (!$this->upload->do_upload('filimg', $inmuebleId)) {
					//echo $this->upload->display_errors();
					$data['error'] = $this->upload->display_errors();
					$data['listado'] = $this->home_model->fotoInmueble($this->session->userdata('id_user'), '');
					$this->micuenta();
					//echo "sdfsdfs";
		}else{
					$uploaded = $this->upload->data();
					$data['img'] = base_url() . 'images/' . $uploaded['file_name'];
					
					//echo $uploaded['file_name']; 
					$data['listado'] = $this->home_model->fotoInmueble($this->session->userdata('id_user'), $inmuebleId);
					$this->micuenta();
					   
					//exit;
		}
						
		//$this->home_model->insertInmueble($_POST, $this->session->userdata('id_user'));
		//$this->micuenta();
	}
	###################################################
	
	###################################################
	
	function listadoInmueble(){
		$data['listado'] = $this->home_model->get_inmuebles_usuarios($this->session->userdata('id_user'));
		$this->load->view('listado_inmueble', $data);
	}
	###################################################
	
	public function editarpublicacion(){
		$id = ($this->uri->segment(3) == "") ? 0 : $this->uri->segment(3);
		//echo $id;
		//exit;
		$data['listado'] = $this->home_model->get_inmuebles_detalle($id);
		$data['imagen'] = $this->home_model->get_inmuebles_imagenes($id);
		
		//echo $listado['areacomun'] ;
		//exit;
        $this->load->view('editarpublicacion', $data);
	}
	###################################################	
	public function subirImagen(){
				$config['upload_path'] = FCPATH.'img/inmuebles';
				$config['overwrite'] = TRUE;
				$config['allowed_types'] = 'jpg';
				$config['max_size'] = '300';
				$config['max_width'] = '800';
				$config['max_height'] = '600';
				
				$this->load->library('upload', $config);
				$inmuebleId = $this->input->post('inmueble');
				$img  = $this->home_model->fotoInmuebleO($this->session->userdata('id_user'), $inmuebleId);
				$img1  = $img.rand(1,1000);
				
				//echo $img;
				// exit;
				
				if (!$this->upload->do_upload('filimg', $img1)) {
					//echo $this->upload->display_errors();
					$data['error'] = $this->upload->display_errors();
					
					$dt  = $this->home_model->updatefotoInmuebleO($this->session->userdata('id_user'), $img, $img1);
					
					$data['listado'] = $this->home_model->get_inmuebles_usuarios($this->session->userdata('id_user'));
					$this->load->view('listado_inmueble', $data);
					//echo "sdfsdfs";
				}else{
					$uploaded = $this->upload->data();
					$data['img'] = base_url() . 'images/' . $uploaded['file_name'];
					
					//echo $uploaded['file_name']; 
					//$img  = $this->home_model->fotoInmuebleO($this->session->userdata('id_user'), $inmuebleId);
					$dt  = $this->home_model->updatefotoInmuebleO($this->session->userdata('id_user'), $img, $img1);
					
					$data['listado'] = $this->home_model->get_inmuebles_usuarios($this->session->userdata('id_user'));
					$this->load->view('listado_inmueble', $data);
					   
					//exit;
				}
						
	}
	#######################################################################
	
	public function eliminarimagen(){
		$id = ($this->uri->segment(3) == "") ? 0 : $this->uri->segment(3);
		$delete = $this->home_model->delete_imagenes_inmuebles($id);
		$this->listadoInmueble();
	}
	
	public function search(){
		$data['q'] = $this->input->get('q');
		//echo $this->input->get('q');
		$this->load->view('search', $data);
	}
	
	public function busqueda_inmuebles(){
		$comp_alq = $this->input->post('cboope');
		$casa = $this->input->post('cbotip');
		$dormitorios = $this->input->post('cbodor');
		$desde = $this->input->post('txtpmn');
		$hasta = $this->input->post('txtpmx');
		$dist = $this->input->post('txtbus');
		
		
		$data['listado'] = $this->home_model->busqueda_inm($comp_alq, $casa, $dormitorios, $desde, $hasta, $dist);
		
		$this->load->view('busqueda_inmuebles', $data);
	}
	
	public function listadoinmuebles(){
		$data['listado'] = $this->home_model->get_inmuebles();
		$this->load->view('listadoinmueble', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */