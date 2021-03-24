</div></div>
<!-- MENU DINAMINCO VENTAS - ALEXANDER FERNANDEZ 13-12-2020 -->  
  <div class="container-fluid">
    <div class="col-xs-6 col-md-6 col-lg-6" style="margin: 200px 0px;display: none;">
    <div class="row">
        <a href="<?= base_url()?>index.php/proformas/nuevo">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/proformas.png" height="150" width="171" style="margin:35px 65px;"/></div>
              <div class="name">Proformas</div>
        </div></a>
        <a href="<?= base_url()?>index.php/notas/nuevo">        
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/nota_ventas.png" height="125" width="151" style="margin:35px 65px;"/></div>
              <div class="name">Notas de Venta</div>
        </div></a>        
        <a href="<?= base_url()?>index.php/comprobantes/nuevo/1/0">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" >           
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/ventas.png" height="150" width="171" style="margin:30px 65px;"/></div>
              <div class="name">Ventas</div>
        </div></a>
        
    </div>

    <div class="row">
        <a href="<?= base_url()?>index.php/movimiento_caja_controlador">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" > 
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/caja_chica.png" height="150" width="180" style="margin:40px 65px;"/></div>
              <div class="name">Movimientos de Caja</div>
        </div></a>
        <a href="<?= base_url()?>index.php/cajas">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/caja03.png" height="150" width="200" style="margin:35px 45px;"/></div>
              <div class="name">Caja</div>
        </div></a>
        <a href="<?= base_url()?>index.php/clientes">
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/clientes1.png" height="120" width="141" style="margin:40px 65px;"/></div>
              <div class="name">Clientes</div>
        </div></a>
    </div>

    <div class="row">
      <a href="<?= base_url()?>index.php/comprobantes_compras/nuevo/1">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" > 
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/compras01.png" height="145" width="171" style="margin:40px 65px;"/></div>
              <div class="name">Compras</div>
        </div></a>
        <a href="<?= base_url()?>index.php/productos">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/productos.png" height="135" width="161" style="margin:35px 45px;"/></div>
              <div class="name">Productos</div>
        </div></a>
        <a href="<?= base_url()?>index.php/proveedores">
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/proveedores.png" height="120" width="141" style="margin:35px 65px;"/></div>
              <div class="name">Proveedores</div>
        </div></a>
    </div>
  </div>  

    </div>
    <style type="text/css">        
       #container2{        
          display: flex;
          justify-content: center;
          flex-wrap: wrap;
          align-items: center;    
          text-align: center;
       }

    </style>
        <div id="container2">
          <img src="<?= base_url()?>images/<?php echo $this->session->userdata('empresa_foto');?>" height="160" width="380" style="text-align:center;" ><br> 
          <h2><?= $this->session->userdata('empresa_razon_social')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;          
          <h3><?= $this->session->userdata('empresa_pie_pagina')?></h3>
        </div>               
        <script src="<?PHP echo base_url();?>assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?PHP echo base_url(); ?>assets/js/jquery-ui-1.11.0.js"></script>         
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
        </div>	
        <div class="modal fade" id="modalCajaMov" tabindex="-1" role="dialog">        
        </div>          
        <div class="modal fade" id="modalCajaMovCierre" tabindex="-1" role="dialog">        
        </div>          
        <div class="modal fade" id="myModalProducto" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalPrecio" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalNuevoCliente" tabindex="-1" role="dialog">
        </div>        
        <div class="modal fade" id="myModalPagoMonto" tabindex="-1" role="dialog">
        </div>
        <div class="modal fade" id="myModalNuevoPaciente" tabindex="-1" role="dialog">
        </div>
    </body>
</html>

<!-- ALEXANDER FERNANDEZ DE LA CRUZ 13-10-2020 -->
<script type="text/JavaScript">
  /*function toggleSlideBox(x) {
      if ($('#'+x).is(":hidden")) {
        $('#'+x).slideDown(50)
      } else {
        $('#'+x).slideUp(100);                
      }
  }*/

  $(".close").on("click",function(){
   location.href ='<?= base_url()?>index.php/acceso/inicio_administrador';
  })
</script>    


