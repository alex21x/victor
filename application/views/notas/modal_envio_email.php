<style type="text/css">
  h1{     
    font-size: 50px;
  }
  h5{
    font-weight: bold;
  }

  .row{
    margin-top: 20px;
    margin-left: 20px;
  }
  i.enviarComprobante{
    font-size:100px;
  }

</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">ENVIAR COMPROBANTE</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="formEnviarEmail">
            <input type="hidden" name="notap_id" id="notap_id" value="<?= $nota->notap_id?>">
              <div class="col-xs-12 col-md-12 col-lg-12">
                <label>Correo
                <input class="form-control col-xs-12 col-md-12 col-lg-12" type="text" name="correo" id="correo" value="<?= $nota->email?>">
            </label>
          </div>
          </form>
	       </div>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn_cerrar" data-dismiss="modal">Regresar</button>
        <button type="button" class="btn btn-primary enviarEmail">Enviar Email</button>
      </div>
    </div>
  </div>

<script>

$(document).ready(function(){
  $(document).on("click",".enviarEmail",function(){
    
    $.ajax({
      url: '<?= base_url()?>index.php/notas/modal_envio_email_g',
      method: 'POST',
      dataType: 'JSON',
      data: $("#formEnviarEmail").serialize(),
      success: function(response) {
          if(response.status == -1){
            toast("danger",3000,response.msg);
          }else if(response.status == 2){
            toast("success",3000, response.msg);
          }
      }
    });
  });

  $(document).on("click",".btn_cerrar",function(){
    var notap_id =  $("#notap_id").val();
    setTimeout(function() { 
      location.href='<?PHP echo base_url()?>index.php/notas/index/'+notap_id;
       }, 2);
  });
 }); 
</script>



