<style type="text/css">
	
	.row{
		padding-top: 20px;
	}
</style>


<script type="text/javascript">
	$(document).ready(function(){
		$("#fecha_de_emision").datepicker();
	});	
</script>



<div class="container">

	<h2>CONSULTA DE COMPROBANTES SUNAT</h2>
  
      <form id="formCajaMov">
	<div class="row">
	<div class="col-xs-6 col-md-6 col-lg-6">
		<label>RUC
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="numRuc" id="numRuc">
		</label>
	</div>

	<div class="col-xs-6 col-md-6 col-lg-6">
		<label>RUC
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="codComp" id="codComp">
		</label>
	</div>	
	</div>

	<div class="row">
	<div class="col-xs-6 col-md-6 col-lg-6">
		<label>SERIE
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="serie" id="serie">
		</label>
	</div>
	<div class="col-xs-6 col-md-6 col-lg-6">
		<label>NUMERO
			<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="numero" id="numero">
		</label>
	</div>
	</div>
	<div class="row">
		<div class="col-xs-6 col-md-6 col-lg-6">
			<label>FECHA
				<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="fecha_de_emision" id="fecha_de_emision">
			</label>
		</div>
		<div class="col-xs-6 col-md-6 col-lg-6">
			<label>MONTO
				<input type="text" class="form-control input-lg col-xs-12 col-md-12 col-lg-12" name="monto" id="monto">
			</label>
		</div>

	</div>	
	<div class="row">
		<button type="button" id="btnBuscar" class="btn btn-primary btn-block">CONSULTAR COMPROBANTE</button>
	</div>
</form>
</div>


<script type="text/javascript">
	


	$("#btnBuscar").on("click",function(){

			$.ajax({
				url: '<?= base_url()?>index.php/comprobantes/consulta_sunat',
				dataType: 'JSON',
				method: 'POST',
				data: $("#formCajaMov").serialize(),
				success: function(response){
					if(response.status == 2){						
						
					}
				}

			});
		})
</script>	