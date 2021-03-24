  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registro Proveedor</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="prov_id" value="<?php echo $proveedor->prov_id?>">
       	<div class="row">
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="codigo">RUC</label>
       				<input type="number" id="ruc" class="form-control input-sm" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="11" value="<?php echo $proveedor->prov_ruc?>">
       			</div>
       		</div>
       		<div class="col-md-8">
       			<div class="form-group">
       				<label for="nombre">Razón Social</label>
       				<input type="text" id="razon_social" class="form-control input-sm" value="<?php echo $proveedor->prov_razon_social?>">
       			</div>
       		</div>
       	</div>
       	<div class="row">
       		<div class="col-md-8">
       			<div class="form-group">
       				<label for="direccion">Dirección</label>
       				<input type="text" id="direccion" class="form-control input-sm" value="<?php echo $proveedor->prov_direccion?>">
       			</div>
       		</div>
       		<div class="col-md-4">
       			<div class="form-group">
       				<label for="telefono">Teléfono</label>
       				<input type="number" id="telefono" class="form-control input-sm" value="<?php echo $proveedor->prov_celular?>" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9">
       			</div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_proveedor">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){

       

  		//guardar
  		$("#btn_guardar_proveedor").click(function(e){
  			e.preventDefault();
  			$(".has-error.has-feedback").removeClass('has-error has-feedback');
  			var datos = {
  							id:$("#prov_id").val(),
  							ruc:$("#ruc").val(),
  							razon_social:$("#razon_social").val(),
  							direccion:$("#direccion").val(),
  							telefono:$("#telefono").val()
  						};
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/proveedores/guardarProveedor',
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
  								$("#"+index).parent().addClass('has-error has-feedback');
  							});
  						}
  						if(response.tipo == '2')
  						{
  							toast('error', 1500, 'El ruc ya està en uso');
  						}
  					}
  					if(response.status == STATUS_OK)
  					{
  						toast('success', 1500, 'Proveedor ingresado');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});
  					
  		});
  	});
  </script>
