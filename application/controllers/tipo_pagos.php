<?php

/**
 * 
 */
class Tipo_pagos extends CI_Controller
{
	
	function __construct()
	{
	  parent::__construct();

	  $this->load->model("tipo_pagos_model");

	}


	public function index(){

      $this->load->view('templates/header_administrador');
    $this->load->view("tipopagos/index");
     $this->load->view('templates/footer');



	}


	public function vista(){
     
    $mostrar=$this->tipo_pagos_model->mostrar();
    //var_dump($mostrar);
    echo json_encode($mostrar);



	}

	public function crear(){

      
     $data=array();
     $data['tipo_pagos'] = $this->tipo_pagos_model->select();
 	echo $this->load->view('tipopagos/modal_crear',$data);



	}


	public function guardar(){
     
     $result = $this->tipo_pagos_model->guardar();
        if($result)
        {
            echo json_encode(['status'=>STATUS_OK]);
            exit();
        }else
        {
            echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
            exit();
        }   



	}


	public function editar($tipo_pagos){
  
  $data['tipo_pago'] = $this->tipo_pagos_model->select($tipo_pagos);

   $this->load->view('tipopagos/modal_crear',$data);



	}


    public function eliminar($tipo_pagos){

    $result=$this->tipo_pagos_model->eliminar($tipo_pagos);
    if ($result) {
        echo json_encode(['status' => STATUS_OK]);
        exit();


    }else{

     echo json_encode(['status' =>STATUS_FAIL]);

  exit();

    }
    
    }



}