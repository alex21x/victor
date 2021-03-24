 
 <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Tipos Pagos</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="id" value="<?php echo $tipo_pago->id;?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="codigo">tipo Pago</label>
       				<input type="text" id="tipo_pago" class="form-control input-sm" value="<?php echo $tipo_pago->tipo_pago;?>">
       			</div>
            <div class="form-group">
              <label for="descripcion">Comentario</label>
              <input type="text" id="comentario" class="form-control input-sm" value="<?php echo $tipo_pago->comentario;?>">
            </div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_pago">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

<script type="text/javascript">
$(document).ready(function(e){

      //guardar
      $("#btn_guardar_pago").click(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');
        var datos = {
                 id:$("#id").val(),
                tipo_pago:$("#tipo_pago").val(),
                comentario:$("#comentario").val()
              };
        $.ajax({
          url:'<?php echo base_url()?>index.php/tipo_pagos/guardar',
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
              toast('success', 1500, 'se registro el tipo pedido');
              dataSource.read();
              $("#myModal").modal('hide');
            }
          }
        });           
      });
    });  


</script>
