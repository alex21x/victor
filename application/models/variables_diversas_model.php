<?php

class Variables_diversas_model extends CI_Model {

    public $detraccion_valor = 700;
    public $porcentaje_detraccion = 0.12; //igual al 10%  -- en 02-04-2018 se cambio al 12%
    public $porcentaje_detraccion_entero = 12; //igual al 10%  -- en 02-04-2018 se cambio al 12%
    public $factura_antigua = "factura_antigua"; //igual al 10%
    public $boleta_antigua = "boleta_antigua"; //igual al 10%
    
    public function __construct() {
        parent::__construct();                                        
        
    }

        
}
