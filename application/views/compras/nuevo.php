

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
    <input type="hidden" name="compraId" id="compraId" value="<?php echo $compra->comp_id?>" >
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>INGRESO - <b><?= $empresa[0]['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group" style="padding-top:20px;">                        
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            <label class="control-label">Proveedor:</label>
                            <input type="text" class="form-control" name="proveedor" id="proveedor" value="<?php echo $compra->prov_razon_social?>">
                            <div id="data_prov"><input type="hidden" name="proveedor_id" id="proveedor_id" value="<?php echo $compra->prov_id?>"></div>
                        </div>    

                       <!-- <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Numero:</label>
                                <?php if($compra->comp_id>0):?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $compra->comp_correlativo?>" readonly>
                                <?php else:?>
                                <input type="text" class="form-control" name="numero" id="numero" value="<?php echo $consecutivo?>" readonly>
                                <?php endif?>    
                                
                        </div> -->

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class=" control-label">Fecha:</label>
                            <?php if($compra->comp_id > 0):?>
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo (new DateTime($compra->comp_doc_fecha))->format('d-m-Y')?>">
                            <?php else:?> 
                                <input type="text" class="form-control" name="fecha" id="fecha" value="<?php echo date('d-m-Y')?>"> 
                            <?php endif?>  
                        </div>    

                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="control-label">Tipo de Moneda:</label>        
                            <select class="form-control" name="moneda_id" id="moneda_id">
                           <?PHP foreach ($monedas as $value) { ?>                          
                               <option value = "<?PHP echo $value['id'];?>" <?php if($compra->comp_moneda_id==$value['id']):?> selected <?php endif?> ><?PHP echo $value['moneda']?></option>
                           <?PHP }?>    
                           </select>
                        </div> 
                                          
                    </div>
                    <div class="form-group">
                       <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="form-label">Serie</label>
                            <input type="text" name="serie" id="serie" class="form-control" value="<?php echo $compra->comp_doc_serie?>" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="4" >
                        </div> 
                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="form-label">Numero</label>
                            <input type="text" name="numero" id="numero" class="form-control" value="<?php echo $compra->comp_doc_numero?>">  
                        </div>  
                     
                        <div class="col-xs-2 col-md-2 col-lg-2">
                            <label class="form-label">Tipo Documento</label>
                            <select class="form-control" name="tipo_documento" id="tipo_documento">
                            <?php foreach ($tipo_documentos as $value): $estado = ($value['id']==$compra->comp_tipo_documento) ? 'selected' : '' ; ?>
                                <?php if ($value['id'] <= 3 OR $value['id']==11): ?>
                                    <option value = "<?PHP echo $value['id'];?>" <?php echo $estado;?>><?PHP echo $value['tipo_documento']?></option>
                                <?php endif ?>
                            <?php endforeach ?>                          
                           </select> 
                        </div> 

                         <div class="col-xs-2 col-md-2 col-lg-2">
                          <label for="tipo_ingreso">Tipo de Ingreso</label>
                          <select id="tipo_ingreso" class="form-control input-sm" name="tipo_ingreso">
                            <option <?php if($compra->comp_tipo_ingreso=='Compra'):?> selected <?php endif?>>Compra</option>
                            <option <?php if($compra->comp_tipo_ingreso=='Devolucion'):?> selected <?php endif?>>Devolución</option>
                            <option <?php if($compra->comp_tipo_ingreso=='Regalo'):?> selected <?php endif?>>Regalo</option>
                            <option <?php if($compra->comp_tipo_ingreso=='Movimiento'):?> selected <?php endif?>>Movimiento</option>
                          </select>
                        </div> 


            <div class="col-md-2" style="display: none;" id="cont-almacen_mov">
              <label for="almacen_mov">Almacén</label>
              <select id="almacen_mov" class="form-control input-sm" name="almacen_mov">
                <option value="">Seleccione Almacén</option>
                <?php foreach($almacenes as $almacen):?>
                    <?php if($this->session->userdata("almacen_id")!=$almacen->alm_id){ ?>
                       <option value="<?php echo $almacen->alm_id?>" <?php if($compra->comp_almacen_mov==$almacen->alm_id):?> selected <?php endif?> ><?php echo $almacen->alm_nombre?></option>
                    <?php } ?>   
                <?php endforeach?>
              </select>
           
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
                            <div class="row" id="valida">
                                <div class="col-lg-12">
                                    <table id="tabla" class="table table-striped" <?php if(count($compra->detalles)==0):?> style="display:none" <?php endif?> >
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
                                            <?php foreach($compra->detalles as $item):?>
                                                <tr class="cont-item">
                                                    <td class="col-sm-3">
                                                        <input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" value="<?php echo $item->compd_descripcion?>">
                                                        <div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?php echo $item->compd_producto_id?>"></div>    
                                                    </td>
                                                    <td>
                                                        <input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="<?php echo $item->compd_cantidad?>">
                                                    </td>
                                                    <td class="col-sm-2">
                                                        <select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">
                                                        <?php foreach($tipo_igv as $value):?>
                                                        <option value = "<?PHP echo $value['id'];?>" <?php if($value['id']==$item->compd_tipo_igv):?> selected <?php endif?> ><?PHP echo $value['tipo_igv']?></option>
                                                        <?php endforeach?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" id="importe" name="importe[]"  class="form-control importe"   value="<?php echo $item->compd_precio_unitario?>">
                                                      
                                                    </td>
                                                    <td>
                                                        <input type="hidden" id="igv"  name="igv[]" class="form-control"  onkeydown="return validDecimals(event,this);" readonly="" value="<?php echo $item->compd_igv?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="desc_uni"  name="descuento[]" class="form-control" value="<?php echo $item->compd_descuento?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" id="total" name="total[]" class="form-control totalp" readonly="" value="<?php echo $item->compd_subtotal?>" >
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
                    <textarea name="observaciones" id="observaciones" rows="3" cols="100"><?php echo $compra->comp_observaciones?></textarea>
                </div>
            </div>            
        </div>        
        <div class="col-xs-4 col-md-4 col-lg-4">
            <div class="panel panel-default">
                <div class="panel panel-body">

                    <div class="input-group">        
                        <span class="input-group-addon">Gravada: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_gravada" name="total_gravada" class="form-control" readonly="" value="<?php echo $compra->comp_doc_subtotal?>">
                    </div>

                    <div class="input-group">        
                        <span class="input-group-addon">IGV: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_igv" name="total_igv" class="form-control" readonly="" value="<?php echo $compra->comp_doc_igv?>">
                    </div>


                    <div class="input-group">                
                        <span class="input-group-addon">Total: <span class="selec_moneda">S/.</span></span>                
                        <input type="text" id="total_a_pagar" name="total_a_pagar" class="form-control" readonly="" value="<?php echo $compra->comp_doc_total?>">
                    </div>    
    
                </div>
            </div>
        </div>
                
        <div class="input-group">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <input type="hidden" name="ajaxId" id="ajaxId" value="<?= $ajaxId;?>"/>
                <?php if($compra->comp_id > 0):?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Actualizar Compra"/>
                <?php else:?>
                    <input id="guardar" type="submit" class="btn btn-primary btn-lg btn-block" value="Registrar Compra"/>
                <?php endif?>
                
            </div>
        </div>        
    
    </div>   
</form>

</div> 
<script src="<?PHP echo base_url(); ?>assets/js/libComprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/comprobante.js"></script>
<script src="<?PHP echo base_url(); ?>assets/js/validar.js"></script>
<script type="text/javascript">    

    $(function(){


       <?php if($compra->comp_tipo_ingreso=="Movimiento"){
           echo "$('#cont-almacen_mov').css('display','block');";
       } ?> 




        $("#tipo_ingreso").change(function(event) {

            $("#proveedor").val('');
            $("#proveedor_id").val('');
            $("#serie").val('');
            $("#numero").val('');



            var tipo = $(this).val();
            if(tipo=="Movimiento"){
                $("#cont-almacen_mov").css("display","block");
                $("#proveedor").attr("readonly",true);
                $("#proveedor_id").attr("readonly",true);
                $("#serie").attr("readonly",true);
                $("#numero").attr("readonly",true);
                $("#tipo_documento").attr("readonly",true);
            }else{
                $("#cont-almacen_mov").css("display","none");
                $("#proveedor").attr("readonly",false);
                $("#proveedor_id").attr("readonly",false);
                $("#serie").attr("readonly",false);
                $("#numero").attr("readonly",false);
                $("#tipo_documento").attr("readonly",false);
            }
        });
    }) 


     //// FALSE : NO IGV; TRUE : SI IGV
    cmp.incluyeIgv=false;

    /*buscar item*/
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_item',
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
                

        $("#proveedor").autocomplete( {
            source: '<?PHP echo base_url(); ?>index.php/compras/buscadorProveedor',
            minLength: 2,
            select: function(event, ui) {
                //console.log(ui);
                var data_prov ='<input type="hidden" value="' + ui.item.prov_id + '" name = "proveedor_id" id = "proveedor_id" >';
                $("#proveedor").val(ui.item.prov_razon_social);
                $('#data_prov').html(data_prov);
            },
            change:function(event, ui){

            }
        });        
      
    });   
       
    //AGREGANDO FILA
    $(function(){       
        var fila = '<tr class="cont-item">'; 

                fila += '<td class="col-sm-3"><input type="text" class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]"><div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>';

                fila += '<td><input type="number" id="cantidad" name="cantidad[]"  class="form-control cantidad" value="1" ></td>';

                fila += '<td class="col-sm-2">';
                fila += '<select class="form-control tipo_igv" id="tipo_igv" name="tipo_igv[]">';
                <?php foreach($tipo_igv as $value):?>
                    fila += '<option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_igv']?></option>';
                <?php endforeach?>
                fila += '</select>'
                fila += '</td>';

                fila += '<td><input type="number" id="importe" name="importe[]"  class="form-control importe" ></td>';
                /*fila += '<td><input type="text" id="subtotal" name="subtotal[]" class="form-control" readonly=""></td>';*/
                fila += '<td><input type="hidden" id="igv"  name="igv[]" class="form-control"  readonly="" ></td>';
                fila += '<td><input type="text" id="desc_uni"  name="descuento[]" onkeydown="return validDecimals(event,this);" class="form-control"   ></td>';
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

    //guardar nota
    $("#guardar").click(function(e){
        e.preventDefault();

       $(".has-error").removeClass(".has-error");
        $.ajax({
            url:'<?PHP echo base_url()?>index.php/compras/guardarCompra',
            method:'post',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_OK)
                {
                    toast("success",1000,"Compra registrada");
                    setTimeout(function(){
                        location.href='<?PHP echo base_url()?>index.php/compras/index';    
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
                        toast("error",2500, response.msg);
                    }

                }
            }
        });          
    });



    
</script>