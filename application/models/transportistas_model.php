<?php


/**
 * 
 */
class Transportistas_model extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

 public function select($transportistas=''){

    if ($transportistas=='') {
       $result=$this->db->from("transportistas")
                        ->get()
                        ->result();

         return $result;

    }else{


      $result=$this->db->from("transportistas")
                       ->where("transp_id",$transportistas)
                       ->get()
                       ->row();
                       return $result;
 
    }

    }

    public function mostrar(){

$mostrar=$this->db->from("transportistas")
                  ->get()
                  ->result();

$rows=count($mostrar);


foreach ($mostrar as $value) {
    $value->hab_editar = "<a class='btn btn-default btn-xs  btn_modificar_transportistas' data-id='{$value->transp_id}'
      data-toggle='modal' data-target='#myModal'>Modificar</a>";

     $value->hab_eliminar = "<a class='btn btn-default btn-xs  btn_eliminar_transportistas' data-id='{$value->transp_id}' data_msg='Desea Eliminar Grado:
    {$value->transp_id} ?'>Eliminar</a>";
}


  $datos = [
              'data' => $mostrar,
               'rows' => $rows
            ];

            return $datos;




    }

    public function guardar(){
             
          if($_POST['transp_id'] == ''){
             $dataInsert = [
            'transp_id' => $_POST['transp_id'],
           'transp_ruc' =>$_POST['transp_ruc'],
            'transp_nombre' => $_POST['transp_nombre'],
            'transp_direccion' => $_POST['transp_direccion'],
            'transp_telefono' => $_POST['transp_telefono'],
            'transp_tipounidad' => $_POST['transp_tipounidad'],
             'transp_placa' => $_POST['transp_placa'],
              'transp_licencia' => $_POST['transp_licencia'],
              'transp_observacion' => $_POST['transp_observacion']
      ];
      $this->db->insert('transportistas',$dataInsert);

    }else{

     $modificar=[
            'transp_id' => $_POST['transp_id'],
           'transp_ruc' =>$_POST['transp_ruc'],
            'transp_nombre' => $_POST['transp_nombre'],
            'transp_direccion' => $_POST['transp_direccion'],
            'transp_telefono' => $_POST['transp_telefono'],
            'transp_tipounidad' => $_POST['transp_tipounidad'],
            'transp_placa' => $_POST['transp_placa'],
             'transp_licencia' => $_POST['transp_licencia'],
             'transp_observacion' => $_POST['transp_observacion']

          ];
       $this->db->where('transp_id',$_POST['transp_id']);
       $this->db->update("transportistas",$modificar);
    }
return true;

}


public function eliminar($transportistas){


$this->db->where("transp_id",$transportistas);
$this->db->delete("transportistas");

return true;

}
    









}