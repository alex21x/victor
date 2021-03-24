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
    <input type="hidden" name="anticipo" id="anticipo" value="0">
    <div class="row">        
        <div class="col-md-12">
            <div style="text-align: center"><h3>ORDEN DE COMPRA - <b><?= $empresa['empresa']?></b></h3></div>
            <div style="text-align: left" id="mensaje"></div>            
            
            <div class="panel panel-info" >
                <div class="panel-heading" >
                    <div class="panel-title">COMPLETE DATOS DEL COMPROBANTE</div>                        
                </div>
                <div class="panel-body">                     
                    <div class="form-group">    
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
                            <button style="width: 9%;float: right;margin-left: 1%;" type="button" id="nuevo_cliente" class="btn btn-primary btn-sm" onclick="consulta_sunat()">SUNAT</button>
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
                               <option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['moneda']?></option>
                           <?PHP }?>    
                           </select>
                        </div>       

                        <div class="col-xs-6 col-md-2 col-lg-2">
                            <label class="control-label">Tip. Cambio:</label>        
                            <input type="text" class="form-control" name="tipo_de_cambio" id="tipo_de_cambio" disabled="">
                        </div>


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
                        </div>

<!-- ARRAY ADELANTO DE ITEMS              --->
<input type="hidden" value="[]" id="adelanto_items" name="adelanto_items">  
                                                                   
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
        <div class="col-xs-12 col-md-8 col-lg-8">                                                                         
                <!-- MUESTRA DETRACCION , FACTURA O BOLETA -->    
                
              <!-- MOSTRAR NOTA DE CREDITO, NOTA DE DEBITO --> 
              <div id="mostrarCompNota">                                               
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
                            <?PHP foreach ($tipo_pagos as $value) { ?>                          
                                <option value = "<?PHP echo $value['id'];?>"><?PHP echo $value['tipo_pago']?></option>
                            <?PHP }?>    
                            </select>    
                                
                        </div>
                        <div class="col-md-3" id="conte-ntarjeta" style="display: none;">
                        </div> 
                    </div>
                </div> 
            </div> 
            <!-- notas -->
        </div>        
        <div class="col-xs-12 col-md-4 col-lg-4">
           
                <div class="panel panel-body" style="border:1px solid #7FB3D5;border-radius:6px;">
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
                    <input id="guardar"  class="btn btn-primary btn-lg btn-block" value="Generar Comprobante de Pago" style="background: #1ABC9C;border:0;"/>
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
        var ruc_id = <?= $empresa['ruc']?>;

        toast("info",4000, 'Buscando . . .');
        if(num!=''){
            $.getJSON("https://apis.sitefact.pe/api/ConsultaRuc",{ruc:num})
             .done(function(json){
      

                 if(json.result.RUC.length!=undefined){
                    $("#cliente_id").val(0);
                    $("#cliente").val("RUC "+json.result.RUC+" "+json.result.RazonSocial);
                    $("#direccion").val(json.result.DireccionFiscal);
                    $("#ruc_sunat").val(json.result.RUC);
                    $("#razon_sunat").val(json.result.RazonSocial);
                     
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe en SUNAT');
                 }
             });
        }else{
             toast("error",3000, 'Ingrese número de documento de búsqueda');
        }        
    }

    function buscar_cliente(){
        var texto = $("#cliente").val();
        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes_orden_compras/buscar_cliente",{texto})
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

        $.getJSON("<?PHP echo base_url() ?>index.php/comprobantes_orden_compras/seleccionar_cliente",{cliente_id})
         .done(function(json){
            console.log(json);
            $("#cliente_id").val(json.prov_id);
            $("#direccion").val(json.prov_direccion); 
         })        
    }

    var array_adelanto_items = [];
    //FUNCIONES FECHAS   

    //registrar comprpobantes
    $("#guardar").click(function(e){
        e.preventDefault();
        $.ajax({
            method:'post',
            url:'<?PHP echo base_url()?>index.php/comprobantes_orden_compras/guardar_comprobante',
            data:$("#formComprobante").serialize(),
            dataType:'json',
            success:function(response){
                if(response.status == STATUS_FAIL)
                {
                    toast("error",3000, response.msg);
                }
                if(response.status == STATUS_OK)
                {
                    toast("success", 1500, 'Orden Compra registrado');
                    location.href='<?PHP echo base_url()?>index.php/comprobantes_orden_compras';
                }
            }
        });        
    });

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

            $.get("<?PHP echo base_url()?>index.php/comprobantes_orden_compras/estado_igv",{valor})
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
    var textoReferenciaNotas = "Operación sujeta al SPOT\nBCO. Nación CTA. CTE. MN 00-000-360155";
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
            source : '<?PHP echo base_url();?>index.php/comprobantes/buscador_itemC',
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
                        $('#item_id').val(''); 
                        $('#importe').val('');                        
                    }

                }
            }                
        });
    });       

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
                               
                fila += '<td class="col-sm-4" style="border:0;"> <input class="form-control descripcion-item" rows="2" id="descripcion" name="descripcion[]" required=""><div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div> </td>';
                
                                
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
</script>