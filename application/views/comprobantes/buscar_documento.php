

<style type="text/css">

#tabla{
	margin-bottom: 220px;
}

@media (min-width:0px) {
       .btn_buscar,h4 {
       	margin-top: 20px;
		font-size: 10px;		
    	}
		.radio-inline{
		font-weight: bold;
		padding-left: 22px;
		}
}
@media (min-width: 768px) {
        .btn_buscar,h4 {
       	margin-top: 20px;
		font-size: 18px;
	    }
	    .radio-inline{
		font-weight: bold;
		padding-left: 15px;
		}
}
@media (min-width: 992px) {
        .btn_buscar,h4 {
       	margin-top: 20px;
		font-size: 18px;
    	}
     	.radio-inline{
		font-weight: bold;
		padding-left: 80px;
		}
}
@media (min-width: 1200px) {
      .btn_buscar,h4 {
       	margin-top: 20px;
		font-size: 18px;
    	}
     .radio-inline{
		font-weight: bold;
		padding-left: 80px;
		}
}
@media (min-width: 1300px) {
      .btn_buscar,h4 {
       	margin-top: 20px;
		font-size: 18px;
    	}
      .radio-inline{
		font-weight: bold;
		padding-left: 80px;
		}
}
</style>

<div class="container"><br><br>	 
	<form id="buscarDocumento">
		<div class="row">
	<div class="col-xs-12 col-md-12 col-lg-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title"><h4><b>TIPO DOCUMENTO<b></h4></div>
			</div>
			<div class="panel-body">
				<div class="row btn_buscar">
					<label class="radio-inline">
				<input type="radio" name="tipo_documento_id" value="<?= ST_NOTA_PEDIDO?>" checked>NOTA VENTA
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_documento_id" value="<?= ST_PROFORMA?>">PROFORMA
				</label>
				<label class="radio-inline">
					<input type="radio" name="tipo_documento_id" value="<?= ST_COMPROBANTE?>">COMPROBANTE
				</label>
				</div><br>
			</div>	
		
		</div><br><br>
	</div></div>		
	<div class="row btn_buscar">
	<div class="col-xs-6 col-md-3 col-lg-3">
		<label>NUMERO DOCUMENTO
			<input type="text" class="form-control" name="nDocumento" id="nDocumento">
		</label>
	</div>

	<div class="col-xs-6 col-md-3 col-lg-3"><br>
		<button id="btnBuscarDocumento" type="button" class="btn btn-primary"> BUSCAR</button>
	</div>		
	</div>
	</form><br><br>
</div>
<div class="container-fluid">
	<div class="col-xs-12 col-md-12 col-lg-12 btn_buscar">
	<div id="tabla">
	</div></div>
</div>


<script type="text/javascript">

	$("#btnBuscarDocumento").on("click",function(){

		$.ajax({
		url: '<?= base_url()?>index.php/comprobantes/buscar_documento_s',
		method: 'POST',
		type: 'HTML',
		data: $("#buscarDocumento").serialize(),
		success: function(response){
			$("#tabla").html(response);
		} 
	})

	});

	

</script>