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
</style>
<div id="mensaje"></div>
<div class="container-fluid">
<form id="formComprobante" class="form-horizontal" role="form" action="<?PHP echo base_url()?>/index.php/comprobantes/modificar_comprobante/<?PHP echo $comprobante['comprobante_id'];?>" method="post" autocomplete="off">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <input type="hidden" name="anticipo" id="anticipo" value="<?php echo $comprobante['comprobante_anticipo'] ?>">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>EDITAR COMPRA</h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">            
                        <div class="col-xs-12 form-inline col-sm-2" >
                            <label> Tipo de Operación</label>                            
                             <select class="form-control" name="operacion" id="operacion">
                                <option value="0101" <?php echo ($comprobante['tipo_operacion']=='0101')?'selected':''?>>Compra Interna</option> 
                                <!--<option value="0200" <?php echo ($comprobante['tipo_operacion']=='0200')?'selected':''?>>Exportación</option>       -->
                             </select>
                        </div>
                        <div class="col-xs-12 form-inline col-sm-10" style="padding-bottom: 1rem;">
                            <label> Incluye igv</label>                            
                            <input type="checkbox" name="incluye_igv" id="incluye_igv" <?php echo ($igv->pu_igv_c==1)?"checked":"";?>>
                        </div>
                         

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <!--
                            <input type="text" class="form-control" name="cliente" id="cliente">
                            <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id"></div>-->

                             <label class="control-label">Cliente:</label>
                            <input type="hidden" name="cliente_id" id="cliente_id" required value="<?PHP echo $comprobante['cliente_id']?>">
                            <input type="text" class="form-control" list="lista_clientes" id="cliente" onkeyup="buscar_cliente()" onchange="seleccionar_cliente()" value="<?PHP echo $comprobante['cli_razon_social'];?>">
                                <datalist id="lista_clientes" >
                                  
                                </datalist>
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <!--<label class="control-label">Dirección:</label>-->
                            <input type="hidden" class="form-control" name="direccion" id="direccion" value="<?PHP echo $comprobante['direccion_cliente'];?>">
                        </div>



                        <div class="col-xs-2 col-md-2 col-lg-2">
                        <label class="control-label">Tipo Documento:</label>        
                        <select class="form-control" name="tipo_documento" id="tipo_documento">
                            <?php
                            foreach ($tipo_documentos as $value_documentos) {
                                $selected = ($value_documentos['id'] == $comprobante['tipo_documento_id']) ? "selected" : "";
                                ?>
                            <option <?php echo $selected;?> value='<?PHP echo $value_documentos['id'];?>'><?PHP echo $value_documentos['tipo_documento'];?></option>
                            <?php
                            }
                            ?>
                        </select>
                        </div>    
        
                        <div class="col-xs-1 col-md-1 col-lg-1">            
                            <label class="control-label">Serie:</label>
                            <!--<input type="text" class="form-control" name="serie" id="serie" value="" maxlength="4" required="">-->
                            <select class="form-control" name="serie" id="serie" required>
                                <option value='<?PHP echo $comprobante['serie'];?>'><?PHP echo $comprobante['serie'];?></option>                
                            </select>       
                        </div>

                        <div class="col-xs-1 col-md-1 col-lg-1">
                            <label class=" control-label">Numero:</label>
                            <input type="text" class="form-control" name="numero" id="numero" value="<?PHP echo $comprobante['numero'];?>" maxlength="9" required="">
                        </div>

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha emision:</label>
                            <input type="text" class="form-control" name="fecha_de_emision" id="fecha_de_emision" value="<?PHP echo $comprobante['fecha_de_emision'];?>" placeholder="Fecha Emision">
                        </div>    

                        <div class="col-xs-2 col-md-1 col-lg-1">
                            <label class="control-label">Tipo de Moneda:</label>        
                             <select class="form-control" name="moneda_id" id="moneda_id">
                            <?PHP foreach ($monedas as $value) { 
                                $selected = ($value['id'] == $comprobante['moneda_id']) ? "SELECTED" : '';?>
                                <option <?PHP echo $selected;?> value="<?PHP echo $value['id'];?>"><?PHP echo $value['moneda']?></option>
                            <?PHP }?>    
                            </select>
                        </div>       

                        <div class="col-xs-2 col-md-1 col-lg-1">
                            <label class="control-label">Tipo de Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" value="<?PHP echo $comprobante['tipo_de_cambio']?>">
                        </div>

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha de Venc:</label>
                            <input type="text" class="form-control" name="fecha_de_vencimiento" id="fecha_de_vencimiento" value="<?PHP echo $comprobante['fecha_de_vencimiento'];?>" placeholder="Fecha de Vencimiento">
                        </div>
                       <!-- <div class="col-xs-2">
                            <label class=" control-label">Vendedor</label>
                            <select class="form-control" name="vendedor" id="vendedor">
                               
                                <?php foreach($vendedores as $v){?>
                                  <option value="<?php echo $v->id?>" <?php echo ($comprobante['empleado_select']==$v->id)?"selected":"";?> ><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                <?php }?>    
                            </select>    
                        </div>-->

                        <div class="col-sm-2">
                                    <label class=" control-label"># Guía</label>
                                    <div class="input-group">
                                        <input type="text" name="numero_guia" id="numero_guia" class="form-control" value="<?php echo $comprobante['numero_guia_remision']?>">
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
                                <label class="control-label">N° Pedido </label>
                                <input type="text" name="numero_pedido" class="form-control" value="<?php echo $comprobante['numero_pedido']?>">
                            </div> 
                        <?php endif ?>
                        <!-- orden compra -->
                        <?php if ($configuracion->orden_compra): ?>                            
                            <div class="col-xs-2">
                                <label class="control-label">N° Orden de Compra </label>
                                <input type="text" name="orden_compra" class="form-control" value="<?php echo $comprobante['orden_compra']?>">
                            </div>
                        <?php endif ?>
                        <!-- numero de guia remision -->
                        <?php if ($configuracion->numero_guia): ?>                            
                            <div class="col-xs-2">
                                <label class="control-label">Nº Guìa remisiòn</label>
                                <input type="text" name="guia_remision" class="form-control" value="<?php echo $comprobante['numero_guia_remision']?>">
                            </div>
                        <?php endif ?>
                        <!-- anticipos -->
                        <?php if ($configuracion->anticipo): ?>                            
                            <div class="col-xs-1">
                                <label>&nbsp;</label>
                                <br>
                                <?php if($comprobante['comprobante_anticipo'] == 0):?>
                                    <button type="button" class="btn" id="btn_es_anticipo">Anticipo</button>
                                <?php else:?>
                                    <button type="button" class="btn btn-success" id="btn_es_anticipo">Anticipo</button>
                                <?php endif?>
                            </div> 
                        <?php endif ?>
                        <!-- condicion de venta -->
                        <?php if ($configuracion->condicion_venta): ?>                            
                            <div class="col-xs-2">
                                <label>Condición de Venta</label>
                                <input type="text" name="condicion_venta" class="form-control" value="<?php echo $comprobante['condicion_venta']?>">
                            </div> 
                        <?php endif ?>

                        <div class="col-xs-2 form-inline col-sm-2" style="padding-top: 2rem;display: none;">
                            <label> Incluye igv</label>                            
                            <input type="checkbox" name="incluye_igv" id="incluye_igv" <?php if($comprobante['incluye_igv']):?> checked <?php endif?>  >
                        </div>
                        
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
                        
                    <div class="form-group" id="valida">
                        <div class="col-lg-12">                                                    
                            <table id="tabla" class="table">
                                <thead>
                                    <tr>
                                        
                                        <th>Descripcion</th>
                                        
                                        <th>Cant.</th>
                                        <th>Tipo Igv</th>
                                        <th>Precio Unitario</th>     
                                        <!--<th>Sub Total</th>  --> 
                                                                     
                                        <?php
                                         if ($configuracion->descuento) {
                                            echo "<th>Descuento</th>";
                                         } else {
                                            echo "<th style='display:none;'> Descuento</th>";
                                         }                                                    
                                         ?>                               
                                        <th>Total</th>
                                        <th></th> 
                                    </tr>
                                </thead>

                                <tbody>                                                      
                                <?PHP foreach ($items as $val) { ?>
                                <tr class="cont-item">
                                    <input type="hidden" id="item_id" name="item_id[]" value="<?PHP echo $val['item_id'];?>"/>
                                    
                                    

                                     <?PHP if($val['producto_id']!=0){ ?>
                                        <td class="col-sm-3" style="border:0;">
                                        <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" value="<?PHP echo $val['descripcion'];?>" readonly><div id="data_prod"><input type="hidden" name="prod_id[]" id="prod_id" value="<?php echo $val['producto_id']?>"></div></td>
                                     <?PHP }else{ ?>
                                        <td class="col-sm-3" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required="" readonly><?PHP echo $val['descripcion'];?></textarea><div id="data_prod"><input type="hidden" name="prod_id[]" id="prod_id" value="<?php echo $val['producto_id']?>"></div> </td>
                                     <?PHP } ?>
                                      
                                    <td style="border:0;"><input type="number" class="form-control cantidad" id="cantidad" name="cantidad[]"  value="<?PHP echo $val['cantidad'];?>" /></td>
                                    <td class="col-sm-2" style="border:0;"><select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                            <?PHP foreach ($tipo_igv as $value) {
                                                    $selected = ($value['id'] == $val['tipo_igv_id']) ? "SELECTED" : '';?>
                                                    <option <?PHP echo $selected;?> value="<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv'];?></option>
                                            <?PHP }?>
                                        </select></td>                                                                        
                                    <td style="border:0;"><input type="number" class="form-control importe" id="importe" name="importe[]" value="<?PHP echo $val['importe']?>" /></td>
                                    <input type="hidden" class="form-control" id="igv" name="igv[]" value="<?PHP echo $val['igv']?>" readonly=""/>
                                   <!-- <td><input type="hidden" id="subtotal" name="subtotal[]" value="<?PHP echo $val['subtotal']?>" readonly=""/></td>-->
                                    
                                    <?php 
                                    if ($configuracion->descuento){
                                        echo '<td><input type="text" id="desc_uni" class="form-control" name="descuento[]" value="'.$val['descuento'].'" /></td>';
                                    } else {
                                        echo '<td style="display: none;"><input type="text" id="desc_uni" class="form-control" name="descuento[]" value="'.$val['descuento'].'" /></td>';
                                    }
                                    ?>     


                                    <?php $valortotal = ($comprobante->incluye_igv==1) ?  $val['subtotal']+$val['igv'] :$val['total'] ; ?>
                                    <td style="border:0;"><input type="text" class="form-control totalp" id="total" name="total[]" 
                                        value="<?PHP echo number_format($valortotal,2); ?>" readonly=""/></td>
                                    <!--<td class="eliminar"><a class="delete" title="eliminar" onclick ="eliminar('<?PHP echo base_url()?>','items','eliminar','<?PHP echo $val['item_id'];?>','<?PHP echo $comprobante['comprobante_id'];?>')"><span class="glyphicon glyphicon-remove-circle"></span></a></td>-->
                                    <td style="border:0;"><a class="delete" title="eliminar"><span id="<?PHP echo $val['item_id'];?>" class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></a></td>
                                </tr>                                                 
                                <?PHP }?>
                                </tbody>
                            </table>
                            <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>                            
                        </div>
                    </div> 
                        
                    <div id="mostrar"></div>
                    <div id="uu"></div>
                    </div></div>
                    </div></div>
        </div>
    </div>
    <div class="row" style="padding-top:20px;">               
        <div class="col-xs-8 col-md-8 col-lg-8">

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
                               <input type="text" class="form-control input-sm" name="adjunto_serie" placeholder="Serie" value="<?PHP echo $value['adjunto_serie']?>">
                <input type="text" class="form-control input-sm" name="adjunto_numero" placeholder="Número" value="<?PHP echo $value['adjunto_numero']?>">
                <input type="date" class="form-control input-sm" name="adjunto_fecha" placeholder="Fecha de Emisión" value="<?PHP echo $value['adjunto_fecha']?>">
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

            <div class="panel panel-info">                    
                <div class="panel-heading">
                    <div class="panel-title">METODO DE PAGO</div>
                </div>
                <div class="panel-body">
                    <div class="row" style="width: 100%; margin: 0 auto;">
                        <div class="col-sm-4 form-group"> 
                            <label class="control-label">Tipo de Pago:</label>                    
                            <select class="form-control" name="tipo_pago" id="tipo_pago">
                            <?PHP foreach ($tipo_pagos as $value) { $selected= ($value['id'] == $comprobante['pago_id']) ? 'selected' :''; ?>                          
                                <option value = "<?PHP echo $value['id'];?>" <?= $selected;?> > <?PHP echo $value['tipo_pago']?> </option>
                            <?PHP }?>  
                            </select>    
                                
                        </div>
                        <div class="col-md-3" id="conte-ntarjeta" style="display: none;">
                            <label>Numero tarjeta </label>
                            <input type="text" name="ntarjeta" placeholder="******1234" value="000000" value = "<?PHP echo $comprobante['numero_tarjeta'];?>"  class="form-control">
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
            
            <!-- agregra notas -->
            <?php if ($configuracion->notas): ?>                
                <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">Notas <input type="checkbox" name="chkNotas" id="chkNotas" <?php if($comprobante['notas'] != ''):?> checked <?php endif?> ></div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" <?php if($comprobante['notas'] == ''):?> disabled <?php endif?>><?php echo $comprobante['notas']?></textarea>
                    </div>
                </div>
            <?php endif ?>

                       
                <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">Notas <input type="checkbox" name="chkNotas" id="chkNotas" <?php if($comprobante['notas'] != ''):?> checked <?php endif?> ></div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" <?php if($comprobante['notas'] == ''):?> disabled <?php endif?> style="width: 100%;"><?php echo $comprobante['notas']?></textarea>
                    </div>
                </div>
          

        </div>        
          <div class="col-xs-4 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel panel-body">

                    <div class="input-group">        
                        <span class="input-group-addon"> Total Descuento: <span class="selec_moneda"></span></span>                
                        <input type="text" id="descuento_global" name="descuento_global" class="form-control" value="<?php echo $comprobante['descuento_global']?>">
                    </div>
                    <div class="input-group" style="display: none;">        
                        <span class="input-group-addon">Anticipos: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_anticipos" name="total_anticipos" class="form-control" readonly="" value="<?php echo $totalAnticipo?>">
                    </div>
                    

                    <div class="input-group">        
                        <span class="input-group-addon">Total Ope. Inafecta: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_inafecta" name="total_inafecta" class="form-control" value="<?PHP echo $comprobante['total_inafecta']?>" readonly="">
                    </div>
                    <div class="input-group" >        
                        <span class="input-group-addon">Total Op. Exonerada: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_exonerada" name="total_exonerada" class="form-control" value="<?PHP echo $comprobante['total_exonerada']?>" readonly="">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon">Total Ope. Gravada: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control" value="<?PHP echo $comprobante['total_gravada']?>" readonly="">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon"> Total IGV (18%): <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control" value="<?PHP echo $comprobante['total_igv']?>" readonly="">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon">Total Ope. Gratuita: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_gratuita" name="total_gratuita" class="form-control" value="<?PHP echo $comprobante['total_gratuita']?>" readonly="">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon">Otros Cargos: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_otros_cargos" name="total_otros_cargos" class="form-control">
                    </div>


                    <div class="input-group" style="display: none;">        
                        <span class="input-group-addon">Descuento Total: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_descuentos" name="total_descuentos" class="form-control" readonly="" value="<?php echo $comprobante['total_descuentos']?>">
                    </div>    
                    <div class="input-group">                
                        <span class="input-group-addon">Importe Total: <span class="selec_moneda"></span></span>                
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control" value="<?PHP echo $comprobante['total_a_pagar']?>" readonly="">
                    </div>    
    
                </div>
            </div>
        </div>                             
                
        <div class="input-group" style="padding-bottom: 2rem;">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Actualizar Comprobante de Pago" style="background: #1ABC9C;border:0;"/>
            </div>
        </div>                    
    </div> 
