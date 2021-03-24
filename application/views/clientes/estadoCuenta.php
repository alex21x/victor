<script type="text/javascript">	
$(document).ready(function(){
	$("#fecha_inicial").datepicker();
	$("#fecha_final").datepicker();


	$("#cliente").autocomplete({
		source: '<?= base_url();?>index.php/clientes/buscador_cliente',
		minLength: 2,
		select : function(event,ui){
			var data_cli =  '<input type="hidden" id="cliente_id" name="cliente_id" value ="'+ui.item.id+'">';
			$("#data_cli").html(data_cli);	
		}
	});
});		
	
</script>
<style type="text/css">
	label{
		width: 100%;
	}
</style>
<div class="container">
	<form id="formReporte">
		<input type="hidden" name="tipo_pago_id" id="tipo_pago_id" value="2">
	<h2>ESTADO DE CUENTA POR CLIENTE</h2>
	<div class="col-xs-4 col-md-4 col-lg-4">
		<label>Cliente
			<input class="form-control" type="text" id="cliente" name="cliente">
			<div id="data_cli"><input type="hidden" id="cliente_id" name="cliente_id"></div>
		</label>
	</div>

	<div class="col-xs-2 col-md-2 col-lg-2">
		<label>Fecha Inicial
			<input class="form-control" type="text" id="fecha_inicial" name="fecha_inicial">
		</label>
	</div>
	<div class="col-xs-2 col-md-2 col-lg-2">
		<label>Fecha Final
			<input class="form-control" type="text" id="fecha_final" name="fecha_final">
		</label>
	</div>
	<div class="col-xs-2 col-md-2 col-lg-2">
		<label><br>
			<button type="button" class="btn btn-primary btn-sm" name="buscarReporte" id="buscarReporte">Buscar</button>
		</label>
	</div>
	<div class="col-xs-2 col-md-2 col-lg-2">
			<label>&nbsp;<br>
				<input type="button" class="btn btn-primary btn-sm" id="btn_limpiar" value="Limpiar">
			</label>
		</div>	
	<div class="col-xs-2 col-md-2 col-lg-2">
			<label>&nbsp;<br>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
			</label>
	</div>
	</form>	
</div><br><br>
<div class="container-fluid">	
	<div id="contenido">

	</div>		
</div>	

<script type="text/javascript">
	$("#buscarReporte").on('click',function(){
		estadoCuenta();
	})	

	function estadoCuenta(){
		$.ajax({
		url: '<?= base_url()?>index.php/cobros/estadoCuenta_g',
		method: 'POST',
		data: $("#formReporte").serialize(),
		dataType: 'HTML',
		success: function(response){
			$("#contenido").html(response);
		}
	});
	}
		
	//estadoCuenta();
	/*button limpiar*/	    
	$(document).on("click","#btn_limpiar",function(){	    	        
	        $("#formReporte")[0].reset();	      
	        $("#cliente_id").val('');	              	       
	        estadoCuenta();
	}); 



	$('#exportar_repo').click(function() {
			
		datos = $("#formReporte").serialize();		
		vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';
			      
        var url ='<?PHP echo base_url() ?>index.php/cobros/exportarExcel_estadoCuenta?'+datos+'&vendedorText='+vendedorText;
        window.open(url, '_blank');
    });
</script>