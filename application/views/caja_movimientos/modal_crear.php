<link rel="stylesheet" type="text/css" href="<?PHP echo base_url() ?>assets/css/jquery.datetimepicker.css"/>
<script src="<?PHP echo base_url() ?>assets/js/jquery.datetimepicker.js"></script>

<?=

	$caja_movimientos->fecha = date('d-m-Y H:i:s');

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
	
	.row{
		margin-top: 20px;
	}
	.session{
		margin-top: -20px;
		text-align: left;
		font-size: 12px;
	}
	h3{
		text-align: center;
		font-weight: bold;
	}
</style>

<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-title">
					<div class="col-md-9">
						<h3>MOVIMIENTOS DE CAJA</h3>
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
         	<div class="col-md-9">
			<form id="formCajaMov">	
			<input type="hidden" id="caja_movimientos_id" name="caja_movimientos_id" value="<?php echo $caja_movimientos->id ?>">         	

			<div class="row">
			<div class="col-md-3">
        		<label>Tipo Movimiento</label>
         	</div>		
			<div class="col-md-9">
				
				<select class="form-control" name="tipo_cMovimiento" name="tipo_cMovimiento">
				<?PHP foreach($tipo_cMovimiento as $value){
					$selected = ($value->id == $caja_movimientos->tipo_movimiento_id) ? 'SELECTED' : '';
					?>
					<option value="<?= $value->id?>" <?= $selected;?>><?= $value->tipo_cMovimiento;?></option>
					
				<?PHP }?>	
				</select>

			</div>
			</div>	


			<div class="row">
			<div class="col-md-3">
        		<label>Monto</label>
         	</div>		
			<div class="col-md-9">
				<input type="text" class="form-control" id="monto" value= "<?php echo $caja_movimientos->monto ?>" name="monto">
			</div>
			</div>	

			<div class="row">
			<div class="col-md-3">
        		<label>Observaciones</label>
         	</div>		
			<div class="col-md-9">
				<textarea class="form-control" id="observaciones" name="observaciones"><?php echo $caja_movimientos->observaciones; ?></textarea>
			</div>
			</div>
			<div class="row">
			<div class="col-md-3">	
			 <label>Fecha</label>	
            </div>
            <div class="col-md-9">            	
            	<input type="text" class="form-control" id="fecha" name="fecha" value="<?php echo $caja_movimientos->fecha; ?>">
            </div>	
			</div>

			<div class="row">
				<input type="button" id="guardarCajMov" value="guardar"  class="btn btn-success btn-block">
			</div>					 
			</form>
			</div>
			<div class="col-md-3"></div>
  			</div>
  			<div class="modal-footer">
       		</div>
		</div>
	</div>

	<script type="text/javascript">

	function selectRowsCajamoviento(){
	    $.ajax({
	        url: '<?= base_url()?>index.php/movimiento_caja_controlador/selectRowsCajamoviento',
	        method: 'POST',
	        dataType: 'HTML',
	        success: function(response){
	            $("#rowCajaMov").html(response);
	        }
	    });
  	}

		$("#guardarCajMov").on("click",function(){

			$.ajax({
				url: '<?= base_url()?>index.php/movimiento_caja_controlador/guardar',
				dataType: 'JSON',
				method: 'POST',
				data: $("#formCajaMov").serialize(),
				success: function(response){
					if(response.status == 2){						
						selectRowsCajamoviento();
						$("#modalCajaMov").modal('hide');
						
					}
				}

			});
		})
	</script>