

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
    .input-group {width: 100%;font-size: 25px;}
    .input-group-addon { min-width: 180px;text-align: right;font-size: 25px;}    
    
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

       .modal-dialog-pagoMonto{
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

       .modal-dialog-pagoMonto{
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
       .modal-dialog-pagoMonto{
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
<form id="formComprobante" class="form-horizontal" role="form" action="<?PHP echo base_url()?>index.php/proformas/guardarProforma" method="post">
    <input type="hidden" name="proformaId" id="proformaId" value="<?php echo $proforma->prof_id?>" >
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <input type="hidden" name="igvActivo" id="igvActivo" value="<?= $rowIgvActivo->valor?>">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>PROFORMAS - <b><?= $empresa[0]['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DE LA PROFORMA</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">                        
                        <div class="col-md-4 col-lg-4 input_cliente">
                            <label class="control-label"> Cliente:</label>
                            <input type="text" class="form-control" name="cliente" id="cliente" value="<?php echo $proforma->razon_social;?>">
                            <div id="data_cliente"><input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $proforma->prof_cliente_id?>"></div>
                        </div> 
                        <div class="col-xs-12 col-sm-3 col-md-2 col-lg-1 input_busqueda"><br>
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" data-toggle='modal' data-target='#myModalNuevoCliente'>NUEVO</button>                        
                            <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm btn_buscar" onclick="consulta_sunat()">BUSCAR</button> 
                        </div>

                        <div class="col-xs-12 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $proforma->domicilio1;?>">
                        </div>
                       <!-- <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                                <?php if($proforma->comp_id>0):?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $proforma->comp_correlativo?>" readonly>
                                <?php else:?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $consecutivo?>" readonly>
                                <?php endif?>    
                                
                        </div> -->
                        <div class="col-xs-6 col-md-3 col-lg-2">
                            <label class=" control-label">Fecha:</label>
                            <?php if($proforma->prof_id > 0):?>
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo (new DateTime($proforma->prof_doc_fecha))->format('d-m-Y')?>">
                            <?php else:?> 
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d-m-Y')?>"> 
                            <?php endif?>  
                        </div>    

                        <div class="col-xs-6 col-md-3 col-lg-2">
                            <label class="control-label">Tipo de Moneda:</label>        
                            <select class="form-control" name="moneda_id" id="moneda_id">
                           <?PHP foreach ($monedas as $value) { ?>                          
                               <option value = "<?PHP echo $value->id;?>" <?php if($proforma->prof_moneda_id==$value->id):?> selected <?php endif?> ><?PHP echo $value->moneda?></option>
                           <?PHP }?>    
                           </select>
                        </div>                        
                        <div class="col-xs-12 col-md-2 col-lg-2">
                            <label class="control-label">Placa:</label>
                            <input type= "text" class="form-control" id="placa" name="placa" value="<?= $value->placa;?>">
                        </div>                         
                        <div class="col-xs-6 col-md-2">
                                <label class="control-label"> Orden de Compra </label>
                                <input type="text" name="orden_compra" class="form-control">
                        </div>                                                
                        <div class="col-xs-2">
                                <label class="control-label"> N° guia remision </label>
                                <input type="text" name="nguia_remision" class="form-control">
                        </div>
                        <div class="col-xs-6 col-md-3 col-lg-2">
                            <label>Estado</label>
                            <select class="form-control" id="proceso_estado" name="proceso_estado">
                                <!--<option value="">Seleccionar</option>-->
                                    <?PHP foreach ($proceso_estados as $value){
                                      $SELECTED = ($value->id == $proforma->prof_procesoestado_id) ? 'SELECTED' : ''?>
                                <option value="<?php echo $value->id?>" <?= $SELECTED?>><?php echo $value->proceso_estado?></option>
                                    <?php }?>
                            </select>
                        </div>                        
                    </div>
                    <!-- <div class="form-group">
                       <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="form-label">Serie</label>
                            <input type="text" name="serie" id="serie" class="form-control" value="<?php echo $proforma->prof_doc_serie?>" >
                        </div> 
                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="form-label">Numero</label>
                            <input type="text" name="numero" id="numero" class="form-control" value="<?php echo $proforma->prof_doc_numero?>">  
                        </div>                                              
                    </div>   -->
                </div>        
            </div>
            
            <div class="row" style="padding-top:20px;">                
                <div class="col-xs-12 col-md-12 col-lg-12">
                    <div class="panel panel-info" >  
                        <div class="panel-heading">
                            <div class="panel-title">CONCEPTOS DE LA PROFORMA</div>
                        </div>
                        <div class="panel-body">                        
                            <div class="row" id="valida">
                                <div class="col-xs-12 col-md-12 col-lg-12">
                                    <table id="tabla" class="table table-striped" <?php if(count($proforma->detalles)==0):?> style="display:none" <?php endif?> >
                                        <thead>
                                           <tr>
                                                <th class="col-sm-2" colspan="2">Descripcion</th>                                                
                                                <th class="col-sm-1" style="display: none;">Unid. Medida</th>
                                                <th class="col-sm-2">Cant.&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                                <th class="col-sm-3" style="display: none;">Tipo Igv</th>
                                                <th class="col-sm-3">Precio Unitario</th> 
                                                <th class="col-sm-1">&nbsp;</th>    
                                                <!--<th>Sub Total</th>   SUBTOTAL-->                                                 
                                                <th>&nbsp;</th>                                                
                                                <th class="col-sm-2">Total</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>                    
                                        <tbody>                                                      
                                            <?php foreach($proforma->detalles as $item):?>                                              
                                                <tr class="cont-item">
                                                  <?PHP if($item->profd_prod_id != 0){?>                                             
                                                    <td class="col-sm-4" colspan="2">
                                                        <input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" value="<?php echo $item->profd_descripcion?>">
                                                        <div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $item->profd_prod_id?>"></div>    
                                                    </td>
                                                    <td style="display: none;"><input type="text" class="form-control" readonly id="medida" name="medida[]" value="<?php echo $item->profd_unidad_id?>"></td>
                                                  <?PHP } else { ?>
                                                    <td class="col-sm-3">
                                                      <textarea class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required=""><?PHP echo $item->profd_descripcion;?></textarea>
                                                      <div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $item->profd_prod_id?>"></div>
                                                    </td>
                                                    <td class="col-sm-1">
                                                      <select class="form-control" id="medida" name="medida[]">
                                                          <?php foreach ($medida as $valor):?>
                                                              <option value="<?php echo $valor->medida_id;?>" <?php echo ($valor->medida_id == $item->profd_unidad_id)?"selected":"";?>><?php echo $valor->medida_nombre;?></option>
                                                          <?php endforeach ?>                            
                                                       </select></td>
                                                    <?PHP }?>                                                    
                                                    <td><input type="number" class="form-control cantidad" id="cantidad" name="cantidad[]"  value="<?PHP echo $item->profd_cantidad;?>" ></td>                                                   
                                                    <td class="col-sm-2" style="display: none">
                                                        <select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                                            <?PHP foreach ($tipo_igv as $value) {
                                                                    $selected = ($value['id'] == $item->profd_tipo_igv) ? "SELECTED" : '';?>
                                                                    <option <?PHP echo $selected;?> value="<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv'];?></option>
                                                            <?PHP }?>
                                                        </select>
                                                    </td>                                                   
                                                    <td><input type="number" class="form-control importe" id="importe" name="importe[]" value="<?PHP echo $item->profd_precio_unitario?>" /><input type="hidden" id="importeCosto" name="importeCosto[]" required="" class="form-control importeCosto" value="<?= $item->profd_importeCosto?>">
                                                    <td class="precios"><span class="glyphicon glyphicon-new-window btn_agregar_precio" id="btn_1" data-toggle="modal" data-target="#myModalPrecio"></span></td>                                                    
                                                    <td colspan="2">   
                                                    <input type="hidden" id="desc_uni"  name="descuento[]" class="form-control" value="<?php echo $item->profd_descuento?>">
                                                    <input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="<?php echo $item->profd_igv?>">
                                                    <input type="hidden" id="subtotal" name="subtotal[]" class="form-control" value="<?= $item->profd_subtotal;?>">
                                                    <input type="text" id="total" name="total[]" class="form-control totalp" readonly="" value="<?php echo $item->profd_subtotal?>" >
                                                    <input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="<?= $item->profd_totalVenta;?>" readonly="">
                                                    <input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="<?= $item->profd_totalCosto;?>" readonly=""></td>                                                    
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
        <div class="col-xs-12 col-md-8 col-lg-8">                                                                         
            <div class="panel panel-info" id="panel_otros">
                <div class="panel-heading">
                    <div class="panel-title">OBSERVACIONES</div>
                </div>
                <div class="panel-body">
                    <textarea style="width: 100%" name="observaciones" id="observaciones" rows="3" cols="100"><?php echo $proforma->profp_observaciones?></textarea>
                </div>
            </div>            
        </div>        
        <div class="col-xs-12 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel panel-body">

                    <div class="input-group" style="display: none">        
                        <span class="input-group-addon">Gravada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control" readonly="" value="<?php echo $proforma->prof_doc_subtotal?>">
                    </div>

                    <div class="input-group" style="display: none">        
                        <span class="input-group-addon">IGV: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control" readonly="" value="<?php echo $proforma->prof_doc_igv?>">
                    </div>


                    <div class="input-group">                
                        <span class="input-group-addon">Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control" readonly="" value="<?php echo $proforma->prof_doc_total?>">
                    </div>    
    
                </div>
            </div>
        </div>            
        <div class="input-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                <?php if($proforma->prof_id > 0):?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Actualizar proforma"/>
                <?php else:?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Registrar Proforma"/>
                <?php endif?>
                
            </div>
        </div>        
    
    </div>
    <input type="hidden" name="ruc_sunat" id="ruc_sunat">
    <input type="hidden" name="razon_sunat" id="razon_sunat">   
</form>

</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>
<script type="text/javascript">        
    //FUNCIONES FECHAS   
    cmp.incluyeIgv=true;
    /*buscar item*/
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
                

        $("#cliente").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/proformas/buscadorCliente',
            minLength: 2,
            select: function(event, ui) {
                //console.log(ui);
                var data_cliente ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $("#cliente").val(ui.item.razon_social);
                $('#data_cliente').html(data_cliente);
                $("#direccion").val(ui.item.domicilio1);
            },
            change:function(event, ui){

            }
        });        
      
    });


    //AGREGANDO FILA SIN STOCK 02-01-2021   
    function agregar_fila_sin_stock(){     
        var fila = '<tr class="cont-item">';                            
                fila += '<td class="col-sm-3" style="border:0;"><textarea class="form-control" rows="2" id="descripcion" name="descripcion[]" required="">'+
                        '</textarea><div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="0"></div>';     

                fila += '<td class="col-sm-1" style="border:0;">\
                          <select class="form-control" id="medida" name="medida[]">\
                            <option value="">Seleccione</option>'
                            <?php foreach ($medida as $valor):?>
                                fila += '<option value="<?php echo $valor->medida_id;?>"><?php echo $valor->medida_nombre;?></option>';  
                            <?php endforeach ?>
                fila += '</select></td>';                            

                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';
                fila += '<td class="col-sm-2" style="display:none"">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]" style="display:none">';
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

                fila += '<td style="border:0;" colspan="2">'+
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
       
    //AGREGANDO FILA
    $(function(){       
        var fila = '<tr class="cont-item">'; 

                fila += '<td colspan="2" class="col-sm-4" style="border:0;">'+
                        '<input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required="">'+
                        '<div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';              
                fila += '<td  style="border:0;display: none;"><input type="text" class="form-control" readonly id="medida" name="medida[]"></td>';
                fila += '<td style="border:0;"><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1"></td>'

                fila += '<td class="col-sm-2" style="display:none;">';
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
                          '<input type="hidden" id="igv"  name="igv[]" class="form-control" readonly=""></td>';
                
                fila += '<td><input type="hidden" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';

                fila += '<td style="border:0;">'+
                        '<input type="hidden" id="subtotal" name="subtotal[]" class="form-control" readonly="">'+
                        '<input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalVenta" name="totalVenta[]" class="form-control totalVenta" value ="0.00" readonly="">'+
                        '<input type="hidden" id="totalCosto" name="totalCosto[]" class="form-control totalCosto" value ="0.00" readonly=""></td>';
                
                fila += '<td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>';
            fila += '</tr>';

        /*agregar descuento por item*/
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


    });

    //guardar nota
    $("#guardar").click(function(e){
        e.preventDefault();
        $('#guardar').prop('disabled',true);        

       $(".has-error").removeClass(".has-error");
        $.ajax({
            url:'<?PHP echo base_url()?>index.php/proformas/guardarProforma',
            method:'post',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_OK)
                {
                    toast("success",1500,"proforma registrada");
                    setTimeout(function(){
                        location.href='<?PHP echo base_url()?>index.php/proformas/index/'+response.proforma_id;
                    },500);
                    
                }
                if(response.status == STATUS_FAIL)
                {
                    if(response.tipo == '1')
                    {
                        toast("error",1500,"Faltan ingresar datos!");
                        var errores = response.errores;
                        $.each(errores,function(index, value){
                            $("#"+index).parent().addClass("has-error");
                        });
                    }
                    if(response.tipo == '2')
                    {
                        toast("error", 1500, "No ha ingresado productos");
                    }
                }
            }
        });          
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

    //CARGAR MODAL NUEVO CLIENTE
    $(".btn_buscar").on('click',function(e){
        e.preventDefault();
        $("#myModalNuevoCliente").load("<?= base_url()?>index.php/clientes/modal_nuevoCliente",{});
    });
    //CARGAR MODAL BUSCAR PRODUCTO
    $(document).on("click",'#btn_buscar_producto',function(e){
        e.preventDefault();
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProductoProforma",{});
    });
    
</script>