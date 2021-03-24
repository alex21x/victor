  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Categoria</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="categoria_id" value="<?php echo $categoria->cat_id?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="nombre">Nombre</label>
       				<input type="text" id="nombre" class="form-control input-sm" value="<?php echo $categoria->cat_nombre?>">
       			</div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_categoria">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){

  		//guardar
  		$("#btn_guardar_categoria").click(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');
  			var datos = {
  							id:$("#categoria_id").val(),
  							nombre:$("#nombre").val()
  						};
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/categoria/guardarCategoria',
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
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'se registro la categoria');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});
  					
  		});
  	});
  </script>
