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

.medico{
  font-weight: bold;
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
    width: 1800px;
    margin: 10px auto 20px auto;        
}}

@media (min-width: 1500px) {
  .modal-admin{
    width: 1800px;
    margin: 10px auto 20px auto;        
}}   
    
@media (min-width: 1600px) {
  .modal-admin{
    width: 1800px;
    margin: 10px auto 20px auto;        
}} 

@media (min-width: 1900px) {
  .modal-admin{
    width: 1800px;
    margin: 10px auto 20px auto;        
}}   


</style>
<link rel="stylesheet" type="text/css" href="<?PHP echo base_url() ?>assets/css/jquery.datetimepicker.css"/>
<script src="<?PHP echo base_url() ?>assets/js/jquery.datetimepicker.js"></script>
<script type="text/javascript">

$(document).ready(function(){

 $("#paciente").autocomplete({
    source: '<?= base_url()?>index.php/pacientes/buscador_paciente',
    minLength: 2,
    select: function(event,ui){
      var data_pac = '<input type="hidden" value="'+ ui.item.id+'" name="paciente_id" id="paciente_id">';
      $("#data_pac").html(data_pac);           
      $("#ruc").val(ui.item.ruc);
      $("#fecha_nacimiento").val(ui.item.fecha_nacimiento);
      $("#edad").val(ui.item.edad);
      $("#mes").val(ui.item.mes);
      $("#dia").val(ui.item.dia);
      $("#telefono").val(ui.item.telefono);
      $("#alergia").val(ui.item.alergia);
    }
  });

 //FECHA CITA
 $('#fecha_cita').datetimepicker({    
    format:'d-m-Y H:i',
    dayOfWeekStart : 1,
    lang:'es',
    });    
 $('#proxima_cita').datetimepicker({    
    format:'d-m-Y H:i',
    dayOfWeekStart : 1,
    lang:'es',
    }); 


  var idHistoria = $("#id").val();
    if(idHistoria == ''){
      $('#fecha_cita').datetimepicker({value:'15/08/2015 05:03',step:10});
      $('#proxima_cita').datetimepicker({value:'15/08/2015 05:03',step:10});
      $('.some_class').datetimepicker();  
    }

 //FECHA NACIMIENTO
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
  });
