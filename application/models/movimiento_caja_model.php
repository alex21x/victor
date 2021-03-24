<?php
class Movimiento_caja_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

        date_default_timezone_set('America/Lima');
       	
    }

    public function select($caja_id=''){

     if ($caja_id=='') {
        $fecha_desde = isset($_POST['fecha_desde']) ? $_POST['fecha_desde'] : $_GET['fecha_desde'];
        $fecha_hasta = isset($_POST['fecha_hasta']) ? $_POST['fecha_hasta'] : $_GET['fecha_hasta']; 
        $vendedor    = isset($_POST['vendedor']) ? $_POST['vendedor'] : $_GET['vendedor'];

      if($fecha_desde != ''){        
            $fecha_desde =  (new DateTime($fecha_desde))->format('Y-m-d');
            $this->db->where("DATE(caj.fecha) >=", $fecha_desde);
        }
        if($fecha_hasta != ''){
            $fecha_hasta =  (new DateTime($fecha_hasta))->format('Y-m-d');
            $this->db->where("DATE(caj.fecha) <=", $fecha_hasta);
        }
        if($vendedor != ''){            
            $this->db->where('caj.empleado_id',$vendedor);
        }
        
        if($this->session->userdata('accesoEmpleado') != ''){
            $this->db->where('caj.empleado_id',$this->session->userdata('empleado_id'));
        }

        //FILTRO ALMACEN
        $this->db->where('caj.almacen_id',$this->session->userdata('almacen_id'));
     
      $rcaja=$this->db->select('caj.id,caj.monto,caj.observaciones,DATE_FORMAT(caj.fecha,"%d-%m-%Y %h:%i") fecha,tcaj.id tipo_movimiento_id,tcaj.tipo_cMovimiento,CONCAT(epl.nombre," ",epl.apellido_paterno) as empleado',false)
                         ->from("caja_movimientos caj")                         
                         ->join('tipo_cmovimientos tcaj','tcaj.id = caj.tipo_movimiento_id')
                         ->join("empleados epl","epl.id = caj.empleado_id")                         
                         ->order_by('id','desc')
                         ->get()
                         ->result();
                   //var_dump($rcaja);exit;
                          return $rcaja;    
     }else{

      $rcaja=$this->db->from("caja_movimientos")
                          ->where("id",$caja_id)
                          ->get()
                          ->row();
                          return $rcaja;
      }
    }
         
      
    public function guardar(){
      //echo '123123';exit;
      $fecha = new DateTime($_POST['fecha']);
      $fecha  = $fecha->format('Y-m-d H:i:s');

    	if($_POST['caja_movimientos_id'] == ''){
        
            $data = array('tipo_movimiento_id' => $_POST["tipo_cMovimiento"],
                          'monto' => $_POST['monto'],
                          'observaciones' => $_POST['observaciones'],
                          'fecha'=> $fecha,
                          'empleado_id' => $this->session->userdata('empleado_id'),
                          'almacen_id'  => $this->session->userdata('almacen_id')
                        );                          

            $this->db->insert('caja_movimientos',$data);                                        
          } else {

            $data = array('tipo_movimiento_id' => $_POST["tipo_cMovimiento"],
                          'monto' => $_POST['monto'],
                          'observaciones' => $_POST['observaciones'],
                          'fecha'=> $fecha,
                          'empleado_id' => $this->session->userdata('empleado_id'),
                          'almacen_id'  => $this->session->userdata('almacen_id')                       
                        );

            $this->db->where('id',$_POST['caja_movimientos_id']);
            $this->db->update('caja_movimientos',$data);
          }

       return TRUE;             
    }

    public function eliminar($caja_movimientos_id){
      $this->db->where("id",$caja_movimientos_id);
      $this->db->delete("caja_movimientos");
      return true;
    }

     public function selectMovCaj($fecha = ''){

      $resultado = $this->db->select("sum(caj.monto) monto,tcaj.tipo_cMovimiento")
                          ->from("caja_movimientos caj")
                          ->join("tipo_cmovimientos tcaj","tcaj.id = caj.tipo_movimiento_id")
                          ->where('almacen_id',$this->session->userdata('almacen_id'))
                          ->where('fecha >',$fecha)
                          ->where('fecha <',date('Y-m-d H:i:s'))                          
                          ->group_by("tipo_movimiento_id")
                          ->get()
                          ->result_array();
                        
          $array = array();
          foreach ($resultado as $value) {                    
                $array[$value['tipo_cMovimiento']] = $value['monto'];
          }                  
      return $array;                          
  }
}