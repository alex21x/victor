<script type="text/javascript">
  
  $("#fecha").datepicker();
</script>

<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Igv</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="id" value="<?php echo $valor->id;?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="valor">valor</label>
       				<input type="text" id="valor" class="form-control input-sm" value="<?php echo $valor->valor;?>">
       			</div>
            <div class="form-group">
              <label for="descripcion">fecha</label>
              <input type="text" id="fecha" class="form-control input-sm" value="<?php echo $valor->fecha;?>">
            </div>

            <div class="form-group">
              <select class="form-control" id="activo" name="activo">
                  <?PHP foreach($activos as $value) {
                        $selected = ($value->activo == $valor->activo) ? 'SELECTED' : '';?>
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
        <button type="button" class="btn btn-primary" id="btn_guardar_igv">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->

  <script type="text/javascript">
    
$(document).ready(function(e){

      //guardar
      $("#btn_guardar_igv").click(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');
        var datos = {
                 id:$("#id").val(),
                 valor :$("#valor").val(),
                 activo: $("#activo").val(),
                 fecha :$("#fecha").val()
                 
              };
        $.ajax({
          url:'<?php echo base_url()?>index.php/igv/guardar',
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
              toast('success', 1500, 'se registro el registro igv');
              dataSource.read();
              $("#myModal").modal('hide');
            }
          }
        });           
      });
    });  
  </script>