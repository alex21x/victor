<?php

 class Documentos extends CI_controller
 {
 	public function __construct()
 	{
 		parent:: __construct();
        $this->load->model('accesos_model');
        $this->load->model('documentos_model');
        $this->load->model('eventos_model');
        $this->load->model('empleados_model');
        $this->load->model('documento_archivos_model');
 	}

 	public function index()
 	{
       $data['vendedores'] = $this->empleados_model->select2(3);
 	     $this->accesos_model->menuGeneral();
       $this->load->view('documentos/basic_index',$data);
       $this->load->view('templates/footer');
 	}
 	public function crear()
 	{
 	   $this->load->view('documentos/modal_crear');
 	}

  public function editar()
  {      
     $data['documento'] = $this->documentos_model->select($this->uri->segment(3));     
     $this->load->view('documentos/modal_crear',$data);
  }

 	public function guardar_documento()
 	{
      // echo $_POST['descripcion'];exit;
        $error = array();      
        if($_POST['descripcion'] == '')
        {
           $error['descripcion'] = 'falta ingresar tipo_evento';
        }      
     
        if(count($error) > 0)
        {
            $data = ['status'=>STATUS_FAIL,'tipo'=>1, 'errores'=>$error];
            echo json_encode($data);
            exit();
        }   
            
        //guardamos el documento
        $result = $this->documentos_model->guardar_documento();
        if($result)
        {
            echo json_encode(['status'=>STATUS_OK,'descri_doc' => $result]);
            exit();
        }else
        {
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }
 	}
       public function getMainList()
  {
           $rsEventos =  $this->documentos_model->getMainList();
           echo json_encode($rsEventos);
  }

  public function getMainListDetail()
    {
        $rsDatos = $this->documentos_model->getMainListDetail();
        sendJsonData($rsDatos);        
    }


   public function eliminarDocumentoArchivo(){    
        $result = $this->documento_archivos_model->eliminar($this->uri->segment(3));
        $documento_archivos = $this->documento_archivos_model->select('',$this->uri->segment(4));

        $rsHI =  '';                      
            foreach($documento_archivos as $value)
                {              
                    $rsHI .= '<div class="col-xs-12 col-md-12 col-lg-12"><li><strong>'.$value->descri_archi. '</strong>
                              <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$value->archi_id.'"></span></li></div>';                              
                }     
        echo $rsHI;
    }

     public  function eliminar($documentoId)
     {
        $result = $this->documentos_model->eliminar($documentoId);
        if ($result) {
                      echo json_encode(['status'=>STATUS_OK]);
                      exit();
                       } else {
                      echo json_encode(['status'=>STATUS_FAIL]);
                      exit();
                       }
                                      
     }
 } 
 ?>

