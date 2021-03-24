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

   .btn_buscar{
        margin-top: 10px;
    }


    @media (min-width:0px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
        }
       .input_cliente{
           width: 330px;
       }
       .input_busqueda{
           width: 240px;            
       }

       .modal-dialog{
        position: relative;
        display: table; /* <-- This makes the trick */
        overflow-y: auto;    
        overflow-x: auto;        
        width: auto;   
        margin-left: 55px;     
    }
       }    
    @media (min-width: 768px) {
        .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
        }
        .input_cliente{
           width: 250px;
       }
       .input_busqueda{
           width: 240px;            
       }

       .modal-dialog{
        position: relative;
        display: table; /* <-- This makes the trick */
        overflow-y: auto;    
        overflow-x: auto;        
        width: 500px;    
        margin-left: 700px;     
    }
    }
    @media (min-width: 992px) {
        .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
        }
       .input_cliente{
           width: 230px;
       }
       .input_busqueda{
           width: 240px;            
       }
       .modal-dialog{
        position: relative;
        display: table; /* <-- This makes the trick */
        overflow-y: auto;    
        overflow-x: auto;        
        width: 500px;   
        margin-left: 700px;     
    }
    }
    @media (min-width: 1200px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
        }
       .input_cliente{
           width: 300px;
       }
       .input_busqueda{
           width: 240px;            
       }
    }

    @media (min-width: 1300px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
    }
       .input_cliente{
           width: 330px;
       }
       .input_busqueda{
           width: 240px;            
       }
    }

    @media (min-width: 1500px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
    }
       .input_cliente{
           width: 300px;
       }
       .input_busqueda{
           width: 240px;            
       }
    }
    
    @media (min-width: 1600px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
    }
       .input_cliente{
           width: 480px;
       }
       .input_busqueda{
           width: 280px;            
       }
    }

   @media (min-width: 1900px) {
       .btn_buscar {
           margin-top: 8px;
           width: 100px;
           font-size: 13px;
    }
       .input_cliente{
           width: 680px;
       }
       .input_busqueda{
           width: 250px;            
       }
    }        

</style>

