

<style type="text/css">
   .material-switch > input[type="checkbox"] {        
    display: none;  
    height: 0;
}
    .material-switch > label {
    cursor: pointer;
    height: 0px;
    position: relative; 
    width: 50px;  
}
    .material-switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: 25px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 50px;
}
    .material-switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: 25px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
    .material-switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
    .material-switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 30px;
}

    /* Agregando Inputs */
    .input-group {width: 100%;}
    .input-group-addon { min-width: 180px;text-align: right;}    
    
    /* Estilos Comprobante*/
    .clase_tahoma{
        /*font-family: Helvetica, Verdana, Segoe, sans-serif;
        //font-family: italic normal 400 16px/22px Arial, Verdana, Sans-serif;*/
        font-size: 13px;
    }    
    .panel-title{
        font-size: 13px;
        font-weight: bold;
    }    
    
    
    /* AGREGADO VALIDA */    
    .fila-base{
        display: none;        
    }                	
     /* SPAN */     
    #btnEdit{
      margin:-10px 0 0 20px;
    }        
</style>
<div id="mensaje"></div>
     
<div class="container-fluid">
<form id="formComprobante" class="form-horizontal" autocomplete="off">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>COMPRA - <b><?= $empresa['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info" >
                <div class="panel-heading" >
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">    
                        <div class="col-xs-12 col-xs-6 form-inline col-sm-6 col-md-6 col-lg-3"  >
                            <label> Tipo de Operación</label>                            
                             <select class="form-control" name="operacion" id="operacion">
                                <option value="0101">Compra Interna</option> 
                                <option value="0200">Importación</option>      
                             </select>
                        </div>
                        <div class="col-xs-12 form-inline col-sm-6 col-lg-9" style="padding-bottom: 1rem;margin-top: 8px;">
                            <label style="color:#fff;"> Incluye igv</label>                            
                            <input type="hidden" name="incluye_igv" id="incluye_igv" <?php echo ($igv->pu_igv_c==1)?"checked":"";?>>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <!--
                            <input type="text" class="form-control" name="cliente" id="cliente">
                            <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id"></div>-->

                             <label class="control-label" style="width: 100%;text-align: left;">Proveedor:</label>
                            <input type="hidden" name="cliente_id" id="cliente_id" required="">
                            <button style="width: 9%;float: right;margin-left: 1%;" type="button" id="nuevo_cliente" class="btn btn-primary btn-sm" onclick="consulta_sunat()">BUSCAR</button>
                             <button style="width: 9%;float: right;margin-left: 1%;" type="button" id="nuevo_cliente" class="btn btn-primary btn-sm" data-toggle='modal' data-target='#myModalNuevoCliente'>Nuevo</button>
                            <input type="text" class="form-control" list="lista_clientes" id="cliente" onkeyup="buscar_cliente()" onchange="seleccionar_cliente()" style="width: 80%;float: right;" >
                                <datalist id="lista_clientes" >
                                  
                                </datalist>

                                 <input type="hidden" name="ruc_sunat" id="ruc_sunat">
                                <input type="hidden" name="razon_sunat" id="razon_sunat">
                               
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion">
                        </div>

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tipo Documento:</label>        
                            <select  class="form-control" name="tipo_documento" id="tipo_documento">
                            <?PHP foreach ($tipo_documentos as $value) { ?>                          
                                <option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_documento']?></option>
                            <?PHP }?>
                               <!-- <option value="<?php echo $factura_antigua?>">Facturas Antiguas</option>
                                <option value="<?php echo $boleta_antigua?>">Boletas Antiguas</option>-->
                            </select>    
                        </div>    

                        <div class="col-xs-6 col-md-2 col-lg-2">            
                            <label class="control-label">Serie:</label>
                            <!--<input style="text-transform:uppercase" type="text" class="form-control" name="serie" id="serie" placeholder="F001" maxlength="4" pattern='^[fF]{1}[fF|\d]{1}(\d){2}' title="Serie FF.. ó F...">-->
                        
                                <input type="text" class="form-control" name="serie" id="serie" required="" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="4" style="text-transform: uppercase;">
                                   
                        
                         
                        </div>

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                            <input type="text" class="form-control" name="numero" id="numero" maxlength="9" required="">
                        </div>


                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha emision:</label>
                            <input type="text" class="form-control" name="fecha_de_emision" id="fecha_de_emision" value="<?PHP
                            if(isset($_POST['fecha_de_emision']))
                                echo $_POST['fecha_de_emision'];
                            else
                                echo date('d-m-Y');
                            ?>" placeholder="Fecha Emision">
                        </div>    

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Moneda:</label>        
                            <select class="form-control" name="moneda_id" id="moneda_id">
                           <?PHP foreach ($monedas as $value) { ?>                          
                               <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->moneda;?></option>
                           <?PHP }?>    
                           </select>
                        </div>       

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tip. Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="">
                        </div>

                        <!--<div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha de Venc:</label>
                            <input type="text" class="form-control" name="fecha_de_vencimiento" id="fecha_de_vencimiento" value="<?PHP
                                if(isset($_POST['fecha_de_vencimiento']))
                                    echo $_POST['fecha_de_vencimiento'];
                                else
                                    echo date('d-m-Y');
                                ?>" placeholder="Fechad de vencimiento">
                        </div>-->
                         <!--<div class="col-xs-2">
                            <label class=" control-label">Vendedor</label>
                            <select class="form-control" name="vendedor" id="vendedor">
                                <option value="">Seleccione vendedor</option>
                                <?php foreach($vendedores as $v){?>
                                  <option value="<?php echo $v->id?>"><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                <?php }?>    
                            </select>    
                        </div>-->

                        <div class="col-xs-6 col-md-2">
                                    <label class=" control-label"># Guía</label>
                                    <div class="input-group">
                                        <input type="text" name="numero_guia" id="numero_guia" class="form-control" value="">
                                        <!--<span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btn_buscar_guia"><i class="glyphicon glyphicon-search"></i></button>
                                        </span>-->
                                    </div>
                                    
                                </div>

<!-- ARRAY ADELANTO DE ITEMS              --->
<input type="hidden" value="[]" id="adelanto_items" name="adelanto_items">
                         <!-- numero pedido -->
                         <?php if ($configuracion->numero_pedido): ?>
                            <div class="col-xs-2">
                                <label class="control-label">N° pedido </label>
                                <input type="text" name="numero_pedido" class="form-control">
                            </div>      
                         <?php endif ?>
                        
                        <!-- numero de guia remision -->    
                        <?php if ($configuracion->numero_guia): ?>                            
                             <div class="col-xs-2">
                                <label class="control-label"> N° guia remision </label>
                                <input type="text"  class="form-control">
                            </div>                            
                        <?php endif ?>
                        <!-- anticipos -->
                        <?php if ($configuracion->anticipo): ?>
                            <div class="col-xs-1">
                                <label>&nbsp;</label>
                                <br>
                                <button type="button" class="btn" id="btn_es_anticipo">Anticipo</button>
                            </div> 
                        <?php endif ?>
                        <!-- condicion de venta -->
                        <?php if ($configuracion->condicion_venta): ?>                            
                            <div class="col-xs-4">
                                 <label>&nbsp;</label>
                                <label>Condición de Venta</label>
                                <input type="text" name="condicion_venta" class="form-control">
                            </div>
                        <?php endif ?>

                        
                                             
                    </div>  
                </div>        
            </div>
            
            <div class="row" style="padding-top:20px;">                
                <div class="col-lg-12">
                    <div class="panel panel-info" >  
                        <div class="panel-heading">
                            <div class="panel-title">CONCEPTOS DEL COMPROBANTE</div>
                        </div>
                        <div class="panel-body">                        
                            <div class="row" id="valida">
                                <div class="col-lg-12">
                                    <table id="tabla" class="table" style="display:none" border="0">
                                        <thead>
                                            <tr>                                                
                                                <th colspan="2">Descripcion</th>                                                
                                                <th>Cant.</th>
                                                <th>Tipo Igv</th>
                                                <th>Precio Unitario</th>                                                                                            
                                                <?php
                                                 if ($configuracion->descuento) {
                                                    echo "<th>Descuento</th>";
                                                 } else {
                                                    echo "<th style='display:none;'> Descuento</th>";
                                                 }?>                                                                                                      
                                                <th>Total</th>
                                                <th></th>
                                            </tr>
                                        </thead>                    
                                        <tbody>                                                      
                                        </tbody>                    
                                        </table>   
                                    <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>       
                                    <button type="button" id="btn_buscar_producto" data-tipo_comprobante="2" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#myModalProducto" data-keyboard='false' data-backdrop='static'>Buscar Producto</button>                             
                                </div> 
                            </div>            
                            <div id="mostrar"></div>
                            <div id="uu"></div>
                        </div>                            
                    </div>
                </div>                    
            </div>

        </div>
    </div>
    <div class="row" style="padding-top:20px;">               
        <div class="col-xs-12 col-md-8 col-lg-8">                                                                         
                <!-- MUESTRA DETRACCION , FACTURA O BOLETA -->    
                
              <!-- MOSTRAR NOTA DE CREDITO, NOTA DE DEBITO --> 
              <div id="mostrarCompNota">
                  <div class="panel panel-info" id="panelDetraccion">
                  <div class="panel-heading">
                      <div class="panel-title">ADJUNTA A COMPROBANTE</div>
                  </div>
                  
                  <div class="panel-body" >
                      <div class="form-group">                                                                    
                          <div class="col-xs-4 col-md-4 col-lg-4" >                                      
                               <label class="control-label">Documento a Modificar</label>
                <input type="text" class="form-control input-sm" name="adjunto_serie" placeholder="Serie">
                <input type="text" class="form-control input-sm" name="adjunto_numero" placeholder="Número">
                <input type="date" class="form-control input-sm" name="adjunto_fecha" placeholder="Fecha de Emisión">
                          </div>
                          
                           <div class="col-xs-4 col-md-4 col-lg-4">
                              <label class="control-label">Tipo Nota de Crédito</label>
                              <select class="form-control" id ="tipo_ncredito" name="tipo_ncredito">
                                  <?PHP foreach ($tipo_ncreditos as $value) { ?>
                                        <option value="<?PHP echo $value['id'].'*'.$value['codigo']?>"><?PHP echo $value['tipo_ncredito']?></option>
                                  <?PHP }?>
                              </select>                                      
                          </div>
                           
                           <div class="col-xs-4 col-md-4 col-lg-4">
                              <label class="control-label">Tipo Nota de Débito</label>
                              <select class="form-control" id ="tipo_ndebito" name="tipo_ndebito">
                                  <?PHP foreach ($tipo_ndebitos as $value) { ?>
                                        <option value="<?PHP echo $value['id'].'*'.$value['codigo']?>"><?PHP echo $value['tipo_ndebito']?></option>
                                  <?PHP }?>
                              </select>                                      
                          </div>        
                      </div>                                                                                           
                  </div>                          
              </div>                                                
            </div>
            
            <div class="panel panel-info" style="display: none">                    
                <div class="panel-heading">
                    <div class="panel-title">METODO DE PAGO</div>
                </div>
                <div class="panel-body">
                    <div class="row" style="width: 100%; margin: 0 auto;">
                        <div class="col-sm-4 form-group"> 
                            <label class="control-label">Tipo de Pago:</label>                    
                            <select class="form-control" name="tipo_pago" id="tipo_pago">
                            <?PHP foreach ($tipo_pagos as $value) { ?>                          
                                <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->tipo_pago?></option>
                            <?PHP }?>    
                            </select>    
                                
                        </div>
                        <div class="col-md-3" id="conte-ntarjeta" style="display: none;">
                            <label>Numero tarjeta </label>
                            <input type="text" name="ntarjeta" placeholder="******1234" value="000000" class="form-control">
                        </div> 
                    </div>
                </div> 
            </div>
            <!-- anticipos -->
            <?php if ($configuracion->anticipo): ?>                
                <div class="panel panel-info" id="panel_anticipos">
                    <div class="panel-heading">
                        <div class="panel-title">ANTICIPOS</div>
                    </div>
                    <div class="panel-body">
                        <button type="button" class="btn btn-primary btn-sm" id="btn_agregar_anticipo" data-toggle="modal" data-target="#myModal">Agregar Anticipo</button>
                        <br>
                        <br>
                        <div id="lista_anticipos"></div>                    
                    </div>
                </div>
            <?php endif ?>

            <!-- notas -->
            <!--<?php if ($configuracion->notas): ?>                -->
                <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">Notas <input type="checkbox" name="chkNotas" id="chkNotas"></div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" disabled></textarea>
                    </div>
                </div>            
            <!--<?php endif ?>-->
            <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">Notas <input type="checkbox" name="chkNotas" id="chkNotas"></div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" disabled style="width: 100%;"></textarea>
                    </div>
                </div>   

        </div>        
        <div class="col-xs-12 col-md-4 col-lg-4">
           
                <div class="panel panel-body" style="border:1px solid #7FB3D5;border-radius:6px;">
                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Descuento: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="descuento_global" name="descuento_global" class="form-control" style="border:1px solid #ABB2B9;border-bottom:0;" value="0.00">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Inafecta: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_inafecta" name="total_inafecta" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>
                     <div class="input-group" style="display: none;">        
                        <span class="input-group-addon">Anticipos: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_anticipos" name="total_anticipos" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div> 
                    <div class="input-group" >        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Op. Exonerada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_exonerada" name="total_exonerada" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>
  
                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gravada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total IGV (18%): <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Total Ope. Gratuita: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gratuita" name="total_gratuita" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>
   
                    <div class="input-group">        
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">Otros Cargos: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_otros_cargos" name="total_otros_cargos" class="form-control" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>


                    <div class="input-group" style="display: none;">        
                        <span class="input-group-addon">Descuento Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_descuentos" name="total_descuentos" class="form-control" value="0.00" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
                    </div>    
                    <div class="input-group">                
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-right: 0;">Importe Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control" readonly="" style="border:1px solid #ABB2B9;">
                    </div>    
    
                </div>
           
        </div>
                
        <div class="container-fluid">
            <div class="row" style="padding-bottom: 2rem;">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                    <input id="guardar"  class="btn btn-primary btn-lg btn-block" value="Generar Comprobante de Pago" style="background: #1ABC9C;border:0;" data-toggle='modal' data-target='#myModalPagoMonto' data-keyboard='false' data-backdrop='static'/>
                </div>
            </div>
        </div>
    
    </div>  




</form>




    <!--  modal nuevo cliente -->
<div class="modal fade" id="myModalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalNuevoCliente"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Proveedor</h4>
      </div>
      <div class="modal-body" style="display: flex;">
        <div class="container">
    <!-- Example row of columns -->
      <form class="form-horizontal" role="form"  method="POST" id="formNuevoCliente">
    <div class="row">                
       
     
           
     
          
                 
            <div class="col-md-12">
                <div class="form-group">
                    <label for="codigo">RUC</label>
                    <input type="number" id="ruc" name="ruc" class="form-control input-sm" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" value="<?php echo $proveedor->prov_ruc?>">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="nombre">Razón Social</label>
                    <input type="text" id="razon_social" name="razon_social" class="form-control input-sm" value="">
                </div>
            </div>
      
            <div class="col-md-12">
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" name="direccion" class="form-control input-sm" value="">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="telefono">Teléfono</label>
                    <input type="number" id="telefono" name="telefono" class="form-control input-sm" value="" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9">
                </div>
            </div>
   
                
                    
            

        </div>
        <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" id="guardarNuevoCliente" value="Guardar" >
                             <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
         
      </form>
    </div>
</div>
      </div>
      <div class="modal-footer">
       
      </div>            
    </div>  
</div>
</div>

</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>

<script type="text/javascript"> 

     function consulta_sunat(){
        var num = $("#cliente").val();

        if(num!=''){
        if(num.length == 8){//DNI
            $.getJSON('https://mundosoftperu.com/reniec/consulta_reniec.php',{dni:num})
             .done(function(json){                
                if(json[0].length!=undefined){
                    var dni = json[0];
                    var nombres = json[2]+' '+json[3]+' '+json[1];
                    $("#cliente_id").val('nApi');
                    $("#cliente").val("DNI "+ dni +" "+ nombres);
                    $("#direccion").val('LIMA');
                    $("#ruc_sunat").val(dni);
                    $("#razon_sunat").val(nombres);                     
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe');
                 }
             });     
        }else if(num.length == 11){//RUC
            toast("info",4000, 'Buscando . . .');
            $.getJSON('https://mundosoftperu.com/sunat/sunat/consulta.php',{nruc:num})
             .done(function(json){
      
                 if(json.result.RUC.length!=undefined){
                    $("#cliente_id").val('jApi');
                    $("#cliente").val("RUC "+json.result.RUC+" "+json.result.RazonSocial);
                    $("#direccion").val(json.result.Direccion);
                    $("#ruc_sunat").val(json.result.RUC);
                    $("#razon_sunat").val(json.result.RazonSocial);                     
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe en SUNAT');
                 }
             });


        }else{
            toast("error",3000, 'DEBE DE INGRESAR UN DNI/RUC CORRECTO');            
        }} else{         
             toast("error",3000, 'Ingrese número de documento de búsqueda');
        }
    }

    function buscar_cliente(){
        var texto = $("#cliente").val();
        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes_compras/buscar_cliente",{texto})
         .done(function(json){
            
             var html = '';
             $.each(json,function(index,value){
               



                 html+= '<option value="'+ value.prov_id+ ' - ' + 'RUC' + ' ' +value.prov_ruc + ' ' + value.prov_razon_social + '">';
             });

             $("#lista_clientes").html(html);
         })
    }

    function seleccionar_cliente(){
        var opcion = $("#cliente").val();
        var guion = opcion.search("-");
        var cliente_id = opcion.substr(0,guion-1);

        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes_compras/seleccionar_cliente",{cliente_id})
         .done(function(json){
            console.log(json);
            $("#cliente_id").val(json.prov_id);
            $("#direccion").val(json.prov_direccion);

            
             
         })
        
    }

    var array_adelanto_items = [];

    $("#btn_buscar_guia").click(function(){
         var guia = $("#numero_guia").val();
         $.getJSON("<?PHP echo base_url()?>index.php/comprobantes/buscar_guia",{guia})
          .done(function(json){
               
               $("#tabla > tbody tr").remove();

             if(json.det.length>0){  
               $.each(json.det,function(index,value){
                  array_adelanto_items.push(value.notapd_producto_id);
                  var fila = '<tr class="cont-item">';     
                      fila += '<td class="col-sm-3"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+ value.notapd_descripcion +'"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+ value.notapd_producto_id +'"></div> </td>';   
                      fila += '<td><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="'+ value.notapd_cantidad +'"></td>';
                      fila += '<td class="col-sm-2">';
                      fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                        <?php foreach($tipo_igv as $value):?>
                          
                           fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                         
                        <?php endforeach?>
                      fila += '</select>'
                      fila += '</td>';
                      fila += '<td><input type="number" id="importe" name="importe[]" required="" class="form-control importe" value="'+ value.notapd_precio_unitario +'"></td>';
                          /*fila += '<td><input type="text" id="subtotal" name="subtotal[]" class="form-control" readonly=""></td>';*/
                        fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" >';
                            <?php if ($configuracion->descuento): ?>                    
                                fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                            <?php else: ?>
                                fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                            <?php endif ?>

                        fila += '<td><input type="text" id="total" name="total[]" class="form-control totalp" value="'+ value.notapd_subtotal +'" readonly=""></td>';
                        fila += '<td class="eliminar"><span class="glyphicon glyphicon-remove"></span></td>';
                        fila += '</tr>';
                     
                     $("#tabla").css("display","block");  
                     $("#tabla > tbody").append(fila);
                     calcular(); 
               });

               if(json.ade==1){
                 $("#adelanto_items").val(JSON.stringify(array_adelanto_items));
               }
               

               $("#cliente").val(json.cli.razon_social+" "+json.cli.nombres);
               $("#cliente_id").val(json.cli.id);
               $("#direccion").val(json.doc.notap_cliente_direccion);

            }else{
                 toast("error", 1500, "Documento ya fue usado o no se encuentra");
                 $("#cliente").val('');
                 $("#cliente_id").val('');
                 $("#direccion").val('');
                 $("#adelanto_items").val('');
            }  
              

          })
    })




    //FUNCIONES FECHAS   

    //registrar comprpobantes
    /*$("#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            method:'post',
            url:'<?PHP //echo base_url()?>index.php/comprobantes_compras/guardar_comprobante',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_FAIL)
                {
                    toast("error",3000, response.msg);
                }
                if(response.status == STATUS_OK)
                {
                    toast("success", 1500, 'Compra registrado');
                    location.href='<?PHP echo base_url()?>index.php/comprobantes_compras';
                }
            }
        });        
    });*/

    jQuery(document).ready(function($) {

        

        $("#tipo_cliente").change(function () {
            var op = $("#tipo_cliente option:selected").val();
            var array = op.split('xx-xx-xx');
            $("#datos").show();
            if (array[0] == 1) {
                $("#lbl_DNI_RUC").text('DNI');
                $("#ruc").attr("placeholder","DNI");
                $("#ruc").attr("maxlength","8");

                $("#lbl_RAZ_APE").text('Apellidos');
                $("#razon_social").attr("placeholder","Apellidos");
                $("#nombres").show();
            }else{
                $("#lbl_DNI_RUC").text('RUC');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").text('Razon Social');
                $("#razon_social").attr("placeholder","razon_social");
                $("#nombres").hide();
            }

        });

        $('#guardarNuevoCliente').click(function(e){
            e.preventDefault();
        
        var url = "<?PHP echo base_url() ?>index.php/proveedores/grabar_para_comprobante";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#formNuevoCliente").serialize(), 
           success: function(data)             
           {
              var cliente = JSON.parse(data);
              if(cliente['success']==4){
                 toast("success", 1500, 'Proveedor ingresado con exito');
                 $("#formNuevoCliente")[0].reset(); 
                 $("#closeModalNuevoCliente").click(); 
                 $('#cliente').val(cliente['nombre']);     
                 $('#cliente_id').val(cliente['id']); 
                 $("#datos").hide();
                 

              }else{
                 if(cliente['success']==1){
                    toast("error",3000, "Ingrese número de documento");
                 }else if(cliente['success']==2){
                    toast("error",3000, "Ingrese nombre o Razón Social");
                 }
                  
              }
                     
           }
       });
    });
        
        //// FALSE : NO IGV; TRUE : SI IGV
        //cmp.incluyeIgv=false;
        
        $("#incluye_igv").click(function(){
            if( $(this).is(':checked') ) {
                var valor = 1;
            }else{
                var valor = 0;
            }

            $.get("<?PHP echo base_url()?>index.php/comprobantes_compras/estado_igv",{valor})
             .done(function(res){
                 if(res==1){
                    toast('success', 1500, 'IGV Incluido');
                 }else{
                    toast('success', 1500, 'IGV No Incluido');
                 }

                 location.reload();
             })
        });


        <?php 
            $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
            if ($configuracion->pu_igv_c==1) {
                echo "  cmp.incluyeIgv = true;
                        console.log(cmp.incluyeIgv);
                        calcular(); ";
            } else {
                echo "  cmp.incluyeIgv = false;
                         console.log(cmp.incluyeIgv);
                        calcular();";
            }
        ?>

        $('#conte-ntarjeta').hide();

        $('#tipo_pago').change(function(event) {
            if ($(this).val()==1) {
                $('#conte-ntarjeta').hide();
            }else {
                $('#conte-ntarjeta').hide();
            }
        });

       

    });
    function refescar() {
        var tabla = $('#tabla > tbody > tr');
        $.each(tabla,function(indice,value){   
            var parent = $(this); 
            console.log(parent);    
            cmp.calcular(parent);    
        });
    }
    //es anticipo
    var _es_anticipo = false;    
    var textoReferenciaNotas = "";
    $("#chkNotas").change(function(e){
        if($(this).is(":checked"))
        {
            $("#notas").removeAttr("disabled");
            $("#notas").val(textoReferenciaNotas);
        }else{
             $("#notas").attr("disabled","tue");
             $("#notas").val("");           
        }
    });

    $("#moneda_id").change(function(e){
        e.preventDefault();
        var moneda = $(this).val();//1:sol,2dolares,3euro
        var simbolo = '';
        if(moneda=='1')
            simbolo = 'S/.';
        if(moneda == '2')
            simbolo = '$';
        if(moneda == '3')
            simbolo = '€'; 

        $(".selec_moneda").html(simbolo);           
    });
    
    $(function(){
        $("#fecha_de_emision").datepicker();
        $("#fecha_de_vencimiento").datepicker();
                
        /*$('#cliente').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_cliente',
            minLength : 2,
            select : function (event,ui){  
                                        
                var data_cli = '<input type="hidden" value="'+ ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
                $("#direccion").val(ui.item.domicilio1);

                if($('#tipo_documento option:selected').val() == "7"){
                    updateDocumentoNotaCredito();
                }
            },
            change : function(event,ui){
                if(!ui.item){
                    this.value = '';
                    $('#cliente_id').val(''); 
                }                   
            }                
        });*/
    });

    /*buscar item*/
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_itema',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id">';
                _item.find('#data_item').html(data_item);

                _item.find('#descripcion').attr("readonly",true);

                
                _item.find('.importe').val(ui.item.precio);
                
                var parent = $(this).parents().parents().get(0);
                

                _item.find('.totalp').val(ui.item.precio);
                cmp.calcular(parent);
                calcular();
                calcular();
                
            },
            change : function(event,ui){
                if(!ui.item){
                    //si es nota no se pondrá vacio
                    var tipoDocumento = $("#tipo_documento").val();
                    if(tipoDocumento=='1' || tipoDocumento=='3')
                    {
                        this.value = '';
                        _item.find('#item_id').val(''); 
                        _item.find('.importe').val('');
                    }

                }
            }                
        });
    });
    
    function updateDocumentoNotaCredito(){        
        $('#mostrarDetraccion').css('display','none');
        $('#mostrarCompNota').css('display','block');
                    
        var facturas_cliente =
                '<label class="control-label">Documento a Modificar</label>' +
                '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
                '</select>';
        $('#div_facturas_cliente').html(facturas_cliente);
        $("#comp_adjunto").load('<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + $('#cliente_id').val() + '/' + $('#serie option:selected').val());
        $('#tipo_ncredito').prop('disabled',false);
        $('#tipo_ndebito').prop('disabled',true);
    }     

   /* function updateDocumentoNotaCredito(){        
        $('#mostrarDetraccion').css('display','none');
        $('#mostrarCompNota').css('display','block');
                    
        var facturas_cliente =
                '<label class="control-label">Documento a Modificar</label>' +
                '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
                '</select>';
        $('#div_facturas_cliente').html(facturas_cliente);
        $("#comp_adjunto").load('<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + $('#cliente_id').val() + '/' + $('#serie option:selected').val());
        $('#tipo_ncredito').prop('disabled',false);
        $('#tipo_ndebito').prop('disabled',true);
    } */


    function agregar_fila_sin_stock(){
       
        var fila = '<tr class="cont-item">';
                               
                fila += '<td class="col-sm-4" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required=""></textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div> </td>';
                
                                
                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';

                fila += '<td class="col-sm-2" style="border:0;">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>
                  
                   fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                 
                <?php endforeach?>
                fila += '</select>'
                fila += '</td>';
                
                fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe" ></td>';
                /*fila += '<td><input type="text" id="subtotal" name="subtotal[]" class="form-control" readonly=""></td>';*/
                fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" >';
                <?php if ($configuracion->descuento): ?>                    
                    fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>'
                <?php else: ?>
                    fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                <?php endif ?>

                fila += '<td style="border:0;"><input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly=""></td>';
                fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
            fila += '</tr>';

            $("#tabla").css("display","block");
               $("#tabla tbody").append(fila);
               calcular();                            
               //Llamada Evento Chosen
               $('.tipo_igv').chosen({                
                   search_contains : true,
                   no_results_text : 'No se encontraton estos tags',                
               });  
    }

    //AGREGANDO FILA
    $(function(){ 
        

        $("#agrega").on('click', function(){           
            agregarFila();
        });                

        //REMOVIENDO ITEMS
        $(document).on("click",".eliminar",function(){
        //$(this).parents().get(0).remove();
                $(this).parent().remove();
                if($("#tabla tbody tr").length === 0)
                    $("#tabla").css("display","none");      
                    calcular();     
        });

     //EVENTO COMBOBOX TIPO DE CAMBIO
        $(document).on('change','#moneda_id',function(){
         tipoCambio();                                                  
        });
        
        //CAPTURANDO EVENTOS
        $('#tipo_de_detraccion').prop('disabled',true);
        //Operacion Gratuita
        $('#operacion_gratuita').on('change',function(){
            operacion_gratuita();
        });
        //Entrada Solo numeros
        $('#cantidad,#numero,#desc_uni').on('keydown',function(e){
            validNumericos(e);
        });
        //Serir entrada Alfanumerico
        /*$('#serie').on('keydown',function(e){
            validAlfaNumerico(e);
        });*/

       //RECIBIENDO DATOS COMPROBANTE A FACTURAR
        if('<?= $valida;?>' === '1'){
            $.ajax({
                url : '<?= base_url()?>index.php/comprobantes/jsonComprobante/<?= $ajaxId;?>',
                type : 'GET',
                success : function(data){
                    var comprobante_id,moneda_id,cliente_id,razon_social,moneda,fecha,item;
                    $.each(data, function(i,msg){
                        comprobante_id = msg.comprobante_id;
                        moneda_id = msg.moneda_id;
                        cliente_id = msg.cliente_id;
                        razon_social = msg.razon_social;
                        moneda = msg.moneda;
                        fecha = msg.fecha;
                        item = msg.item;
                    });
                    $('#fecha_de_emision').val(fecha);
                    $('#cliente_id').val(cliente_id);
                    $('#cliente').val(razon_social);

                    console.log(comprobante_id +'/'+cliente_id +'/'+ fecha);
                    console.log(item);
                    //tipoMoneda(moneda_id);
                    $('#moneda_id').append('<option value='+moneda_id+' SELECTED>'+moneda+'</option>');

                    agregarFila();
                    $('#descripcion').val(item.descripcion);
                    $('#importe').val(item.importe);

                    var parent = $('table tbody tr');
                    cmp.calcular(parent);
                    tipoCambio();
                },
                dataType : 'JSON'
            });
        }
        //FUNCION AGREGAR FILA
        function agregarFila(){    
            var fila = '<tr class="cont-item" >';
                               
                fila += '<td colspan="2" class="col-sm-4" style="border:0;">'+
                        '<input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="">'+
                        '<div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div> </td>';

                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';
                fila += '<td class="col-sm-2" style="border:0;">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>                  
                        fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';                 
                <?php endforeach?>
                fila += '</select>'
                fila += '</td>';
                
                fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe"></td>';
                /*fila += '<td><input type="text" id="subtotal" name="subtotal[]" class="form-control" readonly=""></td>';*/
                fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" >';
                <?php if ($configuracion->descuento): ?>                    
                    fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php else: ?>
                    fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php endif ?>

                fila += '<td style="border:0;"><input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly=""></td>';
                fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
            fila += '</tr>';
            $("#tabla").css("display","block");
               $("#tabla tbody").append(fila);
               calcular();                            
               //Llamada Evento Chosen
               $('.tipo_igv').chosen({                
                   search_contains : true,
                   no_results_text : 'No se encontraton estos tags',                
               });    
        }


        //EVENTO COMBOBOX TIPO DE CAMBIO
        function tipoCambio(){     
         var selec = $('#moneda_id option:selected').val();
               if(selec > 1){
                   $('#tipo_de_cambio').prop('disabled',false);
                   $.ajax({
                   url : "<?= base_url()?>index.php/comprobantes/tipoCambio",
                   method : "POST",
                   data : {moneda_id : selec},
                   dataType : 'JSON',
                   success : function(data){                    
                               $('#tipo_de_cambio').val(data.tipo_cambio);
                               calcular();
                       }
                   });                    
               } else {
                   $('#tipo_de_cambio').val('');
                   $('#tipo_de_cambio').prop('disabled',true);
                   calcular();
               }                                       
        }

        //COMPROBANTE ADJUNTO 
        $("#comp_adjunto").chosen({
           search_contains : true,
           no_results_text : 'No se encontraton estos tags'        
       });
        $('#mostrarCompNota').css('display', 'none');

        //OBTENIENDO SERIE,NUMERO
        function documentoChange(){
            var selec = $('#tipo_documento option:selected').val();
            //solo para boletas, facturas, notas de credito y debito,
            //obviamos la opcion: facturas antiguas y boletas antiguas
            console.log(selec);
            if((selec == 1) || (selec == 3) || (selec == 7) || (selec == 8) || (selec == 9) || (selec == 10)){
               /* $.ajax({
                    url : '<?= base_url()?>index.php/serNums/selectSerie/<?= $empresa['id']?>',
                    type: 'POST',
                    data: {tipo_documento_id : selec},
                    dataType : 'HTML',
                    success :  function(data){
                        $('#serie').html(data);
                        serieChange();
                    }
                });

                var serie_selec = $('#serie option:selected').val();
               
                
                $('#div_serie_actual').show();
                $('#div_serie_antiguo').hide();
                $("#numero").attr("readonly", true);
                //seteo el valor de la serie antiguo (serie manual).
                $('#serie_antiguo').val('');*/
                
            }else{
                /*$('#div_serie_actual').hide();
                $('#div_serie_antiguo').show();
                $("#numero").attr("readonly", false);
                $("#numero").val('');*/
            }
        }

        function serieChange(){
            var selec  = $("#serie option:selected").val();
          
            var tipo_documento = $('#tipo_documento option:selected').val();
            var url_ser = '<?= base_url()?>index.php/comprobantes/selectUltimoReg/<?= $empresa['id']?>/'+tipo_documento+'/'+selec;
            //alert(url_ser);
            //console.log(selec);
           /* $.ajax({
                url : url_ser,
                type: 'POST',
                data: {serieId : selec},
                dataType : 'JSON',
                success :  function(data){
                    $('#numero').val(parseInt(data.numero));
                }
            });

          

            if(tipo_documento <= 3){
                    $('#mostrarCompNota').css('display','none');
                    if(tipo_documento == 1){
                        $('#mostrarDetraccion').css('display','block');
                    }
                    if(tipo_documento == 3){
                        $('#mostrarDetraccion').css('display','none');
                    }
                } else {
                    $('#mostrarDetraccion').css('display','none');
                    $('#mostrarCompNota').css('display','block');
                    if(tipo_documento == 7 || tipo_documento == 9){
                        cargaDocumentosNotasCredito();
                        $('#tipo_ncredito').prop('disabled',false);
                        $('#tipo_ndebito').prop('disabled',true);
                    }
                    if(tipo_documento == 8 || tipo_documento == 10){
                        cargaDocumentosNotasCredito();
                        $('#tipo_ncredito').prop('disabled',true);
                        $('#tipo_ndebito').prop('disabled',false);
                    }
                }*/
        }

       //carga documentos para Notas de credito
        function cargaDocumentosNotasCredito(){
            var serie_selec = $('#serie option:selected').val();
            var cliente_id = $('#cliente_id').val();

            console.log(serie_selec);
            console.log(cliente_id);
            var facturas_cliente =
                   '<label class="control-label">Documento a Modificar</label>' +
                   '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
                   '</select>';
            $('#div_facturas_cliente').html(facturas_cliente);
            $("#comp_adjunto").load('<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + 1 + '/' + cliente_id + '/' + serie_selec);

            //$('#tipo_ncredito').prop('disabled',false);
            //$('#tipo_ndebito').prop('disabled',true);
        }

        // EVENTO COMBOBOX NOTA DE CREDITO , DEBITO
        $('#tipo_documento').on('change',function(){    
           documentoChange();                
       });

        /*$('#serie').on('click',function(){
           serieChange();
        });*/
        documentoChange();

       // serieChange();
        $("#btn_es_anticipo").click(function(e){
            e.preventDefault();
            var anticipo = 0;
            if(!_es_anticipo)
            {
                _es_anticipo = true;
                $(this).addClass("btn-success");
                $("#anticipo").val('1');
                $("#panel_anticipos").hide();
            }else{
                _es_anticipo = false;
                $(this).removeClass("btn-success");
                $("#anticipo").val('0');
                $("#panel_anticipos").show();
            }
            calcular();
        });
        //modal para agregar anticipo
        $("#btn_agregar_anticipo").click(function(e){
            var datos = {
                            cliente:$("#cliente_id").val()
                        };
            $("#myModal").load('<?php echo base_url()?>index.php/comprobantes/agregarAnticipoUi',datos);
        });


    });

    function obtenerAnticipos()
    {
        //obtenemos los anticipos agregadp en session
        $.ajax({
            url:'<?php echo base_url()?>index.php/comprobantes/getListaAnticiposAgregados',
            method:'post',
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_OK)
                {
                    $("#lista_anticipos").html("");
                    var anticipos = response.data;
                    var _html = '<table class="table table-bordered table-xs" style="width:500px">';
                    _html += '<thead><tr><th>Nº Documento</th><th>Importe</th><th></th></tr></thead>';
                    _html += '<tbody>';
                    $.each(anticipos, function(index, value){
                        _html += '<tr>';
                        _html += '<td>'+value.anticipo_numero+'</td>';
                        _html += '<td>'+value.anticipo_total+'</td>';
                        _html += '<td>'+value.eliminar+'</td>';
                        _html += '</tr>';
                    });
                    _html += '<tbody>';
                    _html += '</table>';
                    $("#lista_anticipos").html(_html);
                    //eliminar anticipo
                    $(".btn-eliminar_anticipo").unbind('click');
                    $(".btn-eliminar_anticipo").click(function(e){
                        e.preventDefault();
                        var datos = {
                                        anticipo:$(this).data('anticipo'),
                                        total_a_pagar:$("#total_a_pagar").val()
                                    };
                        $.ajax({
                            url:'<?php echo base_url()?>index.php/comprobantes/eliminarAnticipo',
                            method:'post',
                            data:datos,
                            dataType:'json',
                            success:function(response){
                                if(response.status == STATUS_OK)
                                {
                                    toast('success', 1500, 'Anticipo quitado');
                                    var totalAnticipo = parseFloat(response.totalAnticipo);
                                    var gravadas = parseFloat(response.gravadas);
                                    var igv = parseFloat(response.igv);
                                    var totalPagar = parseFloat(response.totalPagar);
                                    $("#total_anticipos").val(totalAnticipo.toFixed(2));
                                    $("#total_gravada").val(gravadas.toFixed(2));
                                    $("#total_igv").val(igv.toFixed(2));
                                    obtenerAnticipos();
                                    calcular();
                                }else{
                                    toast('error', 1500, 'No se pudo quitar anticipo');
                                }
                            }
                        });            
                    });
                }
            }
        });
    }

    //CARGAR MODAL BUSCAR PRODUCTO - 04-12-2020 -
    $(document).on("click",'#btn_buscar_producto',function(e){
        e.preventDefault();
        var tipo_comprobante =  $(this).data('tipo_comprobante');        
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProducto/"+tipo_comprobante,{});
    });    
    //CARGAR MODAL PAGO PAGO_MONTO 14-10-2020 
    $("#guardar").on('click',function(e){      
        e.preventDefault();
        $("#myModalPagoMonto").load("<?= base_url()?>index.php/comprobantes_compras/modal_pagoMonto",{});
    });


</script>