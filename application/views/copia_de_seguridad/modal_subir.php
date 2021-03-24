
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Subir Copia Seguridad</h4>
      </div>
      <div class="modal-body">
       <form id="formBackup">
       	<input type="hidden" id="id" name="id" value="<?php echo $turno->id;?>">
       	<div class="row">
       		<div class="col-md-12">       			
            <div class="form-group">
              <label for="backup">Subir Copia</label>
              <input type="file" id="backup" name="backup" class="form-control input-sm">
            </div>                                    
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_backup">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){
  		//guardar

      //guardar
      $("#btn_guardar_backup").on('click',function(){
        $("#formBackup").submit();
      });

      $("#formBackup").on('submit',function(e){          		
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/copia_respaldos/subir_copia_g',
  				dataType:'json',
  				method:'POST',
          data:new FormData(this),
          cache: false,
          contentType:false,
          processData:false,
  				success:function(response){
  					if(response.status == STATUS_FAIL)
  					{
  						if(response.tipo == '1')
  						{
  							var errores = response.errores;
  							toast('error', 1500, 'Faltan ingresar datos.');
  							$.each(errores, function(index, value){
  								$("#"+index).parent().addClass('has-error');
  							});
  						}
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'se registro backup');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});  					
  		});
  	});
  </script>
