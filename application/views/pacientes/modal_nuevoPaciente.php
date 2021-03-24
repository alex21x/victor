<script type="text/javascript">
$("#fecha_nacimiento_mp").datepicker({

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



        $("#edad_mp").val(edad);

        $("#mes_mp").val(meses);

        $("#dia_mp").val(dias);

    }

  });

</script>
<style type="text/css">

<  

    #images_gallery_md img{
        margin-top: 0px;
        border: 1px solid #ccc;
        width: 120px;
        height: 130px;
    }     
</style>
<!--  modal nuevo paciente -->

<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModalNuevoPaciente"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title">Nuevo Paciente</h4>
        <div id="images_gallery_md">
            <div class='col-md-12' align='center'>
              <a class='example-image-link' href='"+objeto_url+"' data-lightbox='example-1'>
                <img src="<?= base_url().'images/pacientes/'.$paciente->foto;?>">
              </a>
            </div>
          </div>

      </div>

      <div class="modal-body" style="height:900px;">

        <div class="container">

    <!-- Example row of columns -->

    <div class="row">       
        <div class="col-md-6">
            <div align="center"><h2>Ingresar Paciente</h2></div><br>
            <form id="formNuevoPaciente" class="form-horizontal">
                    <div class="form-group">                      
                        <label for="ruc_mp" class=" col-xs-4 col-md-5 col-lg-5 control-label">DNI <span style="color: red;">(*)</span></label>            
                        <div class="col-xs-8 col-md-5 col-lg-5">
                            <input type="number" class="form-control" name="ruc_mp" id="ruc_mp" value="<?PHP echo $paciente->ruc;?>" required="">
                        </div>
                        <div class="col-xs-1 col-xs-offset-4 col-lg-offset-0 col-md-1 col-lg-1 dni_auto">           
                          <label class="checkbox-inline "><input type="checkbox" name="dni_auto" id="dni_auto" value="">auto</label>
                        </div>
                        <div class="col-xs-2 col-md-1 col-lg-1">
                          <label class="checkbox-inline"><span class="glyphicon glyphicon-search searchCustomer">buscar</span></label>
                        </div> 

                    </div>     
                    <div class="form-group">
                      <label for="foto_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Foto</label>
                      <div class="col-xs-8 col-md-5 col-lg-7">
                        <input type="file" id="foto_mp" name="foto_mp" class="form-control input-sm" value="<?php echo $paciente->foto;?>">
                      </div>
                    </div>                        
                    <div class="form-group">
                        <label id="lbl_RAZ_APE" for="razon_social_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Apellidos y Nombres </label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="razon_social_mp" id="razon_social_mp" value="<?PHP echo $paciente->razon_social;?>" required="">
                        </div>
                    </div>                    

                    <div class="form-group">

                        <label for="lugar_nacimiento_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Dirección <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="lugar_nacimiento_mp" id="lugar_nacimiento_mp" value="<?PHP echo $paciente->lugar_nacimiento;?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="fecha_nacimiento_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Fecha Nacimiento <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="fecha_nacimiento_mp" id="fecha_nacimiento_mp" value="<?PHP echo $paciente->fecha_nacimiento;?>">
                        </div>
                    </div>

                    <div class="form-group">

                        <label for="edad_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Edad <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="edad_mp" id="edad_mp" value="<?PHP echo $paciente->edad;?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="mes_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Mes <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="mes_mp" id="mes_mp" value="<?PHP echo $paciente->mes;?>" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dia_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Dia <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="dia_mp" id="dia_mp" value="<?PHP echo $paciente->dia;?>" readonly>
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label for="sexo_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Sexo <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <select class="form-control" name="sexo_mp" id="sexo_mp">
                            <?PHP foreach($sexos as $value){
                              $SELECTED =  ($value->id == $paciente->sexo) ? 'SELECTED' : '';?>
                              <option value="<?= $value->id?>" <?= $SELECTED?>><?= $value->sexo?></option>                            
                            <?PHP }?>
                          </select>  
                        </div>
                    </div>                    

                    <div class="form-group">
                        <label for="telefono_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Telefono <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="telefono_mp" id="telefono_mp" value="<?PHP echo $paciente->telefono;?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="alergia_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Alergia <span style="color: red;">(*)</span></label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="alergia_mp" id="alergia_mp" value="<?PHP echo $paciente->alergia;?>">
                        </div>
                    </div>   

                    <div class="form-group">
                    <label for="tipo paciente" class="col-xs-4 col-md-5 col-lg-5 control-label">Tipo paciente  <span style="color: red;">(*)</span></label>
                      <div class="col-xs-8 col-md-5 col-lg-7">
                        <select  name="tipo_paciente_mp" id="tipo_paciente_mp" class="form-control">                           
                           <option value="">Seleccione</option>
                             <?PHP foreach($tipo_pacientes as $value){
                              $selected =  ($value->tipo_pac_id == $paciente->pac_tipo_id) ? 'SELECTED' : '';?>
                             <option value="<?= $value->tipo_pac_id?>" <?= $selected?>><?= $value->tipo_pac_descrip?></option>
                              <?PHP }?>
                        </select>
                      </div>                    
                   </div>  

                   <div class="form-group">
                        <label for="responsable_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Responsable </label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="responsable_mp" id="responsable_mp" value="<?PHP echo $paciente->responsable;?>">
                        </div>
                    </div>               
                    <div class="form-group">
                        <label for="observacion_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Observacion </label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="observacion_mp" id="observacion_mp" value="<?PHP echo $paciente->observacion;?>">
                        </div>
                    </div>               
                    <div class="form-group">
                        <label for="estado_civil_mp" class="col-xs-4 col-md-5 col-lg-5 control-label">Estado Civil </label>
                        <div class="col-xs-8 col-md-5 col-lg-7">
                            <input type="text" class="form-control" name="estado_civil_mp" id="estado_civil_mp" value="<?PHP echo $paciente->responsable;?>">
                        </div>
                    </div>                    
                    <div class="form-group">
                      <label for="telefono_movil_1" class="col-xs-12 col-md-5 col-lg-5 control-label"  style="text-align: center;"><span style="color: red;">(*) Campos obligatorios</span></label>
                  </div>                 

            </form>

        </div>

        <div class="col-md-3">
        </div>
    </div>
