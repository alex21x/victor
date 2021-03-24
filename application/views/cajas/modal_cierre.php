
<style type="text/css">
	
	.row{
		margin-top: 5px;
	}
	h3{
		margin-bottom: 15px;
		margin-left: 10px;
		font-weight: bold;
	}

	h2{
		text-align: center;
		font-weight: bold;
	}
	.session{
		margin-top: -20px;
		text-align: left;
		font-size: 12px;
	}

</style>


<?PHP
	$movCajIngreso = '0.00';
	$movCajSalida  = '0.00';

	$totalContado  = '0.00';
	$totalDeposito = '0.00';
	$totalCheque   = '0.00';
	$totalTarjeta  = '0.00';
	$totalCupon    = '0.00';	
	$totalCredito  = '0.00';

	$totalCambioCT = '0.00';
	$totalCambioNT = '0.00';

	$cobroTotalContado  = '0.00';
	$cobroTotalDeposito = '0.00';
	$cobroTotalCheque   = '0.00';
	$cobroTotalTarjeta  = '0.00';
	$cobroTotalCupon    = '0.00';  
	$cobroTotalCredito  = '0.00';

	//TOTAL CAMBIOS 10-02-2021 - ALEXANDER FERNANDEZ
	if($selectCambio_ct){
		$totalCambioCT =  $selectCambio_ct;
	}
	if($selectCambio_np){
		$totalCambioNT =  $selectCambio_np;
	}
	
	if($cajaMov['Ingreso']){
		$movCajIngreso =  $cajaMov['Ingreso'];
	}
	if($cajaMov['Salida']){
		$movCajSalida = $cajaMov['Salida'];
	}	
	if($selecReporteCaja['Efectivo']){
		$totalContado = $selecReporteCaja['Efectivo'] - $totalCambioCT - $totalCambioNT;
	}
	if($selecReporteCaja['Deposito']){
		$totalDeposito = $selecReporteCaja['Deposito'];
	}
	if($selecReporteCaja['Cheque']){
		$totalCheque   = $selecReporteCaja['Cheque'];
	} 
    if($selecReporteCaja['Tarjeta']){
		$totalTarjeta = $selecReporteCaja['Tarjeta'];
	}
  
      if($selecReporteCaja['Cupon']){
		$totalCupon = $selecReporteCaja['Cupon'];
	}
   	if($selecReporteCaja['Crédito']){
		$totalCredito= $selecReporteCaja['Crédito'];
	}	
	
	//echo $totalContado;exit;


	//COBROS ALEXANDER FERNANDEZ 21-10-2020
	if($selecReporteCobro['Efectivo']){
		$cobroTotalContado = $selecReporteCobro['Efectivo'];
	}
	if($selecReporteCobro['Deposito']){
		$cobroTotalDeposito = $selecReporteCobro['Deposito'];
	}
	if($selecReporteCobro['Cheque']){
		$cobroTotalCheque   = $selecReporteCobro['Cheque'];
	} 
    if($selecReporteCobro['Tarjeta']){
		$cobroTotalTarjeta = $selecReporteCobro['Tarjeta'];
	}  
    if($selecReporteCobro['Cupon']){
		$cobroTotalCupon = $selecReporteCobro['Cupon'];
	}   	

?>

