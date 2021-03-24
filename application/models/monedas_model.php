<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Monedas_model extends CI_Model {

    public function __construct() {
        parent::__construct();


    }    
    
  /*  public function select($id = FALSE) {
        if ($id != FALSE) {
            $sql = "SELECT *FROM monedas
                    WHERE id = " . $id;
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }

        $sql = "SELECT *FROM monedas WHERE 1 = 1 ";
        $query = $this->db->query($sql);
        $rows = array();
        foreach($query->result_array() as $row){
            $rows[] = $row;
        }

        return $rows;
    }
    */



    public function select($monedas=''){

    if ($monedas=='') {
       $result=$this->db->from("monedas")
                        ->get()
                        ->result();

         return $result;

    }else{


      $result=$this->db->from("monedas")
                       ->where("id",$monedas)
                       ->get()
                       ->row();
                       return $result;
 
    }

    }

      public function pintar(){
       
        
        $result=$this->db->from("monedas")
                           ->where("estado",ST_ACTIVO)
                            ->get()
                            ->result();

        
        $rows = count($result);
         foreach ($result as $value) {

          $value->hab_editar = "<a class='btn btn-default btn-xs  btn_modificar_monedas' data-id='{$value->id}'
      data-toggle='modal' data-target='#myModal'>Modificar</a>";

    $value->hab_eliminar = "<a class='btn btn-default btn-xs  btn_eliminar_monedas' data-id='{$value->id}' data_msg='Desea Eliminar Grado:
    {$value->id} ?'>Eliminar</a>";
           # code...
         }
 


         $datos = [
                    'data' => $result,
                    'rows' => $rows
                ];

                return $datos;

            }

            public function guardar(){
             
          if($_POST['id'] == ''){
             $dataInsert = [
            'id' => $_POST['id'],
           'moneda' =>$_POST['moneda'],
            'abreviado' => $_POST['abreviado'],
            'abrstandar' => $_POST['abrstandar'],
            'simbolo' => $_POST['simbolo'],
            'estado'=>ST_ACTIVO
      ];
      $this->db->insert('monedas',$dataInsert);

    }else{

     $modificar=[
            'id' => $_POST['id'],
            'moneda' =>$_POST['moneda'],
            'abreviado' => $_POST['abreviado'],
            'abrstandar' => $_POST['abrstandar'],
            'simbolo' => $_POST['simbolo']


          ];
       $this->db->where('id',$_POST['id']);
       $this->db->update("monedas",$modificar);
       

    }
return true;

}

public function eliminar($monedas){

  $monedasUpdate=[
        "estado" => ST_ELIMINADO
    ];

$this->db->where("id",$monedas);
$this->db->update("monedas", $monedasUpdate);
  return true;


}



}


   /* $dataUpdate = [
            'id' => $_POST['id'],
            'moneda' =>$_POST['moneda'],
            'abreviado' => $_POST['abreviado'],
            'abrstandar' => $_POST['abrstandar'],
            'simbolo' => $_POST['simbolo']

          ];
    $this->db->where('id',$_POST['id']);
    $this->db->update('monedas',$dataUpdate);*/
   
  
           





           



        