<div id="mensaje"></div>
<div class="container-fluid">
<form id="formComprobante" class="form-horizontal" role="form" action="<?PHP echo base_url()?>/index.php/comprobantes/modificar_comprobante/<?PHP echo $comprobante['comprobante_id'];?>" method="post" autocomplete="off">
    <input type="hidden" id="auto" value="<?php echo $configuracion->facturador_auto;?>">
    <input type="hidden" name="anticipo" id="anticipo" value="<?php echo $comprobante['comprobante_anticipo'] ?>">
    <input type="hidden" name="orden_id" id="orden_id" value="<?PHP echo $ordenes;?>">
    <input type="hidden" name="notap_id" id="notap_id" value="<?PHP echo $comprobante['notap_id']?>">
    <input type="hidden" name="prof_id"  id="prof_id" value="<?PHP echo $comprobante['prof_id']?>">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>GENERAR COMPROBANTE DE PAGO</h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                         
                    <div class="form-group" style="padding-top:20px;">            
                        <div class="col-md-12 form-inline col-lg-12" >                                                             
                             <select style="display: none" class="form-control" name="operacion" id="operacion">
                                <option value="0101" <?php echo ($comprobante['tipo_operacion']=='0101')?'selected':''?>>Venta Interna</option> 
                                <option value="0200" <?php echo ($comprobante['tipo_operacion']=='0200')?'selected':''?>>Exportación</option>       
                             </select>  

                            <label class="control-label">Tipo Documento:</label>        
                            <select  class="form-control" name="tipo_documento" id="tipo_documento">
                            <?PHP foreach ($tipo_documentos as $value) { ?>   
                               <?php if($value['id']!=11) {?>  
                                    <?php if($adjunto_estado!=0){ ?>
                                        <option value = "<?PHP echo $value['id'];?>" <?php echo ($value['id']==$adjunto_tipo_documento)?"selected":"";?>><?PHP echo $value['tipo_documento']?></option> 
                                    <?php }else{ ?>
                                         <option value = "<?PHP echo $value['id'];?>" <?php echo ($value['id'] == $tipo_documento_id)?"selected":"";?>><?PHP echo $value['tipo_documento']?></option>
                                    <?php } ?>                                 
                               <?php } ?> 
                            <?PHP }?>                             
                            </select>                              
                        </div>                                            
                        <div class="col-md-4 col-lg-4 input_cliente">
                            <!--
                            <input type="text" class="form-control" name="cliente" id="cliente">
                            <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id"></div>-->
                            <label class="control-label" style="width: 100%;text-align: left;">Cliente:</label>
                             <?php if($adjunto_estado!=0){ 
                                  if($adjunto_datos->tipo_cliente_id==1){$tdoc="DNI";}else if($adjunto_datos->tipo_cliente_id==2){$tdoc="RUC";}else{$tdoc="SIN DOC";}
                             ?>
                                <input type="text" class="form-control" list="lista_clientes" id="cliente" onkeyup="buscar_cliente()" onchange="seleccionar_cliente()" value="<?php echo $tdoc.' '.$adjunto_datos->ruc.' '.$adjunto_datos->razon_social;?>">
                                <input type="hidden" name="cliente_id" id="cliente_id" required="" value="<?php echo $adjunto_datos->cliente_id;?>">
                             <?php }else{ ?>  
                                <input type="text" class="form-control" list="lista_clientes" id="cliente" onkeyup="buscar_cliente()" onchange="seleccionar_cliente()" value="<?= $comprobante['cliente_razon_social'];?>">
                                <input type="hidden" name="cliente_id" id="cliente_id" required="" value="<?= $comprobante['cliente_id'];?>">
                             <?php } ?>   
                                
                                <input type="hidden" name="ruc_sunat" id="ruc_sunat">
                                <input type="hidden" name="razon_sunat" id="razon_sunat">
                                <input type="hidden" name="pago_monto" id="pago_monto">
                               
                        </div>
                       <div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 input_busqueda"><br>
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" data-toggle='modal' data-target='#myModalNuevoCliente'>NUEVO</button>
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" onclick="consulta_sunat()">BUSCAR</button> 
                        </div> 

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>                     
                                <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $comprobante['cliente_domicilio'];?>">
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
                                    <option value = "<?PHP echo $value->id;?>" <?php echo ($value->id==$adjunto_datos->moneda_id)?"SELECTED":"";?>><?PHP echo $value->moneda;?></option>
                                <?php }else{ ?>
                                    <option value = "<?PHP echo $value->id;?>"><?PHP echo $value->moneda;?></option>
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
                       <!-- <div class="col-xs-2">
                            <label class=" control-label">Vendedor</label>
                            <select class="form-control" name="vendedor" id="vendedor">
                               
                                <?php foreach($vendedores as $v){?>
                                  <option value="<?php echo $v->id?>" <?php echo ($comprobante['empleado_select']==$v->id)?"selected":"";?> ><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                <?php }?>    
                            </select>    
                        </div>-->

                        <div class="col-xs-6 col-md-2">
                                    <label class=" control-label"># Guía</label>
                                    <div class="input-group">
                                        <input type="text" name="numero_guia" id="numero_guia" class="form-control" value="<?php echo $comprobante['numero_guia_remision']?>">
                                        <span class="input-group-btn">
                                           <!-- <button class="btn btn-default" type="button" id="btn_buscar_guia"><i class="glyphicon glyphicon-search"></i></button>-->
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
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Transportistas:</label>        
                            <select class="form-control" name="transportista" id="transportista">
                           <?PHP foreach ($transportistas as $value) { 
                                  $selected  = ($value->transp_id == $comprobante['transportista_id']) ? 'SELECTED' : '';?>                          
                               <option value = "<?PHP echo $value->transp_id;?>" <?= $selected;?>><?PHP echo $value->transp_nombre.'-'.$value->transp_tipounidad?></option>
                           <?PHP }?>    
                           </select>
                        </div> 
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Placa:</label>
                            <input type= "text" class="form-control" id="placa" name="placa" value="<?= $comprobante['placa'];?>">
                        </div>
                        <!-- orden compra -->
                        <?php if ($configuracion->orden_compra): ?>                            
                            <div class="col-xs-6 col-md-2">
                                <label class="control-label">Orden de Compra </label>
                                <input type="text" name="orden_compra" class="form-control" value="<?php echo $comprobante['orden_compra']?>">
                            </div>
                        <?php endif ?>
                        <!-- numero de guia remision -->
                        <?php if ($configuracion->numero_guia): ?>                            
                            <div class="col-xs-6 col-md-2">
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
                            <div class="col-xs-6 col-md-4 col-lg-4">
                                <label>Otros</label>
                                <input type="text" name="condicion_venta" class="form-control">
                            </div>
                        <?php endif ?>

                      
                        
                    </div>  
                </div>        
            </div>


            <!-- MOSTRAR NOTA DE CREDITO, NOTA DE DEBITO --> 
              <div id="mostrarCompNota" style="display:none;">
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
                                    <?php $selected = ($comprobante['tipo_nota_codigo']==$value['codigo'])?"selected":""?>
                                        <option <?php echo $selected;?> value="<?PHP echo $value['id'].'*'.$value['codigo']?>"><?PHP echo $value['tipo_ncredito']?></option>
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
                                         }                                                    
                                         ?>                               
                                        <th class="col-sm-2">Total</th>
                                        <th></th> 
                                    </tr>
                                </thead>

                                <tbody>                                                      
                                <?PHP foreach ($items as $val) { ?>
                                <tr class="cont-item">
                                    <?PHP if($val['producto_id']!=0){ ?>
                                    <td colspan="2" class="col-sm-4">
                                        <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" value="<?PHP echo $val['descripcion'];?>" readonly><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $val['producto_id']?>"></div></td>                                        
                                    <td style="display: none;"><input type="text" class="form-control" readonly id="medida" name="medida[]" value="<?php echo $val['medida_nombre']?>"></td>
                                     <?PHP }else{ ?>
                                    <td class="col-sm-3"><textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required="" readonly><?PHP echo $val['descripcion'];?></textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $val['producto_id']?>"></div> </td>
                                     <td class="col-sm-1">
                                        <select class="form-control" id="medida" name="medida[]">
                                            <?php foreach ($medida as $valor):?>
                                                <option value="<?php echo $valor->medida_id;?>" <?php echo ($valor->medida_id==$val['unidad_id'])?"selected":"";?>><?php echo $valor->medida_nombre;?></option>
                                            <?php endforeach ?>                            
                                         </select></td>
                                     <?PHP } ?>
                                      
                                    <td><input type="number" class="form-control cantidad" id="cantidad" name="cantidad[]"  value="<?PHP echo $val['cantidad'];?>" ></td>

                                    <td class="col-sm-2">
                                        <select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                            <?PHP foreach ($tipo_igv as $value) {
                                                    $selected = ($value['id'] == $val['tipo_igv_id']) ? "SELECTED" : '';?>
                                                    <option <?PHP echo $selected;?> value="<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv'];?></option>
                                            <?PHP }?>
                                        </select>
                                    </td>                                                                        
                                    <td><input type="number" class="form-control importe" id="importe" name="importe[]" value="<?PHP echo $val['importe']?>" /><input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" value="<?= $val['importeCosto'];?>">
                                    <td class="precios"><span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span></td>;                                    
                                                                             
                                    <td>   
                                    <input type="hidden" id="desc_uni"  name="descuento[]" class="form-control" value="<?php echo $val['descuento']?>">                                 
                                    <input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="<?php echo $val['igv']?>">
                                    <input type="hidden" id="subtotal" name="subtotal[]" class="form-control" value="<?= $val['subtotal']?>">
                                    <input type="text" id="total" name="total[]" class="form-control totalp" readonly="" value="<?php echo $val['total']?>" >
                                    <input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="<?= $val['totalVenta'];?>" readonly="">
                                    <input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="<?= $val['totalCosto'];?>" readonly=""></td>

                                    <td><a class="delete" title="eliminar"><span id="<?PHP echo $val['item_id'];?>" class="glyphicon glyphicon-remove"></span></a></td>
                                </tr>                                                 
                                <?PHP }?>
                                </tbody>
                            </table>
                            <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                            <button type="button" id="agrega_sin" onclick="agregar_fila_sin_stock()" class="btn btn-primary btn-sm" style="background: #E67E22;border:0;">Agregar Item sin stock</button>
                            <button type="button" id="btn_buscar_producto" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#myModalProducto" data-keyboard='false' data-backdrop='static'>Buscar Producto</button>
                        </div>
                    </div> 
                        
                    <div id="mostrar"></div>
                    <div id="uu"></div>
                    </div></div>
                    </div></div>
        </div>
    </div>
    <div class="row" style="padding-top:20px;">               
        <div class="col-md-12 col-lg-8">
            <div class="panel panel-info" style="display: none;">                    
                <div class="panel-heading">
                    <div class="panel-title">METODO DE PAGO</div>
                </div>
                <div class="panel-body">
                     <div class="row" style="width: 100%; margin: 0 auto;">
                        <div class="col-sm-4 form-group"> 
                            <label class="control-label">Tipo de Pago:</label>                    
                            <select class="form-control" name="tipo_pago" id="tipo_pago">
                            <?PHP foreach ($tipo_pagos as $value) { 
                                $selected = ($value->id == $comprobante['tipo_pago_id']) ? 'SELECTED': '';?>                          
                                <option value = "<?PHP echo $value->id;?>"<?= $selected;?>><?PHP echo $value->tipo_pago;?></option>
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
            
            <!-- agregra notas -->
            <?php if ($configuracion->notas): ?>                
                <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">OBSERVACIONES</div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" <?php if($comprobante['observaciones'] == ''):?> disabled <?php endif?>><?php echo $comprobante['observaciones']?></textarea>
                    </div>
                </div>
            <?php endif ?>                       
                <div class="panel panel-info" id="panel_otros">
                    <div class="panel-heading">
                        <div class="panel-title">OBSERVACIONES</div>
                    </div>
                    <div class="panel-body">
                        <textarea name="notas" id="notas" rows="3" cols="100" <?php if($comprobante['observaciones'] == ''):?> disabled <?php endif?> style="width: 100%;"><?php echo $comprobante['observaciones']?></textarea>
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
<input type="hidden" id="requerimiento_id" name="requerimiento_id" value="<?= $requerimiento['id'];?>">       
        <!-- ICBPER -->    
<input type="hidden" id="valorIcbper" value="<?php echo $configuracion->monto_icbper;?>">
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
    <input type="hidden" id="paciente_id" name="paciente_id" value="<?= $comprobante['paciente_id']?>">
    <input type="hidden" id="medico_id" name="medico_id" value="<?= $comprobante['medico_id']?>">
</form>


    <!--  modal nuevo cliente -->
<div class="modal fade" id="myModalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalNuevoCliente"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Nuevo Cliente</h4>
      </div>
      <div class="modal-body" style="height:600px;">
        <div class="container">
    <!-- Example row of columns -->
    <div class="row">                
       
        <div class="col-md-6">           
            <div align="center"><h2>Ingresar Cliente</h2></div>
            <form class="form-horizontal" role="form"  method="POST" id="formNuevoCliente">
                <div class="form-group">
                    <label for="tipo_cliente" class="col-sm-5 control-label">Tipo Cliente:</label>
                    <div class="col-xs-4">
                        <select class="form-control" name="tipo_cliente" id="tipo_cliente" required="">
                            <option>Seleccionar</option>
                            <?PHP foreach ($tipo_clientes as $value_tipo_clientes) { ?>
                                <option value="<?PHP echo $value_tipo_clientes->id.'xx-xx-xx'.$value_tipo_clientes->tipo_cliente; ?>"><?PHP echo $value_tipo_clientes->tipo_cliente; ?></option>
                                <?PHP
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div id="datos">    
                    <div class="form-group">
                        <label id="lbl_DNI_RUC" for="ruc" class="col-sm-5 control-label">Ruc:</label>
                        <div class="col-xs-6">
                            <input type="number" class="form-control" name="ruc" id="ruc" placeholder="RUC" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                        </div>
                        <div class="col-xs-1">
                            <a href="#"><span class="glyphicon glyphicon-search searchCustomer"></span></a>
                        </div> 
                    </div>
                    <div class="form-group">
                        <label id="lbl_RAZ_APE" for="razon_social" class="col-sm-5 control-label">Razón Social</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="razon_social" required="">
                        </div>
                    </div>
                    <!--<div id="nombres">
                        <div class="form-group">
                            <label for="nombres" class="col-sm-5 control-label">Nombres</label>
                            <div class="col-xs-7">
                                <input type="text" class="form-control" name="nombres" id="nombres" placeholder="nombres">
                            </div>
                        </div>
                    </div>-->
                    
                    <div class="form-group">
                        <label for="domicilio1" class="col-sm-5 control-label">Domicilio <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="domicilio1" id="domicilio1" placeholder="domicilio1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-5 control-label">Email:</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" name="email" id="email" placeholder="email">
                        </div>
                    </div>

                    <!--<div class="form-group">
                        <label for="pagina_web" class="col-sm-5 control-label">Página web:</label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" name="pagina_web" id="pagina_web" placeholder="pagina_web">
                        </div>
                    </div>-->
                    <!--<div class="form-group">
                        <label for="telefono_fijo_1" class="col-sm-5 control-label">Telefono fijo 1:</label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="telefono_fijo_1" id="telefono_fijo_1" placeholder="telefono_fijo_1">
                        </div>
                    </div>-->
                    <div class="form-group">
                        <label for="telefono_movil_1" class="col-sm-5 control-label">Telefono movil 1:</label>
                        <div class="col-xs-7">
                            <input type="number" class="form-control" name="telefono_movil_1" id="telefono_movil_1" placeholder="telefono_movil_1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_movil_1" class="col-sm-10 control-label"  style="text-align: center;"><label style="color: red;">(*) Campos obligatorios</label></label>
                       
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" id="guardarNuevoCliente" value="Guardar" >
                        </div>
                    </div>
                </div>                
            </form>

        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
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
        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes/buscar_cliente",{texto})
         .done(function(json){
            
             var html = '';
             $.each(json,function(index,value){
                if(value.tipo_cliente_id==1){
                    var doc = "DNI";
                }else if(value.tipo_cliente_id==2){
                    var doc = "RUC";
                }else{
                    var doc = "OTROS";
                }
                 html+= '<option value="'+ value.id+ ' - ' + doc + ' ' +value.ruc + ' ' + value.razon_social + '">';
             });

             $("#lista_clientes").html(html);
         })
    }

    function seleccionar_cliente(){
        var opcion = $("#cliente").val();
        var guion = opcion.search("-");
        var cliente_id = opcion.substr(0,guion-1);

        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes/seleccionar_cliente",{cliente_id})
         .done(function(json){
            console.log(json);
            $("#cliente_id").val(json.id);
            $("#direccion").val(json.domicilio1);

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

    //Guardar Orden 11-05-2010

    $("#btn_guardar_orden").click(function(e){        
        e.preventDefault();
        $.ajax({
            method : 'post',
            url : '<?= base_url()?>index.php/ordenes/guardar_orden',
            data: $("#formComprobante").serialize(),
            dataType: 'json',
            success: function(response){
                if(response.status == STATUS_FAIL){
                    toast("error",3000,response.msg);
                }
                if(response.status == STATUS_OK){
                    toast("success",3000, 'Orden Registrada');
                    setTimeout(function(){ 
                            location.href='<?PHP echo base_url()?>index.php/transacciones';
                         }, 1000);
                }
            }
        });    
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
            }else{
                $("#lbl_DNI_RUC").html('RUC <label style="color: red;">(*)</label>');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").html('Razon Social <label style="color: red;">(*)</label>');
                $("#razon_social").attr("placeholder","razon social");
                $("#nombres").hide();
            }

        });
        
       //SearchCustomer        
       $('.searchCustomer').on('click',function(){           
           var op = $("#tipo_cliente option:selected").val();
           var array = op.split('xx-xx-xx');
           var tipoCliente =  array[0];
                      
           var ruc = $('#ruc').val();           
           var url = '<?= base_url();?>index.php/clientes/searchCustomer';
           
           $.ajax({
               type: 'POST',
               url : url,
               dataType:'json',
               data : {tipoCliente : tipoCliente, ruc: ruc},
               success : function(datosCliente){                   
                if(datosCliente.status == STATUS_OK){                    
                    var datos = eval(datosCliente);                             
                    if(datos.typeCustomer == 1){                    
                    $("input[name*='razon_social']").val(datos.paterno+' '+datos.materno+' '+datos.nombres);}
                    if(datos.typeCustomer == 2){
                    $("input[name*='razon_social']").val(datos.razonSocial);    
                    $("input[name*='domicilio1']").val(datos.direccionFiscal);}
                }                      
                if(datosCliente.status == STATUS_FAIL){                                    
                    toast("error",1500, datosCliente.msg);
                }
               }                              
           });
           return false;
       });

        $('#guardarNuevoCliente').click(function(e){
            e.preventDefault();
        
        var url = "<?PHP echo base_url() ?>index.php/clientes/grabar_para_comprobante";
        $.ajax({                        
           type: "POST",                 
           url: url,                     
           data: $("#formNuevoCliente").serialize(), 
           success: function(data)             
           {
              var cliente = JSON.parse(data);
              if(cliente['success']==4){
                 toast("success", 1500, 'Cliente ingresado con exito');
                 $("#formNuevoCliente")[0].reset(); 
                 $("#closeModalNuevoCliente").click(); 
                 $('#cliente').val(cliente['nombre']);     
                 $('#direccion').val(cliente['direccion']);
                 $('#cliente_id').val(cliente['id']); 
                 $("#datos").hide();
                 $("#limitado_detalle").hide();

              }else{
                 if(cliente['success']==1){
                    toast("error",3000, "Ingrese número de documento");
                 }else if(cliente['success']==2){
                    toast("error",3000, "Ingrese nombre o Razón Social");
                 }else{
                    toast("error",3000, "Ingrese domicilio");
                 }
                  
              }
                     
           }
       });
    });

         //// FALSE : NO IGV; TRUE : SI IGV
        //cmp.incluyeIgv=false;


        <?php 
            $configuracion = $this->db->from('comprobantes_ventas')->get()->row();
            if ($configuracion->pu_igv==1) {
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
    var textoReferenciaNotas = "";

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
            parent.remove();
            calcular();           
            return false;
        });                       
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

                if($('#tipo_documento option:selected').val() == "7"){
                    updateDocumentoNotaCredito();
                }
            }
        });
    });

    /*buscar producto*/
        $('body').delegate('.descripcion-item', 'keydown', function() {          
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id" >';
                _item.find('#data_item').html(data_item);
                _item.find('#descripcion').attr("readonly",true);
                _item.find('#medida').val(ui.item.medida);
                
                _item.find('.importe').val(ui.item.precio);
                _item.find('.importeCosto').val(ui.item.precioCosto);
                
                var parent = $(this).parents().parents().get(0);
                
                _item.find('.totalp').val(ui.item.precio);
                cmp.calcular(parent);
                calcular();
                calcular();
                
            },
            change : function(event,ui){
              if (ui.item.prod_stock == 0){
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

    //modal para agregar anticipo
    $("#btn_agregar_anticipo").click(function(e){
        var datos = {
                        cliente:$("#cliente_id").val()
                    };
        $("#myModal").load('<?php echo base_url()?>index.php/comprobantes/agregarAnticipoUi',datos);
    });  

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

          

    //AGREGANDO FILAa
    $(function(){               
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
                        '<input type="hidden" id="subtotal" name="subtotal[]" class="form-control" readonly="">'+
                        '<input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="0.00" readonly=""></td>';
                fila += '<td class="eliminar" style="border:0;">'+
                        '<span class="glyphicon glyphicon-remove" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
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
            validAlfaNumerico(e);
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
        var facturas_cliente =
               '<label class="control-label">Documento a Modificar</label>' +
               '<select class="form-control input-sm" name="comp_adjunto" id="comp_adjunto">' +
               '</select>';
        $('#div_facturas_cliente').html(facturas_cliente);

        <?php if($comprobante['com_adjunto_id']!=''){ ?>
        var url = '<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec + '/' + <?php echo $comprobante['com_adjunto_id']?>;
        <?php }else{ ?> 
            var url = '<?PHP echo base_url(); ?>index.php/comprobantes/comprobantesNotasCredito/' + <?= $empresa['id']?> + '/' + cliente_id + '/' + serie_selec;
         <?php } ?>   
        //console.log(url);
        $("#comp_adjunto").load(url);         
    }
    
    // EVENTO COMBOBOX NOTA DE CREDITO , DEBITO
    $('#tipo_documento').on('change',function(){    
        documentoChange();                
    });
    
    $('#serie').on('change',function(){
        serieChange();
    });
    documentoChange();        
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
</script>