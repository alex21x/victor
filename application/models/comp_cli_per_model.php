<?PHP

class Comp_cli_per_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

        // $modo: 1 = campo (1 solo campo), 2 = registro (mas de un campo), 2 = tabla (mas de 1 registro)
    public function select($modo, $select = array(), $condicion = array(), $order = '') {

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

        $campos = ($select == array()) ? '*' : implode(", ", $select);
        $sql = "SELECT " . $campos . " FROM comp_cli_per WHERE 1 = 1 " . $where . " " . $order;
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
    
    public function seleccion($anio, $mes){
        $sql = "SELECT 
        comp.id comp_cli_per_id,
        comp.`anio`,
        comp.`mes`,
        comp.`monto`,
        comp.`descripcion`,
        comp.`observaciones`,
        mon.`abreviado`,
        mon.`moneda`,
        mon.`simbolo`,
        cli.id cliente_id,
        cli.`razon_social`,
        cli.`ruc` ruc_cliente,
        emp.id empresa_id,
        emp.`empresa`,
        emp.`ruc` ruc_empresa,
        tip.id tipo_documento_id,
        tip.`tipo_documento`
        FROM comp_cli_per comp
        JOIN `empresas` emp ON comp.`empresa_id` = emp.`id`
        JOIN `tipo_documentos` tip ON comp.`tipo_documento_id` = tip.id
        JOIN clientes cli ON comp.`cliente_id` = cli.`id`
        JOIN monedas mon ON comp.`moneda_id` = mon.`id`
        WHERE comp.`anio` = " . $anio . " AND comp.`mes` = " . $mes;
        //echo $sql;exit;
        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;                
    }
    
    public function selectCustomer($anio, $mes, $where_customer = ''){
        //echo "--".$where_customer."__";exit;
        $sql = "SELECT 
        c_c.id comp_cli_per_id,
        c_c.`anio`,
        c_c.`mes`,
        c_c.`tipo_documento_id`,
        tip.`tipo_documento`,
        cli.`ruc` ruc_cliente,
        cli.`razon_social`,
        mon.`moneda`,
        c_c.`monto`,
        c_c.`descripcion`,
        c_c.empresa_id,
        c_c.anio anio_de_permanente,
        c_c.mes mes_de_permanente,
        c_c.cliente_id cliente_id,
        com.enviado_cliente enviado_cliente,
        com.`enviado_sunat`,
        com.`estado_sunat`,
        com.id comprobante_id,
        com.anulado sunat_anulado,
        com.serie serie_comprobante,
        com.numero numero_comprobante,
        emp.`empresa`
        FROM `comp_cli_per` c_c
        JOIN tipo_documentos tip ON c_c.`tipo_documento_id` = tip.id
        JOIN monedas mon ON mon.`id` = c_c.`moneda_id`
        JOIN clientes cli ON c_c.`cliente_id` = cli.`id`
        JOIN empresas emp ON c_c.`empresa_id` = emp.`id`
        LEFT JOIN `comprobantes_comp_cli_per` com_c ON c_c.id = com_c.`comp_cli_per_id`
        LEFT JOIN `comprobantes` com ON com.id = com_c.`comprobante_id`
        WHERE c_c.`anio` = " . $anio . " AND c_c.`mes` = " . $mes . " " . $where_customer . " ORDER BY c_c.id DESC";

        $query = $this->db->query($sql);
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;  
    }
    
    public function selectConMoneda($comp_cli_per_id){
        
        $sql = "SELECT com.id comp_cli_per_id, com.`descripcion`, com.`monto`, com.`moneda_id` moneda_id, mon.`moneda` FROM comp_cli_per com 
        JOIN monedas mon ON com.`moneda_id` = mon.`id`
        WHERE com.`id` = " . $comp_cli_per_id;
        
        $query = $this->db->query($sql);
        
        $rows = array();
        foreach ($query->result_array() as $row) {
            $rows[] = $row;
        }
        return $rows;
        
    }


    public function insertar($empresa_id, $tipo_documento_id, $cliente_id, $moneda_id, $monto, $descripcion, $observaciones, $anio, $mes, $emp_insert, $fecha_insert) {
        //var_dump($array); exit;
        
        $sql = "INSERT INTO `comp_cli_per` (`empresa_id`,     `tipo_documento_id`,         `cliente_id`, `moneda_id`,              `monto`,        `descripcion`, `observaciones`, anio, mes, emp_insert, fecha_insert) VALUES "
                                  . "( " . $empresa_id . ", " . $tipo_documento_id. ", " . $cliente_id. ", " . $moneda_id . ", " . $monto. ", '" . $descripcion . "', '" . $observaciones ."', " . $anio . ", " . $mes . ", " . $emp_insert . ", '" . $fecha_insert . "')";
        //mysql_query($sql);
        $query = $this->db->query($sql);
        //$this->session->set_flashdata('mensaje', 'Registro Agregado Correctamente');
    }
    
    public function modificar($id,$data) {
        $this->db->where('id', $id);
        $rs = $this->db->update('comp_cli_per',$data);
        //echo $this->db->last_query();exit;
        if (!$rs)
            $this->session->set_flashdata('mensaje', 'Error '.$this->db->_error_message());
        else
            $this->session->set_flashdata('mensaje', 'operación correctamente');
    }
    
    public function eliminar($compCliPer_id) {
        $sql  = "UPDATE comp_cli_per SET eliminado = 1 WHERE id = ".$compCliPer_id;
        $this->db->query($sql);
        $this->session->set_flashdata('mensaje','Registro eliminado correctamente');
    }
}