<div class="modal-dialog modal-lg">
	<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<div class="modal-title">
		<div class="col-md-9">
				<h2>CIERRE DE CAJA</h2>
			</div>
			<div class="col-md-3">
			<div class="session">	
			<?PHP
                $nombre = (strpos($this->session->userdata('usuario'), ' ') != '')?substr($this->session->userdata('usuario'), 0,  strpos($this->session->userdata('usuario'), ' ')):$this->session->userdata('usuario');
                                    ?>
                <li><?PHP echo $nombre.' '.$this->session->userdata('apellido_paterno'); ?><?PHP echo "<br>".$this->session->userdata('almacen_nom'); ?>&nbsp;&nbsp;&nbsp;&nbsp;</li>
        	</div>      
        	</div>
        </div>	
	</div>

	<div class="modal-body">
		<form id="formCierreCaja">
			<input type="hidden" name="caja_id" id="caja_id" value="<?= $caja->id?>">			
				<div class="row">
				<div class="col-md-3">
					<label> Fecha de Apertura</label>

				</div>
				<div class="col-md-6">
					<input type="text" class="form-control" name="fecha_apertura" id="fecha_apertura" value="<?= $caja->fechaApertura?>" readonly>					
				</div>
				</div>
				<div class="row">					
					<h3>Ventas</h3>				
					<div class="col-md-3">
						<label> Efectivo</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalContado" id="totalContado" value="<?= $totalContado?>" readonly>					
					</div>
				</div>		
				<div class="row">
					<div class="col-md-3">
						<label> Deposito</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalDeposito" id="totalDeposito" value="<?= $totalDeposito;?>" readonly>					
					</div>
				</div>	
				  <div class="row">
					<div class="col-md-3">
						<label> Cheque</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalCheque" id="totalCheque" value="<?= $totalCheque?>" readonly>					
					</div>
				</div>	

				 <div class="row">
					<div class="col-md-3">
						<label> Tarjeta</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalTarjeta" id="totalTarjeta" value="<?= $totalTarjeta?>" readonly>					
					</div>
				</div>					
				<div class="row">
					<div class="col-md-3">
						<label> Cupon</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalCupon" id="totalCupon" value="<?= $totalCupon?>" readonly>					
					</div>
				</div>	
				<div class="row">
					<div class="col-md-3">
						<label> Credito</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalCredito" id="totalCredito" value="<?= $totalCredito?>" readonly>					
					</div>
				</div>	
				<div class="row">					
						<h3>Total</h3>					
					<div class="col-md-3">
						<label> Total Venta</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="montoTotal" id="montoTotal" value="<?= $caja->ingreso?>" readonly>					
					</div>					
				</div>
				<!-- COBROS ALEXANDER FERNANDEZ 21-10-2020  ----->
				<div class="row">					
					<h3>Cobros</h3>				
					<div class="col-md-3">
						<label> Efectivo</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="cobroTotalContado" id="cobroTotalContado" value="<?= $cobroTotalContado?>" readonly>					
					</div>
				</div>		
				<div class="row">
					<div class="col-md-3">
						<label> Deposito</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="cobroTotalDeposito" id="cobroTotalDeposito" value="<?= $cobroTotalDeposito;?>" readonly>					
					</div>
				</div>	
				  <div class="row">
					<div class="col-md-3">
						<label> Cheque</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="cobroTotalCheque" id="cobroTotalCheque" value="<?= $cobroTotalCheque?>" readonly>					
					</div>
				</div>	
				 <div class="row">
					<div class="col-md-3">
						<label> Tarjeta</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="cobroTotalTarjeta" id="cobroTotalTarjeta" value="<?= $cobroTotalTarjeta?>" readonly>					
					</div>
				</div>					
				<div class="row">
					<div class="col-md-3">
						<label> Cupon</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="cobroTotalCupon" id="cobroTotalCupon" value="<?= $cobroTotalCupon?>" readonly>
					</div>
				</div>	
				<div class="row">					
						<h3>Total</h3>					
					<div class="col-md-3">
						<label> Total Cobro</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="montoTotalCobro" id="montoTotalCobro" readonly>					
					</div>					
				</div>		
				<!-- COBROS ALEXANDER FERNANDEZ 21-10-2020  ----->
				<div class="row">
					<h3>Mov. de Caja</h3>
				<div class="col-md-3">
					<label> Monto Inicial</label>

				</div>
				<div class="col-md-6">
					<input type="text" class="form-control" name="montoInicial" id="montoInicial" value="<?= $caja->saldo_inicial?>" readonly>
					
				</div>

				</div>

				<div class="row">				
					<div class="col-md-3">
						<label> Ventas Efectivo</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="totalContado" id="totalContado" value="<?= $totalContado?>" readonly>					
					</div>

				</div>


				<div class="row">																			
					<div class="col-md-3">
						<label> Ingresos</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="movCajIngreso" id="movCajIngreso" value="<?= $movCajIngreso?>" readonly>					
					</div>
				</div>		

				<div class="row">					
					<div class="col-md-3">
						<label> Egresos</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="movCajSalida" id="movCajSalida" value="<?= $movCajSalida?>" readonly>					
					</div>
				</div>		
				<div class="row">					
						<h3>Total Efectivo</h3>					
					<div class="col-md-3">
						<label> Total</label>
					</div>
					<div class="col-md-6">
						<input type="text" class="form-control" name="montoTotalEfectivo" id="montoTotalEfectivo" value="<?= $caja->ingreso?>" readonly>					
					</div>					
				</div>				
				<div class="row">
					<div class="col-md-12">
						<button type="button" id="cerrarCaja" class="btn btn-danger btn-block">CERRA CAJA</button>
					</div>	
				</div>
			</form>
	</div>
	<div class="modal-footer">		
	</div>	
