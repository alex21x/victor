<style type="text/css">
  img{        
        width: 120px;
        height: 130px; 
        margin-left: 50px;       
    }       
</style>
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Profesional</h4>
      </div>
      <div class="modal-body">
       <form id="formProfesional">
       	<input type="hidden" id="id" name="id" value="<?php echo $profesional->prof_id;?>">        
       	<div class="row">
          <div class="col-xs-6 col-md-6 col-lg-6"> 
              <?PHP if($profesional->prof_foto != ''){?>
              <div id="images_gallery">
                  <img  src="<?= base_url().'images/profesional/foto/'.$profesional->prof_foto;?>"></div>
              <?PHP } ?>
          </div>
          <div class="col-xs-6 col-md-6 col-lg-6">     
              <?PHP if($profesional->prof_foto != ''){?>
              <div id="images_gallery">
                  <img  src="<?= base_url().'images/profesional/firma/'.$profesional->prof_firma;?>"></div>            
             <?PHP }?>
          </div>
          <div class="col-md-12">             
            <div class="form-group">
              <label for="codigo">Código</label>
              <input type="text" id="codigo" name="codigo" class="form-control input-sm" value="<?php echo $profesional->prof_codigo;?>">
            </div>                                    
          </div>
       		<div class="col-md-12">       			
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input type="text" id="nombre" name="nombre" class="form-control input-sm" value="<?php echo $profesional->prof_nombre;?>">
            </div>                                    
       		</div>          
          <div class="col-md-12">             
            <div class="form-group">
              <label for="direccion">Dirección</label>
              <input type="text" id="direccion" name="direccion" class="form-control input-sm" value="<?php echo $profesional->prof_direccion;?>">
            </div>                                    
          </div>
          <div class="col-md-12">             
            <div class="form-group">
              <label for="telefono">Teléfono</label>
              <input type="text" id="telefono" name="telefono" class="form-control input-sm" value="<?php echo $profesional->prof_telefono;?>">
            </div>                                    
          </div>
          <div class="col-md-12">             
            <div class="form-group">
              <label for="especialidad">Especialidad</label>
              <select class="form-control" name="especialidad" id="especialidad">
                  <option value="">Seleccione</option>
                <?PHP foreach($especialidades as $value){
                  $selected =  ($value->esp_id == $profesional->prof_especialidad_id) ? 'SELECTED' : '';?>
                    <option value="<?= $value->esp_id?>" <?= $selected?>><?= $value->esp_descripcion?></option>
                <?PHP }?>
              </select>              
            </div>                                    
          </div>  
          <div class="col-md-12">
            <div class="form-group">
              <label for="foto">Foto</label>
              <input type="file" id="foto" name="foto" class="form-control input-sm" value="<?php echo $profesional->foto;?>">
            </div>
          </div> 
          <div class="col-md-12">
            <div class="form-group">
              <label for="firma">Firma</label>
              <input type="file" id="firma" name="firma" class="form-control input-sm" value="<?php echo $profesional->firma;?>">
            </div>
          </div> 
       	</div>
       </form>          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_profesional">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
  	$(document).ready(function(e){

        //guardar
      $("#btn_guardar_profesional").click(function(e){
        $("#formProfesional").submit();
      });  		
  		
      $("#formProfesional").on('submit',function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/profesionales/guardarProfesional',
  				dataType:'json',
  				data:new FormData(this),
          contentType:false,
          processData:false,
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
  						toast('success', 1500, 'se registro la profesional');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});  					
  		});
  	});
  </script>
