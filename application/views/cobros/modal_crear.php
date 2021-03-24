<link rel="stylesheet" type="text/css" href="<?PHP echo base_url() ?>assets/css/jquery.datetimepicker.css"/>
<script src="<?PHP echo base_url() ?>assets/js/jquery.datetimepicker.js"></script>

<?PHP
	$cobroFecha = date('d-m-Y H:i:s');
?>
<script type="text/javascript">		
	$('#fecha').datetimepicker({
		format:'d-m-Y H:i:s',  
   		defaultTime: '12:00',
    	dayOfWeekStart : 1,
    	lang:'es'
	});
</script>
<style type="text/css">	
	.rowCobro{
		padding-top: 20px;
	}
	h2{
		text-align: center;
		font-weight: bold;
	}
	.session{
		margin-top: -5px;
		text-align: left;
		font-size: 12px;
	}
</style>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
	<div class="modal-header">
		<div class="modal-title">
			<div class="col-md-9">
				<h2>COBROS</h2>
			</div>
			<div class="col-md-3">
				<div class="session">	
					<?PHP
                		$nombre = (strpos($this->session->userdata('usuario'), ' ') != '')?substr($this->session->userdata('usuario'), 0,  strpos($this->session->userdata('usuario'), ' ')):$this->session->userdata('usuario');
                                    ?>
                	<li><?PHP echo $nombre.' '.$this->session->userdata('apellido_paterno'); ?><?PHP echo "<br>".$this->session->userdata('almacen_nom'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
        		</div>      
        	</div>
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          		<span aria-hidden="true">&times;</span>
        	</button>
        </div>
	</div>	
	<div class="modal-body">
		
		<input type="hidden" name="saldoPendiente" id="saldoPendiente" value="<?= $saldo?>">
		
		<form id="formCobro">
			<input type="hidden" name="moneda" id="moneda" value="<?= $moneda;?>">
			<input type="hidden" name="tipo_pago_id" id="tipo_pago_id" value="2">
			<input type="hidden" name="id" id="id">
			<input type="hidden" name="comprobante_id" id="comprobante_id" value="<?= $comprobante_id?>">
			<input type="hidden" name="cliente_id_m" id="cliente_id_m" value="<?= $cliente_id?>">
			<input type="hidden" name="vendedor_id_m" id="vendedor_id_m" value="<?= $vendedor_id?>">
			<input type="hidden" name="serNum_m" id="serNum_m" value="<?= $serNum?>">
			<input type="hidden" name="totalCredito_m" id="totalCredito_m" value="<?= $totalCredito?>">
			
			<input type="hidden" name="tipoComprobante" id="tipoComprobante" value="<?= $tipoComprobante?>">
			<div class="row rowCobro">
				<div class="col-xs-1 col-md-1 col-lg-1">
					<label>Saldo</label>
				</div>
				<div class="col-xs-6 col-md-6 col-lg-6">	
					<input type="text" name="saldo" id="saldo" value="<?= $saldo?>" class="form-control" readonly>		
				</div>
			</div>

			<div class="row rowCobro">
				<div class="col-xs-1 col-md-1 col-lg-1">
					<label>Tipo Pago</label>
				</div>
				<div class="col-xs-6 col-md-6 col-lg-6">
					<select class="form-control" id="tipo_pago" name="tipo_pago">
						<?PHP foreach($tipo_pagos as $value){?>
						<option value="<?= $value->id?>"><?= $value->tipo_pago?></option>
						<?PHP }?>
					</select>
				</div>
			</div>
			<div class="row rowCobro">
				<div class="col-xs-1 col-md-1 col-lg-1">
					<label>Monto</label>
				</div>
				<div class="col-xs-6 col-md-6 col-lg-6">
					<input type="number" class="form-control" id="monto" name="monto">
				</div>
			</div>

			<div class="row rowCobro">
				<div class="col-xs-1 col-md-1 col-lg-1">
					<label>Fecha</label>
				</div>
				<div class="col-xs-6 col-md-6 col-lg-6">
					<input type="text" class="form-control" id="fecha" name="fecha" value="<?= $cobroFecha?>">					
				</div>
				<div class="col-xs-2 col-md-2 col-lg-2">
					<button id="btn_guardar_cobro" type="button" class="btn btn-primary ">Agregar Cobro</button>
				</div>		
				<div class="col-xs-2 col-md-2 col-lg-2 col-md-offset-1">
					<a id="pdfCobro" href="#"><img src="<?= base_url();?>images/pdf.png"></a>
                    <a id="ticketCobro" href="#"><span class="glyphicon glyphicon-print"></span></a>
				</div>          
		</form>	<br><br>		
		<div id="rowCobro"></div>				
	</div>
	<div class="modal-footer">	
		 <!--<button id="agregarcobro" type="button" class="btn btn-primary ">Agregar cobro</button>-->
		<button type="button" class="btn btn-secondary btn_cerrar" data-dismiss="modal">Volver</button>       
	</div>
	</div>
</div>


<script type="text/javascript">	
	$(document).ready(function(){
	function rowCobro(){
		$.ajax({

			url: '<?= base_url()?>index.php/cobros/rowCobro',
			method: 'POST',
			dataType: 'HTML',
			data: $("#formCobro").serialize(),
			success: function(response){
					$("#rowCobro").html(response);					
				}							
		});
	}

	rowCobro();

	$("#btn_guardar_cobro").click(function(){
		$(".has-error").removeClass('has-error');

		var monto = $("#monto").val();
		var saldo = $("#saldo").val();

		var saldo = saldo - monto;

		if(saldo < 0) {
			alert('INGRESE MONTO VÁLIDO');$("#monto").val('');$("#saldo").val(saldoPendiente.toFixed(2));		
		} else {					
		$.ajax({

			url: '<?= base_url()?>index.php/cobros/guardar',
			method: 'POST',
			dataType: 'JSON',
			data: $("#formCobro").serialize(),
			success: function(response){

				if(response.status == STATUS_FAIL){

					if(response.tipo == 1){						
						var errores = response.error;
						//console.log(errores);
						toast('danger',1500,'Falta ingresar datos');
						$.each(errores, function(index,value){
							console.log($("#"+index).parent());
							$("#"+index).parent().addClass('has-error');
						});
					}
					if(response.tipo == 2){
						if(response.status == STATUS_FAIL){
     
						toast("danger",1500,'Error al registrar');
					}
				}}
				if(response.status == STATUS_OK){
					//alert(123);
					//y llamo al metodo de LISTACOMPROBANTES
					listaComprobantes();
					rowCobro();
					calcularSaldo(1,monto);
					toast("success",1500,'Cobro registrado correctamente');
					//$("#myModal").modal('hide');
					
				}				
			}
		});
		}	
	});


	$(document).on("click",".removeCobro",function(e){		
		e.preventDefault(); 
		var idCobro = $(this).data('id');              
		var monto = $(this).data('monto');
        var msg = "Está seguro de eliminar Cobro?";
        var url = '<?php echo base_url()?>index.php/cobros/eliminar/'+idCobro

		$.confirm({
                    title: 'Confirmar',
                    content: msg,
                    buttons: {
                        confirm:{
                            text:'aceptar',
                            btnClass: 'btn-blue',
                            action:function(){
                                $.ajax({
                                    url:url,
                                    dataType:'json',
                                    method:'get',
                                    success:function(response){
                                        if(response.status == STATUS_OK)
                                        {
                                            toast('success', 1500, 'Cobro eliminado');
                                            rowCobro();
                                            listaComprobantes();
                                            calcularSaldo(2,monto);
                                        }
                                        if(response.status == STATUS_FAIL)
                                        {
                                            toast('error', 1500 ,'No se puedo eliminar Cobro.');
                                        }
                                    }
                                });
                            }
                        },
                        cancel: function () {                            
                }
            }
       });		
	});

//llamo a esta funcion para refrescar la pagina
function listaComprobantes(){

		$.ajax({

			url: '<?= base_url()?>index.php/cobros/listaComprobantes',
			method: 'POST',
			dataType: 'HTML',
			data: $("#formCobro").serialize(),
			success: function(response){
				$("#listaComprobantes").html(response);
			}

		})
	}
      
	$("#agregarcobro").click(function(){
		$.ajax({

        url: '<?= base_url()?>index.php/cobros/modal_crearr',
			method: 'POST',
			dataType: 'JSON',
			data: $("#formCobro").serialize(),
            success: function(response){
              listaComprobantes();
            }
		});
	});
	
	function calcularSaldo(modo,monto1=0){
			
		var monto =  ($("#monto").val() != '') ? $("#monto").val() : 0.00;

		if(modo == 1){			
				var saldo = $("#saldo").val();
				var saldo = parseFloat(saldo) - parseFloat(monto1);				
			} else{
				var saldo = $("#saldo").val();	
				var saldo = parseFloat(saldo) + parseFloat(monto1);
			}		
		$("#saldo").val(saldo.toFixed(2));				
	}


	//PDF
    $(document).on("click","#pdfCobro",function(){

    	var cliente_id = $("#cliente_id_m").val();   	
    	var vendedor_id = $("#vendedor_id_m").val();
    	var comprobante_id = $("#comprobante_id").val();    	
    	var tipoComprobante = $("#tipoComprobante").val();
    	var serNum = $("#serNum_m").val();
    	var totalCredito = $("#totalCredito_m").val();

        javascript:window.open('<?= base_url()?>index.php/cobros/pdfCobro/'+tipoComprobante+'/'+comprobante_id+'/'+cliente_id+'/'+vendedor_id+'/'+serNum+'/'+totalCredito,'','width=750,height=600,scrollbars=yes,resizable=yes');
    });

    //IMPRIMIR TICKET
    var PrintWindow;
    $(document).on("click","#ticketCobro",function(){
        var cliente_id = $("#cliente_id_m").val();
        var vendedor_id = $("#vendedor_id_m").val();
    	var comprobante_id = $("#comprobante_id").val();
    	var tipoComprobante = $("#tipoComprobante").val();
    	var serNum = $("#serNum_m").val();
    	var totalCredito = $("#totalCredito_m").val();
    	
        PrintWindow = window.open('<?= base_url()?>index.php/cobros/ticketCobropdf/'+tipoComprobante+'/'+comprobante_id+'/'+cliente_id+'/'+vendedor_id+'/'+serNum+'/'+totalCredito,'','width=750,height=600,scrollbars=yes,resizable=yes');
    });
});

</script>





