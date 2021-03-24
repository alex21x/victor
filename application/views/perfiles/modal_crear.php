  <style>
  li{
      list-style: none;
  }  
  .mod_padre{
    font-weight: bold;
  }

  </style>
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Perfil</h4>
      </div>
      <div class="modal-body">
       <form id="datos">
       	<input type="hidden" name="id" id="perfil_id" value="<?php echo $perfil->id?>">
       	<div class="row">
       		<div class="col-md-12">
       			<div class="form-group">
       				<label for="nombre">Nombre</label>
       				<input type="text" name="nombre" id="nombre" class="form-control input-sm" value="<?php echo $perfil->tipo_empleado?>" <?php if($perfil->id==1 or $perfil->id==20):?> readonly <?php endif ?>>
       			</div>
       		</div>
       	</div>
        <div class="row" style="height: 500px; overflow: scroll;">
          <ul id="modulos">
            <?php foreach($modulos as $padre):?>
              <li class="mod_padre">
                <?php echo strtoupper($padre->mod_descripcion)?>
                  <!--modulos hijos-->
                  <ul>
                    <?php foreach($padre->modulos_hijos as $hijo):?>
                      <li class="mod_hijo">
                        <div class="checkbox">
                          <label>
                            <input type="checkbox" name="modulos[]" value="<?php echo $hijo->mod_id?>" <?php if($hijo->checkbox=='1'):?> checked <?php endif?> > <?php echo $hijo->mod_descripcion?>
                          </label>
                        </div>                         
                      </li>
                    <?php endforeach?>                     
                  </ul>
   
              </li>
            <?php endforeach?>
          </ul>
        </div>
       </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_perfil">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){

  		//guardar
  		$("#btn_guardar_perfil").click(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');
  			/*var datos = {
  							id:$("#perfil_id").val(),
  							nombre:$("#nombre").val()
  						};*/
        var datos = {
                      dato:$("#datos").serialize()
                    };      
  			$.ajax({
  				url:'<?php echo base_url()?>index.php/perfiles/guardarPerfil',
  				dataType:'json',
  				data:$("#datos").serialize(),
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
  						toast('success', 1500, 'se registro el perfil');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});
  					
  		});
  	});
  </script>
