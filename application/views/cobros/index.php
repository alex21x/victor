<style type="text/css">	

	label{
		width: 100%;
	}

</style>

<br><br><br>
<div class="container">
	<form id="formComprobante">
		<input type="hidden" name="tipo_pago_id" id="tipo_pago_id" value="2">
	<div class="col-xs-5 col-md-5 col-lg-5">
		<label>Cliente
			<input type="text" class="form-control" name="cliente" id="cliente">
			<div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id"></div>
		</label>
	</div>	
	<div class="col-xs-3 col-md-3 col-lg-3">
		<label>Estado
		<select class="form-control" id="estado" name="estado">
			<option value="">Seleccionar</option>
			<option value="0">Cancelado</option>
			<option value="1" selected="">Pendiente</option>			
		</select>
	</div>

	<div class="col-xs-12 col-md-6 col-lg-3">
			<label>Vendedor
				<select class="form-control" name="vendedor" id="vendedor">
					<option value="">Seleccionar</option>
				<?PHP foreach($vendedores as $empleado){?>			
					<option value="<?php echo $empleado['id']?>"><?php echo $empleado['apellido_paterno']." ".$empleado['apellido_materno'].", ".$empleado['nombre'] ?></option>
				<?PHP }?>
				</select>	
			</label>
		</div>	
	<div class="col-xs-3 col-md-1 col-lg-1">
		<label><br>
			<button id="btn_buscarComprobante" type="button" class="btn btn-primary btn-sm">Buscar</button>
		</label>
	</div>
	<div class="col-xs-6 col-md-2 col-lg-1">
			<label>&nbsp;<br>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
			</label>
	</div>
</form>
</div>	
<br><br>
<div class="container-fluid">
	<div id = "listaComprobantes">
	</div>
</div>


<script type="text/javascript">

$(document).ready(function(){
	$("#cliente").autocomplete({

		source: '<?= base_url()?>index.php/clientes/buscador_cliente',
		minLength: 2,
		select: function(event,ui){
				data_cli = '<input type="hidden" name="cliente_id" id="cliente_id" value="'+ ui.item.id+'">';
				$("#data_cli").html(data_cli);
		}
	});


	$(document).on("click",".btn_regCobro",function(){
		var comprobante_id =  $(this).data("id");
		var cliente_id =  $(this).data("cliente");
		var vendedor_id =  $(this).data("vendedor");
		var moneda=  $(this).data("moneda");
		var saldo =  $(this).data("saldo");
		var tipoComprobante =  $(this).data("tipocomprobante");
		var serNum =  $(this).data("sernum");
		var totalCredito =  $(this).data("totalcredito");		


		//alert(tipoComprobante);
		$("#myModal").load("<?= base_url()?>index.php/cobros/modal_crear/",{comprobante_id: comprobante_id,cliente_id: cliente_id,vendedor_id: vendedor_id,moneda: moneda,saldo: saldo,tipoComprobante:tipoComprobante,serNum:serNum,totalCredito:totalCredito});
	});

	function listaComprobantes(){

		$.ajax({

			url: '<?= base_url()?>index.php/cobros/listaComprobantes',
			method: 'POST',
			dataType: 'HTML',
			data: $("#formComprobante").serialize(),
			success: function(response){
				$("#listaComprobantes").html(response);
			}
		})
	}

	listaComprobantes();


	$("#btn_buscarComprobante").click(function(){
		listaComprobantes();
	});


	$('#exportar_repo').click(function() {
			
		datos = $("#formComprobante").serialize();		
		vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';
			      
        var url ='<?PHP echo base_url() ?>index.php/cobros/exportarExcel?'+datos+'&vendedorText='+vendedorText;
        window.open(url, '_blank');
    });
});
</script>
