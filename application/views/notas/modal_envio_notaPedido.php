<style type="text/css">
  h1{     
    font-size: 50px;
    font-weight: bold;
  }
  h2{         
    font-weight: bold;
    font-size: 35px;
  }
  h3{         
    font-weight: bold;    
  }

  h5{         
    font-weight: bold;
  }
  .row{
    margin-top: 20px;
    margin-left: 20px;
    text-align: center;
  } 

  
   @media (min-width:0px) {
      h2 {
        font-size: 15px;
      }
       .enviarComprobante{
          height: 80px;
          width: 90px;
          cursor: pointer;
        }
      .enviarComprobanteTicket{
        height: 100px;
        width: 110px;
        cursor: pointer;
      }

      .enviarComprobantePdf{
        height: 80px;
        width: 70px;
        cursor: pointer;
      }
      .enviarComprobanteCorreo{
        margin-top: 18px;
        margin-left: 10px;
        height: 60px;
        width: 60px;
        cursor: pointer;
      }
      .enviarComprobanteCorreo:hover{
        background: #F39C12;
      }}    
    @media (min-width: 768px) {
        .btn_buscar {
       margin-top: 8px;
       width: 70px;
       font-size: 13px;
    }}
    @media (min-width: 992px) {
      h2 {
        font-size: 40px;
      }
      .enviarComprobante{
          height: 151px;
          width: 166px;
          cursor: pointer;
        }
      .enviarComprobanteTicket{
        height: 220px;
        width: 190px;
        cursor: pointer;
      }

      .enviarComprobantePdf{
        height: 160px;
        width: 150px;
        cursor: pointer;
      }
      .enviarComprobanteCorreo{
        margin-top: 40px;
        margin-left: 20px;
        height: 120px;
        width: 130px;
        cursor: pointer;
      }
      .enviarComprobanteCorreo:hover{
        background: #F39C12;
      }}

</style>
  <div class="modal-dialog  modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"><?= $tipo_documento?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">  
        <div class="row">              
        <div class="col-xs-12 col-md-12 col-lg-12">          
            <h2><?= $tipo_documento.' / NP- '.str_pad($nota->notap_correlativo, 8, "0", STR_PAD_LEFT);?></h2>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-12">            
            <h3><?= $nota->ruc.' / '.$nota->razon_social;?></h3>
        </div>
        <div class="col-xs-12 col-md-12 col-lg-12">            
            <h3><?= 'Monto: '.$nota->simbolo.$nota->notap_total;?></h3>
        </div>
        </div>
        <div class="row">          
          <div class="col-xs-6 col-md-6  col-lg-6">
            <span id="enviarWatsap" data-id = '<?= $nota->notap_id; ?>'><img src="<?= base_url()?>/images/whatsap.png" class="enviarComprobante" title="enviar por wasap"></span>
        </div>        
        <div class="col-xs-6 col-md-6 col-lg-6">
           <span id="enviarPdf" data-id = '<?= $nota->notap_id; ?>'><img src="<?= base_url()?>/images/pdf_2.png" class="enviarComprobantePdf" title="enviar por pdf"></span>
        </div>        

        </div>
        <div class="row">
        <div class="col-md-6 col-xs-6 col-xs-6">
          <span id="enviarTicket" data-id = '<?= $nota->notap_id; ?>'><img src="<?= base_url()?>/images/ticketera.png" class="enviarComprobanteTicket" title="enviar por ticket"></span></div>
        
        <div class="col-md-6 col-xs-6 col-xs-6">
          <span id="enviarEmail" data-id = '<?= $nota->notap_id; ?>'><img src="<?= base_url()?>/images/correo.png" class="enviarComprobanteCorreo" title="enviar por correo"></span>
        </div>
        </div>        
     </div>	               
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn_cerrar" data-dismiss="modal">Cerrar</button>
        <!--<button type="button" class="btn btn-primary btn_agrega_precio">Save changes</button>-->
      </div>
  </div>
  </div>

<script>

$(document).ready(function(){

  $(document).on("click","#enviarEmail",function(){

    var notap_id = $(this).data("id");
    //alert(comprobante_id);
    $("#myModal").load("<?= base_url();?>index.php/notas/modal_envio_email/"+notap_id,{});

  });

  $(document).on("click","#enviarWatsap",function(){

    var notap_id = $(this).data("id");
    //alert(comprobante_id);
    $("#myModal").load("<?= base_url();?>index.php/notas/modal_envio_whatsap/"+notap_id,{});
  });


   $(document).on("click","#enviarPdf",function(){

    var notap_id = $(this).data("id");
    javascript:window.open('<?PHP echo base_url() ?>index.php/notas/decargarPdf/'+notap_id,'','width=750,height=600,scrollbars=yes,resizable=yes');
  });


   $(document).on("click","#enviarTicket",function(){
    
    var notap_id = $(this).data("id");
    javascript:window.open('<?PHP echo base_url() ?>index.php/notas/decargarPdf_ticket/'+notap_id+'','','width=750,height=600,scrollbars=yes,resizable=yes');                                
  });
 }); 
</script>



