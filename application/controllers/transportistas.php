<?php


class Transportistas extends CI_Controller
{
	
	function __construct()
	{
	parent::__construct();

	$this->load->model("transportistas_model");
	}



	public function index(){

   $this->load->view('templates/header_administrador');
    $this->load->view("transportistas/index");
    $this->load->view('templates/footer');
	}

	public function vista(){
 
 	//echo 123123;exit;
   $vistas=$this->transportistas_model->mostrar();
   //var_dump($vista);exit;
   echo json_encode($vistas);

	}

	public function mostrar(){
    $this->load->view("transportistas/mostrarimportes");
	}

	

	public function crear(){
   $data=array();
   $data['transportista']=$this->transportistas_model->select();
   echo $this->load->view("transportistas/modal_crear",$data);
	}

	public function editar($transportistas){
    $data['transportistas']=$this->transportistas_model->select($transportistas);
    $this->load->view("transportistas/modal_crear",$data);


	}

	public function guardar(){

 $res=$this->transportistas_model->guardar();
 if ($res) {
 	 echo json_encode(['status'=>STATUS_OK]);
 	  exit();
 }else{


echo json_encode(['status'=>STATUS_FAIL, 'tipo'=>2]);
 exit();

 }

}


public function eliminar($transportistas){


$eliminar=$this->transportistas_model->eliminar($transportistas);

if ($eliminar) {
echo   json_encode(['status' => STATUS_OK]);
exit();
}else{

echo json_encode(['status' => STATUS_FAIL]);
exit();
}





}
public function importarTrans(){

      $archivo = $_FILES['files'];
        
        //establecemos la ruta desde donde leeremos
        $rutaArchivo = $archivo['tmp_name'];
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
        $spreadsheet = $reader->load($rutaArchivo);
        $sheet = $spreadsheet->getActiveSheet();
        $arrayClientes = array();
         $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
           foreach($sheet->getRowIterator(2) as $row)
        {

       $transportistas=array();    
        $transp_ruc= $sheet->getCellByColumnAndRow('2', $row->getRowIndex())->getValue();  
        $transportistas['transp_ruc']=$transp_ruc;
        $rsruc=$this->db->from("transportistas")
                     ->get()
                     ->row();

         $transp_nombre= $sheet->getCellByColumnAndRow('3', $row->getRowIndex())->getValue();
          $transportistas['transp_nombre']=$transp_nombre;  
          $rsnombre=$this->db->from("transportistas")
                     ->get()
                     ->row();
          
           $transp_direccion= $sheet->getCellByColumnAndRow('4', $row->getRowIndex())->getValue(); 
            $transportistas['transp_direccion']=$transp_direccion; 
          $rsdireccion=$this->db->from("transportistas")
                     ->get()
                     ->row();

           $transp_telefono= $sheet->getCellByColumnAndRow('5', $row->getRowIndex())->getValue();
            $transportistas['transp_telefono']=$transp_telefono; 
          $rstelefono=$this->db->from("transportistas")
                     ->get()
                     ->row(); 

             $transp_tipounidad= $sheet->getCellByColumnAndRow('6', $row->getRowIndex())->getValue(); 
              $transportistas['transp_tipounidad']=$transp_tipounidad;
          $rstipounidad=$this->db->from("transportistas")
                     ->get()
                     ->row();

                   $transp_placa= $sheet->getCellByColumnAndRow('7', $row->getRowIndex())->getValue();
                    $transportistas['transp_placa']=$transp_placa;  
            $rsplaca=$this->db->from("transportistas")
                     ->get()
                     ->row();
          
            $transp_licencia= $sheet->getCellByColumnAndRow('8', $row->getRowIndex())->getValue();
             $transportistas['transp_licencia']=$transp_licencia;  
            $rslicencia=$this->db->from("transportistas")
                     ->get()
                     ->row(); 

              $transp_observacion= $sheet->getCellByColumnAndRow('9', $row->getRowIndex())->getValue();
               $transportistas['transp_observacion']=$transp_observacion;  
            $rsobservacion=$this->db->from("transportistas")
                     ->get()
                     ->row();        
                                                  

            $this->db->insert("transportistas",$transportistas);

        }
  
	}


}