

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
<form id="formComprobante" class="form-horizontal" role="form" action="<?PHP echo base_url()?>index.php/notas/guardarNota" method="post" autocomplete="off">
    <input type="hidden" name="notaId" id="notaId" value="<?php echo $nota->notap_id?>" >
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>PEDIDO - <b><?= $empresa[0]['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">                        
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            <label class="control-label">Cliente:</label>
                            <input type="text" class="form-control" name="cliente" id="cliente" value="<?php echo $nota->razon_social?>">
                            <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id" value="<?php echo $nota->notap_cliente_id?>"></div>
                        </div>
    

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                            <?php if($nota->notap_id > 0):?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $nota->notap_correlativo?>" readonly>
                            <?php else:?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $consecutivo?>" readonly>
                            <?php endif?>
                            
                        </div>

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha:</label>
                            <?php if($nota->notap_id > 0):?>
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo $nota->notap_fecha?>">
                            <?php else:?> 
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d-m-Y')?>"> 
                            <?php endif?>  
                        </div>    

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="control-label">Tipo de Moneda:</label>        
                            <select class="form-control" name="moneda_id" id="moneda_id">
                           <?PHP foreach ($monedas as $value) { ?>                          
                               <option value = "<?PHP echo $value['id'];?>" <?php if($nota->notap_moneda_id==$value['id']):?> selected <?php endif?> ><?PHP echo $value['moneda']?></option>
                           <?PHP }?>    
                           </select>
                        </div>       

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="control-label">Tipo de Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="" value="<?php echo $nota->notap_tipo_cambio?>">
                        </div> 
                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <label class="control-label">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" class="form-control" value="<?php echo $nota->notap_cliente_direccion?>" >
                        </div> 
                        <!--<div class="col-xs-2">
                            <label class=" control-label">Vendedor</label>
                            <select class="form-control" name="vendedor" id="vendedor">
                                <option value="">Seleccione vendedor</option>
                                <?php foreach($vendedores as $v){?>
                                  <option value="<?php echo $v->id?>" <?php echo ($nota->notap_vendedor==$v->id)?"selected":"";?> ><?php echo $v->nombre.' '.$v->apellido_paterno?></option>
                                <?php }?>    
                            </select>    
                        </div>-->               
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
                                    <table id="tabla" class="table table-striped" <?php if(count($nota->detalles)==0):?> style="display:none" <?php endif?> >
                                        <thead>
                                            <tr>
                                                <th>Descripcion</th>                                                
                                                <th>Cant.</th>
                                                <th>Tipo Igv</th>
                                                <th>Precio Unitario</th>     
                                                <!--<th>Sub Total</th>  --> 
                                                <th></th>                              
                                                <th>Descuento</th>                              
                                                <th>Total</th>
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
                                                        <input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="<?php echo $item->notapd_cantidad?>" >
                                                    </td>
                                                    <td class="col-sm-2">
                                                        <select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                                        <?php foreach($tipo_igv as $value):?>
                                                        <option value = "<?PHP echo $value['id'];?>" <?php if($value['id']==$item->notapd_tipo_igv):?> selected <?php endif?> ><?PHP echo $value['tipo_igv']?></option>
                                                        <?php endforeach?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" id="importe" name="importe[]"  class="form-control importe" value="<?php echo $item->notapd_precio_unitario?>" >
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" value="<?php echo $item->notapd_igv?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="desc_uni"  name="descuento[]" class="form-control" value="<?php echo $item->notapd_descuento?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="total" name="total[]" class="form-control totalp" readonly="" value="<?php echo $item->notapd_subtotal?>" >
                                                    </td>
                                                    <td class="eliminar"><span class="glyphicon glyphicon-remove-circle"></span></td>
                                                </tr>
                                            <?php endforeach?>    
                                        </tbody> 

                                        </table>   
                                    <button type="button" id="agrega" class="btn btn-primary btn-sm">Agregar Item</button>
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
        <div class="col-xs-8 col-md-8 col-lg-8">                                                                         
            <div class="panel panel-info" id="panel_otros">
                <div class="panel-heading">
                    <div class="panel-title">OBSERVACIONES</div>
                </div>
                <div class="panel-body">
                    <textarea name="observaciones" id="observaciones" rows="3" cols="100"><?php echo $nota->notap_observaciones?></textarea>
                </div>
            </div>            
        </div>        
        <div class="col-xs-4 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel panel-body">
                   <!-- <div class="input-group">        
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
  
                    <div class="input-group">        
                        <span class="input-group-addon">Gravada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control" readonly="" value="<?php echo $nota->notap_subtotal?>">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon">IGV: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control" readonly="" value="<?php echo $nota->notap_igv?>">
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
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control" readonly="" value="<?php echo $nota->notap_total?>">
                    </div>    
    
                </div>
            </div>
        </div>
                
        <div class="input-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                <?php if($nota->notap_id > 0):?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Editar Nota Pedido"/>
                <?php else:?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Generar Nota Pedido"/>
                <?php endif?>
                
            </div>
        </div>        
    
    </div>   
<input type="hidden" name="descontar_stock" id="descontar_stock" value="0">

</form>

</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>
<script type="text/javascript">        
    //FUNCIONES FECHAS    
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/pedidos/buscador_item',
            minLength : 2,
            select : function (event,ui){                
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id" >';
                _item.find('#data_item').html(data_item);

                
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
        });
      
    });   
       
    //AGREGANDO FILA
    $(function(){       
        var fila = '<tr class="cont-item">'; 

                fila += '<td class="col-sm-3"><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';

                fila += '<td><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1"></td>';

                fila += '<td class="col-sm-2">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>
                    fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                <?php endforeach?>
                fila += '</select>'
                fila += '</td>';

                fila += '<td><input type="number" id="importe" name="importe[]"  class="form-control importe"></td>';
                /*fila += '<td><input type="text" id="subtotal" name="subtotal[]" class="form-control" readonly=""></td>';*/
                fila += '<td><input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" ></td>';
                fila += '<td><input type="text" id="desc_uni"  name="descuento[]" class="form-control"   ></td>';
                fila += '<td><input type="text" id="total" name="total[]" class="form-control totalp" value ="0.00" readonly=""></td>';
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
    //$("#formComprobante").submit();
    //guardar nota
    $("#guardar").click(function(e){
        e.preventDefault();
      
       $(".has-error").removeClass(".has-error");

       /*if(confirm("¿Descontar stock?")){
          $("#descontar_stock").val(1);
       }*/    
            $.ajax({
                url:'<?PHP echo base_url()?>index.php/pedidos/guardarNota',
                method:'post',
                data:$("#formComprobante").serialize(),
                dataType:'json',
                success:function(response){
                    if(response.status == STATUS_OK)
                    {
                        toast("success",1500,"Pedido registrada");
                        setTimeout(function(){
                            location.href='<?PHP echo base_url()?>index.php/pedidos/index';    
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
                        }else{
                            toast("error", 1500, response.msg);
                        }
                    }
                }
            }); 

                 
    });



    
</script>