</form>
</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>

<script type="text/javascript"> 

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

            if($('#tipo_documento option:selected').val() == "7" || $('#tipo_documento option:selected').val() == "9"){
                    updateDocumentoNotaCredito();
             }
             
         })
        
    }

    var array_adelanto_items = [];

    $("#btn_buscar_guia").click(function(){
         var guia = $("#numero_guia").val();
         $.getJSON("<?PHP echo base_url()?>index.php/comprobantes/buscar_guia",{guia})
          .done(function(json){
               
               $("#tabla > tbody tr").remove();
               $.each(json.det,function(index,value){
                  array_adelanto_items.push(value.notapd_producto_id);
                  var fila = '<tr class="cont-item">';     
                      fila += '<td class="col-sm-3"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="" value="'+ value.notapd_descripcion +'"><div id="data_item"><input type="hidden" name="prod_id[]" id="prod_id" value="'+ value.notapd_producto_id +'"></div> </td>';   
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
                        fila += '<td><input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" ></td>';
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

              

          })
    })

    $("#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            method:'post',
            url:"<?PHP echo base_url()?>/index.php/comprobantes_compras/modificar_comprobante/<?PHP echo $comprobante['comprobante_id'];?>",
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_FAIL)
                {
                    toast("error",1500, response.msg);
                }
                if(response.status == STATUS_OK)
                {
                    toast("success", 1500, 'Comprobante registrado');
                    location.href='<?PHP echo base_url()?>index.php/comprobantes_compras';
                }
            }
        });        
    });

    jQuery(document).ready(function($) {

         //// FALSE : NO IGV; TRUE : SI IGV
        //cmp.incluyeIgv=false;

         $("#incluye_igv").click(function(){
            if( $(this).is(':checked') ) {
                var valor = 1;
            }else{
                var valor = 0;
            }

            $.get("<?PHP echo base_url()?>index.php/comprobantes/estado_igv_c",{valor})
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
                        refescar(); ";
            } else {
                echo "  cmp.incluyeIgv = false;
                         console.log(cmp.incluyeIgv);
                        refescar();";
            }
        ?>

        <?php 
            if ($comprobante['numero_tarjet']>0) {
                echo "$('#conte-ntarjeta').hide();";
            }

         ?>
        

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

    <?php if($comprobante['comprobante_anticipo'] == 0):?>
        var _es_anticipo = false;
    <?php else:?>
        var _es_anticipo = true;
    <?php endif?>  
    var textoReferenciaNotas = "Operación sujeta al SPOT\nBCO. Nación CTA. CTE. MN 00-000-360155";

    $('body').delegate('#btn_es_anticipo', 'click', function(e) {
       e.preventDefault();
        var anticipo = 0;
        if(!_es_anticipo)
        {
            _es_anticipo = true;
            $(this).addClass("btn-success");
            $("#anticipo").val('1');
            $("#panel_anticipos").hide();
        } else {
            _es_anticipo = false;
            $(this).removeClass("btn-success");
            $("#anticipo").val('0');
            $("#panel_anticipos").show();
        }
        calcular();
    });


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
        simboloMoneda();         
    });      
    simboloMoneda();   
    //FUNCIONES FECHAS
    $(function(){
        $("#fecha_de_emision").datepicker();
        $("#fecha_de_vencimiento").datepicker();                
        /*$('#cliente').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_cliente',
            minLength : 2,
            select : function (event,ui){
                var data_cli = '<input type="hidden" value="'+ ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);                 
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
        
        //ELIMINADO ITEMS
        $('a.delete').click(function(){
            //e.preventDefault();
            var parent = $(this).parent().parent();
            var valor  = $(this).children('span').attr('id');
            console.log(parent);
            if(confirm("Esta seguro que desea eliminar el item?")){
            $.ajax({
                type:'GET',
                url :'<?PHP echo base_url()?>index.php/items/eliminar',
                data:'delete='+valor,
                success: function(){
                    parent.remove();
                    calcular();
                }                
            });                 
          }   
            return false;
        });                       
    });

    /*buscar producto*/
        $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "prod_id[]" id = "prod_id" >';
                _item.find('#data_prod').html(data_item);
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
                        $('#item_id').val(''); 
                        $('#importe').val('');                        
                    }

                }
            }                
        });
    });

  

    //modal para agregar anticipo
    $("#btn_agregar_anticipo").click(function(e){
        var datos = {
                        cliente:$("#cliente_id").val()
                    };
        $("#myModal").load('<?php echo base_url()?>index.php/comprobantes/agregarAnticipoUi',datos);
    });  

      function agregar_fila_sin_stock(){
       
        var fila = '<tr class="cont-item">';
                               
                fila += '<td class="col-sm-3" style="border:0;"> <textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required=""></textarea><div id="data_prod"><input type="hidden" name="prod_id[]" id="prod_id" value="0"></div> </td>';
                
                                
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
                    fila += '<td style="border:0;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
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

          

    //AGREGANDO FILAa
    $(function(){               
        var fila = '<tr class="cont-item">';                          
                fila += '<td class="col-sm-3" style="border:0;"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required=""><div id="data_prod"><input type="hidden" name="prod_id[]" id="prod_id"></div> </td>';
                
                                
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
                    fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                <?php else: ?>
                    fila += '<td style="display:none;"><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';                    
                <?php endif ?>               

                fila += '<td style="border:0;"><input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly=""></td>';
                fila += '<td class="eliminar" style="border:0;"><span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
            fila += '</tr>';                   
                            
        $("#agrega").on('click', function(){
            agregarFila();                                                                                                                                                   
        });                
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
           // validNumericos(e);
        });
        //Serir entrada Alfanumerico
        $('#serie').on('keydown',function(e){
           // validAlfaNumerico(e);
        });  
                    
    
     //FUNCION AGREGAR FILA     
     function agregarFila(){    
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
     
    //IGV ENVENTO CHOSEN 
    $('.tipo_igv').chosen({                
        search_contains : true,
        no_results_text : 'No se encontraton estos tags',                
    });     
     
    //COMPROBANTE ADJUNTO
    $("#comp_adjunto").chosen({
        search_contains : true,
        no_results_text : 'No se encontraton estos tags'
    });
    $('#mostrarCompNota').css('display','none');

     //OBTENIENDO SERIE,NUMERO
    function documentoChange(){
        var selec = $('#tipo_documento option:selected').val();
            //solo para boletas, facturas, notas de credito y debito,
            //obviamos la opcion: facturas antiguas y boletas antiguas
        if((selec == 1) || (selec == 3) || (selec == 7) || (selec == 8) || (selec == 9) || (selec == 10)){
          /*  $.ajax({
                url : '<?= base_url()?>index.php/serNums/selectSerie/<?= $empresa['id']?>',
                type: 'POST',
                data: {tipo_documento_id : selec},
                dataType : 'HTML',
                success :  function(data){
                    $('#serie').html(data);
                    serieChange();
                }
            });

            var serie_selec = $('#serie option:selected').val();*/
          

            /*$('#div_serie_actual').show();
            $('#div_serie_antiguo').hide();
           
            //seteo el valor de la serie antiguo (serie manual).
            $('#serie_antiguo').val('');*/

        }else{
           /* $('#div_serie_actual').hide();
            $('#div_serie_antiguo').show();
         
            $("#numero").val('');*/
        }
    }
    
    function serieChange(){
       /* var selec  = $("#serie option:selected").val();
        var tipo_documento = $('#tipo_documento option:selected').val();        
        var url_ser = '<?= base_url()?>index.php/comprobantes/selectUltimoRegModificar/<?= $empresa['id']?>/'+tipo_documento+'/'+selec+'/'+<?php echo $comprobante['tipo_documento_id'];?>+'/'+ '<?php echo $comprobante['serie'];?>'+'/'+ '<?php echo $comprobante['numero'];?>';
        console.log(url_ser);
        $.ajax({
            url : url_ser,
            type: 'POST',
            data: {serieId : selec},
            dataType : 'JSON',
            success :  function(data){
                $('#numero').val(parseInt(data.numero));
            }
        });*/

        /*if(tipo_documento == 7){
            cargaDocumentosNotasCredito();
        }*/

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
        var facturas_cliente =
               '<label class="control-label">Documento a Modificar</label>' +
               '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
               '</select>';
        $('#div_facturas_cliente').html(facturas_cliente);
        var url = '<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec;
        //console.log(url);
        $("#comp_adjunto").load(url);

        //$('#tipo_ncredito').prop('disabled',false);
        //$('#tipo_ndebito').prop('disabled',true);
    }
    
    function cargaValores(){
        var total_pagar = <?php echo $comprobante['total_detraccion_calculada']?>;                        
        $("#total_detraccion").val(total_pagar);                
        
        var tipo_cambio = $('#tipo_de_cambio').val();
        var moneda_id = $('#moneda_id').val();         
        var fecha_emision = <?PHP echo "'".$comprobante["fecha_de_emision_No_format"]."'";?>;
                
        if((tipo_cambio == "") && (moneda_id != 1)){
            var url_tipo_cambio = '<?= base_url()?>index.php/comprobantes/tipoCambioFechaJson/' + moneda_id + '/' + fecha_emision;
            $.getJSON( url_tipo_cambio, function( data ) {
                $("#tipo_de_cambio").val(data);
            });
        } 
    }
    
    // EVENTO COMBOBOX NOTA DE CREDITO , DEBITO
    $('#tipo_documento').on('change',function(){    
        documentoChange();                
    });
    
    $('#serie').on('change',function(){
        serieChange();
    });
    documentoChange();    
    cargaValores();
    obtenerAnticipos();
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
                    if(anticipos.length>0){                    
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
            }
        });
    }
    function simboloMoneda()
    {
        var moneda = $("#moneda_id").val();//1:sol,2dolares,3euro
        var simbolo = '';
        if(moneda=='1')
            simbolo = 'S/.';
        if(moneda == '2')
            simbolo = '$';
        if(moneda == '3')
            simbolo = '€'; 

        $(".selec_moneda").html(simbolo);       
    }
</script>