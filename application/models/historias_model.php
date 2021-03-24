<?PHP


class Historias_model extends CI_Model
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('productos_model');
		date_default_timezone_set('America/Lima');
	}

	public function select($idHistoria = '',$idPaciente = '',$fecha_desde = '', $fecha_hasta = ''){		
		if($idHistoria == ''){

		if($idPaciente != '')
			$this->db->where('his.his_paciente_id',$idPaciente);
		if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) <=", $fecha_hasta);
        }        
			
			$rsHistorias = $this->db->select('his.*,pac.*,DATE_FORMAT(his.his_fecha_cita,"%d-%m-%Y %h:%i") his_fecha_cita,DATE_FORMAT(his.his_proxima_cita,"%d-%m-%Y %h:%i") his_proxima_cita,pac.ruc pac_ruc,pac.razon_social pac_razon_social,DATE_FORMAT(pac.fecha_nacimiento,"%d-%m-%Y") pac_fecha_nacimiento,pac.edad pac_edad,pac.mes pac_mes,pac.dia pac_dia,pac.telefono pac_telefono,pac.correo pac_correo,pac.alergia pac_alergia',FALSE)
									->from('historias his')
		                            ->join('pacientes pac','pac.id = his.his_paciente_id')                           	                           
					 				->get()
					 				->result();			 				
					 return $rsHistorias;
		} else{
			$rsHistoria =  $this->db->select('his.*,pac.*,DATE_FORMAT(his.his_fecha_cita,"%d-%m-%Y %h:%i") his_fecha_cita,DATE_FORMAT(his.his_proxima_cita,"%d-%m-%Y %h:%i") his_proxima_cita,pac.ruc pac_ruc,pac.razon_social pac_razon_social,DATE_FORMAT(pac.fecha_nacimiento,"%d-%m-%Y") pac_fecha_nacimiento,pac.edad pac_edad,pac.mes pac_mes,pac.dia pac_dia,pac.telefono pac_telefono,pac.correo pac_correo,pac.alergia pac_alergia',FALSE)
									->from('historias his')
									->join('pacientes pac','pac.id =  his.his_paciente_id')
									->where('his.his_id',$idHistoria)
									->get()
									->row();	

			//DETALLES
			$rsDetalles = $this->db->from("historia_detalles")
                               	   ->where("hid_his_id", $idHistoria)
                                   ->get()
                                   ->result();


			//OTROS
			$rsOtros    = $this->db->from("historia_otros")
                               	   ->where("hio_his_id", $idHistoria)
                                   ->get()
                                   ->result();

            //IMAGENES
			$rsImagenes = $this->db->from("historia_imagenes")
                               	   ->where("hii_his_id", $idHistoria)
                                   ->get()
                                   ->result();

			$rsHistoria->detalles = $rsDetalles;
			$rsHistoria->otros    = $rsOtros;
			$rsHistoria->imagenes = $rsImagenes;
			return $rsHistoria;
		}
	}

	public function guardarHistoria(){
		//REGISTRO DE PACIENTE
		//if ($_POST['paciente_id'] == '') {			
			$rsPaciente =  $this->db->from('pacientes')
									->where('ruc',$_POST['ruc'])
									->get()
									->row();			


			$fecha_nacimiento =  (new DateTime($_POST['fecha_nacimiento']))->format('Y-m-d');
			$arrayPaciente = array('ruc' => $_POST['ruc'],
									   'razon_social' => $_POST['paciente'],
									   'fecha_nacimiento' => $fecha_nacimiento,
									   'edad' => $_POST['edad'],
									   'mes'  => $_POST['mes'],
									   'dia'  => $_POST['dia'],
									   'telefono' => $_POST['telefono'],
									   'alergia'  => $_POST['alergia']);
			if(empty($rsPaciente)){
				$this->db->insert('pacientes',$arrayPaciente);
				$_POST['paciente_id'] = $this->db->insert_id();
			}else{				
				$this->db->where('id',$rsPaciente->id);
				$this->db->update('pacientes',$arrayPaciente);
				$_POST['paciente_id'] = $rsPaciente->id;
			}
		//}

		//ARRAY HISTORIA - ALEXANDER FERNANDEZ DE LA CRUZ 06-11-2020
		$fecha_cita   =  (new DateTime($_POST['fecha_cita']))->format('Y-m-d h:i:s');
		$proxima_cita =  (new DateTime($_POST['proxima_cita']))->format('Y-m-d h:i:s');
		$arrayHistoria =  array('his_paciente_id' => $_POST['paciente_id'],
								'his_profesional_id' => $_POST['profesional'],
								'his_especialidad_id' => $_POST['especialidad'],
								'his_motivo' => $_POST['motivo'],
								'his_documento_venta'  => $_POST['documento_venta'],
								'his_enfermedad_actual'=> $_POST['enfermedad_actual'],
								// cambio para insertar el tipo de enfermedad
					      		'his_codigo_cie'=> substr($_POST['codigo_cie'],0,4),
								'his_diagnostico' => $_POST['diagnostico'],
								'his_tratamiento' => $_POST['tratamiento'],
								'his_ini_peso' => $_POST['peso_ini'],
								'his_ini_talla'=> $_POST['talla_ini'],
								'his_ini_temperatura' => $_POST['temperatura_ini'],
								'his_ini_presion_arterial'=> $_POST['presion_arterial_ini'],
								'his_ini_otros'=> $_POST['otros_ini'],
								'his_fin_peso' => $_POST['peso_fin'],
								'his_fin_talla'=> $_POST['talla_fin'],
								'his_fin_temperatura' => $_POST['temperatura_fin'],
								'his_fin_presion_arterial'=> $_POST['presion_arterial_fin'],
								'his_fin_otros'  => $_POST['otros_fin'],
								'his_fecha_cita' => $fecha_cita,
								'his_proxima_cita' => $proxima_cita,
								'his_recomendacion' => $_POST['recomendacion'],
								'his_historia_estado_id' => $_POST['estado'],
								'his_historia_estadoComprobante_id' => $_POST['estado_documentoVenta']							
								);
		if($_POST['id'] == ''){
			$correlativo = $this->maximoConsecutivo();			
			$arrayHistoria = array_merge($arrayHistoria, array('his_correlativo' => $correlativo,
															   'his_fecha'=> date('Y-m-d h:i:s'),
															   'his_empleado_insert' => $this->session->userdata('empleado_id'),
															   'his_estado' => ST_ACTIVO));
			$this->db->insert('historias',$arrayHistoria);
			$_POST['id'] = $this->db->insert_id();

		} else {

			$this->db->where('his_id',$_POST['id']);
			$this->db->update('historias',$arrayHistoria);
		}

		//INGRESANDO ITEMS - ALEXANDER FERNANDEZ 06-11-2020
		//Eliminamos Items		
		$this->db->where('hid_his_id',$_POST['id']);
		$this->db->delete('historia_detalles');

		//ELIMINAMOS OTROS EXÁMENTES 08-02-2021
		$this->db->where('hio_his_id',$_POST['id']);
		$this->db->delete('historia_otros');

		//Ingresamos Items
		//var_dump($_POST['item_id']);exit;
		$item_id =  $_POST['item_id'];
		$descripcion =  $_POST['descripcion'];
		$cantidad    =  $_POST['cantidad'];
		$dosificacion =  $_POST['dosificacion'];


		//OTROS EXÁMENES 08-02-2021
		$itemOtros_id = $_POST['itemOtros_id'];
		$descripcionOtros = $_POST['descripcionOtros'];
		$cantidadOtros    = $_POST['cantidadOtros'];
		$observacionOtros = $_POST['observacionOtros'];

		$dataItem = array();
		$i=0;
		foreach ($item_id as $value) {
			$result = $this->db->from('productos')
                               ->where('prod_id',$item_id[$i])
                               ->get()
                               ->row();			
		
			$dataItem['hid_his_id'] = $_POST['id'];
			$dataItem['hid_producto_id'] =  $item_id[$i];
			$dataItem['hid_descripcion'] =  $result->prod_nombre;;
			$dataItem['hid_cantidad']    =  $cantidad[$i];
			$dataItem['hid_dosificacion'] =  $dosificacion[$i];
			$dataItem['hid_estado'] =  ST_ACTIVO;
			$this->db->insert('historia_detalles',$dataItem);		
			$i++;	
		}

		//HISTORIA OTROS 08-02-2021
		$dataItem = array();
		$i=0;
		foreach ($itemOtros_id as $value) {
			$result = $this->db->from('productos')
                               ->where('prod_id',$itemOtros_id[$i])
                               ->get()
                               ->row();			
		
			$dataItem['hio_his_id'] = $_POST['id'];
			$dataItem['hio_producto_id'] =  $itemOtros_id[$i];
			$dataItem['hio_descripcion'] =  $result->prod_nombre;
			$dataItem['hio_cantidad']    =  $cantidadOtros[$i];
			$dataItem['hio_observacion'] =  $observacionOtros[$i];
			$dataItem['hio_estado'] =  ST_ACTIVO;
			$this->db->insert('historia_otros',$dataItem);
			$i++;	
		}
		

		if($_FILES['images']['name'][0] != ""){

			//echo $_FILES['images']['name'][0];exit;
			//$this->db->where('hii_his_id',$_POST['id']);
			//$this->db->delete('historia_imagenes');
		//var_dump($_FILES['images']);exit;

        if(is_array($_FILES))  
        {  
            foreach($_FILES['images']['name'] as $name => $value)
                {  
               $file_name = explode(".", $_FILES['images']['name'][$name]);
               $allowed_extension = array("jpg", "jpeg", "png", "gif");  
               if(in_array($file_name[1], $allowed_extension))  
               {                      
                    $sourcePath = $_FILES["images"]["name"][$name];
                    $destino = "images/historias/".$sourcePath;
                    //move_uploaded_file($sourcePath, $targetPath);
                    copy($_FILES['images']['tmp_name'][$name], $destino);
                    $data = array(
                            'hii_his_id' => $_POST['id'],
                            'hii_foto' => $_FILES['images']['name'][$name]
                    );                                                                
                    $this->db->insert("historia_imagenes", $data);
               }  
        }}}


		return $_POST['id'];
	}


	public function getMainList(){

        if ($_POST['paciente_check'] == 'on') {
            $fecha_desde = '';
            $fecha_hasta = '';
        } else {
            $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        	$fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];         
        }		

        $paciente_id  = isset($_POST['paciente']) ? $_POST['paciente'] : $_GET['paciente'];
        $profesional_id = isset($_POST['profesional']) ? $_POST['profesional'] : $_GET['profesional'];        
        $especialidad_id = isset($_POST['especialidad']) ? $_POST['especialidad'] : $_GET['especialidad'];        
        $estado_id       = isset($_POST['estado']) ? $_POST['estado'] : $_GET['estado'];                     
                
        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) <=", $fecha_hasta);
        }        
        if($paciente_id != ''){            
            $this->db->where('his.his_paciente_id',$paciente_id);
        }        
        if($profesional_id != '' OR $profesional_id != null){            
        	//echo $profesional_id;exit;
            $this->db->where('his.his_profesional_id',$profesional_id);
        }   
        if($especialidad_id != ''){            
            $this->db->where('esp.esp_id',$especialidad_id);
        }   
        if($estado_id != ''){            
            $this->db->where('his.his_historia_estado_id',$estado_id);
        }        


		$select = $this->db->select('his.his_id his_id, DATE_FORMAT(his.his_fecha, "%d-%m-%y") his_fecha,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%y %h:%i") his_fecha_cita,DATE_FORMAT(his.his_proxima_cita, "%d-%m-%y %h:%i") his_proxima_cita,pac.razon_social pac_razon_social,pac.telefono pac_telefono,prof.prof_nombre prof_nombre,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_id,hie.hie_descripcion estado,CONCAT(hec.hec_descripcion," / ",his.his_documento_venta) estadoComprobante',FALSE)
		 					->from('historias his')
		 					->join('pacientes pac','pac.id = his.his_paciente_id')
		 					->join('profesionales prof','prof.prof_id =  his.his_profesional_id')
		 					->join('especialidades esp','esp.esp_id = prof.prof_especialidad_id')
		 					->join('empleados emp','emp.id = his.his_empleado_insert')
		 					->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
		 					->join('historia_estadocomprobante hec','hec.hec_id = his.his_historia_estadoComprobante_id')
                         	->where("his_estado",ST_ACTIVO)
                         	->order_by("his_fecha","DESC")
                         	->order_by("hie.hie_order ASC");

	      if($_POST['search'] != ''){
	        $select->like("pac.razon_social",$_POST['search']);
	      }

	      $selectCount = clone $select;
	      $rsCount = $selectCount->get()->result();

	      $rows = count($rsCount);

	      $rsHistorias = $select->limit($_POST['pageSize'],$_POST['skip'])
	                                 ->order_by("his_id","desc")
	                                 ->get()
	                                 ->result();
	      $i=1;
	      foreach ($rsHistorias as $historia) {
	          $historia->id = "<a class='show_galeria' title ='ver' href= '#' data-id='{$historia->his_id}'>{$historia->his_id}</a>";
	          
	          $historia->btn_ticket = '<a href="'.base_url().'index.php/historias/decargarPdf_ticket/'.$historia->his_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$historia->his_id.'" class="descargar-pdf"></a>';
              $historia->boton_pdf = '<a href="'.base_url().'index.php/historias/decargarPdf/'.$historia->his_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$historia->his_id.'" class="descargar-pdf"></a>';    


              	$historia->his_editar = "<a class='btn btn-default btn-xs  btn_modificar_historia' data-id='{$historia->his_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
              if($historia->hie_id == 4){//ESTADO FINALIZADO
              	 if($this->session->userdata('accesoEmpleado') == ''){
              		$historia->his_editar  = "<a class='btn btn-default btn-xs  btn_modificar_historia' data-id='{$historia->his_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
              	}else{
              		$historia->his_editar  = "<a class='btn btn-default btn-xs'>Modificar</a>";
              	}
              }		          

	          $historia->his_eliminar= "<a class='btn btn-default btn-xs btn_eliminar_historia' data-id='{$historia->his_id}' data-msg='Desea Eliminar Historia: {$historia->his_id} ?'>Eliminar</a>";
	        $i++;
	      }

	      $datos = [
	          'data' => $rsHistorias,
	          'rows' => $rows
	      ];
	      return $datos;
	}



	public function selectAutocompleteprod($buscar){           
        $where = '(pr.prod_estado='.ST_ACTIVO.' AND pr.prod_nombre LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo LIKE "%'.$buscar.'%") OR (pr.prod_estado='.ST_ACTIVO.' AND pr.prod_codigo_barra LIKE "%'.$buscar.'%") ';
        $result = $this->db->from('productos pr')
                            ->join("categoria cat","pr.prod_categoria_id=cat.cat_id")
                            ->join("medida md","pr.prod_medida_id=md.medida_id")                            
                            ->where($where)                            
                            ->get()
                            ->result();                             

        $data = array();    
        foreach ($result as $prod){
          $stock = $this->productos_model->getStockProductos($prod->prod_id,$this->session->userdata("almacen_id"));
          $producto_stock = ($stock!='')?$stock:'0';

            $data[] = array(
                "value" => $prod->prod_nombre." / Stock: ".$producto_stock,
                "codigo" => $prod->prod_codigo,
                "precio" => $prod->prod_precio_publico,                    
                "precioCosto" => $prod->prod_precio_compra,
                "id" => $prod->prod_id,
                "prod_cat_id" => $prod->prod_categoria_id,
                "prod_stock" => $producto_stock,
                "categoria" => $prod->cat_nombre,
                "prod_medida_id" => $prod->prod_medida_id,
                "medida" => $prod->medida_nombre              
            );
        } 
       
        return $data;
    }


    public function maximoConsecutivo()
    {
        //obtenemos el maximo consecutivo del las historias
        $select = $this->db->from("historias")
                           ->select_max("his_correlativo")
                           ->get()
                           ->row();		              

        $rsMayorConsecutivo = $select->his_correlativo;
        $rsMayorConsecutivo++;

        return $rsMayorConsecutivo;
    }



    public function exportarReporteHistoria(){
    	if ($_GET['paciente_check'] == 'on') {
            $fecha_desde = '';
            $fecha_hasta = '';
        } else {
            $fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
        	$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';         
        }    	

        $paciente_id = isset($_GET['paciente_s_id']) ? $_GET['paciente_s_id'] : '';
        $profesional_id = isset($_GET['profesional_s']) ? $_GET['profesional_s'] : '';
        $especialidad_id = isset($_GET['especialidad_s']) ? $_GET['especialidad_s'] : '';
        $estado_id       = isset($_GET['estado_s']) ? $_GET['estado_s'] : '';
                
        if($fecha_desde != ''){
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(his.his_fecha_cita) <=", $fecha_hasta);
        }        
        if($paciente_id != ''){            
            $this->db->where('his.his_paciente_id',$paciente_id);
        }        
        if($profesional_id != '' OR $profesional_id != null){            
        	//echo $profesional_id;exit;
            $this->db->where('his.his_profesional_id',$profesional_id);
        }   
        if($especialidad_id != ''){            
            $this->db->where('esp.esp_id',$especialidad_id);
        }   
        if($estado_id != ''){            
            $this->db->where('his.his_historia_estado_id',$estado_id);
        }        

		$rsHistorias = $this->db->select('his.his_id his_id, DATE_FORMAT(his.his_fecha, "%d-%m-%y") his_fecha,DATE_FORMAT(his.his_fecha_cita, "%d-%m-%y %h:%i") his_fecha_cita,DATE_FORMAT(his.his_proxima_cita, "%d-%m-%y %h:%i") his_proxima_cita,pac.razon_social pac_razon_social,pac.telefono pac_telefono,prof.prof_nombre prof_nombre,esp.esp_descripcion,CONCAT(emp.nombre," ",emp.apellido_paterno) empleado,hie.hie_id,hie.hie_descripcion estado,CONCAT(hec.hec_descripcion," / ",his.his_documento_venta) estadoComprobante',FALSE)
		 					->from('historias his')
		 					->join('pacientes pac','pac.id = his.his_paciente_id')
		 					->join('profesionales prof','prof.prof_id =  his.his_profesional_id')
		 					->join('especialidades esp','esp.esp_id = prof.prof_especialidad_id')
		 					->join('empleados emp','emp.id = his.his_empleado_insert')
		 					->join('historia_estados hie','hie.hie_id = his.his_historia_estado_id')
		 					->join('historia_estadocomprobante hec','hec.hec_id = his.his_historia_estadoComprobante_id')
                         	->where("his_estado",ST_ACTIVO)
                         	->order_by("his_fecha","DESC")
                         	->order_by("hie.hie_order ASC")
                         	->get()
	                        ->result();	      

	      return $rsHistorias;
    }
}
