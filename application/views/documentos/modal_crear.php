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
        <h4 class="modal-title">Registrar nuevo Documento</h4>
      </div>
      <div class="modal-body" style="height:500px;">
       <form id="formEvento">
        <input type="hidden" id="id" name="id" value="<?php echo $documento->doc_id;?>">        
          <div class="row">
          <div class="col-xs-7 col-md-12 col-lg-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="panel-title">Nuevo Documento</div>
              </div>
              <div class="panel-body">
                <fieldset class="border p-1">
                    <legend  class="w-auto">Documento </legend>
            <div class="row">            
                <div class="col-xs-12 col-md-12 col-lg-12">
                  <div class="form-group">
                    <label for="tipo_evento">Nombre Doc.</label>
                    <input class="form-control" type="text" name="descripcion" id="descripcion" value="<?php echo $documento->descri_doc;?>">
                  </div>
                </div>                                          
            </div>
              <div class="row">
                <output id="list">
                  <?PHP
                          foreach($documento->detalles as $value)
                            {              
                              echo '<div class="col-xs-12 col-md-12 col-lg-12"><li><strong>'.$value->descri_archi. '</strong>
                              <span '.$this->session->userdata('accesoEmpleado').' class="glyphicon glyphicon-remove eliminarImagen" data-id="'.$value->archi_id.'"></span></li></div>';                            
                            }
                  ?>
                </output>                                        
              </div><br>
            <div class="row">
              <div class="col-md-12">             
              <div class="form-group">
              <label for="images">Subir archivos</label>
              <input type="file" id="images" name="images[]" class="form-control input-sm" multiple>
            </div>                                    
          </div>
        </div>
                                           
          </div>                  
         <div class="panel-footer">     
                <div class="col-xs-12 col-md-12 col-lg-12" style="padding-top: 40px;">        
                  <div class="form-group">                    
                    <button type="button" class="btn btn-default col-sm-6" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary col-sm-6" id="btn_guardar_evento">Guardar</button>
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
     
    $(document).ready(function(){      

      $("#btn_guardar_evento").click(function(){
          $("#formEvento").submit();
      })

      //guardar Historia
      $("#formEvento").submit(function(e){
        e.preventDefault();
        $(".has-error").removeClass('has-error');

        $.ajax({
          url:'<?php echo base_url()?>index.php/documentos/guardar_documento',
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
              //dataSource.read();
              $("#myModal").modal('hide');
                setTimeout(function() { 
                      location.href='<?PHP echo base_url()?>index.php/documentos/index/'+response.descri_doc;
                }, 2000);
            }
          }
        });           
      });

    $("#images").on("change",function(){
            //$("#list").html('');
            var archivos = document.getElementById('images').files;
            var navegador = window.URL || window.webkitURL;

           /* Recorrer los archivos */
          var output = [];
          for(x=0; x<archivos.length; x++){
               /* Validar tamaño y tipo de archivo */
               var size = archivos[x].size;
               var type = archivos[x].type;
               var name = archivos[x].name;

               var objeto_url = navegador.createObjectURL(archivos[x]);

               output.push('<li><strong>', archivos[x].name, '</strong> ', '</li>');
          }

          $("#list").append('<ul>' + output.join('') + '</ul>');

    });

    $("#cliente").autocomplete({
        source: '<?= base_url()?>index.php/clientes/buscador_cliente',
        minLength: 2,
        select: function(event,ui){
          var data_cli = '<input type="hidden" value="'+ ui.item.id+'" name="cliente_id" id="cliente_id">';
          $("#data_cli").html(data_cli);                        
        }
      });


      //ELIMINAR DOCUMENTO - ALEXANDER FERNANDEZ DE LA CRUZ 06-03-2021
      $(document).on('click','.eliminarImagen',function(e){
        e.preventDefault();
        var idDocumento = $('#id').val();
        var idDocumentoArchivo = $(this).data('id');

        //console.log(idDocumentoArchivo);
        var msg = 'Está seguro de eliminar imagen??';
        var url = '<?= base_url()?>index.php/documentos/eliminarDocumentoArchivo/'+idDocumentoArchivo+'/'+idDocumento;
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
                      toast('success',1500,'Documento eliminado');
                      $("#list").html('');
                      $("#list").append(response);
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
</script>