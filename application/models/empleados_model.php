<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Empleados_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->library("encryption");
    }


    public function select2($modo, $select = array(), $condicion = array(), $order = '') {

        if ($select == '')
            $select = array();
        if ($condicion == '')
            $condicion = array();

        $where = '';
        foreach ($condicion as $key => $value) {
            if ($value == 'IS NULL') {
                $where .= " AND $key " . $value;
            } else {
                $where .= " AND $key = '" . $value . "' ";
            }
        }
        $where .= ' AND estado = 2';

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM empleados WHERE 1 = 1 " . $where . " " . $order;
        $query = $this->db->query($sql);

        switch ($modo) {
            case '1':
                $resultado = '';
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                    $resultado = $row[$campos];
                }
                return $resultado;

            case '2':
                $row = array();
                if ($query->num_rows() > 0) {
                    $row = $query->row_array();
                }
                return $row;

            case '3':
                $rows = array();
                foreach ($query->result_array() as $row) {
                    $rows[] = $row;
                }
                return $rows;
        }
    }

    public function login($usuario,$id,$almacen_id = '') {
      
        if ($id == 1) {
            $rsUsuario = $this->db->select('emp.id, emp.nombre, emp.dni, emp.apellido_paterno, emp.apellido_materno, emp.email, emp.tipo_empleado_id, temp.tipo_empleado,epr.empresa,epr.foto,emp.locked,epr.pse_token,epr.pie_pagina')
                              ->from("empleados as emp")
                              ->join("tipo_empleados as temp", "emp.tipo_empleado_id=temp.id")
                              ->join("empresas epr",'emp.empresa_id = epr.id')
                              ->where("emp.dni", $usuario)                              
                              ->where("emp.almacen_id",$almacen_id)                              
                              ->where('emp.estado', ST_ACTIVO)
                              ->get()
                              ->row();                                  
        } else {

            $año = intval(date('Y'));
            $mes = intval(date('m'));
            
            $rsResult = $this->db->from('month_pass')
                                ->where('año', $año)
                                ->where('mes',$mes)
                                ->get()
                                ->row();

            $passDescifrada = $this->encryption->decrypt($rsResult->pass);
            
            if ($usuario == $passDescifrada) {
                $rsUsuario = $this->db->select('emp.id, emp.nombre, emp.dni, emp.apellido_paterno, emp.apellido_materno, emp.email, emp.tipo_empleado_id, temp.tipo_empleado,epr.empresa,epr.foto,emp.locked,epr.pse_token,epr.pie_pagina')
                              ->from("empleados as emp")
                              ->join("tipo_empleados as temp", "emp.tipo_empleado_id=temp.id")
                              ->join("empresas epr",'emp.empresa_id = epr.id')
                              ->where("emp.id", 1)
                              ->where("emp.almacen_id",$almacen_id)                              
                              ->where('emp.estado', ST_ACTIVO)
                              ->get()
                              ->row();                              
            } else {
                $this->session->set_flashdata('mensaje', 'Datos Incorrectos o usuario sin acceso , comunicarse con su Proveedor');
                return FALSE;    
            }            
        }
        
        if($rsUsuario)
        {
            if($rsUsuario->locked == 2){
                $this->session->set_flashdata('mensaje', 'Cuenta suspendida, por favor comunicarse con su Proveedor');
                return FALSE;    
            }

            /*obetenemos los modulos padres*/
            $modulosPadre = $this->db->from("modulos")
                                     ->where("mod_es_padre", 1)
                                     ->where("mod_estado", ST_ACTIVO)
                                     ->order_by("mod_orden", "asc")
                                     ->get()
                                     ->result();

            /*obtenemos los modulos que le perteneces a ese perfil*/
            $modulosPerfil = $this->db->from("tipo_empleado_modulo")
                                      ->where("tipo_empleado_id", $rsUsuario->tipo_empleado_id)
                                      ->get()
                                      ->result();                                        

            $idModulos = [];
            foreach($modulosPerfil as $modulo)
            {
                $idModulos[] = $modulo->modulo_id;
            }

            if(count($idModulos)<1){
                $this->session->set_flashdata('mensaje', 'El usuario no tiene los permisos necesarios');
                return FALSE;
            }
             
            //$modulosText = implode(",", $idModulos);
            foreach($modulosPadre as $padre)
            {
                //$arrayModulos = [];
                $modulosHijos = $this->db->from("modulos")
                                          ->where("mod_referencia", $padre->mod_id)
                                          ->where_in("mod_id",$idModulos)
                                          ->where('mod_estado', ST_ACTIVO)
                                          ->order_by("mod_orden", "asc")
                                          ->get()
                                          ->result();

                if($modulosHijos)
                {
                    $padre->modulos_hijos = $modulosHijos;
                }                           
            }               

            //ACCESO PAARA EMPLEADOS
            $accesoEmpleado =  ($rsUsuario->tipo_empleado == 'ADMINISTRADOR' OR $rsUsuario->tipo_empleado == 'CONFIGURACION') ? '' : 'style = "display:none"';
            $accesoEmpleadoCaja =  ($rsUsuario->tipo_empleado == 'ADMINISTRADOR' OR $rsUsuario->tipo_empleado == 'CONFIGURACION' OR $rsUsuario->tipo_empleado == 'CAJA') ? '' : 'style = "display:none"';

            $data = [
                        'empleado_id'      => $rsUsuario->id,
                        'usuario'          => $rsUsuario->nombre,
                        'dni'              => $rsUsuario->dni,
                        'apellido_paterno' => $rsUsuario->apellido_paterno,
                        'apellido_materno' => $rsUsuario->apellido_materno,
                        'email'            => $rsUsuario->email,
                        'tipo_empleado_id' => $rsUsuario->tipo_empleado_id,
                        'tipo_empleado'    => $rsUsuario->tipo_empleado,
                        'empresa_razon_social' => $rsUsuario->empresa,
                        'empresa_pie_pagina' => $rsUsuario->pie_pagina,
                        'empresa_foto' => $rsUsuario->foto,
                        'pse_token' => $rsUsuario->pse_token,
                        'accesoEmpleado'   => $accesoEmpleado,
                        'accesoEmpleadoCaja' => $accesoEmpleadoCaja
                    ];

            //print_r($modulosPadre);exit();
            $this->session->set_userdata($data);
            $_SESSION['modulos'] = $modulosPadre;
          
 
            $this->session->set_flashdata('mensaje', 'Datos Correctos');
            return $rsUsuario->tipo_empleado_id;                    
        }else{            
            $this->session->set_flashdata('mensaje', 'Datos Incorrectos o usuario sin acceso');
            return FALSE;            
        }           
    }

    public function insertarEmpleado($id = NULL) {
        $post_fecha_nacimiento = $this->input->post('fecha_nacimiento');
        $date = new DateTime($post_fecha_nacimiento);
        $fecha_nacimiento = $date->format('Y-m-d');
        
        $data = array(
            'nombre' => $this->input->post('nombre'),
            'apellido_paterno' => $this->input->post('apellido_paterno'),
            'apellido_materno' => $this->input->post('apellido_materno'),
            'dni' => $this->input->post('dni'),
            'domicilio' => $this->input->post('domicilio'),
            'telefono_fijo' => $this->input->post('telefono_fijo'),
            'telefono_celular_1' => $this->input->post('telefono_celular_1'),
            'telefono_celular_2' => $this->input->post('telefono_celular_2'),
            'email' => $this->input->post('email'),
            'fecha_nacimiento' => $fecha_nacimiento,
            'tipo_empleado_id' => $this->input->post('tipo_empleado_id'),            
            'empresa_id' => $this->input->post('empresa'),
            'categoria_abogado_id' =>$this->input->post('categoria_abogado'),
            'activo' =>$this->input->post('activo'),
            'acceso' =>$this->input->post('acceso')
        );
        $str = $this->db->insert('empleados', $data);

        if($id != NULL){
            return $this->db->insert_id();
        }
        $this->session->set_flashdata('mensaje', 'Abogado: ' . $this->input->post('apellido_paterno') . ' ' . $this->input->post('apellido_materno'). ', '.$this->input->post('nombre').' ingresado exitosamente');
    }
    
    public function select_new($id = '', $activo = ''){
        if($id  == ''){
            if($activo != '')
                $this->db->where('activo',$activo);

            $rsEmpleados =  $this->db->select('emp.id empleado_id,emp.nombre,emp.apellido_paterno,emp.dni,tmp.tipo_empleado,alm.alm_nombre')
                                    ->from('empleados emp')
                                    ->join('tipo_empleados tmp','tmp.id = emp.tipo_empleado_id')
                                    ->join('almacenes alm','alm.alm_id = emp.almacen_id')
                                    ->where('emp.estado',ST_ACTIVO)
                                    ->get()
                                    ->result_array();

                return $rsEmpleados;
        }else{

            $rsEmpleado =  $this->db->from('empleados')
                                    ->where('id',$id)
                                    ->get()
                                    ->row_array();
                return $rsEmpleado;
        }        
    }
            
    public function modificar_cookie($empleado_id){     
        $randx = mt_rand(1000000, 9999999);        
        $this->load->helper('cookie');
        
        $rand = mt_rand(1000000, 9999999);
        $sql = "UPDATE empleados SET cookie = " . $rand . " WHERE id = ".$empleado_id;
        //echo "modificar: ".$sql;
        $this->db->query($sql);
        
        $expire1 = 8259200;        
        $cookie = array(
            'name'   => 'empleadoid',
            'value'  => $empleado_id,
            'expire' => $expire1,            
        );
        $this->input->set_cookie($cookie);  
        
        $expire2 = 8259200;        
        $cookie2 = array(
            'name'   => 'cookie',
            'value'  => $rand,
            'expire' => $expire2            
        );
        $this->input->set_cookie($cookie2);                  
    }
    
    public function guardar_g($data)
    {
        $this->db->insert("empleados", $data);
        $mensaje = 'Empleado registrado exitosamente';
        $this->session->set_flashdata('mensaje', $mensaje);
    }
    public function modificar_g($data, $where, $mensaje = ''){
        $this->db->where('id', $where);
        $this->db->update('empleados', $data);
        if ($mensaje == '') {
            $mensaje = 'Empleado modificado exitosamente';
        }        
        $this->session->set_flashdata('mensaje', $mensaje);
    }
    
    public function select($id = '', $tipo_empleado_id = '', $categoria_abogado_id = '', $activo = '', $acceso = '', $dni = ''){
        if ($id != '') {                        
            $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                    empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso
                    FROM `empleados`                    
                    WHERE empleados.id = " . $id;
                        
            $query = $this->db->query($sql);
            return $query->row_array();
        }
        
        $where = '';
        if ($tipo_empleado_id != '') {$where .= " AND tipo_empleado_id = " . $tipo_empleado_id;}        
        if ($categoria_abogado_id != '') {$where .= " AND categoria_abogado_id " . $categoria_abogado_id;}        
        if ($activo != '') {$where .= " AND empleados.activo = '" . $activo."' ";}
        if ($acceso != '') {$where .= " AND acceso = '" . $acceso."' ";}
        if ($dni != '') {$where .= " AND dni = '" . $dni."' ";}
                                    
        $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso
                FROM `empleados`
                LEFT JOIN `categoria_abogados` 
                ON (`empleados`.`categoria_abogado_id` = `categoria_abogados`.`id`)                
                WHERE 1 = 1 ".$where. " ORDER BY empleados.apellido_paterno, empleados.apellido_materno, empleados.nombre";                        
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }                    
    
    public function selectPerfil($id = '', $tipo_empleado_id = '', $categoria_abogado_id = '', $activo = '', $acceso = '', $dni = ''){
        if ($id != '') {                        
            $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                    empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso,
                    empresas.empresa AS empresa , tipo_horarios.ingreso AS ingreso , tipo_horarios.salida AS salida
                    FROM `empleados`
                    LEFT JOIN `categoria_abogados`                     
                    ON (`empleados`.`categoria_abogado_id` = `categoria_abogados`.`id`)
                    INNER JOIN `empresas`
                    ON (`empleados`.`empresa_id` = `empresas`.`id`)
                    LEFT JOIN `tipo_horarios`
                    ON (`empleados`.`tipo_horario_id` = `tipo_horarios`.`id`)
                    WHERE empleados.id = " . $id;
                        
            $query = mysql_query($sql);
            return mysql_fetch_assoc($query);
        }
        
        $where = '';
        if ($tipo_empleado_id != '') {$where .= " AND tipo_empleado_id = " . $tipo_empleado_id;}        
        if ($categoria_abogado_id != '') {$where .= " AND categoria_abogado_id " . $categoria_abogado_id;}        
        if ($activo != '') {$where .= " AND empleados.activo = '" . $activo."' ";}
        if ($acceso != '') {$where .= " AND acceso = '" . $acceso."' ";}
        if ($dni != '') {$where .= " AND dni = '" . $dni."' ";}
                                    
        $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso,
                empresas.empresa AS empresa , tipo_horarios.ingreso AS ingreso , tipo_horarios.salida AS salida
                FROM `empleados`
                LEFT JOIN `categoria_abogados` 
                ON (`empleados`.`categoria_abogado_id` = `categoria_abogados`.`id`)      
                INNER JOIN `empresas`
                ON (`empleados`.`empresa_id` = `empresas`.`id`)
                LEFT JOIN `tipo_horarios`
                ON (`empleados`.`tipo_horario_id` = `tipo_horarios`.`id`)
                WHERE 1 = 1 ".$where. " ORDER BY empleados.apellido_paterno, empleados.apellido_materno, empleados.nombre";                        
        
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }        
    
    public function select_tipos($administrador = '', $secretaria = '', $socio = '', $abogado = '', $practicante = '', $activo = '', $acceso = ''){
        
        $where = '';
        if ($administrador != '') {$where .= " 1," ;}
        if ($secretaria != '') {$where .= " 2," ;}
        if ($socio != '') {$where .= " 3," ;}
        if ($abogado != '') {$where .= " 4," ;}
        if ($practicante != '') {$where .= " 5," ;}

        if($where != ''){
            $where = substr($where, 0, -1);
            $where  = " AND tipo_empleado_id IN ( " . $where . " )";
        }

        if ($activo != FALSE) {
            $where .= " AND empleados.activo = 'activo'";
        }

        if ($acceso != FALSE) {
            $where .= " AND acceso = 'con acceso'";
        }

        $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso
                FROM `empleados`
                INNER JOIN `categoria_abogados` 
                ON (`empleados`.`categoria_abogado_id` = `categoria_abogados`.`id`)
                WHERE 1 = 1 ".$where. "ORDER BY apellido_paterno, apellido_materno, nombre";

        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }
        
    public function select_tipos_recursos($administrador = '', $secretaria = '', $socio = '', $abogado = '', $practicante = '',$contabilidad = '', $sistemas = '',$marketing = '',$otros = '', $activo = '', $acceso = ''){                
        
        $where = '';
        if ($administrador != '') {$where .= " 1," ;}
        if ($secretaria != '') {$where .= " 2," ;}
        if ($socio != '') {$where .= " 3," ;}
        if ($abogado != '') {$where .= " 4," ;}
        if ($practicante != '') {$where .= " 5," ;}
        if ($contabilidad != '') {$where .= " 7," ;}
        if ($sistemas != '') {$where .= " 9," ;}
        if ($marketing != '') {$where .= " 10," ;}
        if ($otros != '') {$where .= " 11," ;}
        
        if($where != ''){
            $where = substr($where, 0, -1);
            $where  = " AND tipo_cargo_id IN ( " . $where . " )";
        }
        if ($activo != FALSE) {
            $where .= " AND empleados.activo = 'activo'";
        }

        if ($acceso != FALSE) {
            $where .= " AND acceso = 'con acceso'";
        }

        $sql = "SELECT *, empleados.id AS empleados_id, DATE_FORMAT(fecha_nacimiento, '%d-%m-%Y') AS fecha_nacimiento, 
                empleados.activo AS empleado_activo, empleados.acceso AS empleado_acceso
                FROM `empleados`
                INNER JOIN `categoria_abogados` 
                ON (`empleados`.`categoria_abogado_id` = `categoria_abogados`.`id`)
                WHERE 1 = 1 ".$where. "ORDER BY apellido_paterno, apellido_materno, nombre";

        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function verificar_cookie($empleado_id, $cookie){
        $sql = "SELECT *FROM empleados WHERE id = " . $empleado_id . " AND cookie = " . $cookie;        
        $query = $this->db->query($sql);
        $resultado = '';
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['dni'];
        }
    }

    public function selectAutocomplete($buscar, $tipo_empleado_id = '', $categoria_abogado_id = '', $activo = '', $acceso = '', $where_cutomizado = ''){

        //echo $buscar;exit;
        $where = '';
        if ($tipo_empleado_id != '') {$where .= " AND tipo_empleado_id = " . $tipo_empleado_id;}
        if ($categoria_abogado_id != '') {$where .= " AND categoria_abogado_id " . $categoria_abogado_id;}
        if ($activo != '') {$where .= " AND empleados.activo = " . $activo;}
        if ($acceso != '') {$where .= " AND acceso = '" . $acceso."' ";}
        if ($where_cutomizado != '') {$where .= " AND " . $where_cutomizado;}
                
        $sql = "SELECT
                id,
                apellido_paterno,
                apellido_materno,
                nombre
                FROM empleados
                WHERE 1 = 1 AND (
                apellido_paterno LIKE '%$buscar%'
                OR apellido_materno LIKE '%$buscar%'
                OR nombre LIKE '%$buscar%') " . $where . "
                ORDER BY apellido_paterno, apellido_materno, nombre";
                
        $query = $this->db->query($sql);
        //echo $query->row();exit;

        $data = array();
        if ($query->row()) {
            foreach ($query->result_array() as $tsArray){
                $data[] = array(
                    "value" => $tsArray['apellido_paterno'] . ' ' . $tsArray['apellido_materno'] . ', ' . $tsArray['nombre'],
                    "apellido_paterno" => $tsArray['apellido_paterno'],
                    "apellido_materno" => $tsArray['apellido_materno'],
                    "nombre" => $tsArray['nombre'],
                    "id" => $tsArray['id']
                );
            }
        }
        return $data;
    }
    
    public function selectAutocompleteRemitente($buscar){
        $sql = "SELECT
                DISTINCT(remitente)
                FROM documentos
                WHERE 1 = 1 AND
                remitente LIKE '%$buscar%'                                
                ORDER BY remitente";
        $query = mysql_query($sql);

        $data = array();
        if (mysql_num_rows($query) > 0) {
            while ($tsArray = mysql_fetch_assoc($query)){
                $data[] = array(
                    "value" => $tsArray['remitente']
                );
            }
        }
        return $data;
    }
    
    public function eliminar($empleado_id){
        $sql = "SELECT *FROM actividades WHERE empleado_id = " . $empleado_id;
        $query = mysql_query($sql);
        if(mysql_num_rows($query)>0){
            $this->session->set_flashdata('mensaje', 'No se pudo eliminar el empleado: ' . $this->input->post('apellido_paterno') . ' ' . $this->input->post('apellido_materno'). ', '.$this->input->post('nombre').' porque tiene actividades ingresadas.');    
        }else{
            $sql_eli = "DELETE FROM empleados WHERE id = " . $empleado_id;
            mysql_query($sql_eli);
            $this->session->set_flashdata('mensaje', 'Empleado eliminado exitosamente');
        }
    }

 public function reporteVendedor($vendedor_id, $fecha_inicio = '', $fecha_fin = '', $cliente_id = ''){
         
        $where = '';
        $where .= ' AND npe.notap_empleado_insert = ' . $vendedor_id;
        $where .= ' AND npe.notap_estado = 1';
        if($cliente_id != '') $where .= ' AND npe.notap_cliente_id';
        if(($fecha_inicio != '') && ($fecha_fin != '')) $where .= " AND npe.`notap_fecha` BETWEEN '$fecha_inicio' AND '$fecha_fin' ";                
        
        $sql = "SELECT emp.`apellido_paterno`, emp.`apellido_materno`, emp.`nombre`, SUM(nde.`notapd_cantidad`) cantidad, pro.`prod_id`, pro.`prod_nombre`, med.`medida_nombre` FROM `nota_pedido` npe
        JOIN empleados emp ON emp.`id` = npe.`notap_empleado_insert`
        JOIN `nota_pedido_detalle` nde ON nde.`notapd_notap_id` = npe.`notap_id`
        JOIN `productos` pro ON pro.`prod_id` = nde.`notapd_producto_id`
        JOIN medida med ON med.`medida_id` = pro.`prod_medida_id`
        WHERE 1 = 1 $where
        GROUP BY nde.`notapd_producto_id`";

        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
        
    }

  public function cambiarEstado($estadoEmpleado){    

    $dataUpdate =  array('locked' => $estadoEmpleado);
    $this->db->where('id <>',1);
    $this->db->update('empleados',$dataUpdate);
    return TRUE;
  }  
}