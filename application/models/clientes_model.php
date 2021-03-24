<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Clientes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function insertar($data, $mensaje = '') {          
        $this->db->insert('clientes', $data);
        if($mensaje != ''){
            $this->session->set_flashdata('mensaje_cliente_index', $mensaje);
        }
    }

    public function obtener_codigo() {  
        
        $this->db->select_max('id');
        $result = $this->db->get('clientes');
        $id = ($result->result())[0]->id + 1;
        return $id;
    }

    public function modificar($id, $data, $mensaje = ''){
        $this->db->where('id', $id);
        $this->db->update('clientes', $data);
        
        if($mensaje != ''){
            $this->session->set_flashdata('mensaje_cliente_index', 'Cliente modificado correctamente.');
        }
    }

    public function select($id = '', $activo = '', $id_listado = '', $empresa = '',$tipo_cliente='', $empleado_id = '', $activo_contrato = '',$limit = FALSE, $start = FALSE){
        $where  = '';
        $limite = '';
        
        $where .= ($activo != '') ? " AND cli.activo = '".$activo."'" : '';        
        $where .= ($id_listado != '') ? " AND cli.id = ".$id_listado : '';
        $where .= ($empresa != '') ? " AND cli.empresa_id = ".$empresa : '';
        $where .= ($tipo_cliente != '')  ? " AND cli.`tipo_cliente_id` = " . $tipo_cliente : '';        
        $where .= ($empleado_id  != '')  ? " AND cli.`empleado_id_insert` = " . $empleado_id : '';
        if (($limit !== FALSE) && ($start !== FALSE))
            $limite .= " LIMIT ".$start .', '.$limit;
        
        if ($id != '' && $id_listado == '') {

            $sql = "SELECT *FROM clientes WHERE id = " . $id;
            $query = $this->db->query($sql);
            return $query->row_array();
        }
                                              
        
        if (($tipo_contrato != '') || ($activo_contrato != '')){
            $where .= ($activo_contrato != '') ? " AND con.activo = '".$activo_contrato."'" : '';
            $where .= ($tipo_contrato != '')   ? " AND con.`tipo_contrato_id` = " . $tipo_contrato : '';
            
            $sql = "SELECT
                DISTINCT(cli.id) cliente_id, 
                cli.tipo_cliente_id,
                cli.tipo_cliente,
                cli.ruc,
                cli.nombres,
                cli.`razon_social`,
                cli.`email`,
                cli.`email2`,
                cli.`email3`,
                cli.domicilio1,
                cli.telefono_fijo_1,
                cli.telefono_fijo_2,    
                epr.`empresa` empresa
                FROM clientes cli
                JOIN `contratos` con ON cli.id = con.cliente_id
                JOIN `empresas` epr ON cli.`empresa_id` = epr.`id`                 
                WHERE 1 = 1 AND cli.eliminado_cliente = 0 " . $where . " 
                ORDER BY cli.id DESC".$limite;                            
        }else{
            $sql = "SELECT
                cli.id cliente_id,
                cli.tipo_cliente_id,
                cli.tipo_cliente,
                cli.ruc,
                cli.nombres,
                cli.`razon_social`,
                cli.`email`,
                cli.`email2`,
                cli.`email3`,
                cli.domicilio1,
                cli.telefono_fijo_1,
                cli.telefono_fijo_2,
                if(cli.empleado_id_insert = 0, 'USUARIO GENERAL', CONCAT(epl.nombre,' ',epl.apellido_paterno,' ',epl.apellido_materno)) empleado,

                epr.`empresa` empresa
                FROM clientes cli
                JOIN empresas epr ON cli.empresa_id = epr.id
                LEFT JOIN empleados epl ON cli.empleado_id_insert =  epl.id                
                WHERE 1 = 1 AND cli.eliminado_cliente = 0 ".$where." ORDER BY cli.id DESC".$limite;
        }
        //echo $sql;
        $rows = $this->db->query($sql);
        return $rows->result_array();
    }

    public function clientePorRuc($ruc){

        $sql = "SELECT razon_social, id FROM clientes WHERE ruc = '" . $ruc . "'";
        $query = $this->db->query($sql);
        
        $respuesta = array();
        if($query->num_rows() > 0){
            $row = $query->row_array();
            
            $respuesta['placa'] = $row['placa'];
            $respuesta['razon_social'] = $row['razon_social'];
            $respuesta['id'] = $row['id'];
        }
        return $respuesta;        
    }

    public function clientePorTipoContratos($tipo_contrato, $activo = ''){
        $where = ($activo != '') ? " AND con.activo = '".$activo."'" : '';
        $sql = "SELECT 
        DISTINCT(cli.id) id, 
        cli.ruc,
        cli.`razon_social`,
        cli.domicilio1,
        cli.telefono_fijo_1,
        cli.telefono_fijo_2,    
        con.tipo_contrato tipo_contrato,
        epr.`empresa` empresa
        FROM clientes cli
        JOIN `contratos` con ON cli.id = con.cliente_id
        JOIN `empresas` epr ON cli.`empresa_id` = epr.`id` 
        WHERE con.`tipo_contrato_id` = " . $tipo_contrato . $where . " 
        ORDER BY cli.razon_social
        ";
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }

    public function selectAutocomplete($buscar, $activo = ''){
        $where = ' AND eliminado_cliente=0';
        //if($activo != ''){$where = " AND activo = '".$activo."' ";}
        $sql = "SELECT
                id,
                tipo_cliente_id,
                ruc,nombres,
                razon_social,
                domicilio1,
                placa
                FROM clientes
                WHERE
                (ruc LIKE '%$buscar%'
                OR razon_social LIKE '%$buscar%') " . $where . "
                ORDER BY razon_social, ruc";

        $query = $this->db->query($sql);

        $data = array();
        if ($query->row()) {
            foreach ($query->result_array() as $tsArray){
                if ($tsArray['tipo_cliente_id'] == 1) {
                    $doc = "DNI";
                }else if($tsArray['tipo_cliente_id'] == 2){
                    $doc = "RUC";
                }else{
                    $doc = "OTROS";
                }

                $data[] = array(
                    "value" => $tsArray['id'].' - '.$doc.' '.$tsArray['ruc'].' '.$tsArray['razon_social']." ".$tsArray['nombres'],
                    "ruc" => $tsArray['ruc'],
                    "razon_social" => $tsArray['razon_social'],
                    "domicilio1" => $tsArray['domicilio1'],
                    "placa"      => $tsArray['placa'],
                    "id" => $tsArray['id']
                );
            }
        }

        return $data;
    }

    public function selectAutocomplete_p($buscar, $activo = ''){
        $where = ' AND prov_estado=2';
        //if($activo != ''){$where = " AND activo = '".$activo."' ";}
        $sql = "SELECT
                *
                FROM proveedores
                WHERE
                (prov_ruc LIKE '%$buscar%'
                OR prov_razon_social LIKE '%$buscar%') " . $where . "
                ORDER BY prov_razon_social, prov_ruc";
        $query = $this->db->query($sql);

        $data = array();
        if ($query->row()) {
            foreach ($query->result_array() as $tsArray){
                $data[] = array(
                    "value" => $tsArray['prov_razon_social'],
                    "ruc" => $tsArray['prov_ruc'],
                    "domicilio1" => $tsArray['prov_direccion'],
                    "id" => $tsArray['prov_id']
                );
            }
        }

        return $data;
    }

    public function eliminar(){
        $sql_eli = "DELETE FROM clientes WHERE id = " . $this->uri->segment(3);
        mysql_query($sql_eli);
        $this->session->set_flashdata('mensaje_cliente_index', 'Cliente: eliminado exitosamente');
    }    
    
    public function clientesDeInteres($abogado_interesado, $activo_cliente = '', $activo_contrato = ''){
        $where = '';
        if($activo_cliente != ''){$where = " AND cli.activo = '".$activo_cliente."' ";}
        if($activo_contrato != ''){$where = " AND con.activo = '".$activo_contrato."' ";}
        
        $sql = "SELECT DISTINCT(con.`cliente_id`) cliente_id FROM `empleado_interesados` ei
        JOIN `contratos` con
        ON ei.`contrato_id` = con.`id`
        JOIN clientes cli
        ON cli.id = con.cliente_id
        WHERE ei.`empleado_id` = " .$abogado_interesado . $where;
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row['cliente_id'];
        }
        return $rows;        
    }
        
    //FACTURACION CLIENTES PERMANENTES    
    public function clientesFacturacionPermanente($tipo_contrato, $activo = ''){
        $where = ($activo != '') ? " AND con.activo = '".$activo."'" : '';
        $sql = "SELECT 
        DISTINCT(cli.id)    cliente_id, 
        cli.ruc             cliente_ruc,
        cli.`razon_social`  cliente_razon_social,
        cli.domicilio1      cliente_domicilio1,        
        
        con.id  contrato_id,
        con.moneda_id moneda_id,
        
        epr.id empresa_id,
        epr.empresa empresa,
        epr.ruc     empresa_ruc
                
        FROM clientes cli
        JOIN `contratos` con ON cli.id = con.cliente_id
        JOIN `empresas` epr ON cli.`empresa_id` = epr.`id`
        WHERE con.`tipo_contrato_id` = " . $tipo_contrato . $where . " 
        ORDER BY cli.razon_social
        ";
        
        //echo $sql;
        $query = mysql_query($sql);
        $rows = array();
        while ($row = mysql_fetch_assoc($query)){
            $rows[] = $row;
        }
        return $rows;
    }
    
    public function clientesPorContratoJson($buscar, $tipo_contrato = '', $activo = '') {
        
        $where = '';
        $where.= ($tipo_contrato != '') ? " AND tipo_contrato_id = ".$tipo_contrato : '';
        $where.= ($activo != '') ? " AND con.activo = '".$activo."'" : '';
        $sql = "SELECT 
        DISTINCT(cli.id)    cliente_id, 
        cli.ruc             cliente_ruc,
        cli.`razon_social`  cliente_razon_social,
        cli.domicilio1      cliente_domicilio1,        
        
        con.id  contrato_id,
        con.moneda_id moneda_id,
        
        epr.id empresa_id,
        epr.empresa empresa,
        epr.ruc     empresa_ruc
                
        FROM clientes cli
        JOIN `contratos` con ON cli.id = con.cliente_id
        JOIN `empresas` epr ON cli.`empresa_id` = epr.`id`
        WHERE
        (cli.ruc LIKE '%$buscar%'
                OR cli.razon_social LIKE '%$buscar%') " . $where . "
                ORDER BY razon_social";
                
        $query = $this->db->query($sql);        
        $data = array();
        if ($query->row()) {
            foreach ($query->result_array() as $tsArray){
                $data[] = array(
                    "value" => $tsArray['cliente_razon_social'],
                    "ruc" => $tsArray['cliente_ruc'],
                    "domicilio1" => $tsArray['cliente_domicilio1'],
                    "id" => $tsArray['cliente_id']
                );
            }
        }

        return $data;
        
    }
    public function getNameCliente($idcliente) {
        $cliente = '';
         $result = $this->db->from('cliente')
                            ->where('id',$idcliente)
                            ->limit(1)
                            ->get()
                            ->result();
        foreach ($result as $key => $value) {
            $cliente = $value->razon_social." ".$value->nombres;
        }

        return $cliente;    
    }



    //Creado por Alexander Fernández - 01-03-2021
    public function guardarClienteNube(){
        //REGISTRO DE CLIENTE API   
         if($_POST['cliente_id'] == 'jApi'){ //REGISTRA CLIENTE RUC
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),                        
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 2,
                        'tipo_cliente' => 'Persona Jurídica'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id;
                }        
         } else if($_POST['cliente_id'] == 'nApi'){//REGISTRA CLIENTE DNI
                $this->db->where('ruc',$_POST['ruc_sunat']);
                $dato_sunat_cliente = $this->db->get('clientes')->row();
                if(empty($dato_sunat_cliente->ruc)){
                    $id = $this->clientes_model->obtener_codigo();
                    $data = array(
                        'id' => $id,
                        'ruc' => $_POST['ruc_sunat'],
                        'razon_social' => strtoupper($_POST['razon_sunat']),
                        'domicilio1' => strtoupper($_POST['direccion']),
                        'empresa_id' => 1,
                        'activo' => 'activo',
                        'empleado_id_insert' => $this->session->userdata('empleado_id'),
                        'tipo_cliente_id' => 1,
                        'tipo_cliente' => 'Persona Natural'
                    );
                    $this->db->insert('clientes',$data);
                    $_POST['cliente_id'] = $id;
                }else{
                    $_POST['cliente_id'] = $dato_sunat_cliente->id; 
                }
         }
         return $_POST['cliente_id'];
    }
}