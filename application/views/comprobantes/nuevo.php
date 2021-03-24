<!-- COMPROBANTE CSS -->
<link rel="stylesheet" href="<?PHP echo base_url()?>assets/css/comprobante.css">
<div id="mensaje"></div>
     
<div class="container-fluid">
<input type="hidden" id="auto" value="<?php echo $configuracion->facturador_auto;?>">

<form id="formComprobante" class="form-horizontal" autocomplete="off">
    <input type="hidden" id="pse_token" name="pse_token" value="<?= $empresa['pse_token']?>">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>COMPROBANTE DE PAGO - <b><?= $empresa['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info" >
                <div class="panel-heading" >
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">    
                        <div class="col-md-12 form-inline col-lg-12">                            
                             <select class="form-control" name="operacion" id="operacion" style="display: none;">
                                <?php if($adjunto_estado!=0){ ?>
                                  <option value="0101" <?php echo ($adjunto_datos->tipo_operacion=="0101")?"SELECTED":"";?>>Venta Interna</option> 
                                  <option value="0200" <?php echo ($adjunto_datos->tipo_operacion=="0200")?"SELECTED":"";?>>Exportación</option>
                                <?php }else{ ?>
                                   <option value="0101" >Venta Interna</option> 
                                   <option value="0200" >Exportación</option>
                                <?php } ?>                                       
                             </select>

                             <label class="control-label">Tipo Documento:</label>        
                            <select  class="form-control" name="tipo_documento" id="tipo_documento">
                            <?PHP foreach ($tipo_documentos as $value) { ?>   
                               <?php if($value['id']!=11) {?>  
                                    <?php if($adjunto_estado!=0){ ?>
                                        <option value = "<?PHP echo $value['id'];?>" <?php echo ($value['id']==$adjunto_tipo_documento)?"selected":"";?>><?PHP echo $value['tipo_documento']?></option> 
                                    <?php }else{ ?>
                                         <option value = "<?PHP echo $value['id'];?>" <?php echo ($value['id']==3)?"selected":"";?>><?PHP echo $value['tipo_documento']?></option>
                                    <?php } ?>  

                               
                               <?php } ?> 
                            <?PHP }?>                              
                            </select>    
                        </div>                    
                        <div class="col-md-4 col-lg-4 input_cliente">
                             <label class="control-label" style="width: 100%;text-align: left;">Cliente:</label>                     

                             <?php if($adjunto_estado!=0){ 
                                  if($adjunto_datos->tipo_cliente_id==1){$tdoc="DNI";}else if($adjunto_datos->tipo_cliente_id==2){$tdoc="RUC";}else{$tdoc="SIN DOC";}
                             ?>
                                <input type="text" class="form-control " list="lista_clientes" id="cliente" onkeyup="buscar_cliente()" onchange="seleccionar_cliente()" value="<?php echo $tdoc.' '.$adjunto_datos->ruc.' '.$adjunto_datos->razon_social;?>">
                                <input type="hidden" name="cliente_id" id="cliente_id" required="" value="<?php echo $adjunto_datos->cliente_id;?>">
                             <?php }else{ ?>  
                                <input type="text" class="form-control"  id="cliente">
                                <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id" required="" value=""></div>
                             <?php } ?>                                   
                                <input type="hidden" name="ruc_sunat" id="ruc_sunat">
                                <input type="hidden" name="razon_sunat" id="razon_sunat">
                                <input type="hidden" name="pago_monto" id="pago_monto">
                               
                        </div>
                        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-1 input_busqueda"><br>
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" data-toggle='modal' data-target='#myModalNuevoCliente'>NUEVO</button>                        
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" onclick="consulta_sunat()">BUSCAR</button> 
                        </div>                          
                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>
                            <?php if($adjunto_estado!=0){ ?>
                                <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $adjunto_datos->direccion_cliente;?>"> 
                            <?php }else{ ?>
                                <input type="text" class="form-control" name="direccion" id="direccion" value="LIMA">
                            <?php } ?>      
                            
                        </div>                        
                        <div class="col-xs-6 col-md-2 col-lg-2">            
                            <label class="control-label">Serie:</label>                            
                            <div id="div_serie_actual">
                                <select readonly class="form-control disabled " name="serie" id="serie">
                                    <?PHP foreach ($ser_nums as $value) {?>                
                                    <option value = "<?= $value['serie']?>"><?= $value['serie']?></option>
                                    <?PHP }?>
                                </select>
                            </div>
                            <div id="div_serie_antiguo">
                                <input type="text" class="form-control" name="serie_antiguo" id="serie_antiguo" value="" />
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                            <input type="text" class="form-control" name="numero" id="numero" maxlength="9" required="" readonly>
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
                                <?php if($adjunto_estado!=0){ ?>
                                    <option value = "<?PHP echo $value->id;?>" <?php echo ($value->id==$adjunto_datos->moneda_id)?"SELECTED":"";?>><?PHP echo $value->moneda?></option>
                                <?php }else{ ?>
                                    <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->moneda?></option>
                                <?php } ?>                         
                               
                           <?PHP }?>    
                           </select>
                        </div>       
                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tip. Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="">
                        </div>
                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha de Venc:</label>
                            <input type="text" class="form-control" name="fecha_de_vencimiento" id="fecha_de_vencimiento" value="<?PHP
                                if(isset($_POST['fecha_de_vencimiento']))
                                    echo $_POST['fecha_de_vencimiento'];
                                else
                                    echo date('d-m-Y');
                                ?>" placeholder="Fechad de vencimiento">
                        </div>                         
                        <div class="col-xs-6 col-md-2">
                                    <label class=" control-label"># Guía</label>
                                    <div class="input-group">
                                        <input type="text" name="numero_guia" id="numero_guia" class="form-control" value="">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="btn_buscar_guia"><i class="glyphicon glyphicon-search"></i></button>
                                        </span>
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
                         <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Transportistas:</label>        
                            <select class="form-control" name="transportista" id="transportista">
                           <?PHP foreach ($transportistas as $value) { ?>                          
                               <option value = "<?PHP echo $value->transp_id;?>"><?PHP echo $value->transp_nombre.'-'.$value->transp_tipounidad?></option>
                           <?PHP }?>    
                           </select>
                        </div>   
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Placa:</label>
                            <input type= "text" class="form-control" id="placa" name="placa" value="<?= $value->placa;?>">
                        </div>
                         <!-- orden de compra -->
                        <?php if ($configuracion->orden_compra): ?>                            
                            <div class="col-xs-6 col-md-2">
                                <label class="control-label"> Orden de Compra </label>
                                <input type="text" name="orden_compra" class="form-control">
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
                            <div class="col-xs-6 col-md-4 col-lg-4">
                                <label>Otros</label>
                                <input type="text" name="condicion_venta" class="form-control">
                            </div>
                        <?php endif ?>
                                                                                
                    </div>  
                </div>        
            </div>

            <!-- MOSTRAR NOTA DE CREDITO, NOTA DE DEBITO --> 
              <div id="mostrarCompNota">
                  <div class="panel panel-info" id="panelDetraccion">
                  <div class="panel-heading">
                      <div class="panel-title">ADJUNTA A COMPROBANTE</div>
                  </div>
                  
                  <div class="panel-body" >
                      <div class="form-group">                                                                    
                          <div class="col-xs-4 col-md-4 col-lg-4" id="div_facturas_cliente">                                      

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
            
            <div class="row" style="padding-top:20px;">                
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="panel panel-info" >  
                        <div class="panel-heading">
                            <div class="panel-title">CONCEPTOS DEL COMPROBANTE</div>
                        </div>
                        <div class="panel-body">                        
                            <div class="row" id="valida">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                  <input type="text" class="form-control" name="codigoBarra" id="codigoBarra" style="width: 400px;" placeholder="CODIGO BARRA" ><br>
                                    <table id="tabla" class="table" style="display:none" border="0">
                                        <thead>
                                            <tr>                                                
                                                <th colspan="2" class="col-sm-2">Descripcion</th>
                                                <th class="col-sm-1" style="display: none;">Unid. Medida</th>
                                                <th class="col-sm-2">Cant.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <th class="col-sm-3">Tipo Igv&nbsp;&nbsp;</th>
                                                <th class="col-sm-1">Precio Unitario&nbsp;&nbsp;</th> 
                                                <th class="col-sm-1">&nbsp;</th>    
                                                <!--<th>Sub Total</th>  -->                                                
                                                <?php
                                                 if ($configuracion->descuento) {
                                                    echo "<th>Descuento</th>";
                                                 } else {
                                                    echo "<th style='display:none;'> Descuento</th>";
                                                 }?>                                                                          
                                                <th class="col-sm-2">Total</th>
                                                <th></th>  
                                            </tr>
                                        </thead>                    
                                        <tbody>                                                      
                                        </tbody>                    
                                        </table>   
                                    <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                                    <button type="button" id="agrega_sin" onclick="agregar_fila_sin_stock()" class="btn btn-primary btn-sm" style="background: #E67E22;border:0;">Agregar sin stock</button>
                                    <button type="button" id="btn_buscar_producto" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#myModalProducto" data-keyboard='false' data-backdrop='static'>Buscar Producto</button>
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
        <div class="col-md-8 col-lg-8">                                                                         
            <!-- MUESTRA DETRACCION , FACTURA O BOLETA -->                                    
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
                        <div class="panel-title">OBSERVACIONES</div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" disabled></textarea>
                    </div>
                </div>            
            <!--<?php endif ?>-->
            <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">OBSERVACIONES</div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" style="width: 100%;"></textarea>
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
                        <span class="input-group-addon" style="border:1px solid #ABB2B9;border-bottom: 0;border-right: 0;">ICBPER: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_icbper" name="total_icbper" class="form-control" readonly="" style="border:1px solid #ABB2B9;border-bottom:0;">
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
               
