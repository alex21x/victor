<div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar transportistas</h4>
      </div>
      <div class="modal-body">
       <form>
       	<input type="hidden" id="transp_id" value="<?php echo $transportistas->transp_id;?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="codigo">ruc</label>
       				<input type="text" id="transp_ruc" class="form-control input-sm" value="<?php echo $transportistas->transp_ruc;?>">
       			</div>
            <div class="form-group">
              <label for="descripcion">nombre</label>
              <input type="text" id="transp_nombre" class="form-control input-sm" value="<?php echo $transportistas->transp_nombre;?>">
            </div>
            <div class="form-group">
              <label for="descripcion">direccion</label>
              <input type="text" id="transp_direccion" class="form-control input-sm" value="<?php echo $transportistas->transp_direccion;?>">
            </div>
            <div class="form-group">
              <label for="descripcion">telefono</label>
              <input type="text" id="transp_telefono" class="form-control input-sm" value="<?php echo $transportistas->transp_telefono;?>">
            </div>
             <div class="form-group">
              <label for="descripcion">tipounidad</label>
              <input type="text" id="transp_tipounidad" class="form-control input-sm" value="<?php echo $transportistas->transp_tipounidad;?>">
            </div>
             <div class="form-group">
              <label for="descripcion">placa</label>
              <input type="text" id="transp_placa" class="form-control input-sm" value="<?php echo $transportistas->transp_placa;?>">
            </div>
             <div class="form-group">
              <label for="descripcion">licencia</label>
              <input type="text" id="transp_licencia" class="form-control input-sm" value="<?php echo $transportistas->transp_licencia;?>">
            </div>
             <div class="form-group">
              <label for="descripcion">observacion</label>
              <input type="text" id="transp_observacion" class="form-control input-sm" value="<?php echo $transportistas->transp_observacion;?>">
            </div>

       		</div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_transportistas">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->


  <script type="text/javascript">
 $(document).ready(function(e){

      //guardar
      $("#btn_guardar_transportistas").click(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');
        var datos = {
                 transp_id:$("#transp_id").val(),
                transp_ruc:$("#transp_ruc").val(),
                transp_nombre:$("#transp_nombre").val(),
                 transp_direccion:$("#transp_direccion").val(),
                  transp_telefono:$("#transp_telefono").val(),
                   transp_tipounidad:$("#transp_tipounidad").val(),
                    transp_placa:$("#transp_placa").val(),
                     transp_licencia:$("#transp_licencia").val(),
                      transp_observacion:$("#transp_observacion").val()
              };
        $.ajax({
          url:'<?php echo base_url()?>index.php/transportistas/guardar',
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
              toast('success', 1500, 'se registro el transportistas');
              dataSource.read();
              $("#myModal").modal('hide');
            }
          }
        });           
      });
    });     

  </script>