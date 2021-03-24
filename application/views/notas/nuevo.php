<link rel="stylesheet" href="<?PHP echo base_url()?>assets/css/comprobante.css">
<div id="mensaje"></div>
     
<div class="container-fluid">
<form id="formComprobante" class="form-horizontal" role="form" autocomplete="off">
    <input type="hidden" name="notaId" id="notaId" value="<?php echo $nota->notap_id?>" >
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>NOTA DE VENTA - <b><?= $empresa[0]['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">                        
                        <div class="col-md-4 col-lg-4 input_cliente">
                            <label class="control-label">Cliente:</label>                            
                            <input type="text" class="form-control" name="cliente" id="cliente" value="<?php echo $nota->razon_social?>">
                            <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $nota->notap_cliente_id?>"></div>
                        </div>    
                        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-1 input_busqueda"><br>
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" data-toggle='modal' data-target='#myModalNuevoCliente'>NUEVO</button>                        
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" onclick="consulta_sunat()">BUSCAR</button> 
                        </div>                           
                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                            <?php if($nota->notap_id > 0):?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $nota->notap_correlativo?>" readonly>
                            <?php else:?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $consecutivo?>" readonly>
                            <?php endif?>                            
                        </div>

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha:</label>
                            <?php if($nota->notap_id > 0):?>
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $nota->notap_fecha?>">
                            <?php else:?> 
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d-m-Y')?>"> 
                            <?php endif?>  
                        </div>    

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tipo de Moneda:</label>        
                            <select class="form-control" name="moneda_id" id="moneda_id">
                           <?PHP foreach ($monedas as $value) { ?>                          
                               <option value = "<?PHP echo $value->id;?>" <?php if($nota->notap_moneda_id==$value->id):?> selected <?php endif?> ><?PHP echo $value->moneda?></option>
                           <?PHP }?>    
                           </select>
                        </div>       

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tipo de Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="" value="<?php echo $nota->notap_tipo_cambio?>">
                        </div> 
                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo $nota->notap_cliente_direccion?>" >
                        </div>                         
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Transportistas:</label>        
                            <select class="form-control" name="transportista" id="transportista">
                           <?PHP foreach ($transportistas as $value) { ?>                          
                               <option value = "<?PHP echo $value->transp_id;?>" <?php if($nota->notap_transportista_id==$value->transp_id):?> selected <?php endif?> ><?PHP echo $value->transp_nombre.'-'.$value->transp_tipounidad?></option>
                           <?PHP }?>    
                           </select>
                        </div>                             
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Placa:</label>
                            <input type= "text" class="form-control" id="placa" name="placa" value="<?= $nota->placa;?>">
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
                                    <table id="tabla" class="table table-striped" <?php if(count($nota->detalles)==0):?> style="display:none" <?php endif?> >
                                        <thead>
                                            <tr>
                                                <th class="col-sm-3">Descripcion</th>                                                
                                                <th class="col-sm-3">Cant.&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <!--<th class="col-sm-3">&nbsp;</th>-->
                                                <th class="col-sm-3">Precio Unitario</th>
                                                <th class="col-sm-1">&nbsp;</th><!--PRECIOS -->
                                                <!--<th>Sub Total</th>-->
                                                <!--<th class="col-sm-1">&nbsp;</th><!--IGV -->
                                                <!--<th class="col-sm-2">Descuento</th>-->
                                                <th class="col-sm-2">Total</th>
                                                <th class="col-sm-1">&nbsp;</th>
                                            </tr>
                                        </thead>                    
                                        <tbody>                                                      
                                            <?php foreach($nota->detalles as $item):?>
                                                <tr class="cont-item">
                                                    <td class="col-sm-3">
                                                        <input type="text" class="form-control descripcion-item" rows="2" id="descripcion" value="<?php echo $item->notapd_descripcion?>" name="descripcion[]">
                                                        <div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $item->notapd_producto_id;?>"></div>
                                                    </td>
                                                    <td>
                                                        <input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="<?php echo $item->notapd_cantidad?>">
                                                    </td>
                                                    <td class="col-sm-2" style="display:none">
                                                        <select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                                        <?php foreach($tipo_igv as $value):?>
                                                        <option value = "<?PHP echo $value['id'];?>" <?php if($value['id']==$item->notapd_tipo_igv):?> selected <?php endif?> ><?PHP echo $value['tipo_igv']?></option>
                                                        <?php endforeach?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" id="importe" name="importe[]"  class="form-control importe" value="<?php echo $item->notapd_precio_unitario?>">
                                                        <input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" value="<?= $item->notapd_importeCosto;?>">
                                                    </td>
                                                    <td class="precios"><span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span></td>     
                                                    <td>
                                                        <input type="hidden" id="desc_uni"  name="descuento[]" class="form-control" value="<?php echo $item->notapd_descuento?>">
                                                        <input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="<?php echo $item->notapd_igv?>">
                                                        <input type="hidden" id="subtotal" name="subtotal[]" class="form-control" value="<?= $item->notapd_subtotal?>">
                                                        <input type="text" id="total" name="total[]" class="form-control totalp" readonly="" value="<?php echo $item->notapd_total?>" >
                                                        <input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="<?= $item->notapd_totalVenta;?>" readonly="">
                                                        <input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="<?= $item->notapd_totalCosto;?>" readonly=""></td>
                                                    </td>
                                                    <td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>
                                                </tr>
                                            <?php endforeach?>    
                                        </tbody> 
                                        </table>   
                                    <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
                                    <button type="button" id="agrega_sin" onclick="agregar_fila_sin_stock()" class="btn btn-primary btn-sm" style="background: #E67E22;border:0;">Agregar Item sin stock</button>
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
        <!-- col-md-8 col-lg-8 -->
    <div class="col-xs-12 col-md-8 col-lg-8 ">             
            <div class="panel panel-info" id="panel_otros" style="display: none">
                <div class="panel-heading">
                    <div class="panel-title">TIPO PAGO</div>
                </div>
                <div class="panel-body">
                    <div class="col-xs-12 col-md-4 col-lg-4 ">
                            <label class="control-label">Tipo de pagos:</label>        
                            <select class="form-control" name="tipo_pago" id="tipo_pago">
                           <?PHP foreach ($tipo_pagos as $value) { ?>                          
                               <option value = "<?PHP echo $value->id;?>" <?php if($nota->notap_tipopag_id==$value->id):?> selected <?php endif?> ><?PHP echo $value->tipo_pago?></option>
                           <?PHP }?>    
                           </select>
                    </div>                    
                </div>
            </div>                                       
            <div class="panel panel-info" id="panel_otros">
                <div class="panel-heading">
                    <div class="panel-title">OBSERVACIONES</div>
                </div>
                <div class="panel-body">
                    <textarea class="col-xs-12" name="observaciones" id="observaciones" rows="3" cols="100"><?php echo $nota->notap_observaciones?></textarea>
                </div>
            </div>                    
    </div>    

    <div class="col-xs-12 col-md-4 col-lg-4 ">                    
            <div class="panel panel-default">
                <div class="panel panel-body">
                   <!--<div class="input-group">        
                        <span class="input-group-addon">Descuento Global: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="descuento_global" name="descuento_global" class="form-control">
                    </div>-->
                   <!-- <div class="input-group">        
                        <span class="input-group-addon">Exonerada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_exonerada" name="total_exonerada" class="form-control" readonly="">
                    </div>-->

                    <!--<div class="input-group">        
                        <span class="input-group-addon">Inafecta: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_inafecta" name="total_inafecta" class="form-control" readonly="">
                    </div>-->                    
                    <div class="input-group input-group-sm mb-3" style="display: none;">                        
                        <span class="input-group-addon">Gravada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control input-lg" readonly="" value="<?php echo $nota->notap_subtotal?>">
                    </div>

                    <div class="input-group" style="display: none;">        
                        <span class="input-group-addon">IGV: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control input-lg" readonly="" value="<?php echo $nota->notap_igv?>">
                    </div>
                   <!-- <div class="input-group">        
                        <span class="input-group-addon">Gratuita: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gratuita" name="total_gratuita" class="form-control" readonly="">
                    </div> -->
   
                    <!--<div class="input-group">        
                        <span class="input-group-addon">Otros Cargos: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_otros_cargos" name="total_otros_cargos" class="form-control" >
                    </div>-->
                    <!--<div class="input-group">        
                        <span class="input-group-addon">Descuento Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_descuentos" name="total_descuentos" class="form-control" value="0.00" readonly="">
                    </div>   --> 
                    <div class="input-group">                
                        <span class="input-group-addon">Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control input-lg" readonly="" value="<?php echo $nota->notap_total?>">
                    </div>                   
                </div>
            </div>            
    </div>    
        <div class="input-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                <?php if($nota->notap_id > 0):?>
                    <button id="guardar" type="button" class="btn btn-primary btn-lg btn-block" data-toggle='modal' data-target='#myModalPagoMonto'>Editar Nota Pedido</button>
                <?php else:?>
                    <!-- original -->
                <!--<input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Generar Nota Pedido"/>-->
                <button id="guardar" type="button" class="btn btn-primary btn-lg btn-block"  data-toggle='modal' data-target='#myModalPagoMonto' data-keyboard='false' data-backdrop='static'>Generar Nota Pedido</button>

                <?php endif?>
                
            </div>
        </div>            
    </div>     
    <input type="hidden" name="descontar_stock" id="descontar_stock" value="1">
    <input type="hidden" name="ruc_sunat" id="ruc_sunat">
    <input type="hidden" name="razon_sunat" id="razon_sunat">
    <input type="hidden" name="pago_monto" id="pago_monto">
</form>
</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>
<script type="text/javascript">            

    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/notas/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id" >';
                _item.find('#data_item').html(data_item);
                
                _item.find('.importe').val(ui.item.precio);
                _item.find('.importeCosto').val(ui.item.precioCosto);
                
                var parent = $(this).parents().parents().get(0);
                

                _item.find('.totalp').val(ui.item.precio);
                cmp.calcular(parent);
                calcular();
                calcular();
                
            },
            change : function(event,ui){
                if ((ui.item.prod_medida_id != 59) && (ui.item.prod_stock == 0)){                        
                var tipoDocumento = $("#tipo_documento").val();
                    
                      toast("error",1200,"Producto sin stock");
                       $(this).closest('.cont-item').remove();

                       if($("#tabla tbody tr").length === 0)
                          $("#tabla").css("display","none");      
                          calcular();                                                                        
              }               
            }                
        });
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
        $("#fecha").datepicker();
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
       
    //AGREGANDO FILA
    $(function(){       
        var fila = '<tr class="cont-item">'; 
                fila += '<td><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';

                fila += '<td><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1"></td>';
                fila += '<td style="display:none">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>
                    fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                <?php endforeach?>
                fila += '</select></td>';

                //agregue una opcion modal para diferentes precios
                fila += '<td>'+
                        '<input type="number" id="importe" name="importe[]"  class="form-control importe">'+
                        '<input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" ></td>';

                fila += '<td class="precios">'+
                        '<span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span>'+
                          '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly=""></td>';                                                                                                                   
                fila += '<td style="border:0;">'+
                        '<input type="hidden" id="codBarra" name="codBarra[]" class="form-control">'+
                        '<input type="hidden" id="desc_uni"  name="descuento[]" class="form-control">'+
                        '<input type="hidden" id="subtotal" name="subtotal[]" class="form-control" readonly="">'+
                        '<input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00">'+
                        '<input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="0.00" readonly=""></td>';                                    
                fila += '<td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>';
                fila += '</tr>';


        $("#agrega").on('click', function(){           
            agregarFila();
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
    });
        
    //AGREGANDO FILA SIN STOCK    
    function agregar_fila_sin_stock(){     
        var fila = '<tr class="cont-item">';
                            
                fila += '<td class="col-sm-4" style="border:0;"><textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required=""></textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div>';                                                            
                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';

                fila += '<td class="col-sm-2" style="display:none"">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                        <?php foreach($tipo_igv as $value):?>                          
                                fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';                    
                        <?php endforeach?>
                fila += '</select>'
                fila += '</td>';
                
                fila += '<td style="border:0;"><input type="number" id="importe" name="importe[]" required="" class="form-control importe">'+
                        '<input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto"></td>';                        
                fila += '<td class="precios">'+
                        '<span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span>'+
                          '<input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly=""></td>';                        

                fila += '<td style="border:0;">'+
                        '<input type="hidden" id="desc_uni"  name="descuento[]" class="form-control">'+
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


        //agregando una funcion para llamar al modal      
        $(document).on("click",".btn_agregar_precio",function(){                        
            var _item = $(this).parents('.cont-item');                    
            var productoId = _item.find('#item_id').val();                  
            
            _item.find('#importe').addClass( "precioSelected" );
                        
            var datos = {
                        productoId: productoId                        
                    };
            $("#myModalPrecio").load('<?php echo base_url()?>index.php/notas/SeleccionaListaPrecio',datos);
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
      
        $("#guardarNotas").click(function(e){
          $('#guardarNotas').prop('disabled',true);
          $('.btn_cerrar').prop('disabled',true);
            $.ajax({
            method:'post',
                url:'<?PHP echo base_url()?>index.php/notas/guardarNota',
                data:$("#formComprobante").serialize(),
                dataType:'json',
                success:function(response){
                    console.log(response);
                    if(response.status == STATUS_FAIL)
                    {
                        toast("error",3000, response.msg);
                        $('#guardarNotas').prop('disabled',false);
                        $('.btn_cerrar').prop('disabled',false);
                    }
                    if(response.status == STATUS_OK)
                    {                        
                        toast("success", 1500, 'Notas registrado');                         
                        setTimeout(function() { 
                        location.href='<?PHP echo base_url()?>index.php/notas/index/'+response.notap_id;
                        }, 500);                          
                    }
            }
        });        
     });    
    
    
    $(".close,.btn_cerrar").on("click", function(){
        $(".precioSelected").removeClass("precioSelected");
        $('#guardarNotas').prop('disabled',false);
    }); 
    


        //BUSCAR CLIENTE
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

    //CARGAR MODAL BUSCAR PRODUCTO
    $(document).on("click",'#btn_buscar_producto',function(e){
        e.preventDefault();
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProductoNota",{});
    });

    //CARGAR MODAL NUEVO CLIENTE
    $(".btn_buscar").on('click',function(e){
        e.preventDefault();
        $("#myModalNuevoCliente").load("<?= base_url()?>index.php/clientes/modal_nuevoCliente",{});
    });
    //CARGAR MODAL PAGO PAGO_MONTO 14-10-2020 
    $("#guardar").on('click',function(e){      
        e.preventDefault();
        $("#myModalPagoMonto").load("<?= base_url()?>index.php/notas/modal_pagoMonto",{});
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

</script>