<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/TR/REC-html40" lang="en">
    <head>
        <title>MUNDOSOFTPERU</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">      
        <link rel="shortcut icon" type="image/x-icon" href="<?PHP echo base_url();?>images/siti01.ico" />
       

        <link rel="stylesheet" type="text/css" href="<?PHP echo base_url();?>assets/plugins/lightbox2/dist/css/lightbox.min.css">
        <script src="<?PHP echo base_url(); ?>assets/plugins/lightbox2/dist/js/lightbox-plus-jquery.min.js"></script>
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/kendo.common-material.min.css">                
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/kendo.material.min.css">          
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?PHP echo base_url()?>assets/plugins/chosen/chosen.css">
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/themes-smoothness-jquery-ui.css">         
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/jquery.toast.min.css">         
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/jquery-confirm.min.css">         
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/style_hector.css">  
         <!-- custom css -->
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/custom.css">
        <link rel="stylesheet" href="<?PHP echo base_url();?>assets/css/jquery.dataTables.min.css">   
        
     
                        
        <script src="<?PHP echo base_url(); ?>assets/js/jquery-1.11.1.min.js"></script> 
        <script src="<?PHP echo base_url()?>assets/plugins/chosen/chosen.jquery.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/jquery-ui-1.11.0.js"></script>        
        <script src="<?PHP echo base_url(); ?>assets/js/jquery.toast.min.js"></script>        
        <script src="<?PHP echo base_url(); ?>assets/js/jquery-confirm.min.js"></script>        
        <script src="<?PHP echo base_url(); ?>assets/js/function_dashboard.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/chart.min.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/jquery.dataTables.min.js"></script>                
        <script src="<?PHP echo base_url(); ?>assets/js/kendo.all.min.js"></script>    
        <script src="https://cdn.jsdelivr.net/gh/jquery-form/form@4.2.2/dist/jquery.form.min.js" integrity="sha384-FzT3vTVGXqf7wRfy8k4BiyzvbNfeYjK+frTVqZeNDFl8woCbF0CYG6g2fMEFFo/i" crossorigin="anonymous"></script>
	<script src="<?PHP echo base_url(); ?>assets/js/cliente.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/paciente.js"></script>

        <style type="text/css" >

        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }
        input[type=number] { -moz-appearance:textfield; }                    
        </style>                                                                                                    
    </head>
    <body>
        <div class="container-fluid" style="margin: 0 25px;">
            <!-- Example row of columns -->
            <div class="row">                
                <div class="col-md-12">
                    <nav class="navbar navbar-default" role="navigation" style="background: #fff;border-bottom:1px solid #D6DBDF;border-left:1px solid #D6DBDF;border-right:  1px solid #D6DBDF;">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">MENU
                                    <span class="sr-only"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>                            
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <!-- <img src="<?PHP echo "https://www.tytl.com.pe/abogados/" . $this->session->userdata('ruta_foto'); ?>" title="<?PHP echo $this->session->userdata('title');?>" height="60" width="60"> -->
                                </ul>
                                <ul class="nav navbar-nav">                                  
                                  <?php //if($this->session->userdata('tipo_empleado_id')==1):?>
                                     <li><a href="<?PHP echo base_url(); ?>index.php/acceso/inicio_administrador"><div class="img"><img src="<?= base_url();?>images/menus/inicio.png?>" height="80" width="101" style="margin:5px 25px;"/></div><i class="glyphicon glyphicon-list-alt"></i> INICIO</a></li>
                                  <?php //endif ?>  
                                    <?php foreach($_SESSION['modulos'] as $padre):?>
                                        <?php if(count($padre->modulos_hijos)>0):?>
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><div class="img"><img src="<?= base_url();?>images/menus/<?= $padre->mod_imagen?>" height="80" width="101" style="margin:5px 25px;"/></div><?= $padre->mod_icon." ".$padre->mod_descripcion?><span class="caret"></span></a>
                                                <ul class="dropdown-menu" role="menu">
                                                    <?php foreach($padre->modulos_hijos as $hijo):?>
                                                        <li>
                                                            <?php if($hijo->mod_sunat ==1):?>
                                                                <a href="<?php echo $hijo->mod_enlace?>" target="_blank"><?php echo $hijo->mod_descripcion?></a>
                                                            <?php else: ?>
                                                                <a href="<?php echo base_url()?>index.php/<?php echo $hijo->mod_enlace?>" onclick="javascript:toggleSlideBox('book_renew_div');"><?php echo $hijo->mod_descripcion?></a>
                                                            <?php endif ?>
                                                        </li>
                                                    <?php endforeach?>
                                                </ul>
                                            </li>
                                        <?php endif?>    
                                    <?php endforeach?>                                                                                               
                                    <?PHP
                                    if($this->session->userdata('grande')==1){?>
                                    <li><a href="<?PHP echo base_url(); ?>index.php/acceso/menu_principal">Menu</a></li>
                                    <?PHP }                                    
                                          $empresa = $this->db->get('empresas')->row();
                                          if($empresa->save==1):
                                    ?>
                                        <li><a href="<?php echo IP.':'.PUERTO_FACTURADOR.'/#';?>" target="_blank" onclick="cargar_facturador()">Facturador SUNAT</a></li>
                                    <?php endif ?>   

                                    <li><a href="<?PHP echo base_url(); ?>index.php/acceso/logout"><div class="img"><img src="<?= base_url();?>images/menus/salir.png?>" height="80" width="101" style="margin:5px 25px;"/></div><i class="glyphicon glyphicon-list-alt"></i> CERRAR SESION</a></li>                              
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    <?php                                                                                 
                                        $empresa = $this->db->select('id,empresa,foto')->from('empresas')->where('id',1)->get()->row();
                                        echo "<img width='100px' src='".base_url()."images/".$empresa->foto."'>";
                                     ?> 
            

                                    
                                    <?PHP
                                    $nombre = (strpos($this->session->userdata('usuario'), ' ') != '')?substr($this->session->userdata('usuario'), 0,  strpos($this->session->userdata('usuario'), ' ')):$this->session->userdata('usuario');
                                    ?>
                                    <li><strong>Sesión :</strong>&nbsp;<?PHP echo $nombre; ?><?PHP echo "<br>".$this->session->userdata('almacen_nom'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                </ul>
                            </div><!-- /.navbar-collapse -->                
                        </div><!-- /.container-fluid -->            
                    </nav>

                </div>                
            </div>
            <span class="label label-primary"><?= MODO;?></span>
        </div>        

<div id="contenedor">
    <div id="book_renew_div">
    <div class="menu_header" style="margin:50px"><div class="close" onclick="javascript:toggleSlideBox('contenedor');" style="font-size: 50px">X</div></div>