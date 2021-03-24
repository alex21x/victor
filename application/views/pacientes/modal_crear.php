<?PHP
$medico_id = 0;
if(isset($paciente)){
  $paciente_id =  $paciente->id;  
}
?>

<script type="text/javascript">
  
$("#fecha_nacimiento").datepicker({
    changeMonth :true,
    changeYear : true,    
    yearRange: '1900:2025',
    onSelect: function(value,ui){
      var today = new Date();     

        ahora_ano = today.getYear();
        ahora_mes = today.getMonth();
        ahora_dia = today.getDate();

        ano  = ui.selectedYear;
        mes  = ui.selectedMonth;
        dia  = ui.selectedDay;

        //realizamos el calculo
            var edad = (ahora_ano + 1900) - ano;
            if (ahora_mes < mes)
                edad--;           
            if ((mes == ahora_mes) && (ahora_dia < dia))
                edad--;           
            if (edad > 1900)
                edad -= 1900;           

        // calculamos los meses
            var meses = 0;
            if (ahora_mes > mes && dia > ahora_dia)
                meses = ahora_mes - mes - 1;
            else if (ahora_mes > mes)
                meses = ahora_mes - mes
            if (ahora_mes < mes && dia < ahora_dia)
                meses = 12 - (mes - ahora_mes);
            else if (ahora_mes < mes)
                meses = 12 - (mes - ahora_mes + 1);
            if (ahora_mes == mes && dia > ahora_dia)
                meses = 11;

          // calculamos los dias
          var dias = 0;
          if (ahora_dia > dia)
                dias = ahora_dia - dia;       
        if (ahora_dia < dia) {
               ultimoDiaMes = new Date(ahora_ano, ahora_mes - 1, 0);
                 dias = ultimoDiaMes.getDate() - (dia - ahora_dia);
            }

        $("#edad").val(edad);
        $("#mes").val(meses);
        $("#dia").val(dias);
    }
  });

</script>
<style type="text/css">
    #images_gallery img{
        margin-top: 10px;
        border: 1px solid #ccc;
        width: 150px;
        height: 160px;
    }     
   </style>
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <div class="text-center">
          <h4 class="modal-title">Registrar Paciente</h4>
          <div id="images_gallery">
            <div class='col-md-12' align='center'>
              <a class='example-image-link' href='"+objeto_url+"' data-lightbox='example-1'>
                <img src="<?= base_url().'images/pacientes/'.$paciente->foto;?>">
              </a>
            </div>
          </div>
        </div>              
      </div>
      <div class="modal-body" style="height:800px;">      
        <div class="container">
          <div class="row">
          <div class="col-md-6">
        <form id="formPaciente" class="form-horizontal">
       	<input type="hidden" id="id" name="id" value="<?php echo $paciente->id;?>">
                    <div class="form-group">                     
                    <div class="form-group">
                        <label for="ruc" class="col-sm-5 control-label">DNI <label style="color: red;">(*)</label></label>
                        <div class="col-xs-5">
                            <input type="text" class="form-control" name="ruc" id="ruc" value="<?PHP echo $paciente->ruc;?>" required="">
                        </div>
                        <div class="col-xs-1 dni_auto">           
                          <label class="checkbox-inline "><input type="checkbox" name="dni_auto" id="dni_auto" value="">auto</label>
                        </div>
                        <div class="col-xs-1">
                          <label class="checkbox-inline"><span class="glyphicon glyphicon-search searchCustomer">buscar</span></label>
                        </div> 
                    </div>
                    <div class="form-group">
                      <label for="foto" class="col-sm-5 control-label">Foto</label>
                      <div class="col-xs-7">
                        <input type="file" id="foto" name="foto" class="form-control input-sm" value="<?php echo $paciente->foto;?>">
                      </div>
                    </div>
                    <div class="form-group">
                        <label id="lbl_RAZ_APE" for="razon_social" class="col-sm-5 control-label">Apellidos y Nombres <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="razon_social" id="razon_social" value="<?PHP echo $paciente->razon_social;?>" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lugar_nacimiento" class="col-sm-5 control-label">Dirección <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="lugar_nacimiento" id="lugar_nacimiento" value="<?PHP echo $paciente->lugar_nacimiento;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="fecha_nacimiento" class="col-sm-5 control-label">Fecha Nacimiento <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?PHP echo $paciente->fecha_nacimiento;?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edad" class="col-sm-5 control-label">Edad <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="edad" id="edad" value="<?PHP echo $paciente->edad;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mes" class="col-sm-5 control-label">Mes <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="mes" id="mes" value="<?PHP echo $paciente->mes;?>" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="dia" class="col-sm-5 control-label">Dia <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="dia" id="dia" value="<?PHP echo $paciente->dia;?>" readonly>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="sexo" class="col-sm-5 control-label">Sexo <label style="color: red;">(*)</label></label>                        
                        <div class="col-xs-7">
                          <select class="form-control" name="sexo" id="sexo">
                            <?PHP foreach($sexos as $value){
                              $SELECTED =  ($value->id == $paciente->sexo) ? 'SELECTED' : '';?>
                              <option value="<?= $value->id?>" <?= $SELECTED?>><?= $value->sexo?></option>                            
                            <?PHP }?>
                          </select>                            
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="telefono" class="col-sm-5 control-label">Telefono <label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="telefono" id="telefono" value="<?PHP echo $paciente->telefono;?>">
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label for="alergia" class="col-sm-5 control-label">Alergia<label style="color: red;">(*)</label></label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="alergia" id="alergia" value="<?PHP echo $paciente->alergia;?>">
                        </div>
                    </div>
                   <div class="form-group">
                    <label for="tipo paciente" class="col-sm-5 control-label">Tipo paciente<label style="color: red;">(*)</label></label>
                      <div class="col-xs-7">
                        <select  name="tipo_paciente" id="tipo_paciente" class="form-control">
                           <option value="">Seleccione</option>
                             <?PHP foreach($tipo_pacientes as $value){
                              $selected =  ($value->tipo_pac_id == $paciente->pac_tipo_id) ? 'SELECTED' : '';?>
                             <option value="<?= $value->tipo_pac_id?>" <?= $selected?>><?= $value->tipo_pac_descrip?></option>
                              <?PHP }?>
                        </select>
                      </div>                    
                   </div>                                    
                   <div class="form-group">
                        <label for="responsable" class="col-sm-5 control-label">Responsable </label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="responsable" id="responsable" value="<?PHP echo $paciente->responsable;?>">
                        </div>
                    </div>               
                    <div class="form-group">
                        <label for="observacion" class="col-sm-5 control-label">Observacion </label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="observacion" id="observacion" value="<?PHP echo $paciente->observacion;?>">
                        </div>
                    </div>               
                    <div class="form-group">
                        <label for="estado_civil" class="col-sm-5 control-label">Estado Civil </label>
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="estado_civil" id="estado_civil" value="<?PHP echo $paciente->responsable;?>">
                        </div>
                    </div>               
                    <div class="form-group">
                        <label for="telefono_movil_1" class="col-sm-10 control-label"  style="text-align: center;"><label style="color: red;">(*) Campos obligatorios</label></label>
                    </div>
                </div>
       </form>
