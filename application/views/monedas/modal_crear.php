<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Monedas</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="id" value="<?php echo $moneda->id;?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="codigo">moneda</label>
       				<input type="text" id="moneda" class="form-control input-sm" value="<?php echo $moneda->moneda;?>">
       			</div>
            <div class="form-group">
              <label for="descripcion">abreviado</label>
              <input type="text" id="abreviado" class="form-control input-sm" value="<?php echo $moneda->abreviado;?>">
            </div>
            <div class="form-group">
              <label for="descripcion">abrstandar</label>
              <input type="text" id="abrstandar" class="form-control input-sm" value="<?php echo $moneda->abrstandar;?>">
            </div>
            <div class="form-group">
              <label for="descripcion">simbolo</label>
              <input type="text" id="simbolo" class="form-control input-sm" value="<?php echo $moneda->simbolo;?>">
            </div>

       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_monedas">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

  <script type="text/javascript">
    
$(document).ready(function(e){

      //guardar
      $("#btn_guardar_monedas").click(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');
        var datos = {
                 id:$("#id").val(),
                moneda:$("#moneda").val(),
                abreviado:$("#abreviado").val(),
                 abrstandar:$("#abrstandar").val(),
                  simbolo:$("#simbolo").val()
              };
        $.ajax({
          url:'<?php echo base_url()?>index.php/monedas/guardar',
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
              toast('success', 1500, 'se registro el registro moneda');
              dataSource.read();
              $("#myModal").modal('hide');
            }
          }
        });           
      });
    });  
  </script>