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
<div class="container">
	<h3>COMISION VENDEDOR</h3><br>
	<form id="formReporte">
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
		<div class="col-xs-12 col-md-6 col-lg-3"><br>	
				<a id="buscarReporte" href="#" class="btn btn-primary btn-sm colbg">&nbsp;Buscar&nbsp;</a>
				<a id="exportar_repo" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
				<a id="exportar_repo_pdf" href="#" class="btn btn-danger btn-sm colbg">&nbsp;Eportar pdf&nbsp;</a>			
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
				url: '<?= base_url()?>index.php/reportes/reporte_comisionVendedor_bs',
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


        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarComisionVendedor?'+datos+'&vendedorText='+vendedorText;
        window.open(url, '_blank');

    });

		$('#exportar_repo_pdf').click(function() {
        datos = $("#formReporte").serialize();        
        vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';


        var url ='<?PHP echo base_url() ?>index.php/reportes/exportarComisionVendedor_pdf?'+datos+'&vendedorText='+vendedorText;
        window.open(url, '_blank');

    });

});
</script>