</div>
<div class="col-md-3"></div>       
      </div></div></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="btn_guardar_paciente">Guardar</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script type="text/javascript">    
    $(document).ready(function () {
      if($("#paciente_id").val() > 0)
        $("#datos").show();
      else
        $("#datos").hide();
        $("#limitado_detalle").hide();              

       });
  </script>

  <script>
    $("#formPaciente")[0].reset();
  	$(document).ready(function(e){
      //guardar
      $("#btn_guardar_paciente").on('click',function(){        
        $("#formPaciente").submit();
      });

  		//guardar
  		$("#formPaciente").on('submit',function(e){        
        e.preventDefault();
  			$(".has-error").removeClass('has-error');

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/pacientes/guardarPaciente_v',
  				dataType:'JSON',
          method:'POST',
  				data:new FormData(this),  	
          contentType:false,
          processData:false,  			
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
  						toast('success', 1500, 'se registro el grado');
  						dataSource.read();
  						$("#myModal").modal('hide');
  					}
  				}
  			});  					
  		});

      //SearchCustomer        
      $('.searchCustomer').on('click',function(){                                            
           var ruc = $('#ruc').val();           

           $.getJSON('https://mundosoftperu.com/reniec/consulta_reniec.php',{dni:ruc})
             .done(function(json){                
                if(json[0].length!=undefined){
                    var dni = json[0];
                    var nombres = json[1]+' '+json[2]+' '+json[3];
                    $("input[name*='razon_social").val(nombres);
                    $("input[name*='lugar_nacimiento']").val('LIMA');                                        
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe');
                 }
             });
       });

      //DNI AUTOMATICO
      $(document).on("click",'#dni_auto',function(){            
        if($('#dni_auto').prop('checked')){           
            $.ajax({
                url: '<?= base_url()?>index.php/pacientes/dni_auto',
                dataType : 'JSON',
                method: 'POST',
                success: function(response){                  
                  if(response.status == STATUS_OK){                    
                      $("#ruc").val(response.dni_auto);
                  }
                }
            })
        } else{
          $("#ruc").val('');
        }             
    });


      //CARGAR IMAGENES DE HISTORIA - ALEXANDER FERNANDEZ DE LA CRUZ 18-11-2020
      $('#foto').change(function(){
        /* Limpiar vista previa */
           $("#images_gallery").html('');
           var archivos = document.getElementById('foto').files;
           var navegador = window.URL || window.webkitURL;
           /* Recorrer los archivos */
           for(x=0; x<archivos.length; x++)
           {
               /* Validar tamaño y tipo de archivo */
               var size = archivos[x].size;
               var type = archivos[x].type;
               var name = archivos[x].name;
               if (size > 1024*1024)
               {
                   $("#images_gallery").append("<p style='color: red'>El archivo "+name+" supera el máximo permitido 1MB</p>");
               }
               else if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png' && type != 'image/gif')
               {
                   $("#images_gallery").append("<p style='color: red'>El archivo "+name+" no es del tipo de imagen permitida.</p>");
               }
               else
               {
                 var objeto_url = navegador.createObjectURL(archivos[x]);                 
                 $("#images_gallery").append("<div class='col-md-12' align='center'><a class='example-image-link' href='"+objeto_url+"' data-lightbox='example-1'><img src="+objeto_url+"></a></div>");
               }
           }        
      });  
  	});
  </script>
