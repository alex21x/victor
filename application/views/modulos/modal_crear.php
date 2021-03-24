  
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Módulo</h4>
      </div>
      <div class="modal-body">
       <form id="formModulo">
       	<input type="hidden" id="id" name="id" value="<?php echo $modulo->mod_id;?>">
       	<div class="row">
       		<div class="col-xs-12 col-md-12 col-lg-12">
            <div class="form-group">
              <label for="descripcion">Descripcion</label>
              <input type="text" id="descripcion" name="nombre" id="nombre" class="form-control input-sm" value="<?php echo $modulo->mod_descripcion;?>">
            </div>            
       		</div>
          <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="form-group">
              <?PHP $CHECKED = ($modulo->mod_sunat == 1) ? 'CHECKED': '';
                                //$DISPLAY =  ($comprobanteVenta->passwordDelete == 1) ? 'style=display:block': ''?>

              <label for="externo">Enlace&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="externo" name="externo" <?= $CHECKED?>>&nbsp;externo
              <input type="text" id="descripcion" name="enlace" id="enlace" class="form-control input-sm" value="<?php echo $modulo->mod_enlace;?>">
            </div>            
          </div>
        </div>
        <div class="row">
          <div class="col-xs-6 col-md-6 col-lg-6">             
            <div class="form-group">
              <label for="tipoModulo">Tipo</label>
                <select class="form-control" id="tipoModulo" name="tipoModulo">
                  <option value=""  <?PHP if(isset($modulo->mod_es_padre) && $modulo->mod_es_padre == '') echo 'SELECTED';?>>Seleccionar</option>                  
                  <option value="0" <?PHP if(isset($modulo->mod_es_padre) && $modulo->mod_es_padre == 0)  echo 'SELECTED';?>>Hijo</option>
                  <option value="1" <?PHP if(isset($modulo->mod_es_padre) && $modulo->mod_es_padre == 1)  echo 'SELECTED';?>>Padre</option>
                </select>
            </div>            
          </div>          
          <div id="padre">
            <div class="col-xs-6 col-md-6 col-lg-6">             
            <div class="form-group">
              <label>Padre</label>
              <select class="form-control" name="referencia" id="referencia">
                      <option value="">Seleccionar</option>';
                        <?PHP foreach ($padres as $key => $value) {
                            $SELECTED = ($value->mod_id == $modulo->mod_referencia) ? 'SELECTED': '';?>
                                 <option value="<?= $value->mod_id;?>" <?= $SELECTED?>><?= $value->mod_descripcion;?></option>
                        <?PHP }?>
              </select>
          </div></div>
        </div></div>
        <div class="row">
          <div class="col-xs-6 col-md-6 col-lg-6">             
            <div class="form-group">
              <label for="orden">Orden</label>
                  <input type="text" class="form-control" name="orden" id="orden" value="<?php echo $modulo->mod_orden;?>">
            </div>            
          </div>
          <div class="col-xs-6 col-md-6 col-lg-6">             
            <div class="form-group">
              <label for="icon">Icono</label>
                  <input type="text" class="form-control" name="icono" id="icono" value="<?php echo $modulo->mod_icon;?>">
            </div>            
          </div>
       	</div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_modulo">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){
  		//guardar
  		$("#btn_guardar_modulo").click(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');          
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/modulos/guardarModulo',
  				dataType:'json',
  				data:$("#formModulo").serialize(),
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
  						toast('success', 1500, 'se registro el Módulo');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});  					
  		});

      //CARGAR MENÚ PADRE
      $(document).on("change","#tipoModulo",function(){              
        cargarPadre();               
      });

      cargarPadre();
      function cargarPadre(){        
        var padre =  $("#tipoModulo option:selected").val(); 
        (padre == 0 && padre != '') ? $('#padre').css("display","block") : $('#padre').css("display","none");//SI ES HIJO CARGAMOS EL COMBO PADRE          
      }
  	});
  </script>