</script>
  <div class="modal-dialog modal-lg modal-admin" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Registrar Historia Clínica</h4>
      </div>
      <div class="modal-body" style="height:1100px;overflow-y: scroll;">
       <form id="formHistoria">
       	<input type="hidden" id="id" name="id" value="<?php echo $historia->his_id;?>">
        <input type="hidden" name="ruc" id="ruc" value="<?php echo $historia->pac_ruc;?>">
       	  <div class="row">
          <div class="col-xs-12 col-md-6 col-lg-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">PACIENTE</div>
              </div>
              <div class="panel-body">
                <fieldset class="border p-1">
                    <legend  class="w-auto">Paciente</legend>
            <div class="row">
               <div class="col-xs-6 col-md-3 col-lg-3">
                  <div class="form-group">
                    <label for="fecha_cita">Fecha Cita</label>
                    <input type="text" id="fecha_cita" name="fecha_cita" class="form-control" value="<?php echo $historia->his_fecha_cita;?>">
                  </div>                                    
                </div>             
                <div class="col-xs-6 col-md-3 col-lg-3">
                  <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control" id="estado" name="estado">
                      <option value="">Seleccione</option>
                      <?PHP foreach($historia_estados as $value){
                            $selected = ($value->hie_id == $historia->his_historia_estado_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->hie_id?>" <?= $selected?>><?= $value->hie_descripcion?></option>
                      <?PHP }?>                        
                    </select>
                  </div>
                </div>
            </div>  
            <div class="row">
              <div class="col-xs-12 col-md-12 col-lg-8">
                  <label>Paciente <span style="color: red;">(*)</span></label>         
                  <input class="form-control" type="text" name="paciente" id="paciente" placeholder="Buscar Paciente" value="<?= $historia->pac_razon_social?>">
                  <div id="data_pac"><input type="hidden" name="paciente_id" id="paciente_id" value="<?= $historia->his_paciente_id?>"></div>
              </div>                 
               <div class="col-xs-12 col-md-12 col-lg-4">
                  <label>&nbsp;</label><br>
                  <button type="button" id="btn_nuevo_paciente" class="col-lg-6 btn btn-default btn-sm" data-toggle='modal' data-target='#myModalNuevoPaciente' data-keyboard='false' data-backdrop='static'>NUEVO</button>
                  <button type="button" id="btn_buscar_paciente" class="col-lg-6 btn btn-default btn-sm">BUSCAR</button>
              </div>
            </div>
            <div class="row">              
              <div class="col-xs-6 col-md-6 col-lg-3">
                <div class="form-group">
                  <label>F.Nacimiento <span style="color: red;">(*)</span></label> 
                  <input class="form-control" type="text" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= $historia->pac_fecha_nacimiento?>">
                </div>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-1">    
                <div class="form-group">
                  <label>Edad</label>  
                  <input class="form-control" type="text" name="edad" id="edad" value="<?= $historia->pac_edad?>" readonly="">
                </div>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-1">
                <div class="form-group">
                  <label>Mes</label> 
                  <input class="form-control" type="text" name="mes" id="mes" value="<?= $historia->pac_mes?>" readonly="">
                </div>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-1">    
                <div class="form-group">
                  <label>Día</label> 
                  <input class="form-control" type="text" name="dia" id="dia" value="<?= $historia->pac_dia?>" readonly="">
                </div>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-3">    
                <div class="form-group">
                  <label>Teléfono</label> 
                  <input class="form-control" type="text" name="telefono" id="telefono" value="<?= $historia->pac_telefono?>">
                </div>
              </div>
              <div class="col-xs-6 col-md-6 col-lg-3">    
                <div class="form-group">
                  <label>Alergia</label> 
                  <input class="form-control" type="text" name="alergia" id="alergia" value="<?= $historia->pac_alergia?>">
                </div>
              </div>
             </div>
             <div class="row">
              <div class="col-xs-6 col-md-6 col-lg-6">
                <div class="form-group">
                  <label for="especialidad">Especialidad</label>
                    <select class="form-control" id="especialidad" name="especialidad">
                      <option value="">Seleccione</option>
                      <?PHP foreach($especialidades as $value){
                            $selected = ($value->esp_id == $historia->his_especialidad_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->esp_id?>" <?= $selected?>><?= $value->esp_descripcion?></option>
                      <?PHP }?>                        
                    </select>
                </div>                                    
              </div>
              <div class="col-xs-6 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="profesional">Profesional</label>
                    <select class="form-control" id="profesional" name="profesional">
                      <option value="">Seleccione</option>
                      <?PHP foreach($profesionales as $value){
                            $selected = ($value->prof_id == $historia->his_profesional_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->prof_id?>" <?= $selected?>><?= $value->prof_nombre?></option>
                      <?PHP }?>                        
                    </select>
                  </div>                                    
              </div>            
           		<div class="col-xs-6 col-md-6 col-lg-6">
                <div class="form-group">
                  <label for="descripcion">Motivo</label>
                  <input type="text" id="motivo" name="motivo" class="form-control" value="<?php echo $historia->his_motivo;?>">
                </div>                                    
           		</div>
              <div class="col-xs-6 col-md-6 col-lg-3">
                <div class="form-group">
                  <label for="descripcion">Documento Venta</label>
                  <input type="text" id="documento_venta" name="documento_venta" class="form-control" value="<?php echo $historia->his_documento_venta;?>">
                </div>                                    
              </div>   
               <div class="col-xs-6 col-md-3 col-lg-3">
                  <div class="form-group">
                    <label for="estado_documentoVenta">Honorarios</label>
                    <select class="form-control" id="estado_documentoVenta" name="estado_documentoVenta">
                      <option value="">Seleccione</option>
                      <?PHP foreach($historia_estadoComprobante as $value){
                            $selected = ($value->hec_id == $historia->his_historia_estadoComprobante_id) ? 'SELECTED' : '';?>
                          <option value="<?= $value->hec_id?>" <?= $selected?>><?= $value->hec_descripcion?></option>
                      <?PHP }?>                        
                    </select>
                  </div>                                    
                </div>                     
            </div>            
          </div>          
         <div class="panel-footer">     
                <div class="col-xs-6 col-md-12 col-lg-12" style="padding-top: 40px;">
                  <input type="hidden" name="profesional_firma" id="profesional_firma">          
                  <div class="form-group">                    
                    <button type="button" class="btn btn-default col-sm-6" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary col-sm-6" id="btn_guardar_historia">Guardar</button>
                  </div>                                    
                </div>                 
         </div> 
       </div>
          </div>
          <div class="col-xs-12 col-md-6 col-lg-6">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">TRIAJE</div>
              </div>
              <div class="panel-body">         
                <div class="form-group"> 
                <fieldset class="border p-1">
                    <legend  class="w-auto">Inicio</legend>
                  <div class="col-xs-2 col-md-4 col-lg-2">
                      <label>Peso
                        <input type="text" class="form-control" name="peso_ini" name="peso_ini" value="<?= $historia->his_ini_peso?>">
                      </label>
                  </div>
                  <div class="col-xs-2 col-md-4 col-lg-2">
                      <label>Talla
                        <input type="text" class="form-control" name="talla_ini" name="talla_ini" value="<?= $historia->his_ini_talla?>">
                      </label>                  
                  </div>              
                  <div class="col-xs-2 col-md-4 col-lg-2">
                      <label>Temperat.
                        <input type="text" class="form-control" name="temperatura_ini" name="temperatura_ini" value="<?= $historia->his_ini_temperatura?>">
                      </label>                  
                  </div> 
                  <div class="col-xs-3 col-md-4 col-lg-2">    
                      <label>Presion
                        <input type="text" class="form-control" name="presion_arterial_ini" name="presion_arterial_ini" value="<?= $historia->his_ini_presion_arterial?>">
                      </label>                  
                  </div> 
                  <div class="col-xs-3 col-md-4 col-lg-4">    
                      <label>Otros
                        <input type="text" class="form-control" name="otros_ini" name="otros_ini" value="<?= $historia->his_ini_otros?>">
                      </label>                  
                  </div>   
                  </fieldset>              
                </div>
                <div class="form-group">                         
                  <fieldset class="border p-1">
                    <legend  class="w-auto">Final</legend>
                  <div class="col-xs-2 col-md-4 col-lg-2">    
                      <label>Peso
                        <input type="text" class="form-control" name="peso_fin" name="peso_fin" value="<?= $historia->his_fin_peso?>">
                      </label>                  
                  </div>              
                  <div class="col-xs-2 col-md-4 col-lg-2">
                      <label>Talla
                        <input type="text" class="form-control" name="peso_fin" name="peso_fin" value="<?= $historia->his_fin_peso?>">
                      </label>
                  </div>
                  <div class="col-xs-2 col-md-4 col-lg-2">
                      <label>Temperat.
                        <input type="text" class="form-control" name="temperatura_fin" name="temperatura_fin" value="<?= $historia->his_fin_temperatura?>">
                      </label>                  
                  </div> 
                  <div class="col-xs-3 col-md-4 col-lg-2">    
                      <label>Presion
                        <input type="text" class="form-control" name="presion_arterial_fin" name="presion_arterial_ini" value="<?= $historia->his_fin_presion_arterial?>">
                      </label>                  
                  </div> 
                  <div class="col-xs-3 col-md-4 col-lg-4">    
                      <label>Otros
                        <input type="text" class="form-control" name="otros_fin" name="otros_fin" value="<?= $historia->his_fin_otros?>">
                      </label>                  
                  </div>   
                  </fieldset>
                </div>
              </div>  
              <div class="panel-footer">
              </div>  
            </div>

                <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">ESTUDIO DE IMAGENES</div>
              </div>
              <div class="panel-body">
                <div class="form-group">
                 <div class="row">
                  <div id="vista-previa">
                    <div id="images_gallery">
                      <?PHP
                          foreach($historia_imagenes as $image)
                            {              
                              echo '<div class="col-xs-2 col-md-2 col-lg-2" align="center" ><a class="example-image-link" href="'.base_url().'images/historias/'. $image->hii_foto.'" data-lightbox="example-1"><img class="example-image" src="'.base_url().'images/historias/'. $image->hii_foto .'" width="120px" height="100px" style="border:1px solid #ccc;margin-top:10px;" /></a>
                                <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$image->hii_id.'"></span></div>';                              
                            }
                            ?>
                      </div>
                    </div>   
                    </div>                                      
                      <!--<form id="formImgHistoria" method="POST" enctype="multipart/form-data">-->
                      <div class="row alert alert-info">
                        <div class="col-sm-2">
                          <span>Seleccionar imágenes</span>
                        </div>
                        <div class="col-sm-4"><input type="file" id="images" name="images[]" multiple></div>
                      </div>
                      <input type="hidden" name="historia_id_1" id="historia_id_1" value="<?= $historia->his_id;?>">                      
                      <!--</form>                                                                    -->
                  </div>                             
              </div>
              <div class="panel-footer">
              </div>  
            </div>
          </div>
       	</div>
        <!-- FILA ITEMS -->
        <div class="row">
          <div class="col-xs-12 col-md-12 col-lg-12">                 
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">TRATAMIENTO</div>
              </div>
              <div class="panel-body">                
            <table id="tableProducto" class=" table table-streap">
              <thead>
                <tr>
                  <th class="col-xs-2 col-sm-3">Descripción</th>
                  <th class="col-xs-1 col-sm-2">Cantidad</th>
                  <th class="col-xs-4 col-sm-2">Dosificación</th>              
                  <th class="col-xs-1 col-sm-2">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?PHP foreach($historia->detalles  as $value){?>
                      <tr class="cont-item">
                          <td><input type="text" class="form-control descripcion-item" name="descripcion[]" id="descripcion" value="<?= $value->hid_descripcion?>">
                              <div id="data_item"><input type="hidden" name="item_id[]" id="item_id" value="<?= $value->hid_producto_id?>"></div></td>
                          <td><input type="text" class="form-control cantidad" name="cantidad[]" id="cantidad" value="<?= $value->hid_cantidad?>"></td>
                          <td><input type="text" class="form-control" name="dosificacion[]" id="dosificacion" value="<?= $value->hid_dosificacion?>"></td>
                          <td class="eliminarFila"><span class="glyphicon glyphicon-remove"></span></td>
                        </tr>
                <?PHP }?>  
              </tbody>
            </table>            
            <button type="button" class="btn btn-primary btn_agregar_producto">Agregar</button>
            <button type="button" id="btn_buscar_producto" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#myModalProducto" data-keyboard='false' data-backdrop='static'>Buscar Producto</button>
          </div>
        </div>
          </div>
        </div> 



         <!-- FILA ITEMS -->
        <div class="row">
          <div class="col-xs-12 col-md-12 col-lg-12">                 
            <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">OTROS EXAMENES - PROCEDIMIENTOS Y LABORATORIOS</div>
              </div>
              <div class="panel-body">                
            <table id="tableProductoOtros" class=" table table-streap">
              <thead>
                <tr>
                  <th class="col-xs-2 col-sm-3">Descripción</th>
                  <th class="col-xs-1 col-sm-2">Cantidad</th>
                  <th class="col-xs-4 col-sm-2">Obervaciones</th>
                  <th class="col-xs-1 col-sm-2">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <?PHP foreach($historia->otros  as $value){?>
                      <tr class="cont-itemOtros">
                          <td><input type="text" class="form-control descripcion-itemOtros" name="descripcionOtros[]" id="descripcionOtros" value="<?= $value->hio_descripcion?>">
                              <div id="data_itemOtros"><input type="hidden" name="itemOtros_id[]" id="itemOtros_id" value="<?= $value->hio_producto_id?>"></div></td>
                          <td><input type="text" class="form-control cantidad" name="cantidadOtros[]" id="cantidadOtros" value="<?= $value->hio_cantidad?>"></td>
                          <td><input type="text" class="form-control" name="observacionOtros[]" id="observacionOtros" value="<?= $value->hio_observacion?>"></td>
                          <td class="eliminarFilaOtros"><span class="glyphicon glyphicon-remove"></span></td>
                        </tr>
                <?PHP }?>  
              </tbody>              
            </table>
            <button type="button" class="btn btn-primary btn_agregar_productoOtros">Agregar</button>
            <!--<a href="<?= base_url()."index.php/historias/decargarPdf_ticket/".$historia->his_id?>" target="_seld">IMPRIMIR</a>-->            
            <button type="button" id="btn_buscar_productoOtros" class="btn btn-info btn-sm"  data-toggle="modal" data-target="#myModalProducto" data-keyboard='false' data-backdrop='static'>Buscar Producto</button>
            <button type="button" class="btn btn-warning btn-sm btn_imprimir_receta" onclick="javascript:imprimirReceta();">Imprimir Solicitud</button>
          </div>
        </div>
          </div>
        </div> 

        <br><br>
        <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="row">
          <div class="panel panel-primary">
              <div class="panel-heading">
                <div class="panel-title">ENFERMEDAD Y DIAGNOSTICO</div>
              </div>
              <div class="panel-body">
                <fieldset class="border p-1">
                    <legend  class="w-auto">Enfermedad y Diagnóstico</legend>
                <div class="col-xs-6 col-md-4 col-lg-4">
                    <div class="form-group">
                      <label for="codigo_cie">CODIGO CIE - Tipo enfermedad</label>
                      <input type="text" id="codigo_cie" name="codigo_cie" list="lista_codigo_cie" class="form-control" value="<?php echo $historia->his_codigo_cie;?>">
                      <datalist id="lista_codigo_cie"></datalist>
                    </div>
                  </div>
                <div class="col-xs-6 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label for="enfermedad_actual">Enfermedad Actual</label>
                    <input type="text" id="enfermedad_actual" name="enfermedad_actual" class="form-control" value="<?php echo $historia->his_enfermedad_actual;?>">
                  </div>                                    
                </div>
                <div class="col-xs-6 col-md-4 col-lg-4">
                  <div class="form-group">
                    <label for="diagnostico">Diagnóstico</label>
                    <input type="text" id="diagnostico" name="diagnostico" class="form-control" value="<?php echo $historia->his_diagnostico;?>">
                  </div>                                    
                </div>                
                <div class="col-xs-6 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="tratamiento">Examen Físico</label>
                    <input type="text" id="tratamiento" name="tratamiento" class="form-control" value="<?php echo $historia->his_tratamiento;?>">
                  </div>                                    
                </div>                       
                 <div class="col-xs-6 col-md-6 col-lg-2">
                  <div class="form-group">
                    <label for="proxima_cita">Próxima Cita</label>
                    <input type="text" id="proxima_cita" name="proxima_cita" class="form-control" value="<?php echo $historia->his_proxima_cita;?>">
                  </div>                                    
                </div>  
                 <div class="col-xs-6 col-md-6 col-lg-4">
                  <div class="form-group">
                    <label for="recomendacion">Otros exámenes y recomendación</label>
                    <textarea id="recomendacion" name="recomendacion" class="form-control"><?php echo $historia->his_recomendacion;?></textarea>
                  </div>                                    
                </div>  
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
    //$('.modal-admin').css('width', '1800px');
    //$('.modal-admin').css('margin', '100px auto 20px auto');     

    function imprimirReceta(){


      var hoy = new Date();
      diaEntrada = hoy.getDate();

      var fechaEmision = diaEntrada + '-' + (hoy.getMonth()+1) + '-' + hoy.getFullYear();      
      var hora = hoy.getHours() + ':' + hoy.getMinutes();
      //var hora = hoy.getHours() + ':' + hoy.getMinutes() + ':' + hoy.getSeconds();
      var fechaHoraEmision = fechaEmision + ' ' +hora;
      var profesional_firma = $("#profesional_firma").val();
      //alert(profesional_firma);

      var tabla = $('#tableProductoOtros > tbody > tr');
      var productosOtros = ''; 

        $.each(tabla,function(indice,value){   
            var parent = $(this); 

            var descripcionOtros  = $(parent).children().children('#descripcionOtros').val();
                descripcionOtros = descripcionOtros.substr(0,descripcionOtros.indexOf('/'));

            var cantidadOtros     = $(parent).children().children('#cantidadOtros').val();
            var observacionOtros  = $(parent).children().children('#observacionOtros').val();

            productosOtros = productosOtros.concat('<tr><td class="tabla_datos_cantidad">'+cantidadOtros+'</td>\
                                                        <td class="tabla_cabecera">'+descripcionOtros+'</td>\
                                                        <td class="tabla_datos_cantidad">'+observacionOtros+'</td></tr>');
        });        
        //console.log(productosOtros);  
        var ruc = $('#ruc').val();
        var paciente = $('#paciente').val();
        var edad = $('#edad').val();
        var mes  = $('#mes').val();
        var dia  = $('#dia').val();


        var especialidad = $('#especialidad option:selected').text();
        var profesional  = $('#profesional option:selected').text();

        var peso  = $("[name=peso_ini]").val();
        var talla = $("[name=talla_ini]").val();
        var presion  = $("[name=presion_arterial_ini]").val();
        var temperatura = $("[name=temperatura_ini]").val();
        var otros       = $("[name=otros_ini]").val();

        w = window.open();           
        var descripcionOtros =  $("#tableProductoOtros");
        var cantidadOtros    =  $("[name=cantidadOtros]").val();
        var observacionOtros =  $("[name=observacionOtros]").val();
        //console.log(descripcionOtros);

      /*<?php 
          //$ruta_foto = base_url()."images/".$empresa->foto;          
      ?>*/

      console.log('<img class="historia" src="<?= base_url()?>images/profesional/firma/'+profesional_firma+'">');
       w.document.write('<html><head>');
       w.document.write('<style>\
            html, body {\
              margin: 0 2px;\
              padding: 0;\
              font-family: sans-serif;\
            }\
            span #height-container { position: absolute; left: 0px; right: 0px; top: 0px; }\
            .datos_titulo1{\
                font-size: 5px;\
                text-align: center;\
                line-height: 1em;\
            }\
            img.historia{\
                width: 70px;\
                height: 70px;\
                margin-left: 15px;\
            }\
            .tabla_cabecera{\
                font-size: 5px;\
            }\
            .tabla_datos{\
                font-size: 4px;\
                text-align: center;\
            }\
            .tabla_datos_cantidad{\
                font-size: 5px;\
                text-align: center;\
            }\
            .datos_totales{\
                font-size: 5px;\
            }\
            .datos_totales_bold{\
                font-size: 6px;\
                font-weight: bold;\
            }\
            .datos_cabecera{\
                font-size: 4.3px;\
                text-align: center;\
                line-height: 1em;\
            }\
            .datos_cabecera_bold{\
                font-weight: bold;\
            }\
            .datos_cliente{\
                text-align: left;\
                margin-left: 4px;\
                font-size: 4.5px;\
                line-height: 1em;\
            }\
        </style>');

       w.document.write('</head><body>');
      //<img src="<?php //echo "images/".$empresa->foto;?>" height="80" width="100%" style="text-align:center;" border="0">
       w.document.write('<span id="height-container">\
            <p class ="datos_titulo1 cabecera">\
                <span class="datos_cabecera_bold"><?php echo $empresa['empresa']?></span><br>\
                <span class="datos_cabecera_bold">RUC : <?php echo $empresa['ruc']?></span><br>\
                <span class="datos_cabecera"><?php echo $empresa['domicilio_fiscal']?></span><br>\
                <?php
                if($almacen_principal->ver_direccion_comprobante == 1){?>
                <span class="datos_cabecera_bold">Dirección almacén: <?php echo $almacen_principal->alm_direccion?></span><br>\
                <?php }?>
                <b><?php echo "Solicitud Médica"; ?>&nbsp;&nbsp;<?php echo "SM-".str_pad($historia->his_correlativo, 8, "0", STR_PAD_LEFT)?></b><br>\
                -------------------------------------------------------------<br>\
                <!--<b><?php echo "Nota de Venta"; ?>&nbsp;&nbsp;<?php echo "NP-".str_pad($historia->notap_correlativo, 8, "0", STR_PAD_LEFT)?></b><br>-->\
                Fecha/hora emision: '+ fechaHoraEmision +'<br>\
                Usuario : <?php echo $this->session->userdata('usuario'). " ". $this->session->userdata('apellido_paterno'); ?><br>\
                Profesional : '+ profesional +'<br>\
                Especialidad : '+ especialidad +'<br>\
                --------------------------------------------------------------<br></p>\
            <p class="datos_cliente">\
                Paciente: '+ paciente +'<br>\
                DNI: '+ ruc +'<br>\
                EDAD: '+ edad +' '+ mes + ' '+ dia +'<br>\
                -----------------------------------------------------------------------------------------------------------<br>\
            </p>\
            <p class="datos_cliente">\
                Peso: '+ peso +' Talla '+ talla +' Presion '+ presion +'<br>\
                Temperatura: '+ temperatura+'<br>\
                Otros: '+ otros +'<br>\
                -----------------------------------------------------------------------------------------------------------<br>\
            </p>\
            <table width="100%">\
                <thead>\
                    <tr>\
                        <th width="8" align="center" class="tabla_cabecera">CANT.</th>\
                        <th width="35" class="tabla_cabecera">PRODUCTO</th>\
                        <th width="10" align="center" class="tabla_cabecera">OBSERVACIONES</th>\
                    </tr>\
                </thead>\
                <tbody>'+productosOtros+         
                '</tbody>\
            </table>\
            <p align="center" class ="datos_titulo1">\
            ------------------------------------------------------------------------------------------------<br>\
            </p>');

            w.document.write('<table>\
                <tr>\
                  <td colspan="2">\
                    <div id="images_gallery">\
                              <img class="historia" src="<?= base_url()?>images/profesional/firma/'+profesional_firma+'">\
                    </div>\
                  </td>\
          </tr>\
            </table>\
        </span><br>');

          /*<tr>\
              <td colspan="2">\
                <div id="images_gallery">\
                          <img class="historia" src="<?php //echo 'images/profesional/firma/'.$historia->prof_firma;?>">\
                </div>\
              </td>\
          </tr>\          
           w.document.write('<table>\
                <tr>\
                    <td class="datos_totales">CODIGO CIE</td>\
                    <td class="datos_totales"><?php echo $historia->his_codigoCEI_descripcion?></td>\
                </tr>\
                <tr>\
                    <td class="datos_totales">ENFERMEDAD ACTUAL :</td>\
                    <td class="datos_totales"><?php echo $historia->his_enfermedad_actual?></td>\
                </tr>\
                <tr>\
                    <td class="datos_totales">MOTIVO :</td>\
                    <td class="datos_totales"><?php echo $historia->his_motivo?></td>\
                </tr>\
                <tr>\
                    <td class="datos_totales">DIAGNOSTICO :</td>\
                    <td class="datos_totales"><?php echo $historia->his_diagnostico?></td>\
                </tr>\
                <tr>\
                     <td class="datos_totales">EXÁMEN FÍSICO :</td>\
                    <td class="datos_totales"><?php echo $historia->his_tratamiento?></td>\
                </tr>\
                <tr>\
                     <td class="datos_totales">OTROS EXÁMENES Y RECOMENDACIÓN</td>\
                     <td class="datos_totales"><?php echo $historia->his_recomendacion?></td>\
                </tr>\
                <tr>\
                    <td class="datos_totales">PRÓXIMA CITA :</td>\
                    <td class="datos_totales"><?php echo $historia->his_fecha_cita?></td>\
                </tr>\
            </table>\
        </span><br>');

          */
        w.document.write('</body></html>');
                //w.document.write(printContents);
                w.document.close(); // necessary for IE >= 10
                w.focus(); // necessary for IE >= 10
                w.print();
                //w.close();
          return true;
    }

    //FUNCIONES//
    //AGREGANDO FILA
    function agregarFila(){
      var fila =  '<tr class="cont-item">\
                          <td><input type="text" class="form-control descripcion-item" name="descripcion[]" id="descripcion">\
                              <div id="data_item"><input type="hidden" name="item_id[]" id="item_id"></div></td>\
                          <td><input type="text" class="form-control cantidad" name="cantidad[]" id="cantidad" value="1"></td>\
                          <td><input type="text" class="form-control" name="dosificacion[]" id="dosificacion"></td>\
                          <td class="eliminarFila"><span class="glyphicon glyphicon-remove"></span></td>\
                        </tr>';
      $("#tableProducto tbody").append(fila);      
    }

    //AGREGANDO FILA OTROS 08-02-2021
    function agregarFilaOtros(){
      var fila =  '<tr class="cont-itemOtros">\
                          <td><input type="text" class="form-control descripcion-itemOtros" name="descripcionOtros[]" id="descripcionOtros">\
                              <div id="data_itemOtros"><input type="hidden" name="itemOtros_id[]" id="itemOtros_id"></div></td>\
                          <td><input type="text" class="form-control cantidad" name="cantidadOtros[]" id="cantidadOtros" value="1"></td>\
                          <td><input type="text" class="form-control" name="observacionOtros[]" id="observacionOtros"></td>\
                          <td class="eliminarFilaOtros"><span class="glyphicon glyphicon-remove"></span></td>\
                        </tr>';
      $("#tableProductoOtros tbody").append(fila);
    }

    //AGREGAR FILA
    $(".btn_agregar_producto").click(function(){
        agregarFila();
    });

    //AGREGAR FILA OTROS 08-02-2021
    $(".btn_agregar_productoOtros").click(function(){
        agregarFilaOtros();
    });

    //ELIMINAR FILA
    $(document).on("click",".eliminarFila",function(){
        $(this).parent().remove();
    });   

    //ELIMINAR FILA OTROS 08-02-2021
    $(document).on("click",".eliminarFilaOtros",function(){
        $(this).parent().remove();
    });   


    //CARGAR PROFESIONALES - ALEXANDER FERNANDEZ 13-11-20
    $("#especialidad").change(function(){
      var idEspecialidad = $("#especialidad").val();
      $.ajax({
        url: '<?= base_url()?>index.php/especialidades/cargarProfesionales',
        dataType: 'HTML',
        method: 'POST',
        data: {idEspecialidad:idEspecialidad},
        success: function(response){
          $("#profesional").html(response);          
        }
      });
    });

    //CARGAR FIRMA PROFESIONAL - 11-02-2021
    $("#profesional").change(function(){
        cargarFirmaProfesional();
    });
    //CARGAR FIRMA PROFESIONAL - 11-02-2021
    function cargarFirmaProfesional(){
      var idProfesional = $("#profesional").val();
      $.ajax({
        url: '<?= base_url()?>index.php/profesionales/cargarEspecialidad',
        dataType: 'JSON',
        method: 'POST',
        data: {idProfesional:idProfesional},
        success: function(response){
          $("#profesional_firma").val(response.prof_firma);
        }
      });
    }
    
    //BUSCAR PRODUCTO ALEXANDER FERNANDEZ 06-11-2020
    $('body').delegate('.descripcion-item', 'keydown', function() {
        $('.descripcion-item').autocomplete({
            source : '<?PHP echo base_url();?>index.php/historias/buscador_item',
            minLength : 2,
            select : function (event,ui){
                var _item = $(this).closest('.cont-item');
                var data_item = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "item_id[]" id = "item_id">';
                _item.find('#data_item').html(data_item);
                _item.find('#descripcion').attr("readonly",true);
                _item.find('#medida').val(ui.item.medida);                                                                          
            }            
        });
    });


    //BUSCAR PRODUCTO OTROS EXÁMENES ALEXANDER FERNANDEZ 06-11-2020
    $('body').delegate('.descripcion-itemOtros', 'keydown', function() {
        $('.descripcion-itemOtros').autocomplete({
            source : '<?PHP echo base_url();?>index.php/historias/buscador_item',
            minLength : 2,
            select : function (event,ui){
                var _item = $(this).closest('.cont-itemOtros');
                var data_itemOtros = '<input class="val-descrip"  type="hidden" value="'+ ui.item.id + '" name = "itemOtros_id[]" id = "itemOtros_id">';
                _item.find('#data_itemOtros').html(data_itemOtros);
                _item.find('#descripcionOtros').attr("readonly",true);
                //_item.find('#medida').val(ui.item.medida);
            }            
        });
    });



    //BUSCAR PACIENTE ALEXANDER FERNANDEZ 06-11-2020
    $("#btn_buscar_paciente").on("click",function(){            
        consulta_dniPaciente();            
    })
    //AUTOCOMPLETE PRODUCTO - ALEXANDER FERNANDEZ DE LA CRUZ
  	$(document).ready(function(){

      $("#btn_guardar_historia").click(function(){
          $("#formHistoria").submit();
      })

  		//guardar Historia
  		$("#formHistoria").submit(function(e){
  			e.preventDefault();
  			$(".has-error").removeClass('has-error');

  			$.ajax({
  				url:'<?php echo base_url()?>index.php/historias/guardarHistoria',
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
  						toast('success', 1500, 'se registro la historia');
  						//dataSource.read();
  						$("#myModal").modal('hide');
                setTimeout(function() { 
                      location.href='<?PHP echo base_url()?>index.php/historias/index/'+response.his_id;
                }, 2000);
  					}
  				}
  			});  					
  		});

      //CARGAR IMAGENES DE HISTORIA - ALEXANDER FERNANDEZ DE LA CRUZ 18-11-2020
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


    //CARGAR MODAL NUEVO PACIENTE 26-11-2020
    $("#btn_nuevo_paciente").on('click',function(e){
        e.preventDefault();
        $("#myModalNuevoPaciente").load("<?= base_url()?>index.php/pacientes/modal_nuevoPaciente",{});
    });


    //ELIMINAR IMAGEN - ALEXANDER FERNANDEZ DE LA CRUZ 29-11-2020
      $(document).on('click','.eliminarImagen',function(e){

        e.preventDefault();
        var idHistoria = $('#historia_id_1').val();
        var idHistoriaImagen = $(this).data('id');
        var msg = 'Está seguro de eliminar imagen??';
        var url = '<?= base_url()?>index.php/historias/eliminarHistoriaImagen/'+idHistoriaImagen+'/'+idHistoria;
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

      //CARGAR MODAL BUSCAR PRODUCTO
      $(document).on("click",'#btn_buscar_producto',function(e){        
        e.preventDefault();
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProductoHistoria",{});
      });

      //CARGAR MODAL BUSCAR PRODUCTO OTROS
      $(document).on("click",'#btn_buscar_productoOtros',function(e){        
        e.preventDefault();
        $("#myModalProducto").load("<?= base_url()?>index.php/productos/modal_buscarProductoHistoriaOtros",{});
      });


      //CARGAR CODIGO CIE
      $("#codigo_cie").on("keyup",function(){
          var texto =  $(this).val();
          $.getJSON("<?= base_url()?>index.php/historias/buscar_codigoCIE",{texto})
          .done(function(json){
            var html = '';
            $.each(json,function(index,value){
              html += '<option value="'+ value.descripcion+"|"+'">';
            })

            $("#lista_codigo_cie").html(html);
          })
      })
      //SELECT CODIGO CIE
      $("#codigo_cie").on("change",function(){
          var opcion = $(this).val();
          var guion  = opcion.search("-");
          var cod    = opcion.substr(0,guion-1);

          $.getJSON("<?= base_url()?>index.php/historias/seleccionar_codigoCIE",{cod})
          .done(function(json){

          })
      })
  	});  
  </script>
