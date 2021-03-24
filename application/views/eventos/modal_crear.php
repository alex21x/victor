<style type="text/css">  
.ui-autocomplete { z-index:2147483647; }  
fieldset 
{
    border: 1px solid #ddd !important;
    margin: 0;
    xmin-width: 0;
    padding: 10px;       
    position: relative;
    border-radius:4px;
    background-color:#f5f5f5;
    padding-left:10px!important;
} 
  
legend
{
      font-size:15px;
      font-weight:bold;
      margin-bottom: 0px; 
      width: 35%; 
      border: 1px solid #ddd;
      border-radius: 4px; 
      padding: 5px 5px 5px 10px; 
      background-color: #ffffff;
}

@media (min-width:0px) {
  .modal-admin{
    width: 600px;
    margin: 10px auto 20px auto;        
}}    

@media (min-width: 768px) {
  .modal-admin{
    width: 800px;
    margin: 10px auto 20px auto;        
}}

@media (min-width: 992px) {
  .modal-admin{
    width: 1000px;
    margin: 10px auto 20px auto;        
}}      

@media (min-width: 1200px) {
  .modal-admin{
    width: 800px;
    margin: 10px auto 20px auto;        
}}     

@media (min-width: 1300px) {
  .modal-admin{
    width: 1200px;
    margin: 10px auto 20px auto;        
}}

@media (min-width: 1500px) {
  .modal-admin{
    width: 1200px;
    margin: 10px auto 20px auto;        
}}   
    
@media (min-width: 1600px) {
  .modal-admin{
    width: 1200px;
    margin: 10px auto 20px auto;        
}} 

@media (min-width: 1900px) {
  .modal-admin{
    width: 1200px;
    margin: 10px auto 20px auto;        
}}   

</style>
<link rel="stylesheet" type="text/css" href="<?PHP echo base_url() ?>assets/css/jquery.datetimepicker.css"/>
<script src="<?PHP echo base_url() ?>assets/js/jquery.datetimepicker.js"></script>
<script type="text/javascript">

 $('#fecha_evento').datetimepicker({    
    format:'d-m-Y H:i',
    dayOfWeekStart : 1,
    lang:'es',
 });

 var idEvento = $("#id").val();
 if(idEvento == '')
  $('#fecha_evento').datetimepicker({value:'15/08/2015 05:03',step:10});

<?PHP
  if(empty($evento->tipo_evento_id)){
    $evento->tipo_evento_id = 1;
    $evento->ingreso = date('H:i');
  }
