<div class="col-xs-12 col-md-12 col-lg-12">
    <div class="modal-dialog modal-md modal-dialog-pagoMonto" role="document">
        <div class="modal-content">
            <div class="modal-header">                
                <div class="modal-title">PAGÓ
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
              <form id="formPagoMonto">
                <div class="form-group">
                <table id="tablaPago" class="table" style="display:block;" border="0">                                                      
                                        <tbody>                                                      
                                        </tbody>                    
                                        </table>   
                                    <button type="button" id="agregarFilaPagoMonto" class="btn btn-primary btn-sm">Agregar Pago</button>
                </div><br><br>      
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 col-lg-4">
                        <label>Total a Pagar</label>
                    </div>    
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <input type="text" class="form-control" name="total_pago" id="total_pago" readonly="">
                    </div>                    
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 col-lg-4">
                        <label>Pago</label>
                    </div>
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <input type="number" class="form-control" name="pago" id="pago" readonly="">
                    </div>                        
                </div>
                <div class="form-group">
                    <div class="col-xs-12 col-md-4 col-lg-4">
                        <label>Vuelto</label>
                    </div>    
                    <div class="col-xs-12 col-md-6 col-lg-6">
                        <input type="text" class="form-control" name="cambio" id="cambio" readonly="">
                    </div>
                </div>
            </div><br><br><br><br> 
            </form>  
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn_cerrar" data-dismiss="modal">Volver</button>
                <button class="btn btn-primary" id="guardarComprobante">Guardar</button>
            </div>    
        </div>
     </div>   
</div>  
<script type="text/javascript">

    //FUNCION AGREGAR PAGO MONTO 10-10-2020
        function agregarFilaPagoMonto(){ 
            var rowMontoPago = $(".montoPago").toArray().length;                        
                    importe_pagoMonto = (rowMontoPago == 0) ? $("#total_a_pagar").val() : '';
                    moneda = $("#moneda_id option:selected").text();

            var  fila = '<div class="panel panel-default cont-item montoPago">';                 
                 fila += '<div class="panel-heading">Medio Pago '+moneda.toUpperCase()+'</div>'
                 fila += '<div class="panel-body">'
                 fila += '<div class="col-xs-12 col-md-6 col-lg-6">'
                 fila += '<label class="tipo_pagoMonto">Tipo Pago'+
                         '<select class="form-control tipo_pagoMonto" id="tipo_pagoMonto" name="tipo_pagoMonto[]">';
                          <?php foreach($tipo_pagos as $value):?>
                           fila += '<option value = "<?PHP echo $value->id;?>"><?PHP echo $value->tipo_pago?></option>';
                          <?php endforeach?>
                fila +=  '</select></label></div>';
                fila += '<div class="col-xs-12 col-md-6 col-lg-6">'
                fila += '<label>Monto'+
                        '<input type="number" id="importe_pagoMonto" name="importe_pagoMonto[]" required="" class="form-control importe_pagoMonto" value="'+importe_pagoMonto+'"></label></div>';   
                fila += '<div class="col-xs-12 col-md-6 col-lg-6">'                     
                fila += '<label>Observacion'+
                        '<input type="text" id="observacion_pagoMonto" name="observacion_pagoMonto[]" required="" class="form-control observacion_pagoMonto"></label></div>';
                fila += '<div class="col-xs-12 col-md-6 col-lg-6">'
                fila +=  '<span class="glyphicon glyphicon-remove eliminarPagoMonto" style="color:#F44336;font-size:20px;cursor:pointer;"></span></td>';
                fila +=  '</div></div></div>';
                fila +=  '</tr>';
                $("#tablaPago").css("display","block");
                $("#tablaPago tbody").append(fila);
                calcularPago();
                //calcular();               
        }

        agregarFilaPagoMonto();
        //AGREGANDO PAGO MONTO 14-10-2020  
        $("#agregarFilaPagoMonto").on('click', function(){
            agregarFilaPagoMonto();
        });
        //REMOVIENDO ITEMS PAGO MONTO 14-10-2020
        $(document).on("click",".eliminarPagoMonto",function(){          
            $(this).parent().parent().parent(0).remove();                
            calcularPago();
            //calcular();     
        });

    //GUARDAR COMPROBANTE 03-08-2020 ALEXANDER FERNANDEZ
    $("#guardarComprobante").click(function(e){
        var rowMontoPago = $(".montoPago").toArray().length;        
        if(rowMontoPago > 0){            
            $('#guardarComprobante').prop('disabled',true);
            $('.btn_cerrar').prop('disabled',true);        
            guardarComprobante();
        }else {
            alert('DEBE DE INGRESAR AL MENOS UN METODO DE PAGO');
        }
    });     

    function guardarComprobante(){
        $.ajax({
            method:'post',
            url:'<?PHP echo base_url()?>index.php/comprobantes_compras/guardar_comprobante',
            data:$("#formComprobante,#formPagoMonto").serialize(),
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
                           location.href='<?PHP echo base_url()?>index.php/comprobantes_compras/index/'+response.cpe_id;
                         }, 2000);
                    }       
                }
            }
        });        
    }

    //ALEXANDER FERNANDEZ DE LA CRUZ 15-10-2020
    $('#total_pago').val($('#total_a_pagar').val());
    $('#pago').val($('#total_a_pagar').val());
    calcularPago();   

    //EVENTO TEXTOBOX MODAL PAGO_CAMBIO 11-12-2019
    $(document).on('keyup','.importe_pagoMonto',function(){
        calcularPago();
    });

   //CALCULAR PAGO
   function calcularPago(){
        var total_a_pagar = $('#total_a_pagar').val();

        var sumImporte_pagoMonto = 0;
        //var importe_pagoMonto = $('.importe_pagoMonto').val();    
             $(".importe_pagoMonto").each(function(){                
                importe_pagoMonto = ($(this).val() != '') ? $(this).val() : 0;
                sumImporte_pagoMonto += parseFloat(importe_pagoMonto);
            });
        
        var pago = sumImporte_pagoMonto.toFixed(2);
        var cambio = parseFloat(pago - total_a_pagar).toFixed(2);

        $("#pago").val(pago);
        $('#pago_monto').val(pago);
        $('#cambio').val(cambio);
   }    

   
</script>