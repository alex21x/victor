<style>
    .shadow {
        box-shadow: 0px 0px 1px 0px #b3b2b2e3;
        border: .2px solid #cac8c8e3;
        border-radius: 1px;
    }
    .table-responsive table tbody tr td {
        border: .2px solid #dedede85;
        padding: 1rem;
        text-align: center;
    }            
    .totalVenta{
       display: flex;
       padding:10px;
    }

    @media (min-width:0px) {

      .img_menu{height:100px; width:120px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:150px; width:250px; background:#F3F3F3; float:left;  margin:24px 54px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}

      .name{height:30px; width:260px;margin : 20px 0px; color:#1401A0; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:22px; text-align:center; border-radius:5px;}  
      .imgMenu img{height : 90px;width  : 120px;margin : 10px 35px;}      

       
    }    
    @media (min-width: 768px) {

      .img_menu{height:50px; width:80px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:150px; width:200px; background:#F3F3F3; float:left;  margin:24px 54px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}

      .name{height:30px; width:220px; color:#1401A0;margin : 50px 0px;font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:22px; text-align:center; border-radius:5px;} 
      .imgMenu img{height : 90px;width  : 120px;margin : 10px 35px;}       
    }
   
    @media (min-width: 1200px) {
      .img_menu{height:50px; width:80px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:130px; width:190px; background:#F3F3F3; float:left;  margin:24px 24px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}

      .name{height:30px; width:220px;margin : 30px 0px;color:#1401A0; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:16px; text-align:center; border-radius:5px;}      
      .imgMenu img{height : 90px;width : 90px;margin : 0px 35px;}      
    }    


    @media (min-width: 1500px) {
      .img_menu{height:100px; width:120px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:130px; width:190px; background:#F3F3F3; float:left;  margin:24px 24px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}

      .name{height:30px; width:120px; color:#1401A0;margin : 30px 0px; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:18px; text-align:center; border-radius:5px;}  
      .imgMenu img{height : 80px;width  : 70px;margin : 10px 35px;}
    }    
    @media (min-width: 1700px) {
      .img_menu{height:100px; width:120px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:130px; width:210px; background:#F3F3F3; float:left;  margin:24px 24px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}

      .name{height:30px; width:200px; color:#1401A0;margin : 30px 0px; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:18px; text-align:center; border-radius:5px;}  
      .imgMenu img{height : 90px;width  : 90px;margin : 10px 35px;}
    }    

    @media (min-width: 1900px) {
      .img_menu{height:100px; width:120px; }
      #book_issue,#book_return,#book_renew,#new_book,#new_student,#remove,#setting,#notice,#notes{height:150px; width:250px; background:#F3F3F3; float:left;  margin:24px 20px 0 0; border-radius:5px; border:#C9C9C9 1px solid; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}      
      #book_renew:hover{background:#FFFFFF; border: 1px solid #666; box-shadow: 0px 2px 2px #999;
                    cursor:pointer; transition: background 0.4s linear 0s , border 0.4s linear 0.2s;}
      .name{height:30px; width:260px; color:#1401A0;margin : 10px 0px; font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;  font-size:18px; text-align:center; border-radius:5px;}  
      .imgMenu img{height : 90px;width  : 120px;margin : 10px 85px;}      
    }    
        /*#book_renew_div,#book_return_div,#book_issue_div,#bs_remove_div,#book_entry_div,#member_entry_div,#get_details{height:auto; margin:0 auto; width:1800px; -webkit-box-shadow: -1px 2px 14px -1px rgba(0,0,0,0.75);-moz-box-shadow: -1px 2px 14px -1px rgba(0,0,0,0.75);box-shadow: -1px 2px 14px -1px rgba(0,0,0,0.75);  background:#E1E1E1; display:none; margin-top:6px; border-radius:4px; margin-bottom:100px;}*/    

</style>
<!-- <div align="center" style="font-size: 27px">SISTEMA FACTURACIÓN ELECTRÓNICA</div> -->
<div class="container-fluid" style="margin: 0 25px;">
    <div class="row">
        <div style="font-family: tahoma; font-size: 20px" class="col-md-12">
            <span>Bienvenido:</span><?PHP echo " " . ucfirst($this->session->userdata('tipo_empleado')) . "&nbsp;&nbsp;&nbsp;" . $this->session->userdata('usuario') . ", " . $this->session->userdata('apellido_paterno'); ?>&nbsp;&nbsp;&nbsp - <?php echo $this->session->userdata('almacen_nom');?> 
        </div>
    </div>
    <hr style="border:1px solid #F2F3F4;">
</div>
<div class="container">
    <div class="sms"></div>
</div>

<div class="container-fluid" style="margin: 0 25px;">
    <div class="row">
  <div class="col-xs-12 col-md-4 col-lg-4 imgMenu">
      <div class="row">

        <a href="<?= base_url()?>index.php/comprobantes/nuevo/1/0">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" >           
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/ventas.png"></div>
              <div class="name">Ventas</div>
        </div></a>        
        <a href="<?= base_url()?>index.php/notas/nuevo">        
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/nota_ventas.png"></div>
              <div class="name">Notas de Venta</div>
        </div></a>
        <a href="<?= base_url()?>index.php/proformas/nuevo">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/proformas.png"></div>
              <div class="name">Proformas</div>
        </div></a>                        
    </div>

    <div class="row">

      <a href="<?= base_url()?>index.php/clientes">
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/clientes1.png"></div>
              <div class="name">Clientes</div>
        </div></a>          
        <a href="<?= base_url()?>index.php/productos">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/productos.png"></div>
              <div class="name">Productos</div>
        </div></a>
      <!--        
        <a href="<?= base_url()?>index.php/cajas">
        <div id="book_return" onclick="javascript:toggleSlideBox('book_return_div');">
          <div class="img_menu">  
              <img src="<?= base_url()?>images/menus/caja03.png"></div>
              <div class="name">Caja</div>
        </div></a>
        <a href="<?= base_url()?>index.php/movimiento_caja_controlador">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" > 
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/caja_chica.png"></div>
              <div class="name">Movimientos de Caja</div>
        </div></a>-->
    </div>

    <div class="row">     
      <a href="<?= base_url()?>index.php/proveedores">
        <div id="book_issue" onclick="javascript:toggleSlideBox('book_issue_div');">
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/proveedores.png"></div>
              <div class="name">Proveedores</div>
        </div></a>      
        <a href="<?= base_url()?>index.php/comprobantes_compras/nuevo/1">
        <div id="book_renew" onclick="javascript:toggleSlideBox('book_renew_div');" > 
          <div class="img_menu">
              <img src="<?= base_url()?>images/menus/compras01.png"></div>
              <div class="name">Compras</div>
        </div></a>        
    </div>
  </div>
  <div class="col-xs-12 col-md-6 col-lg-6" style="display: flex;padding:10px;">
      <canvas id="bar-chart-grouped" width="800" height="450"></canvas>
  </div>
  </div>
  <br><br>
  <div class="row">

    <div id="contenidoLeft" style="display: block">
<div class="col-xs-10 col-md-12 col-lg-12">
  <div class="container-fluid">
    <div class="row">
      <div class="col-xs-4 col-md-4 col-lg-4" style="padding:0px;">          
            <div class="row">
              <div class="col-lg-6" style="padding: 15px;font-size: 20px;">
                     <select id="almacen" class="form-control input-sm" name="almacen" onchange="init_dashboard()">
                        <?php foreach($almacenes as $almacen):?>
                       
                               <option value="<?php echo $almacen->alm_id?>" <?php if($this->session->userdata("almacen_id")==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                            
                        <?php endforeach?>
                      </select>
              </div>
               <div class="col-lg-6" style="padding: 15px;font-size: 25px;">
                      <select class="form-control" name="moneda" id="moneda" onchange="init_dashboard()">
                           <?PHP foreach ($monedas as $value) { ?>   
                                    <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->moneda?></option>          
                           <?PHP }?>    
                           </select>
              </div>
            </div>         
      </div> 
   </div>   
   <div class="row">        
      <div class="col-xs-12 col-md-3 col-lg-3 totalVenta">        
          <div class="container">
            <div class="row" style="background: #17A589;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                VENTAS DE HOY
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                <label id="a1"></label> <label id="a2"></label>
              </div>
            </div>
         </div>
      </div>  
      <div class="col-xs-12 col-md-3 col-lg-3 totalVenta" <?= $this->session->userdata('accesoEmpleado')?>>
          <div class="container">
            <div class="row" style="background: #E74C3C;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                VENTAS DEL MES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                <label id="b1"></label> <label id="b2"></label>
              </div>
            </div>
         </div>
      </div> 
      <div class="col-xs-12 col-md-3 col-lg-3 totalVenta" <?= $this->session->userdata('accesoEmpleado')?>>
          <div class="container">
            <div class="row" style="background: #E67E22;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                COMPRAS DEL MES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                 <label id="c1"></label> <label id="c2"></label>
              </div>
            </div>
         </div>
      </div> 
      <div class="col-xs-12 col-md-3 col-lg-3 totalVenta">
          <div class="container">
            <div class="row" style="background: #26C6DA;color:#fff;border-radius: 5px;text-align: center;">
              <div class="col-lg-12" style="padding: 15px 0;font-size: 20px;">
                TOTAL CLIENTES
              </div>
               <div class="col-lg-12" style="padding: 15px 0;font-size: 30px;">
                 <label id="d1"></label> <label id="d2"></label>
              </div>
            </div>
         </div>
      </div>
      </div>
     </div>
    </div>
  </div>
</div><br><br><br><br><br>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script type="text/javascript" >

  function init_dashboard(){
    var almacen = $("#almacen").val();
    var moneda = $("#moneda").val();

    $.getJSON('<?= base_url();?>index.php/comprobantes/init_dashboard',{almacen,moneda})
     .done(function(json){

        $("#a1").text(json['moneda']);
        $("#b1").text(json['moneda']);
        $("#c1").text(json['moneda']);

        $("#a2").text(json['a']);
        $("#b2").text(json['b']);
        $("#c2").text(json['c']);
        $("#d2").text(json['d']);

       console.log(json['grafico_compras'][0]);

      //Bar chart
      new Chart(document.getElementById("bar-chart-grouped"), {
              type: 'bar',
              data: {
                labels: [json['grafico_meses'][3],json['grafico_meses'][2],json['grafico_meses'][1],json['grafico_meses'][0]],
                datasets: [
                  {
                    label: "Ventas",
                    backgroundColor: "#3e95cd",
                    data: [json['grafico_ventas'][3],json['grafico_ventas'][2],json['grafico_ventas'][1],json['grafico_ventas'][0]]
                  }, {
                    label: "Compras",
                    backgroundColor: "#8e5ea2",
                    data: [json['grafico_compras'][3],json['grafico_compras'][2],json['grafico_compras'][1],json['grafico_compras'][0]]
                  }
                ]
              },
              options: {
                title: {
                  display: true,
                  text: 'RESUMEN DE COMPRAS/VENTAS X MES'
                }
              }
          });           
     });
  }

  init_dashboard();          
</script>
 
