<script type="text/javascript">
    
    $(document).ready(function(){

        $("#fechaApertura").datepicker();
        $("#fechaCierre").datepicker();

    });
</script>

    <div class="container">

        <form id="formBusqueda">
        <div class="col-xs-6 col-md-3 col-lg-3">            
            <label>F.Apertura
                <input type="text" class="form-control" id="fechaApertura" name="fechaApertura">
            </label>
        </div>

        <div class="col-xs-6 col-md-3 col-lg-3">
            <label>Fecha Hasta            
                <input type="text" class="form-control" id="fechaCierre" name="fechaCierre">
            </label>           
        </div>
        <div class="col-xs-12 col-md-6 col-lg-3">
          <label>Vendedor
            <select class="form-control" name="vendedor" id="vendedor">
              <option value="">Seleccionar</option>
            <?PHP foreach($vendedores as $empleado){?>      
              <option value="<?php echo $empleado['id']?>"><?php echo $empleado['apellido_paterno']." ".$empleado['apellido_materno'].", ".$empleado['nombre'] ?></option>
            <?PHP }?>
            </select> 
          </label>
        </div>  
        <div class="col-xs-6 col-md-2 col-lg-2">            
            <label>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-primary" id="buscarCaja">BUSCAR</button>
            </label>
        </div>

        </form>
    </div>    
    <div class="container-fluid">
    <div class="col-xs-12 col-md-12 col-lg-12">
    <h1>Cajas</h1>    
    <div>
        <a class="btn_agregar_cajaMov btn btn-primary" data-toggle="modal" data-target="#modalCajaMov">Apertura</a>
        <input type="hidden" id="tipoTransaccion" name="tipoTransaccion" value="<?= $caja->tipo_transaccion_id?>">
    </div>
    <br>
    <div id = "rowCajaMov">        
    </div>
</div>
</div>
<script type="text/javascript">

 $(document).ready(function(){
  function tipoTransaccion(){
        var tipoTransaccionId = $("#tipoTransaccion").val();
        if(tipoTransaccionId == 1){
            $(".btn_agregar_cajaMov").removeClass().addClass( "btn_cerrar_cajaMov btn btn-danger");
            $("a.btn_cerrar_cajaMov").attr("data-target","#modalCajaMovCierre").attr("data-id", '<?= $caja->id?>').text("Cierre de Caja");
        }

  }
  

  function selectRowsCajamoviento(){
    $.ajax({
        url: '<?= base_url()?>index.php/cajas/selectRowsCajamoviento',
        method: 'POST',
        dataType: 'HTML',
        data: $("#formBusqueda").serialize(),
        success: function(response){
            $("#rowCajaMov").html(response);
            $("#tablaCaja").DataTable({
                "lengthMenu": [[30, 50, -1], [30, 50, "All"]],
                "order": [[ 0, "asc" ]]
            });
            tipoTransaccion();

        }
    });
  }  
  selectRowsCajamoviento();


    $("#buscarCaja").click(function(){        
        selectRowsCajamoviento();
    });

    //MODAL APERTURA DE CAJA
    $(document).on("click",".btn_agregar_cajaMov",function(){
        $("#modalCajaMov").load("<?= base_url()?>index.php/cajas/apertura",{});
    });


    //MODAL CIERRE CAJA    
    $(document).on("click",".btn_cerrar_cajaMov",function(){
        //alert(123);
        var caja_id = $('.btn_cerrar_cajaMov').attr("data-id");
        //alert(caja_id);
        $("#modalCajaMovCierre").load("<?= base_url()?>index.php/cajas/cierre/"+caja_id);
    }); 


    //PDF
    $(document).on("click","#pdfCaja",function(){
        var cajaId = $(this).data("id");        
        javascript:window.open('<?= base_url()?>index.php/cajas/pdfCaja/'+cajaId,'','width=750,height=600,scrollbars=yes,resizable=yes');
    });

    //IMPRIMIR TICKET
    var PrintWindow;
    $(document).on("click","#ticketCaja",function(){
        var cajaId = $(this).data("id");
        PrintWindow = window.open('<?= base_url()?>index.php/cajas/ticketCajapdf/'+cajaId,'','width=750,height=600,scrollbars=yes,resizable=yes');
        //setTimeout(function(){ PrintWindow.close();},400);
    });
});
  </script>