</div>
      </div>
      <div class="modal-footer">
        <div style="text-align: left">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="guardarNuevoPaciente">Guardar</button>
        </div>
      </div>            
    </div>  
</div>



<script type="text/javascript">
 $(document).ready(function(){

 $("#formNuevoPaciente")[0].reset();

 $("#guardarNuevoPaciente").on('click',function(){        
        $("#formNuevoPaciente").submit();
  });

 $("#formNuevoPaciente").on('submit',function(e){ 
        e.preventDefault();
        var url = "<?PHP echo base_url() ?>index.php/pacientes/guardarPaciente_mp";
        $(".has-error").removeClass('has-error');        

        $.ajax({                        
           type: "POST",                 
           url: url,    
           dataType: 'JSON',
           data:new FormData(this), 
           contentType:false,
           processData:false,
           success: function(response)                        
           {                        
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
                if(response.tipo == '2')
                {
                  var errores = response.errores;
                  toast('error', 1500, 'Paciente registrado');
                }
              }

              if(response.status == STATUS_OK)
              {                              
                toast('success', 1500, 'se registro el grado');                
                var paciente = response.paciente;
                $("#ruc").val(paciente['ruc']);
                $('#paciente_id').val(paciente['paciente_id']); 
                $('#paciente').val(paciente['razon_social']);
                $('#fecha_nacimiento').val(paciente['fecha_nacimiento']);
                $('#edad').val(paciente['edad']); 
                $('#mes').val(paciente['mes']); 
                $('#dia').val(paciente['dia']);
                $('#telefono').val(paciente['telefono'])
                $('#alergia').val(paciente['alergia']);
                $("#myModalNuevoPaciente").modal('hide');
              }
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
                      $("#ruc_mp").val(response.dni_auto);
                  }
                }
            })
        } else{
          $("#ruc").val('');
        }             
    });


     //SearchCustomer        
      $('.searchCustomer').on('click',function(){          
           var ruc = $('#ruc_mp').val();           

           $.getJSON('https://mundosoftperu.com/reniec/consulta_reniec.php',{dni:ruc})
             .done(function(json){                
                if(json[0].length!=undefined){
                    var dni = json[0];
                    var nombres = json[1]+' '+json[2]+' '+json[3];
                    $("input[name*='razon_social_mp").val(nombres);
                    $("input[name*='lugar_nacimiento_mp']").val('LIMA');                                        
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe');
                 }
             });
       });

      //CARGAR IMAGENES DE HISTORIA - ALEXANDER FERNANDEZ DE LA CRUZ 18-11-2020
      $('#foto_mp').change(function(){
        /* Limpiar vista previa */
           $("#images_gallery_md").html('');
           var archivos = document.getElementById('foto_mp').files;
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
                   $("#images_gallery_md").append("<p style='color: red'>El archivo "+name+" supera el máximo permitido 1MB</p>");
               }
               else if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png' && type != 'image/gif')
               {
                   $("#images_gallery_md").append("<p style='color: red'>El archivo "+name+" no es del tipo de imagen permitida.</p>");
               }
               else
               {
                 var objeto_url = navegador.createObjectURL(archivos[x]);                 
                 $("#images_gallery_md").append("<div class='col-md-12' align='center'><a class='example-image-link' href='"+objeto_url+"' data-lightbox='example-1'><img src="+objeto_url+"></a></div>");
               }
           }        
      });  

     });  

 </script>