?>
</script>
  <div class="modal-dialog modal-sm modal-admin" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar nuevo Evento</h4>
      </div>
      <div class="modal-body" style="height:600px;">
       <form id="formEvento">
        <input type="hidden" id="id" name="id" value="<?php echo $evento->id;?>">        
          <div class="row">
          <div class="col-xs-10 col-md-12 col-lg-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Nuevo evento</div>
              </div>
              <div class="panel-body">
                <fieldset class="border p-1">
                    <legend  class="w-auto">Evento</legend>
            <div class="row">            
                <div class="col-xs-12 col-md-3 col-lg-4">
                  <div class="form-group">
                    <label for="tipo_evento">Tipo evento</label>
                    <select class="form-control" id="tipo_evento" name="tipo_evento">
                      <option value="">Seleccione</option>
                      <?PHP foreach($tipo_eventos as $value){
                            $selected = ($value->id == $evento->tipo_evento_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->id?>" <?= $selected?>><?= $value->tipo_evento?></option>
                      <?PHP }?>                        
                    </select>
                  </div>
                </div>
                 <div class="col-xs-12 col-md-4 col-lg-4">
                     <label>Fecha Evento :</label>
                     <input class="form-control" type="text" name="fecha_evento" id="fecha_evento" value="<?php echo $evento->fecha;?>">
                </div>
                 <div class="col-xs-12 col-md-3 col-lg-4">
                  <div class="form-group">
                    <label for="turno">Turno</label>
                    <select class="form-control" id="turno" name="turno">
                      <option value="">Seleccione</option>
                      <?PHP foreach($turnos as $value){
                            $selected = ($value->id == $evento->turno_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->id?>" <?= $selected?>><?= $value->turno?></option>
                      <?PHP }?>                        
                    </select>
                  </div>
                </div>                            
            </div>  
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-8">
                  <label>Cliente <span style="color: red;">(*)</span></label>         
                  <input class="form-control" type="text" name="cliente" id="cliente" placeholder="Buscar Cliente" value="<?= $evento->cli_razon_social?>">
                  <input type="hidden" name="ruc_sunat" id="ruc_sunat">
                  <input type="hidden" name="razon_sunat" id="razon_sunat">
                  <div id="data_cli"><input type="hidden" name="cliente_id" id="cliente_id" value="<?= $evento->cliente_id?>"></div>
              </div>                 
               <div class="col-xs-12 col-md-12 col-lg-4">
                  <label>&nbsp;</label><br>
                  <button type="button" id="nuevo_cliente" class="col-lg-6 btn btn-default btn-sm btn_buscar" data-toggle='modal' data-target='#myModalNuevoCliente'>NUEVO</button>                   <button type="button" id="nuevo_cliente" class="col-lg-6 btn btn-default btn-sm" onclick="consulta_dniRucCliente()">BUSCAR</button>                   
              </div>
            </div>

            <div class="row">
              <div class="col-xs-12 col-md-6 col-lg-6">
                <label>Domicilio:</label><br>
                <input class="form-control" type="text" name="direccion" id="direccion"  value="<?=$evento->domicilio1?>">                          
              </div> 
              <div class="col-xs-12 col-md-6 col-lg-6">
                <label>Placa</label><br>
                <input class="form-control" type="text" name="placa" id="placa" value="<?= $evento->placa?>" style="text-transform: uppercase">                          
              </div>                          
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top: 40px;">        
                  <div class="form-group">                    
                    <button type="button" class="btn btn-default col-sm-6" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary col-sm-6" id="btn_guardar_evento">Guardar</button>
                  </div>                                    
                </div>     
            </div>                 
             <div class="row">
              <div class="col-xs-12 col-md-6 col-lg-4">
                <label>Hora Ingreso:</label><br>
                <input class="form-control" type="time" name="hora_ingreso" id="hora_ingreso"  value="<?=$evento->ingreso?>">                          
              </div> 
              <div class="col-xs-12 col-md-6 col-lg-4">
                <label>Hora salida:<input type="checkbox" id="hora_salida_check" name="hora_salida_check"></label><br>
                <input class="form-control" type="time" name="hora_salida" id="hora_salida" value="<?= $evento->salida?>" readonly>                          
              </div>              
              <div class="col-xs-12 col-md-6 col-lg-4">
                <label>Total Horas:</label><br>
                <input class="form-control" type="time" name="total_horas" id="total_horas" value="<?= $evento->salida?>" readonly>                          
              </div>              
            </div> 
             <div class="row">
              <div class="col-xs-12 col-md-6 col-lg-6">
                <label>Responsable:</label><br>
                <input class="form-control" type="datetime" name="responsable" id="responsable"  value="<?= $evento->responsable?>">
              </div> 
              <div class="col-xs-12 col-md-6 col-lg-6">
                <label>Observacion:</label><br>
                  <textarea class="form-control" rows="1" id="observacion" name="observacion" value=""><?=$evento->observacion?></textarea>                          
              </div>              
            </div>
            <div class="row">
                  <div id="vista-previa">
                    <div id="images_gallery">
                      <?PHP
                          foreach($evento_imagenes as $image)
                            {              
                              echo '<div class="col-xs-2 col-md-2 col-lg-2" align="center" ><a class="example-image-link" href="'.base_url().'images/eventos/'. $image->evento_imagen.'" data-lightbox="example-1"><img class="example-image" src="'.base_url().'images/eventos/'. $image->evento_imagen .'" width="120px" height="100px" style="border:1px solid #ccc;margin-top:10px;" /></a>
                                <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$image->id.'"></span></div>';              
                            }
                            ?>
                      </div>
                    </div>   
            </div>        

               <div class="row">
               <div class="col-xs-12 col-md-6 col-lg-6">
                <label>Imagen:</label><br>
                <input type="file" id="images" name="images[]" multiple>
                <input type="hidden" name="evento_id" id="evento_id" value="<?= $evento->id;?>">
               </div>                
                <div class="col-xs-12 col-md-6 col-lg-6">
                  <div class="checkbox">
                    <?php $checked = ($evento->epp == 1) ? 'checked' : '';?>
                    <label><input type="checkbox" name="epp" id="epp"  <?=$checked?> >Epp</label>
                  </div>                 
                </div>            
              </div><br>
              <div class="row">
                <div class="col-xs-12 col-md-4 col-lg-6">
                    <label>N°Documento:</label>
                     <input class="form-control"  type="text" name="num_documento" value="<?=$evento->num_documento?>">              
                </div>
                <div class="col-xs-12 col-md-4 col-lg-6">
                     <label>N° Guia :</label>
                     <input class="form-control" type="text" name="guia" value="<?=$evento->num_guia?>">            
                </div> 
                <div class="col-xs-12 col-md-4 col-lg-6">
                     <label>Otros :</label>
                     <input class="form-control" type="text" name="otros" value="<?=$evento->otros?>">            
                </div>
              </div>           
          </div>                  
         <div class="panel-footer">     
                <!--<div class="col-xs-12 col-md-12 col-lg-12" style="padding-top: 40px;">        
                  <div class="form-group">                    
                    <button type="button" class="btn btn-default col-sm-6" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary col-sm-6" id="btn_guardar_evento">Guardar</button>
                  </div>                                    
                </div>-->
         </div> 
       </div>
          </div>      
        </div>      
       </form>
      </div>
      <div class="modal-footer"><!--
        <input type="text" name="profesional_firma" id="profesional_firma">
        <div class="col-xs-4 col-md-4 col-lg-12" style="text-align: left">
          <button type="button" class="btn btn-default col-sm-2" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary col-sm-2" id="btn_guardar_historia">Guardar</button>
        </div>-->
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
  <script>
     
    $(document).ready(function(){      

      $("#btn_guardar_evento").click(function(){
          $("#formEvento").submit();
      })

      //Guardar Historia
      $("#formEvento").submit(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');

        $.ajax({
          url:'<?php echo base_url()?>index.php/eventos/guardarEvento',
          method:'post',
          dataType:'json',
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
              toast('success', 1500, 'se registro el evento');
              dataSource.read();
              $("#myModal").modal('hide');/*
                setTimeout(function() { 
                      location.href='<?PHP echo base_url()?>index.php/eventos/index/'+response.his_id;
                }, 2000);*/
                impresionTicket(response.eve_id);
            }
          }
        });           
      });

      //CARGAR IMAGENES DE EVENTOS - ALEXANDER FERNANDEZ DE LA CRUZ 18-11-2020
      $('#images').change(function(){
        /* Limpiar vista previa */
           //$("#vista-previa").html('');
           var archivos = document.getElementById('images').files;
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
                   $("#vista-previa").append("<p style='color: red'>El archivo "+name+" supera el máximo permitido 1MB</p>");
               }
               else if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png' && type != 'image/gif')
               {
                   $("#vista-previa").append("<p style='color: red'>El archivo "+name+" no es del tipo de imagen permitida.</p>");
               }
               else
               {
                 var objeto_url = navegador.createObjectURL(archivos[x]);                 
                 $("#vista-previa").append("<div class='col-md-2' align='center'><a class='example-image-link' href='"+objeto_url+"' data-lightbox='example-1'><img src="+objeto_url+" width='140' height='100' style='border:1px solid #ccc;margin-top:10px';></a></div>");
               }
           }        
      });  


      $("#cliente").autocomplete({
        source: '<?= base_url()?>index.php/clientes/buscador_cliente',
        minLength: 2,
        select: function(event,ui){
          var data_cli = '<input type="hidden" value="'+ ui.item.id+'" name="cliente_id" id="cliente_id">';
          $("#data_cli").html(data_cli);
          $("#direccion").val(ui.item.domicilio1);
        }
      });


      //ELIMINAR IMAGEN - ALEXANDER FERNANDEZ DE LA CRUZ 26-02-2021
      $(document).on('click','.eliminarImagen',function(e){

        e.preventDefault();
        var idEvento = $('#evento_id').val();
        var idEventoImagen = $(this).data('id');
        var msg = 'Está seguro de eliminar imagen??';
        var url = '<?= base_url()?>index.php/eventos/eliminarEventoImagen/'+idEventoImagen+'/'+idEvento;
        $.confirm({
          title: 'Confirmar',
          content: msg,
          buttons: {
            confirm:{
              text:'aceptar',
              btnClass: 'btn-blue',
              action: function(){
                $.ajax({
                  url: url,
                  dataType: 'html',
                  method: 'get',
                  success: function(response){                    
                      toast('success',1500,'imagen eliminada');
                      $("#vista-previa").html('');
                      $("#vista-previa").append(response);
                  }
                });
              }
            },
            cancel: function(){
            }
          }
        });
      });


    //CARGAR MODAL NUEVO CLIENTE
    $(".btn_buscar").on('click',function(e){
        e.preventDefault();
        $("#myModalNuevoCliente").load("<?= base_url()?>index.php/clientes/modal_nuevoCliente",{});
    });
  }); 


  $("#hora_salida_check").on("click",function(){
        if($(this).is(":checked")){
        var today = new Date();
            date = addZero(today.getHours())+':'+addZero((today.getMinutes()));
            $("#hora_salida").val(date);
            calculardiferencia();
        } else{          
            $("#hora_salida").val('');
        }          
  });

    //ADD FECHA 0
    function addZero(i) {
      if (i < 10) {
          i = '0' + i;
      }
        return i;
    }

    //RESTAR HORAS 07-03-2021
    function newDate(partes) {
      var date = new Date(0);
      date.setHours(partes[0]);
      date.setMinutes(partes[1]);
      return date;
    }

    function prefijo(num) {
        return num < 10 ? ("0" + num) : num; 
    }

    function calculardiferencia(){
      var dateDesde = newDate($('#hora_ingreso').val().split(":"));
      var dateHasta = newDate($('#hora_salida').val().split(":"));

      var minutos = (dateHasta - dateDesde)/1000/60;
      var horas = Math.floor(minutos/60);
      minutos = minutos % 60;

      $('#total_horas').val(prefijo(horas) + ':' + prefijo(minutos));
    }

    $('#hora_ingreso').change(calculardiferencia);
    $('#hora_salida').change(calculardiferencia);
    calculardiferencia();
</script>

 