<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Igv_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }    
    
    public function select($igv = ''){

    if ($igv=='') {
        $result=$this->db->from("igv")
                        ->get()
                        ->result();
         return $result;

    }else{
        $result=$this->db->from("igv")
                       ->where("id",$igv)
                       ->get()
                       ->row();
                       return $result;
      }
    }


    public function selectIgvActivo(){

      $rsActivo =  $this->db->from('igv')      
                            ->where('activo', 'activo')
                            ->where('estado', ST_ACTIVO)
                            ->get()
                            ->row();

      return   $rsActivo;
    }


    public function pintar(){              
        $result=$this->db->from("igv")
                         ->where("estado",ST_ACTIVO)
                         ->get()
                         ->result();

        
         $rows = count($result);
         foreach ($result as $value) {

          $value->hab_editar = "<a class='btn btn-default btn-xs  btn_modificar_igv' data-id='{$value->id}'
            data-toggle='modal' data-target='#myModal'>Modificar</a>";

          $value->hab_eliminar = "<a class='btn btn-default btn-xs  btn_eliminar_igv' data-id='{$value->id}' data_msg='Desea Eliminar Grado:
            {$value->id} ?'>Eliminar</a>";  
         }

         $datos = [
                    'data' => $result,
                    'rows' => $rows
                ];

                return $datos;
            }

    public function guardar(){

      $rsActivo =  $this->db->from('igv')
                            ->where('activo','activo')
                            ->where('estado', ST_ACTIVO)
                            ->get()
                            ->row();

  $rowActivo = count($rsActivo);
  //SOLO PUEDE ESTAR HABILITADO UN IGV
  if($_POST['activo'] == 'activo' && $rowActivo > 0){ return FALSE;}
             
          if($_POST['id'] == ''){
             $dataInsert = array(
                  'id' => $_POST['id'],
                  'valor' =>$_POST['valor'],
                  'activo' => $_POST['activo'],
                  'fecha' => $_POST['fecha'],
                  'estado'=>ST_ACTIVO
                );
          $this->db->insert('igv',$dataInsert);
    } else {
              $dataUpdate = array(
                'id' => $_POST['id'],
                'valor' =>$_POST['valor'],
                'activo' => $_POST['activo'],
                'fecha' => $_POST['fecha']
              );
       $this->db->where('id',$_POST['id']);
       $this->db->update("igv",$dataUpdate);      
    }
      return true;
}

public function eliminar($igv){

  $igvUpdate=[
        "estado" => ST_ELIMINADO
    ];

$this->db->where("id",$igv);
$this->db->update("igv", $igvUpdate);
  return true;


}}