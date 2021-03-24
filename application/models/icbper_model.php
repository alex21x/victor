<?php

/**
 * 
 */
class Icbper_model extends CI_Model
{
	
	function __construct()
	{
	parent::__construct();
	}

	public function select($icbper=''){

		if ($icbper=='') {
    $todo=$this->db->from("icbper")
                   ->where("estado",ST_ACTIVO)
                   ->get()
                   ->result();
                   return $todo;

		}

		else{
     $fila=$this->db->from("icbper")
                      ->where("icbPer_id",$icbper) 
                      ->where("estado",ST_ACTIVO)
                      ->get()
                      ->row();
                 return $fila;

		}
	}


  public function selectIcbPerActivo(){
      $rsActivo =  $this->db->from('icbper')      
                            ->where('icbPer_activo', 'activo')
                            ->where('estado', ST_ACTIVO)
                            ->get()
                            ->row();
      return   $rsActivo;
  }


	public function mostrar(){

    $mostrar=$this->db->from("icbper")
                      ->where("estado",ST_ACTIVO) 
                      ->get()
                      ->result();

        $rows=count($mostrar);
        
    foreach ($mostrar as $value) {
               
       $value->modificar="<a class='btn btn-default btn-xs btn_modificar' data-id='{$value->icbPer_id}' data-toggle='modal' data-target='#myModal'>modificar</a>";

       $value->eliminar="<a class='btn btn-default btn-xs btn_eliminar' data-id='{$value->icbPer_id}' data-msg='desea eliminar'>eliminar</a>";
       }

      $datos=[
            'data'=>$mostrar,
            'rows'=>$rows
             ];

         return $datos;
	}


	public function guardar(){


  $rsActivo =  $this->db->from('icbper')
              ->where('icbPer_activo','activo')
              ->where('estado', ST_ACTIVO)
              ->get()
              ->row();

  $rowActivo = count($rsActivo);
  //SOLO PUEDE ESTAR HABILITADO UN ICBPER
  if($_POST['icbPer_activo'] == 'activo' && $rowActivo > 0){ return FALSE;}

     if ($_POST['icbPer_id']=='') {
     	
   
     $insertar=[
   
               'icbPer_id'=>$_POST['icbPer_id'],
               'icbPer_nombre'=>$_POST['icbPer_nombre'],
               'icbPer_valor'=>$_POST['icbPer_valor'],
               'icbPer_fecha'=>$_POST['icbPer_fecha'],
               'icbPer_activo'=>$_POST['icbPer_activo'],
               'estado'=>ST_ACTIVO 
               ];

         $this->db->insert("icbper",$insertar);
        // $this->session->set_userdata("$insertar");
     }
     else{
        $modificar=[
               'icbPer_id'=>$_POST['icbPer_id'],
               'icbPer_nombre'=>$_POST['icbPer_nombre'],
               'icbPer_valor'=>$_POST['icbPer_valor'],
               'icbPer_fecha'=>$_POST['icbPer_fecha'],
               'icbPer_activo'=>$_POST['icbPer_activo']   
             ];

          $this->db->where("icbPer_id",$_POST['icbPer_id']);
          $this->db->update("icbper",$modificar);   
     
     }

     return true;


	}


 public function eliminar($icbper){

 	$data=[
          'estado'=>ST_ELIMINADO

 	      ];
    $this->db->where("icbPer_id",$icbper);
    $this->db->update("icbper",$data);
   return true;

 }

}