

  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Endpoint</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="endpoint_id" name="endpoint_id" value="<?php echo $endpoint->id;?>">
       	<div class="row">
       		<div class="col-md-12">       			
            <div class="form-group">
              <label for="endpoint">Descripcion</label>
              <input type="text" id="endpoint" class="form-control input-sm" value="<?php echo $endpoint->endpoint;?>">
            </div>                                    
            <div class="form-group">
              <label for="modo">MODO</label>
              <select id="modo" class="form-control">
                <?PHP foreach($modos as $value){
                  $selected = ($value->modo == $endpoint->modo)? 'SELECTED':'';?>
                <option value="<?= $value->modo;?>" <?= $selected;?>><?= $value->modo;?></option>
                <?PHP }?>
              </select>              
            </div>
            <div class="form-group">
              <label for="activo">Activo</label>
              <select id="activo" class="form-control">
                <?PHP foreach($activos as $value){
                  $selected = ($value->activo == $endpoint->activo)? 'SELECTED':'';?>
                <option value="<?= $value->activo;?>" <?= $selected;?>><?= $value->activo;?></option>
                <?PHP }?>
              </select>              
            </div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_endpoint">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){
  		//guardar
  		$("#btn_guardar_endpoint").click(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');
  			var datos = {
  							id:$("#endpoint_id").val(),  							
                endpoint:$("#endpoint").val(),
                activo: $("#activo").val(),
                modo: $("#modo").val()
  						};
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/endpoints/guardarEndpoint',
  				dataType:'json',
  				data:datos,
  				method:'post',
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
              if(response.tipo == '2')
              {                
                toast('error', 1500, 'Ya existe un enpoint activo.');
                dataSource.read();
                $("#myModal").modal('hide');
              }
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'se registro la Endpoint');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});  					
  		});
  	});
  </script>
