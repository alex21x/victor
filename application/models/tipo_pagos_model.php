<?PHP
    if(!defined('BASEPATH'))
        exit ('No direct script access allowed');
    
    
    class Tipo_pagos_model extends CI_Model {
     
        public function __construct() {
            parent::__construct();
            //$this->load->database();
        }
        
        public function select($tipo_pagos=''){
            if ($tipo_pagos==''){

              $result=$this->db->from("tipo_pagos")
                               ->where("estado",ST_ACTIVO)
                               ->get()
                               ->result();
                             return $result;
              }
          else{
            $result=$this->db->from("tipo_pagos")
                             ->where("id",$tipo_pagos)
                             ->where("estado",ST_ACTIVO)
                             ->get()
                             ->row();

                             return $result;
            }
        }

        //SELECT PARA COMPROBANTES DE AL CREDITO
        public function select_cc($tipo_pagos=''){
            if ($tipo_pagos=='') {

              $result=$this->db->from("tipo_pagos")
                               ->where('id !=', 2)// 2 COMPROBANTES AL CRÉDITO
                               ->where("estado",ST_ACTIVO)
                               ->get()
                               ->result();
                             return $result;
            }
          else{
            $result=$this->db->from("tipo_pagos")
                             ->where("id",$tipo_pagos)                             
                             ->where("estado",ST_ACTIVO)
                             ->get()
                             ->row();
                             return $result;
          }
        }

        public function mostrar(){
       
        $result=$this->db->from("tipo_pagos")
                         ->where("estado",ST_ACTIVO)
                          ->get()
                          ->result();
                  
        $rows = count($result);
        foreach ($result as $value) {
            $value->hab_editar = "<a class='btn btn-default btn-xs  btn_modificar_pagos' data-id='{$value->id}'
              data-toggle='modal' data-target='#myModal'>Modificar</a>";
            $value->hab_eliminar = "<a class='btn btn-default btn-xs  btn_eliminar_pagos' data-id='{$value->id}' data_msg='Desea Eliminar Grado: ?'>Eliminar</a>";             
         }

           $datos = [
                    'data' => $result,
                    'rows' => $rows
                ];

                 return $datos;
          }


        public function guardar(){

           if($_POST['id'] != ''){
            $dataUpdate = [
                  'id' => $_POST['id'],
                  'tipo_pago' => $_POST['tipo_pago'],
                  'comentario' => $_POST['comentario']
                ];
            $this->db->where('id',$_POST['id']);
            $this->db->update('tipo_pagos',$dataUpdate);
          } else {
              $dataInsert = [
                'id' => $_POST['id'],
                'tipo_pago' => $_POST['tipo_pago'],
                'comentario' => $_POST['comentario'],
                'estado'=>ST_ACTIVO
            ];
            $this->db->insert('tipo_pagos',$dataInsert);
          }
            return true;          
         }



         public function eliminar($tipo_pagos){

         $tipo_pago = [
               "estado"=>ST_ELIMINADO
              ];
              $this->db->where("id",$tipo_pagos);
              $this->db->update("tipo_pagos",$tipo_pago);
              return true;
           }
       }


    