</div>
</div>




<script type="text/javascript">


	function calcular(){
		var montoInicial = $("#montoInicial").val();		
		var movCajIngreso = $("#movCajIngreso").val();
		var movCajSalida  = $("#movCajSalida").val();
		var totalContado  = $("#totalContado").val();
		var totalDeposito = $("#totalDeposito").val();
		var totalCheque   = $("#totalCheque").val();
		var totalTarjeta  = $("#totalTarjeta").val();
		var totalCupon    = $("#totalCupon").val();		
		var totalCredito  = $("#totalCredito").val();

		//COBROS ALEXANDER FERNANDEZ 21-10-2020
		var cobroTotalContado  = $("#cobroTotalContado").val();
		var cobroTotalDeposito = $("#cobroTotalDeposito").val();
		var cobroTotalCheque   = $("#cobroTotalCheque").val();
		var cobroTotalTarjeta  = $("#cobroTotalTarjeta").val();
		var cobroTotalCupon    = $("#cobroTotalCupon").val();				

		var montoTotalEfectivo =  parseFloat(montoInicial) + parseFloat(movCajIngreso) - parseFloat(movCajSalida) + parseFloat(totalContado) + parseFloat(cobroTotalContado);
		$("#montoTotalEfectivo").val(montoTotalEfectivo);

		var montoTotal =  parseFloat(totalContado)+parseFloat(totalDeposito)+parseFloat(totalCheque)+parseFloat(totalTarjeta)+parseFloat(totalCupon)+parseFloat(totalCredito);
		var montoTotalCobro = parseFloat(cobroTotalContado)+parseFloat(cobroTotalDeposito)+parseFloat(cobroTotalCheque)+parseFloat(cobroTotalTarjeta)+parseFloat(cobroTotalCupon);

		$("#montoTotal").val(montoTotal);
		$("#montoTotalCobro").val(montoTotalCobro);

	}

	calcular();
	function selectRowsCajamoviento(){
	    $.ajax({
	        url: '<?= base_url()?>index.php/cajas/selectRowsCajamoviento',
	        method: 'POST',
	        dataType: 'HTML',
	        success: function(response){
	            $("#rowCajaMov").html(response);
	            $("#tablaCaja").DataTable({
                "lengthMenu": [[30, 50, -1], [30, 50, "All"]],
           		 });	            
	        }
	    });
  	}	


	$("#cerrarCaja").click(function(){

	var msg = 'Esta seguro de cerrar la caja?';
	$.confirm({
		title: 'Confirmar',
		content: msg,
		buttons: {
		confirm:{
		text:'aceptar',
		btnClass: 'btn-blue',
		action: function(){	
			$.ajax({
			url: '<?= base_url()?>index.php/cajas/guardarCierre',
			method: 'POST',
			dataType: 'JSON',
			data: $("#formCierreCaja").serialize(),
			success: function(response){
				if (response.status==2){
       				selectRowsCajamoviento();
					$("#modalCajaMovCierre").modal('hide');
					$(".btn_cerrar_cajaMov").removeClass().addClass("btn_agregar_cajaMov btn btn-primary");
					$("a.btn_agregar_cajaMov").attr("data-target","#modalCajaMov").removeAttr("data-id").text("Apertura");
				}}
			});
		}
		},
			cancel: function(){
			}
		}
	});
	});

</script>