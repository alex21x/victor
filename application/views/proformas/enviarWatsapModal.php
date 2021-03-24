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

  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">ENVIAR WHATSAP</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container">
        <form id="formEnviarWatsap">
        <input type="hidden" name="proforma_id" id="proforma_id" value="<?= $proforma->prof_id;?>">
        <input type="hidden" id="cli_razon_social" name="cli_razon_social" value="<?=$proforma->razon_social;?>">
          <div class="col-xs-12 col-md-12 col-lg-12">
            <label>Numero
              <input class="form-control" type="number" name="telefono_movil" id="telefono_movil" value="<?= $proforma->telefono_movil_1;?>">              
            </label>
          </div>
	       </div>        
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn_cerrar" data-dismiss="modal">Regresar</button>
        <button type="button" class="btn btn-primary enviarWatsap">Enviar Watsap</button>
      </div>
    </div>
  </div>

<script>

$(document).ready(function(){

  $(document).on("click",".enviarWatsap",function(){    
    var proforma_id = $("#proforma_id").val();
    var telefono_movil = $("#telefono_movil").val();
    var cli_razon_social =  $("#cli_razon_social").val();


    var url_pdf = '%0AFORMATO%20A4%20:<?= base_url()?>'+'index.php/download/downloadPdf/'+proforma_id;
    var url_pdfTicket = '%0AFORMATO%20TICKET%20:<?= base_url()?>'+'index.php/download/downloadPdfTicket/'+proforma_id;    
    $.ajax({
      url: '<?= base_url()?>index.php/proformas/enviarWatsapModal_g',
      method: 'POST',
      dataType: 'JSON',
      data: $("#formEnviarWatsap").serialize(),
      success: function(response) {
          if(response.status == -1){
            toast("danger",3000,response.msg);
          }else if(response.status == 2){
            toast("success",3000, response.msg);
            setTimeout(function() {
              var a = document.createElement('a');
              a.href="https://api.whatsapp.com/send?phone=51"+telefono_movil+"&text=Gracias por tu Compra "+cli_razon_social+", desde los siguientes enlaces puedes descargar tu comprobante electrónico   "+url_pdf+" "+url_pdfTicket;
              a.target = '_blank';
              document.body.appendChild(a);
              a.click();
              }, 2000);
          }
      }
    });    

  });

  $(document).on("click",".btn_cerrar",function(){
    var comprobante_id =  $("#comprobante_id").val();
    setTimeout(function() { 
      location.href='<?PHP echo base_url()?>index.php/comprobantes/index/'+comprobante_id;
       }, 2);
  });
 }); 
</script>



