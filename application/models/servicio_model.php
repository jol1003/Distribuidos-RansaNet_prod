<?php
class Servicio_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
		
    }
	
	public function get_logeo($usuario, $clave){
		//$resultado = false;
		
		//echo "hola";
		$query = $this->db->query("SELECT ApellidosUsuario FROM usuario WHERE CodUsuario=? AND Contrasenia=?" , array( $usuario, $clave));
		
		if($query->num_rows()>0){
			$resultado = 1;
		}else{
			$resultado = 0;
		}
		
		return $resultado;
	}
	
	public function get_area(){
		
		$query = $this->db->query("SELECT IdArea, NomArea FROM area");
									
        return $query->result_array();
	}
	
	public function get_tipoua(){
		
		$query = $this->db->query("SELECT IdTipoUa, NomTipoUa FROM tipoua");
									
        return $query->result_array();
	}
	
	/*public function get_inmuebles_detalle($id){
		$query = $this->db->query("SELECT i.*,  it.nombre AS depa
									FROM 
									inmueble i, inmuebletipo it
									WHERE i.id=".$id." 
									AND i.estado='1'
									AND i.inmuebletipo_id = it.id ");
        return $query->row_array();
	}
	
	public function get_inmueble_area_comun($id){
		$query = $this->db->query("SELECT *
									FROM 
									areacomun
									WHERE id=".$id."");
        return $query->row_array();
	}
	
	public function get_usuario($id){
		$query = $this->db->query("SELECT *
									FROM 
									usuario 
									WHERE id=".$id."");
        return $query->row_array();
	}
	
	public function get_persona($id){
		$query = $this->db->query("SELECT *
									FROM 
									persona 
									WHERE id=".$id."");
        return $query->row_array();
	}
	
	public function insert_usuario($_POST){
		if(isset($_POST['chkbol'])){
			$boletin = $_POST['chkbol'];
		}else{
			$boletin = 0;
		}
		
		$usuario = $this->db->query("START TRANSACTION");
		
		$usuario = $this->db->query("INSERT INTO 
								usuario(id,usuariotipo,email,contrasena,boletin,estado,fechaacceso,fechacreacion) 								VALUES('','".$_POST['cboreg']."','".$_POST['txtmai']."','".md5($_POST['txtpwd'])."','".$boletin."','0',NOW(),NOW())");
		
		$personId = $this->db->insert_id();
		
		if($usuario){
			$usuario = $this->db->query("COMMIT");
			
			$persona = $this->db->query("INSERT INTO persona(id,nombres,apellidos,dni,estado) VALUES(".$personId.",'".$_POST['txtnom']."','".$_POST['txtape']."','".$_POST['txtdni']."','0')");
			
			$cod = md5(time());

		 	$sav = $this->db->query("INSERT INTO u_confirma(id,codigo,estado,fechacreacion) VALUES(".$personId.",'".$cod."','1',NOW())");
		  
		}
		
		return 1;
		
		
	}
	
	public function model_verificar_login($usr, $pwd){
		$user = $usr;
		$pwd  = $pwd;
		
		//AND estado='1'
		$query = $this->db->query("SELECT id,usuariotipo,imagen,estado FROM usuario WHERE email=? AND contrasena=?  LIMIT 0,1", 
										   array($user, md5($pwd)) // cierra array  
									); //cierra query
		
		if($query->num_rows()>0){							
			$row = $query->row_array();
		
			//validamos el estado
			if($row['estado']==1){
				//validamos el tipo usuario
					if($row['usuariotipo']==1){
						$nom = $this->db->query("SELECT nombres,apellidos FROM persona WHERE id=".$row['id']." LIMIT 0,1");
						//$nom = testsho($nom->nombres." ".$nom->apellidos);
						$rowN = $nom->row_array();
						$nom = $rowN['nombres']." ".$rowN['apellidos'];
					}else{
						$nom = $this->db->query("SELECT nombrecontacto FROM empresa WHERE id=".$row['id']." LIMIT 0,1");
						$rowN = $nom->row_array();
						$nom  = $rowN['nombrecontacto'];
					}
				
					//$upd = $this->db->query("UPDATE u_usuario SET fechaacceso=NOW() WHERE id=".$row['id']." LIMIT 1");
					
					$data = array('id_user' => $row['id'], 'usuario' => $nom, 'usuariotipo' => $row['usuariotipo'], 'logeado' => TRUE); //crea la sesion
					$this->session->set_userdata($data);
					
					return 1;
			}else{
					//usuario no activo
					return 2;
			}
		}else{
			//usuario no existe
			return 3;
		}
		
	}
	
	###################################################################################
	public function listarUsuarios($id){
		$query = $this->db->query("SELECT u.*, p.* FROM usuario u, persona p WHERE u.id = p.id AND u.id = ?", array($id));
		return $query->row_array();
	}
	###################################################################################
	
	public function updateUsuario($_POST, $id, $foto){
	
			$nombre    = $this->input->post('txtnom');
			$apellidos = $this->input->post('txtape');
			$dni = $this->input->post('txtdni');
			
			$tel1 = $this->input->post('txtte1');
			$tel2 = $this->input->post('txtte2');
			$tel3 = $this->input->post('txtte3');
			
			if($foto!=''){
				$upd = $this->db->query("UPDATE usuario SET telefono1=? , 
															telefono2 =?,
															telefono3 = ?,
															imagen = ?
															WHERE id=".$id." LIMIT 1", array($tel1, $tel2, $tel3, $foto));	
			}else{
				$upd = $this->db->query("UPDATE usuario SET telefono1=? , 
															telefono2 =?,
															telefono3 = ? 
															WHERE id=".$id." LIMIT 1", array($tel1, $tel2, $tel3));	
			}
			
			$upd = $this->db->query("UPDATE persona SET nombres=?,
															apellidos = ?,
															dni = ?
															WHERE id=".$id." LIMIT 1", array( $nombre, $apellidos, $dni));
	}
	
	########################################################################
	public function insertInmueble($_POST, $usr){
		$categoria = 1;
		$proyecto_id = 0;
		
		$cbotipo = $this->input->post('cboope');
		$cbotip = $this->input->post('cbotip');
		$nom = $this->input->post('txtnom');
		$urlnombre = '';
		$img = '';
		$antiguedad = $this->input->post('cboant');
		$dis = $this->input->post('cbodis');
		$dir = $this->input->post('txtdir');
		$minTot = $this->input->post('txtato');
		$mincTot = $this->input->post('txtaco');
		
		$preciom = $this->input->post('txtpso');
		$preciomd = $this->input->post('txtpdo');
		$dormit = $this->input->post('txtdor');
		$banos = $this->input->post('txtban');
		$arc = $this->input->post('chkarc'); 
		//echo $arc."---";
		//echo count($arc)."---";
		
		if(count($arc)>0 && $arc!=''){
			$arc = implode(",", $arc);
		}
		$areac = $arc;
		//exit;
		
		$adicionales = $this->input->post('txtadi');
		
		$estado = '1';
		$fecActual = date('Y-m-d H:i:s');
		 //fecha de 10 dias gratis
		  $year  = date('Y');
		  $month = date('m');
		  $day   = date('d');
		  
		  $fechafin = mktime(0,0,0,$month,$day+10,$year);
		  $fechafinal = date('Y-m-d' , $fechafin); 
		
		//$lat = testins($_POST["hdnlat"]); $lon = testins($_POST["hdnlon"]);
		 
		$lat = $this->input->post('hdnlat');
		$long = $this->input->post('hdnlon');
		
		$sav = $this->db->query("INSERT INTO inmueble(usuario_id,categoria,proyecto_id,tipo,inmuebletipo_id,
			nombre,urlnombre,imagen,antiguedad,
			distrito_id,direccion,areatotalmin,areaconstruidamin,
			preciomin,preciodolaresmin,dormitoriosmin,banosmin,
			
			areacomun,adicionales,latitud,longitud,
			
			estado,fechacreacion,fechaactualizacion,fechainicio, fechafin) 
			
			VALUES( ?, ?, ?, ?,?,
							  ?,?,?,?,
							  ?,?,?,?,
							  ?,?,?,?,
							  ?,?,?,?,
							  ?,?,?,?,?
			)", array(
										$usr, $categoria, 	$proyecto_id, $cbotipo, $cbotip, 
										$nom, $urlnombre, $img, $antiguedad,
										$dis, $dir, $minTot, $mincTot,
										
										$preciom, $preciomd, $dormit , $banos,
										$arc, $adicionales, $lat,  $long,
										$estado,  $fecActual, $fecActual, $fecActual,  $fechafinal)
		
		);
		
		$inmuebleId = $this->db->insert_id();
		
		return $inmuebleId;
		
		
	} 
	########################################################################################
	public function fotoInmueble($id, $foto){
		$estado = 1;
		$imagen = $foto.".jpg";
		$fecha  = date('Y-m-d H:i:s');
		
		if($foto!=''){
			$queryFoto = $this->db->query("UPDATE inmueble set imagen = '".$foto.".jpg' where id = ".$foto);
			$sav = $this->db->query("INSERT INTO inmueblegaleria ( inmueble_id, imagen, estado, fechacreacion) values (?,?,?,?)", array($foto, $imagen, $estado, $fecha));
		}
	}
	########################################################################################
	
	public function get_inmuebles_usuarios($id){
		
		$query = $this->db->query("SELECT i.* FROM inmueble i
									WHERE i.estado='1'   and   usuario_id =  ".$id." " );
									
        return $query->result_array();
	}
	
	public function get_inmuebles_imagenes($id){
		$query = $this->db->query("SELECT * FROM inmueblegaleria WHERE inmueble_id = ".$id." ");
        return $query->result_array();
	}
	
	public function fotoInmuebleO($id, $foto){
		$estado = 1;
		$imagen = $foto.".jpg";
		$fecha  = date('Y-m-d H:i:s');
		
		
		$sav = $this->db->query("INSERT INTO inmueblegaleria ( inmueble_id, estado, fechacreacion) values (?,?,?)", array($foto, $estado, $fecha));
		
		
		$inmuebleGId = $this->db->insert_id();
		
		return $inmuebleGId;
	}
	
	public function updatefotoInmuebleO($id, $codigo, $foto){
		$estado = 1;
		$imagen = $foto.".jpg";
		$fecha  = date('Y-m-d H:i:s');
		
		if($foto!=''){
			$sav = $this->db->query("update inmueblegaleria set imagen = '".$imagen."'  where id = ".$codigo );
		}
		
	}
	
	public function delete_imagenes_inmuebles($id){
		$del = $this->db->query("delete from inmueblegaleria where id = ".$id );
	}
	
	public function busqueda_inm($comp_alq, $casa, $dormitorios, $desde, $hasta, $dist){
		
		$varcomp_alq 	= $comp_alq!=''?" and tipo = ".$comp_alq." ":"";
		$varcasa 		= $casa!=''?" and inmuebletipo_id = ".$casa." ":"";
		$vardormitorios = $dormitorios!=0?" and dormitoriosmin = ".$dormitorios." ":"";
		$vardesde 		= $desde!=''?" and preciomin <= ".$desde." ":"";
		$varhasta 		= $hasta!=''?" and preciodolaresmin <= ".$hasta." ":"";
		$vardist 		= $dist!=0?" and distrito_id = ".$dist." ":"";
		
		$sqlFind = "SELECT * FROM inmueble 
					WHERE
					estado = 1
					$varcomp_alq 			
					$varcasa 
					$vardormitorios 
					$vardesde
					$varhasta 
					$vardist 
					";
					
		$query = $this->db->query($sqlFind);
		return $query->result_array();
	}*/
}
?>