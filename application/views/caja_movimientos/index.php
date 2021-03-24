<script type="text/javascript">
  
  $(document).ready(function(){
    $("#fecha_desde").datepicker();
    $("#fecha_hasta").datepicker(); 
  });
</script>

<br><br>
  <div class="container">
  <h3><b>MOVIMIENTOS DE CAJA</b></h3><br>
  <form id="formMovCaj">
  <div class="row">
    <div class="col-xs-6 col-md-3 col-lg-3">
      <label>Fecha Desde
        <input class="form-control" name="fecha_desde" id="fecha_desde">
      </label>
    </div>  
    <div class="col-xs-6 col-md-3 col-lg-3">
      <label>Fecha Hasta
        <input class="form-control" name="fecha_hasta" id="fecha_hasta">
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
      <label>&nbsp;
        <button id="buscarMovCaja" type="button" class="btn btn-primary btn-block">BUSCAR</button>
      </label>
    </div>  
    <div class="col-xs-6 col-md-2 col-lg-1">
      <label>&nbsp;<br>
        <a id="exportarMovCaj" href="#" class="btn btn-success btn-sm colbg">Eportar excel</a>
      </label>
    </div>    
  </div>    
  </form>
</div>


	<div class="container-fluid"><br>  
    <div>
        <a class="btn btn-default btn_agregar_cajaMov btn btn-primary" data-toggle="modal" data-target="#modalCajaMov">Nuevo</a>
    </div>
    <br>
    <div id = rowCajaMov>
    </div>
  </div>
<script type="text/javascript">

  function selectRowsCajamoviento(){
    $.ajax({
        url: '<?= base_url()?>index.php/movimiento_caja_controlador/selectRowsCajamoviento',
        method: 'POST',              
        dataType: 'HTML',
        data: $("#formMovCaj").serialize(),
        success: function(response){
            $("#rowCajaMov").html(response);
        }
    });
  }

  selectRowsCajamoviento();
  $(document).on("click","#buscarMovCaja",function(){
      selectRowsCajamoviento();
  });

  $(document).on("click",".btn_modificar",function(e){
        e.preventDefault();        
        var idCajamovimiento = $(this).data('id');
        console.log(idCajamovimiento);
    $("#modalCajaMov").load('<?= base_url()?>index.php/movimiento_caja_controlador/modificar/'+idCajamovimiento,{});
  });


  $(".btn_agregar_cajaMov").click(function(e){  
      e.preventDefault();   
      $("#modalCajaMov").load('<?= base_url()?>index.php/movimiento_caja_controlador/nuevo/',{});

  });

  $(document).on("click",".btn_eliminar",function(e){
        e.preventDefault();        
        var idCajamovimiento = $(this).data('id');
        console.log(idCajamovimiento);
      

      $.ajax({
        url: '<?= base_url()?>index.php/movimiento_caja_controlador/eliminar/'+idCajamovimiento,
        dataType: 'JSON',
        method: 'GET',
        data: $("#formCajaMov").serialize(),
        success: function(response){
          if(response.status == 2){           
            selectRowsCajamoviento();                        
          }
        }
      });
  });

  $('#exportarMovCaj').click(function() {
      
    datos = $("#formMovCaj").serialize();    
    vendedorText = ($("#vendedor option:selected").val() != '') ? $("#vendedor option:selected").text() : '';    
        
        var url ='<?PHP echo base_url() ?>index.php/movimiento_caja_controlador/exportarMovCaj?'+datos+'&vendedorText='+vendedorText;
        window.open(url, '_blank');

    });
</script>