<!-- ICBPER -->
<input type="hidden" id="valorIcbper" value="<?php echo $rowIcbPerActivo->icbPer_valor;?>">
<!-- ---------->
        <div class="container-fluid">
            <div class="row" style="padding-bottom: 2rem;">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                    <button type="button" id="guardar"  class="btn btn-primary btn-lg btn-block" style="background: #1ABC9C;border:0;" data-toggle='modal' data-target='#myModalPagoMonto' data-keyboard='false' data-backdrop='static'>Generar Comprobante de Pago</button>
                </div>
            </div>
        </div>    
    </div>  
</form>
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
                      fila += '<td class="col-sm-4"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+ value.notapd_descripcion +'" readonly><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+ value.notapd_producto_id +'"></div> </td>';   


                 
                  fila += '<td style="border:0;"><input type="text" class="form-control" readonly name="medida[]" id="medida" value="'+ value.notapd_medida +'"></td>';

                      
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
               
               $("#cliente").val(json.cli.razon_social);
               $("#cliente_id").val(json.cli.id);
               $("#direccion").val(json.doc.notap_cliente_direccion);

            }else{
                 toast("error", 1500, "Documento ya fue usado o no se encuentra");
                 $("#cliente").val('');
                 $("#cliente_id").val('');
                 $("#direccion").val('');
                 $("#adelanto_items").val('');
            }              
          });
    });
   
    // GUARDAR COMPROBANTE 03-08-2020 ALEXANDER FERNANDEZ
    $("#guardarComprobante").click(function(e){

        $('#guardarComprobante').prop('disabled',true);
        $('.btn_cerrar').prop('disabled',true);        
        $.ajax({
            method:'post',
            url:'<?PHP echo base_url()?>index.php/comprobantes/guardar_comprobante',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_FAIL)
                {
                    toast("error",3000, response.msg);
                    $('#guardarComprobante').prop('disabled',false);
                    $('.btn_cerrar').prop('disabled',false);
                }
                if(response.status == STATUS_OK)
                {
                    
                    if($("#auto").val() == 1) { 
                         send_xml(response.cpe_id);
                    }else{     
                         toast("success", 1500, 'Comprobante registrado');                         
                         setTimeout(function() { 
                           location.href='<?PHP echo base_url()?>index.php/comprobantes/index/'+response.cpe_id;
                         }, 2000);
                    }       
                }
            }
        });        
    });   

    //CLOSE MODALPAGO
    $(".close,.btn_cerrar").on("click", function(){
        $(".precioSelected").removeClass("precioSelected");
        $('#guardarComprobante').prop('disabled',false);
    });     

    function send_xml(comprobante_id){
            toast("info",10000, 'Enviando a SUNAT . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosXML",{comprobante_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);
                $.post("<?php echo RUTA_API?>index.php/Sunat/send_xml",{datosJSON})
                 .done(function(res){
                    var response;
                    try{
                             response = JSON.parse(res);
                             if(response.res == 1){
                                
                                     $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoCDR",{comprobante:comprobante_id,firma:response.firma})
                                     .done(function(res){
                                          toast("success", 4000, response.msg); 
                                          setTimeout(function() { 
                                             location.href='<?PHP echo base_url()?>index.php/comprobantes/index/'+comprobante_id;
                                          }, 2000);
                                     })
                             }

                    }catch(e){
                            console.log(res);
                            toast("error",6000,res);
                            location.href='<?PHP echo base_url()?>index.php/comprobantes';
                          
                    }

                           
                })

            });
    }

    jQuery(document).ready(function($) {
 
        $("#fecha_de_emision").change(function(event) {
            /* Act on the event */
            var fecha = $(this).val();
            $("#fecha_de_vencimiento").val(fecha);
            //console.log(fecha);
        });

        $("#datos").hide();
        $("#limitado_detalle").hide();

        $("#tipo_cliente").change(function () {
            var op = $("#tipo_cliente option:selected").val();
            var array = op.split('xx-xx-xx');
            $("#datos").show();
            if (array[0] == 1) {
                $("#lbl_DNI_RUC").html('DNI <label style="color: red;">(*)</label>');
                $("#ruc").attr("placeholder","DNI");
                $("#ruc").attr("maxlength","8");

                $("#lbl_RAZ_APE").html('Nombres <label style="color: red;">(*)</label>');
                $("#razon_social").attr("placeholder","Nombres");
                $("#nombres").show();
                $(".dni_auto").show();
            }else{
                $("#lbl_DNI_RUC").html('RUC <label style="color: red;">(*)</label>');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").html('Razon Social <label style="color: red;">(*)</label>');
                $("#razon_social").attr("placeholder","razon social");
                $("#nombres").hide();
                $(".dni_auto").hide();
            }

        });        
        //// FALSE : NO IGV; TRUE : SI IGV
        //cmp.incluyeIgv=false;
        <?php 
            $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
            if ($configuracion->pu_igv==1) {
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
        $('#cliente').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_cliente',
            minLength : 2,
            select : function (event,ui){  
                                        
                var data_cli = '<input type="hidden" value="'+ ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
                $("#direccion").val(ui.item.domicilio1);
                $("#placa").val(ui.item.placa);

                if($('#tipo_documento option:selected').val() == "7"){
                    updateDocumentoNotaCredito();
                }
            }
        });
    });

    /*buscar item*/
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id">';
                _item.find('#data_item').html(data_item);

                _item.find('#descripcion').attr("readonly",true);
                _item.find('#medida').val(ui.item.medida);  
                
                _item.find('.importe').val(ui.item.precio);
                _item.find('.importeCosto').val(ui.item.precioCosto);
                
                var parent = $(this).parents().parents().get(0);
                
                _item.find('.totalp').val(ui.item.precio);
                cmp.calcular(parent);
                calcular();
            
                
            },
            change : function(event,ui){
              if ((ui.item.prod_medida_id != 59) && (ui.item.prod_stock == 0)){
                var tipoDocumento = $("#tipo_documento").val();
                    if(tipoDocumento==1 || tipoDocumento==3)
                    {
                      toast("error",1200,"Producto sin stock");
                       $(this).closest('.cont-item').remove();

                       if($("#tabla tbody tr").length === 0)
                          $("#tabla").css("display","none");      
                          calcular();                            
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

   
    function agregar_fila_sin_stock(){
       
        var fila = '<tr class="cont-item">';                               
                fila += '<td class="col-sm-3" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required=""></textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div></td>';

                fila += '<td class="col-sm-1" style="border:0;"><select class="form-control" id="medida" name="medida[]"><option value="">Seleccione</option>';
                <?php foreach ($medida as $valor):?>
                    fila += '<option value="<?php echo $valor->medida_id;?>"><?php echo $valor->medida_nombre;?></option>';  
                <?php endforeach ?>                            
                fila += '</select></td>';                                                
                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1"></td>';

                fila += '<td class="col-sm-2" style="border:0;">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>                  
                   fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';                 
                <?php endforeach?>
                fila += '</select>'
                fila += '</td>';
                
                fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe">'+
                        '<input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" ></td>';

                fila += '<td class="precios">'+
                        '<span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span>'+
                          '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly=""></td>';

                <?php if ($configuracion->descuento): ?>                    
                    fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php else: ?>
                    fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php endif ?>

                fila += '<td style="border:0;">'+
                        '<input type="hidden" id="subtotal" name="subtotal[]" class="form-control" readonly="">'+
                        '<input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="0.00" readonly=""></td>';                
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
        $('#serie').on('keydown',function(e){
            validAlfaNumerico(e);
        });

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
                        '<div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';

                fila += '<td style="border:0;display: none;"><input type="text" class="form-control" readonly id="medida" name="medida[]"></td>' 
                fila += '<td style="border:0;">'+
                        '<input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';

                fila += '<td class="col-sm-2" style="border:0;">'+
                        '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                          <?php foreach($tipo_igv as $value):?>
                                fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                          <?php endforeach?>
                fila += '</select></td>';                
                
                fila += '<td style="border:0;">'+
                        '<input type="number" id="importe" name="importe[]" required="" class="form-control importe" >'+
                        '<input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" ></td>';
                //fila += '<td></td>';
                fila += '<td class="precios">'+
                        '<span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span>'+
                          '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly=""></td>';                
                fila += '<input type="hidden" id="icbper"  name="icbper[]" class="form-control"  readonly="">';

                <?php if ($configuracion->descuento): ?>                    
                    fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php else: ?>
                    fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"></td>';
                <?php endif ?>

                fila += '<td style="border:0;">'+
                        '<input type="hidden" id="codBarra" name="codBarra[]" class="form-control">'+
                        '<input type="hidden" id="subtotal" name="subtotal[]" class="form-control" readonly="">'+
                        '<input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00">'+
                        '<input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="0.00" readonly=""></td>';
                fila += '<td class="eliminar" style="border:0;">'+
                        '<span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
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
                $.ajax({
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
                //$("#numero").attr("readonly", true);
                //seteo el valor de la serie antiguo (serie manual).
                $('#serie_antiguo').val('');
                
            }else{
                $('#div_serie_actual').hide();
                $('#div_serie_antiguo').show();
                //$("#numero").attr("readonly", false);
                $("#numero").val('');
            }
        }

        function serieChange(){
            var selec  = $("#serie option:selected").val();
          
            var tipo_documento = $('#tipo_documento option:selected').val();
            var url_ser = '<?= base_url()?>index.php/comprobantes/selectUltimoReg/<?= $empresa['id']?>/'+tipo_documento+'/'+selec;
            //alert(url_ser);
            //console.log(selec);
            $.ajax({
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
                }
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
            //$("#comp_adjunto").load('<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + 1 + '/' + cliente_id + '/' + serie_selec);

            <?php if($adjunto_estado!=0){ ?>
                console.log("estado 1");
                var url = '<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec + '/' + <?php echo $adjunto_id?>;
                select_documento_adjunto(<?php echo $adjunto_id?>);
            <?php }else{ ?> 
                console.log("estado 0");
                var url = '<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec;
            <?php } ?> 

            $("#comp_adjunto").load(url);            
        }

        /////SELECCION DE DOCUMENTO A MODIFICAR
        $(document).on("change","#comp_adjunto",function(){
            var comprobante_id = $(this).val();
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/obtener_documento_relacionado",{id:comprobante_id})
             .done(function(json){
                 $("#tabla tbody").html("");
                $.each(json,function(index,value){
                    var fila = '<tr class="cont-item" >';        

                    if(value.producto_id!=0){
                        fila += '<td class="col-sm-4" style="border:0;"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+ value.descripcion +'" readonly><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+ value.producto_id +'"></div> </td>';
                        fila += '<td class= "col-sm-1" style="border:0; "><input type="text" class="form-control" readonly id="medida" name="medida[]" value="'+ value.medida_nombre +'"></td>';
                    }else{
                        fila += '<td class="col-sm-4" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required="">'+ value.descripcion +'</textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div> </td>';    

                        fila += '<td style="border:0;"><select class="form-control" id="medida" name="medida[]"><option value="">Seleccione</option>';
                        <?php foreach ($medida as $valor):?>

                            if(value.unidad_id==<?php echo $valor->medida_id?>){
                                fila += '<option value="<?php echo $valor->medida_id;?>" selected><?php echo $valor->medida_nombre;?></option>';  
                            }else{
                                fila += '<option value="<?php echo $valor->medida_id;?>"><?php echo $valor->medida_nombre;?></option>';  
                            }                            
                        <?php endforeach ?>                            
                        fila += '</select></td>';
                    }

                                    
                        fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="'+ value.cantidad +'" ></td>';

                        fila += '<td class="col-sm-2" style="border:0;">';
                        fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                        <?php foreach($tipo_igv as $value):?>
                          
                           fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                         
                        <?php endforeach?>
                        fila += '</select>'
                        fila += '</td>';
                        
                        fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe" value="'+ value.importe +'"></td>';
                        fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="'+ value.igv +'">';
                        <?php if ($configuracion->descuento): ?>                    
                            fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                        <?php else: ?>
                            fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                        <?php endif ?>

                        fila += '<td style="border:0;"><input type="text" id="total" name="total[]" class="form-control totalp" value="'+ value.total +'" readonly=""></td>';
                        fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
                     fila += '</tr>';
               $("#tabla").css("display","block");
               $("#tabla tbody").append(fila);
               calcular(); 

                });

             });        
        })
        function select_documento_adjunto(comprobante_id){                    
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/obtener_documento_relacionado",{id:comprobante_id})
             .done(function(json){
                 $("#tabla tbody").html("");
                $.each(json,function(index,value){
                    var fila = '<tr class="cont-item" >';        

                    if(value.producto_id!=0){
                        fila += '<td class="col-sm-4" style="border:0;"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+ value.descripcion +'" readonly><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="'+ value.producto_id +'"></div> </td>';
                        fila += '<td style="border:0;"><input type="text" class="form-control" readonly id="medida" name="medida[]"></td>';
                    }else{
                        fila += '<td class="col-sm-4" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required="">'+ value.descripcion +'</textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div> </td>';    

                        fila += '<td style="border:0;"><select class="form-control" id="medida" name="medida[]"><option value="">Seleccione</option>';
                        <?php foreach ($medida as $valor):?>

                            if(value.unidad_id==<?php echo $valor->medida_id?>){
                                fila += '<option value="<?php echo $valor->medida_id;?>" selected><?php echo $valor->medida_nombre;?></option>';  
                            }else{
                                fila += '<option value="<?php echo $valor->medida_id;?>"><?php echo $valor->medida_nombre;?></option>';  
                            }
                            
                        <?php endforeach ?>                            
                        fila += '</select></td>';
                    }

                                    
                        fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="'+ value.cantidad +'" ></td>';

                        fila += '<td class="col-sm-2" style="border:0;">';
                        fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                        <?php foreach($tipo_igv as $value):?>
                          
                           fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                         
                        <?php endforeach?>
                        fila += '</select>'
                        fila += '</td>';
                        
                        fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe" value="'+ value.importe +'"></td>';
                        fila += '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="'+ value.igv +'">';
                        <?php if ($configuracion->descuento): ?>                    
                            fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                        <?php else: ?>
                            fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                        <?php endif ?>

                        fila += '<td style="border:0;"><input type="text" id="total" name="total[]" class="form-control totalp" value="'+ value.total +'" readonly=""></td>';
                        fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
                     fila += '</tr>';
               $("#tabla").css("display","block");
               $("#tabla tbody").append(fila);
               calcular();
                });
             });                    
         }
        //MODAL PARA SELECCIONAR PRECIO//03-08-2019
        $(document).on("click",".btn_agregar_precio",function(){                        
            var _item = $(this).parents('.cont-item');                    
            var productoId = _item.find('#item_id').val();                  
            
            _item.find('#importe').addClass( "precioSelected" );
                        
            var datos = {
                        productoId: productoId                        
                    };
            $("#myModalPrecio").load('<?php echo base_url()?>index.php/comprobantes/SeleccionaListaPrecio',datos);
        });
        // EVENTO COMBOBOX NOTA DE CREDITO , DEBITO
        $('#tipo_documento').on('change',function(){    
           documentoChange();                
       });

        $('#serie').on('click',function(){
           serieChange();
        });
        documentoChange();

        serieChange();
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
    //DNI AUTOMATICO
    $(document).on("click",'#dni_auto',function(){            
        if($('#dni_auto').prop('checked')){           
            $.ajax({
                url: '<?= base_url()?>index.php/clientes/dni_auto',
                dataType : 'JSON',
                method: 'POST',
                success: function(response){                  
                  if(response.status == STATUS_OK){                    
                      $("#ruc").val(response.dni_auto);
                  }
                }
            })
        }else{
          $("#ruc").val('');
        }        
    });
    //CARGAR MODAL BUSCAR PRODUCTO
    $(document).on("click",'#btn_buscar_producto',function(e){
        e.preventDefault();
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProducto",{});
    });
    //CARGAR MODAL NUEVO CLIENTE
    $(".btn_buscar").on('click',function(e){
        e.preventDefault();
        $("#myModalNuevoCliente").load("<?= base_url()?>index.php/clientes/modal_nuevoCliente",{});
    });
    //CARGAR MODAL PAGO PAGO_MONTO 14-10-2020 
    $("#guardar").on('click',function(e){      
        e.preventDefault();
        $("#myModalPagoMonto").load("<?= base_url()?>index.php/comprobantes/modal_pagoMonto",{});
    });


//LECTOR DE CODIGO DE BARRAS ALEXANDER FERNANDEZ 10-11-2020      
    var barcode = '';
    $("#codigoBarra").keydown(function(e) {                 

      //console.log($(this).find('#cantidad').val());
        console.log($(this).parents().parents().get(0));
        console.log(barcode + ' --'+barcode.length)
        var code = (e.keyCode ? e.keyCode : e.which);
        var cantidad_1 = 0;
        var repetidos = 0;


        if(code=='13')// Enter key hit
        {                    
            e.preventDefault();
            var tabla = $('#tabla > tbody > tr');

            if(typeof(tabla.length) !== 'undefined'){              
              $.each(tabla,function(indice,value){   
              
              var codBarra =  $(this).find('#codBarra').val();              
              var cantidad =  $(this).find('#cantidad').val();

              if(codBarra == barcode){
                  cantidad++;                  
                  $(this).find('#cantidad').val(cantidad);
                  repetidos++;
                  $('#codigoBarra').val('');
                  $('#codigoBarra').focus();                                                                                                      
              }else{
                //cantidad = (cantidad > 1) ? cantidad : 0;
                cantidad =  0;
              }
              if (repetidos > 0) cantidad = 1;
                                        
                //console.log(codBarra + ' '+cantidad);
                var parent = $(this); 
                //console.log(parent);    
                cmp.calcular(parent);  
                cantidad_1 = cantidad;

            });              
              if(cantidad_1 > 0) barcode = '';              
          }                

        if (cantidad_1 < 1){
            $('#agrega').trigger('click');

            _item  = $('#tabla tr:last');
            //console.log(_item);
            _item.find('#codigoBarra').focus();
            _item.find('#codigoBarra').val(barcode);

            bar = barcode;
            $.post('<?PHP echo base_url();?>index.php/productos/selectPrecioCodBarra',{
                codBarra : barcode             
                },function(data){               
                
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ data.prod_id + '" name = "item_id[]" id = "item_id" >';
                _item.find('#data_item').html(data_item);                
                _item.find('#descripcion').attr("readonly",true);
                _item.find('.descripcion-item').val(data.prod_nombre);
                _item.find('#medida').val(data.prod_medida);
                _item.find('#codBarra').val(bar);
                _item.find('.importe').val(data.prod_precio_publico);
                _item.find('.importeCosto').val(data.prod_precio_compra);

                //PRESENTACION PRODUCTO - ALEXANDER FERNANDEZ 27-10-2020
                $.ajax({
                    url: '<?= base_url();?>index.php/productos/selectPresentacionVenta/'+data.prod_id,
                    dataType: 'HTML',
                    method: 'GET',
                    success: function(data){
                        _item.find('#presentacion').append(data);                        
                        presentacion(parent);
                    }
                });

                $('#codigoBarra').val('');
                $('#codigoBarra').focus();                                                                                                      
                var parent = _item.find('.descripcion-item').parents().parents().get(0);                             
                cmp.calcular(parent);                                           
                },'json');  


               
             
             barcode = '';            
        }}
        else if(code==9)// Tab key hit
        {
            //alert(barcode);
        }else if(code==13 && (barcode.length == 0)){
            barcode = '';
        }
        else
        {
          //barcode = '';
          barcode=barcode+String.fromCharCode(code);
        }
    });




    //PSE TOKEN 10-01-2021
    function send_xmlPSE(comprobante_id){
            toast("info",10000, 'Enviando a SUNAT . . .');
            $.getJSON("<?PHP echo base_url(); ?>index.php/comprobantes/getDatosXML_PSE",{comprobante_id})
            .done(function(json){
                var datosJSON = JSON.stringify(json);                
                //JAVASCRIPT JQUERY 04-01-2021
                var settings = {
                  "url": "<?= base_url()?>index.php/comprobantes/send_xmlPSE",
                  "method": "POST",
                  "dataType": "json",                  
                  "data": {"json": datosJSON}
                };                
                $.ajax(settings).done(function(response){
                    try{     

                        if(response.codigo != '' && response.codigo == 21 && response.codigo == 50){
                            toast("error", 9000, "<div style='font-size:16px'>"+response.errors+"</div>");
                            //location.href='<?PHP echo base_url()?>index.php/comprobantes/index';
                        } else {
                          if(response.sunat_responsecode == "0" || response.enlace != ""){
                            $.post("<?PHP echo base_url(); ?>index.php/comprobantes/updateEstadoCDR_PSE",{comprobante:comprobante_id,firma:response.codigo_hash,enlace_del_xml:response.enlace_del_xml,enlace_del_cdr:response.enlace_del_cdr})
                                     .done(function(res){
                                      response.sunat_description = 'Enviado';
                                          toast("success", 4000, response.sunat_description); 
                                          setTimeout(function() { 
                                             location.href='<?PHP echo base_url()?>index.php/comprobantes/index/'+comprobante_id;
                                             //location.href='<?PHP echo base_url()?>index.php/comprobantes/index/';
                                          }, 2000);
                               })
                             }
                        }     
                    } catch(e){                            
                            toast("error",6000,res.errors);
                          
                    }
                });
            });
    }  
</script>