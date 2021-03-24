<style type="text/css">
	
	label{
		width: 100%;
	}
</style>

<br><br>



<script type="text/javascript">
	
	$(document).ready(function(){
		$("#fecha_desde").datepicker();
		$("#fecha_hasta").datepicker();	
	});
	

</script>

<div class="container-fluid">
<h3>TOTAL VENTAS</h3><br>
	<form id="formReporte">
	<div class="col-xs-3 col-md-3 col-lg-3"></div>
	<div class="col-xs-8 col-md-8 col-lg-8">
		
		<div class="row">
		<div class="col-xs-6 col-md-3 col-lg-3">
			<label>Fecha Desde
				<input class="form-control" name="fecha_desde" id="fecha_desde">
			</label>
		</div>	
		<div class="col-xs-6 col-md-3 col-lg-3">
			<label>Fecha Hasta
				<input class="form-control" name="fecha_hasta" id="fecha_hasta">
			</label>
		</div>	
		<div class="col-xs-12 col-md-6 col-lg-2">
			<label>Vendedor
				<select class="form-control" name="vendedor" id="vendedor">
					<option value="">Seleccionar</option>
				<?PHP foreach($vendedores as $empleado){?>			
					<option value="<?php echo $empleado['id']?>"><?php echo $empleado['apellido_paterno']." ".$empleado['apellido_materno'].", ".$empleado['nombre'] ?></option>
				<?PHP }?>
				</select>	
			</label>
		</div>	
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12 col-lg-2">
			<label>Transportista 1
				<select class="form-control transportista" name="transportista[]" id="transportista_1">
					<option value="">Seleccionar</option>
					<?PHP foreach($transportistas as $value){?>
					<option value="<?= $value->transp_id?>"><?= $value->transp_nombre?></option>
					<?PHP }?>
				</select>	
			</label>
		</div>	

		<div class="col-xs-12 col-md-12 col-lg-2">
			<label>Transportista 2
				<select class="form-control transportista" name="transportista[]" id="transportista_2">
					<option value="">Seleccionar</option>
					<?PHP foreach($transportistas as $value){?>
					<option value="<?= $value->transp_id?>"><?= $value->transp_nombre?></option>
					<?PHP }?>
				</select>	
			</label>
		</div>	

		<div class="col-xs-12 col-md-12 col-lg-2">
			<label>Transportista 3
				<select class="form-control transportista" name="transportista[]" id="transportista_3">
					<option value="">Seleccionar</option>
					<?PHP foreach($transportistas as $value){?>
					<option value="<?= $value->transp_id?>"><?= $value->transp_nombre?></option>
					<?PHP }?>
				</select>	
			</label>
		</div>	
		<div class="col-xs-12 col-md-12 col-lg-2">
			<label>Transportista 4
				<select class="form-control transportista" name="transportista[]" id="transportista_4">
					<option value="">Seleccionar</option>
					<?PHP foreach($transportistas as $value){?>
					<option value="<?= $value->transp_id?>"><?= $value->transp_nombre?></option>
					<?PHP }?>
				</select>	
			</label>
		</div>	
		<div class="col-xs-12 col-md-6 col-lg-6">
				<a id="buscarReporte" type="button" class="btn btn-primary btn-sm colbg">Buscar</a>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>			
				<a id="exportar_repo_pdf" href="#" class="btn btn-danger btn-sm colbg">Eportar pdf</a>			
		</div>
	</div>				
	</div>
	
	</form>
</div>
<div class="container-fluid"> 
	<br><br>
	<div id="contenido"></div>
</div>


<script type="text/javascript">
	
	$(document).ready(function(){


		function buscarReporte(){
			$.ajax({
				url: '<?= base_url()?>index.php/reportes/reporte_totalVentas_bs',
				dataType: 'HTML',
				method: 'POST',
				data: $("#formReporte").serialize(),
				success: function(response){
					$("#contenido").html(response);
				}
			});
		}
		buscarReporte();


		$(document).on("click","#buscarReporte",function(){
			buscarReporte();
		});


		$('#exportar_repo').click(function() {
			
		datos = $("#formReporte").serialize();		
		vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';

		transportista_1 = ($("#transportista_1 option:selected").val() != '') ? $("#transportista_1 option:selected").text() : '';
		transportista_2 = ($("#transportista_2 option:selected").val() != '') ? $("#transportista_2 option:selected").text() : '';
		transportista_3 = ($("#transportista_3 option:selected").val() != '') ? $("#transportista_3 option:selected").text() : '';
		transportista_4 = ($("#transportista_4 option:selected").val() != '') ? $("#transportista_4 option:selected").text() : '';

		transportistaText = transportista_1 +'_'+transportista_2+'_'+transportista_3+'_'+transportista_4;
        
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarTotalVentas?'+datos+'&vendedorText='+vendedorText+'&transportistaText='+transportistaText;
        window.open(url, '_blank');

    });

		$('#exportar_repo_pdf').click(function() {
			
		datos = $("#formReporte").serialize();		
		vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';

		transportista_1 = ($("#transportista_1 option:selected").val() != '') ? $("#transportista_1 option:selected").text() : '';
		transportista_2 = ($("#transportista_2 option:selected").val() != '') ? $("#transportista_2 option:selected").text() : '';
		transportista_3 = ($("#transportista_3 option:selected").val() != '') ? $("#transportista_3 option:selected").text() : '';
		transportista_4 = ($("#transportista_4 option:selected").val() != '') ? $("#transportista_4 option:selected").text() : '';

		transportistaText = transportista_1 +'_'+transportista_2+'_'+transportista_3+'_'+transportista_4;
        
        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarTotalVentas_pdf?'+datos+'&vendedorText='+vendedorText+'&transportistaText='+transportistaText;
        window.open(url, '_blank');

    });

});
</script>