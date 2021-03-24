<?php

class Icbper extends CI_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model("icbper_model");
        $this->load->model("activos_model");
	}

	public function index(){
    $this->load->view('templates/header_administrador');
    $this->load->view("icbper/index");
    $this->load->view('templates/footer');
	}

	public function vista(){

     $vista=$this->icbper_model->mostrar();
     echo json_encode($vista);

	}

	public function crear(){
    
    $data=array();
    $data['activos'] = $this->activos_model->select();
    $data['icbper']=$this->icbper_model->select();
    $this->load->view("icbper/modal_crear",$data);
	}


	public function guardar(){

     $guardar=$this->icbper_model->guardar();
       if($guardar)
        {
            echo json_encode(['status'=>STATUS_OK]);
            exit();
        }else{        
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }
    }


    public function editar($icbper){

        $data['activos'] = $this->activos_model->select();
        $data['icbPer']=$this->icbper_model->select($icbper);
        $this->load->view("icbper/modal_crear",$data);
    }


    public function eliminar($icbper){
        $eliminado=$this->icbper_model->eliminar($icbper);
        if ($eliminado) {
          echo json_encode(['status' => STATUS_OK]);
          exit();
        } else{
          echo json_encode(['status' => STATUS_FAIL]);
          exit();
            }
    }
}