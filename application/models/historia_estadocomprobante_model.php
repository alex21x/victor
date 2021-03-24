<?php


class Historia_estadoComprobante_model extends CI_Model
{
	
	 public function __construct()
	{
		parent::__construct();
	}

 public function select($historia_estadoComprobanteId = ''){

    if ($historia_estadoId == '') {
       $rsHistoriaEstadoComprobantes = $this->db->from("historia_estadocomprobante")
                                     ->get()
                                     ->result();

         return $rsHistoriaEstadoComprobantes;

    }else{
      $rsHistoriaEstadoComprobante = $this->db->from("historia_estadocomprobante")
                                   ->where("hec_id",$historia_estadoComprobanteId)
                                   ->get()
                                   ->row();
                                   return $rsHistoriaEstadoComprobante;
    }
}}