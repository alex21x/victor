<?php


class Historia_estados_model extends CI_Model
{
	
	 public function __construct()
	{
		parent::__construct();
	}

 public function select($historia_estadoId = ''){

    if ($historia_estadoId == '') {
       $rsHistoriaEstados = $this->db->from("historia_estados")
                                     ->get()
                                     ->result();

         return $rsHistoriaEstados;

    }else{
      $rsHistoriaEstado = $this->db->from("historia_estados")
                                   ->where("hie_id",$historia_estadoId)
                                   ->get()
                                   ->row();
                                   return $rsHistoriaEstado;
    }
}}