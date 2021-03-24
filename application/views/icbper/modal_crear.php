
<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar ICBPER</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="icbPer_id" value="<?php echo $icbPer->icbPer_id;?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="icbPer_nombre">icbPer_nombre</label>
       				<input type="text" id="icbPer_nombre" class="form-control input-sm" value="<?php echo $icbPer->icbPer_nombre;?>">
       			</div>
            <div class="form-group">
              <label for="icbPer_valor">icbPer_valor</label>
              <input type="text" id="icbPer_valor" class="form-control input-sm" value="<?php echo $icbPer->icbPer_valor;?>">
            </div>

             <div class="form-group">
              <label for="icbPer_fecha">icbPer_fecha</label>
              <input type="date" id="icbPer_fecha" class="form-control input-sm" value="<?php echo $icbPer->icbPer_fecha;?>">
            </div>
             <div class="form-group">
              <label for="icbPer_fecha">icbPer_activo</label>
              <select class="form-control" id="icbPer_activo" name="icbPer_activo">
                  <?PHP foreach($activos as $value) {
                        $selected = ($value->activo == $icbPer->icbPer_activo) ? 'SELECTED' : '';?>
                    <option <?= $selected;?> value="<?= $value->activo;?>"><?= $value->activo;?></option>
                  <?PHP }?>
              </select>             
            </div>
       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_icberp">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->


  <script type="text/javascript">
    $(document).ready(function(e){

      //guardar
      $("#btn_guardar_icberp").click(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');
        var datos = {
                icbPer_id:$("#icbPer_id").val(),
                icbPer_nombre:$("#icbPer_nombre").val(),
                icbPer_valor:$("#icbPer_valor").val(),
                icbPer_fecha:$("#icbPer_fecha").val(),
                icbPer_activo:$("#icbPer_activo").val()
              };
        $.ajax({
          url:'<?php echo base_url()?>index.php/icbper/guardar',
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
              toast('success', 1500, 'se registro el ICBPER');
              dataSource.read();
              $("#myModal").modal('hide');
            }
          }
        });           
      });
    });  




  </script>
