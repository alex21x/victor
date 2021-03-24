<?php 

class Eventos_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

   public function select($idEvento = '')
   {
   	if ($idEvento == ''){
   		$rsEventos = $this->db->from('eventos')
             							  ->get()
             							  ->result();
   		return $rsEventos;
   	 }else {
   	   $rsEvento = $this->db->select('eve.*,DATE_FORMAT(eve.fecha,"%d-%m-%Y %h:%i:%s") fecha,cli.razon_social cli_razon_social,cli.domicilio1 domicilio1',FALSE)
                            ->from('eventos eve')
                            ->join('clientes cli','eve.cliente_id = cli.id',"LEFT")
   	                        ->where('eve.id',$idEvento)
   	                        ->get()
   	                        ->row();
   	   return $rsEvento;
   	}
   }

   public function guardarEvento()   
   {              
      //GUARDAMOS SI ES CLIENTE NUBE 01-03-2021
      if(!is_int($_POST['cliente_id']))
         $_POST['cliente_id'] = $this->clientes_model->guardarClienteNube();

      $epp = ($_POST['epp'] == 'on') ? 1 : 0;
      $fecha_evento   =  (new DateTime($_POST['fecha_evento']))->format('Y-m-d h:i:s');
       if ($_POST['id'] != '') {
       	        $dataUpdate =  [
                                'cliente_id' => $_POST['cliente_id'],
       	           	            'tipo_evento_id' => $_POST['tipo_evento'],
       	           	            'turno_id' => $_POST['turno'],
       	           	            'fecha'  => $fecha_evento,
                                'ingreso'=> $_POST['hora_ingreso'],
                                'salida' => $_POST['hora_salida'],
                                'responsable'=> $_POST['responsable'],
                                'observacion'=> $_POST['observacion'],
                                'epp'=> $epp,
                                'placa'=> strtoupper($_POST['placa']),
                                'num_documento'=> $_POST['num_documento'],
                                'num_guia'=> $_POST['guia'],
                                'otros'   => $_POST['otros'],
                                'empleado_insert' => $this->session->userdata('empleado_id'),
                                'fecha_insert'=> date('Y-m-d')
       	           			      ];
       	         $this->db->where('id',$_POST['id']);  			   
       	         $this->db->update('eventos',$dataUpdate);

       } else{       
               $dataInsert = [
                                'cliente_id' => $_POST['cliente_id'],
       	           	            'tipo_evento_id' => $_POST['tipo_evento'],
                                'turno_id' => $_POST['turno'],
                                'fecha'  => $fecha_evento,
                                'ingreso'=> $_POST['hora_ingreso'],
                                'salida' => $_POST['hora_salida'],
                                'responsable'=> $_POST['responsable'],
                                'observacion'=> $_POST['observacion'],
                                'epp'=> $epp,
                                'placa'=> strtoupper($_POST['placa']),
                                'num_documento'=> $_POST['num_documento'],
                                'num_guia'=> $_POST['guia'],
                                'otros'   => $_POST['otros'], 
                                'empleado_insert' => $this->session->userdata('empleado_id'),
                                'fecha_insert'=> date('Y-m-d h:i:s'),
                                'estado'=> ST_ACTIVO
                            ];       
                    $this->db->insert('eventos',$dataInsert);
                    $_POST['id'] = $this->db->insert_id();

        }

        if($_FILES['images']['name'][0] != ""){
      
        if(is_array($_FILES))  
        {  
            foreach($_FILES['images']['name'] as $name => $value)
                {  
               $file_name = explode(".", $_FILES['images']['name'][$name]);
               $allowed_extension = array("jpg", "jpeg", "png", "gif");  
               if(in_array($file_name[1], $allowed_extension))  
               {                      
                    $sourcePath = $_FILES["images"]["name"][$name];
                    $destino = "images/eventos/".$sourcePath;
                    //move_uploaded_file($sourcePath, $targetPath);
                    copy($_FILES['images']['tmp_name'][$name], $destino);
                    $data = array(
                            'evento_id' => $_POST['id'],
                            'evento_imagen' => $_FILES['images']['name'][$name],
                            'estado'  => ST_ACTIVO
                    );                                                                
                    $this->db->insert("evento_imagenes", $data);
               }  
        }}}
            
    return $_POST['id'];
   }

  public function eliminar($idevento){
  
 
     	$datoseliminar = [
     	             	  'estado' => ST_ELIMINADO
     	             	 ];
      	$this->db->where('id',$idevento);
      	$this->db->update('eventos',$datoseliminar);
      	return true;
  }
  
  public function getMainList(){
        
        if ($_POST['ver_todos'] == 'on') {
            $fecha_desde = '';
            $fecha_hasta = '';
        } else {
            $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
            $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
        }   
        //echo $_POST['placa'];exit;
        //filtros de busqueda          
          $numero_documento  = isset($_POST['numero_documento']) ? $_POST['numero_documento'] : $_GET['numero_documento'];
          $cliente  = isset($_POST['cliente']) ? $_POST['cliente'] : $_GET['cliente'];
          $placa    = isset($_POST['placa']) ? $_POST['placa'] : $_GET['placa'];
          $empleado  = isset($_POST['empleado']) ? $_POST['empleado'] : $_GET['empleado'];


        if($fecha_desde != ''){
           $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(eve.fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
          $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
          $this->db->where("DATE(eve.fecha) <=", $fecha_hasta);
        }        
        if($placa != ''){            
          $this->db->where('eve.placa',$placa);  
        }
        if($cliente != ''){            
          $this->db->where('eve.cliente_id',$cliente);  
        }
        if($numero_documento != ''){            
          $this->db->where('eve.id',$numero_documento);
        }
        if($empleado != ''){            
          $this->db->where('eve.empleado_insert',$empleado);
        }

          $select = $this->db->select("eve.id evento_id,DATE_FORMAT(eve.fecha,'%d/%m/%Y') fecha_evento,eve.ingreso,eve.salida,eve.responsable,eve.placa,cli.razon_social cli_razon_social,tev.tipo_evento,CONCAT(epl.nombre,' ',epl.apellido_paterno) empleado",FALSE)
                           ->from("eventos eve")
                           ->join("tipo_eventos tev","eve.tipo_evento_id = tev.id")
                           ->join("clientes cli","eve.cliente_id =  cli.id","LEFT")
                           ->join("empleados epl","eve.empleado_insert = epl.id")
                           ->where("eve.estado", ST_ACTIVO);

        if($_POST['search'] != '')
        {
            $select->like("cli.razon_social", $_POST['search']);
        }                   

        $selectCount = clone $select;
        $rsCount = $selectCount->get()
                               ->result();

        $rows = count($rsCount);        
        $rsEventos = $select->limit($_POST['pageSize'],$_POST['skip'])
                            ->order_by("eve.id", "desc")
                            ->get()
                            ->result();                                          

        foreach($rsEventos as $rsEvento)
        {            
                $rsEvento->btn_ticket = '<a href="'.base_url().'index.php/eventos/descargarPdf_ticket/'.$rsEvento->evento_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$rsEvento->evento_id.'" class="descargar-pdf"></a>';
                 $rsEvento->btn_pdf = '<a href="'.base_url().'index.php/eventos/descargarPdf/'.$rsEvento->evento_id.'" target="_seld" ><img src="'.base_url().'/images/pdf.png" data-id="'.$rsEvento->evento_id.'" class="descargar-pdf"></a>';
                $rsEvento->eve_editar = "<a class='btn btn-default btn-xs btn_modificar_evento' data-id='{$rsEvento->evento_id}' data-toggle='modal' data-target='#myModal'>Modificar</a>";
                $rsEvento->eve_eliminar = "<a class='btn btn-danger btn-xs btn_eliminar_evento' data-id='{$rsEvento->evento_id}' data-msg='Desea eliminar el evento'>Eliminar</a>";            
        }

        $datos = [
                    'data' => $rsEventos,
                    'rows' => $rows
                 ];

        return $datos;      
    }

     //SELECT AUTOCOMPLETE 06-10-2020
    public function selectAutocomplete($buscar){        
        $where = '(even.estado='.ST_ACTIVO.' AND even.id LIKE "%'.$buscar.'%")';
        $result = $this->db->from('evento even')                                        
                            ->where($where)
                            ->get()
                            ->result();
                        
        $data = array();    
        foreach ($result as $cat){
            $data[] = array(
              "value" => $cat->cat_nombre,             
             "id" => $cat->cat_id 
            );
        }        
        return $data;
    }

    public function reporteEventos(){
          if ($_GET['ver_todos'] == 'on') {
              $fecha_desde = '';
              $fecha_hasta = '';
          } else {
              $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
              $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta'];
          }                       
          $numero_documento  = isset($_POST['numero_documento']) ? $_POST['numero_documento'] : $_GET['numero_documento'];
          $cliente  = isset($_POST['cliente_s']) ? $_POST['cliente_s'] : $_GET['cliente_s'];
          $placa    = isset($_POST['placa']) ? $_POST['placa'] : $_GET['placa'];
          $empleado = isset($_POST['empleado']) ? $_POST['empleado'] : $_GET['empleado'];


        if($fecha_desde != ''){
           $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(eve.fecha) >=", $fecha_desde);
          }
        if($fecha_hasta != ''){
          $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
          $this->db->where("DATE(eve.fecha) <=", $fecha_hasta);
        }        
        if($placa != ''){            
          $this->db->where('eve.placa',$placa);  
        }
        if($cliente != ''){            
          $this->db->where('eve.cliente_id',$cliente);  
        }
        if($numero_documento != ''){            
          $this->db->where('eve.id',$numero_documento);
        }
        if($empleado != ''){            
          $this->db->where('eve.empleado_insert',$empleado);
        }

          $rsEventos = $this->db->select("eve.id evento_id,DATE_FORMAT(eve.fecha,'%d/%m/%Y') fecha_evento,eve.ingreso,eve.salida,eve.responsable,eve.placa,eve.observacion,TIMEDIFF(eve.salida, eve.ingreso) totalHoras,eve.num_documento,eve.num_guia,eve.otros,cli.razon_social cli_razon_social,tev.tipo_evento,tur.turno,CONCAT(epl.nombre,' ',epl.apellido_paterno) empleado",FALSE)
                           ->from("eventos eve")
                           ->join("tipo_eventos tev","eve.tipo_evento_id = tev.id")
                           ->join("clientes cli","eve.cliente_id =  cli.id","LEFT")
                           ->join("turnos tur","eve.turno_id = tur.id")
                           ->join("empleados epl","eve.empleado_insert = epl.id")
                           ->where("eve.estado", ST_ACTIVO)
                           ->get()
                           ->result_array();
            return $rsEventos;
        }  
 }