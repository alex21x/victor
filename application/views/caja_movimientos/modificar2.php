

<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<div class="modal-title">MODIFICAR</div>
			</div>
         <div class="modal-body">
<form id="formCajaMov">
	<input type="hidden" id="caja_movimientos_id" name="caja_movimientos_id" value="<?php echo $caja_movimientos->id ?>">
         	<!--<div class="col-md-3">
         		<h1>Editar</h1>
         	</div>		
			<div class="col-md-6">
			</div>	-->
			<div class="col-md-6">
        		<label>tipo_movimiento_id</label>
         	</div>		
			<div class="col-md-6">
				<input type="text" id="tipo_movimiento_id" value= "<?php echo $caja_movimientos->tipo_movimiento_id ?>" name="tipo_movimiento_id">
			</div>	



			<div class="col-md-3">
         		<label>empleado_id</label>    
         	</div>		
			<div class="col-md-6">
				 <input type ="text" class="form-control" id="empleado_id" value="<?php echo $caja_movimientos->empleado_id?>" name="empleado_id" maxlength="40">
			</div>	<br><br>

			<div class="col-md-6">
         		<label>estado</label>
         	</div>		
			<div class="col-md-6">
				<input type="text" class="form-control" id="estado" value="<?php echo $caja_movimientos->estado?>"name="estado" >
			</div>	
	
			
			<input type="button" id="modificarCajMov" value="guardar"  class="btn btn-success ">
		   

</form>
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


		$("#modificarCajMov").on("click",function(){

			$.ajax({
				url: '<?= base_url()?>index.php/movimiento_caja_controlador/modificar2',
				dataType: 'JSON',
				method: 'POST',
				data: $("#formCajaMov").serialize(),
				success: function(response){
					if(response.status == 2){
						selectRowsCajamoviento();
						$("#modalmodificar").hide();
					}
				}

			})



		})
		




	</script>