
<div class="container" style="margin-bottom:10px;">
  <h3>Registro de Compras Electrónico</h3>
</div>

<div class="container">
  <div class="row" style="border:2px solid #F2F4F4;padding: 20px 10px;border-radius:5px;">
    <div class="col-lg-4">
       <select id="mes" class="form-control input-sm">
           <option value="0">Mes</option>
           <?php if(intval(date('m'))>1){ ?><option value="01">Enero</option><?php }?>
           <?php if(intval(date('m'))>2){ ?><option value="02">Febrero</option><?php } ?>
           <?php if(intval(date('m'))>3){ ?><option value="03">Marzo</option><?php } ?>
           <?php if(intval(date('m'))>4){ ?><option value="04">Abril</option><?php } ?>
           <?php if(intval(date('m'))>5){ ?><option value="05">Mayo</option><?php } ?>
           <?php if(intval(date('m'))>6){ ?><option value="06">Junio</option><?php } ?>
           <?php if(intval(date('m'))>7){ ?><option value="07">Julio</option><?php } ?>
           <?php if(intval(date('m'))>8){ ?><option value="08">Agosto</option><?php } ?>
           <?php if(intval(date('m'))>9){ ?><option value="09">Setiembre</option><?php } ?>
           <?php if(intval(date('m'))>10){ ?><option value="10">Octubre</option><?php } ?>
           <?php if(intval(date('m'))>11){ ?><option value="11">Noviembre</option><?php } ?>
           <?php if(intval(date('m'))>12){ ?><option value="12">Diciembre</option><?php } ?>
       </select>
    </div>
    <div class="col-lg-3">
       <select id="anio" class="form-control input-sm">
           <option value="0">Año</option>
            <?php for($i=date('Y')-1;$i<=date('Y');$i++){ ?>
             <option value="<?php echo $i;?>"><?php echo $i;?></option>
           <?php } ?>
       </select>
    </div>
    <div class="col-lg-4">
       <select id="operaciones" class="form-control input-sm">
           <option value="3">Indicador de operaciones</option>
           <option value="1">Empresa o entidad operativa</option>
           <option value="2">Cierre del libro - no obligado a llevarlo</option>
           <option value="0">Cierre de operaciones - baja de inscripción en el RUC</option>
       </select>
    </div>
    <div class="col-lg-1">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Generar
      <span class="caret"></span>
      </button>
      <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
        <li><a onclick="generar(1)">Normal</a></li>
        <li><a onclick="generar(2)">Simplificado</a></li>
      </ul>
    </div>
  </div>
</div>

<div class="container" style="margin-bottom:10px;margin-top:10px;">
  <h3>Lista</h3>
</div>

<div class="container" style="margin-bottom:10px;margin-top:10px;">
  <div class="row">
    <div class="col-lg-2"><input type="date" class="form-control input-sm" id="fecha_insert_1" onchange="listar()"></div>
    <div class="col-lg-2"><input type="date" class="form-control input-sm" id="fecha_insert_2" onchange="listar()"></div>
    <div class="col-lg-2">
      <select class="form-control input-sm" id="select_mes" onchange="listar()">
           <option value="0">Mes</option>
           <option value="01">Enero</option>
           <option value="02">Febrero</option>
           <option value="03">Marzo</option>
           <option value="04">Abril</option>
           <option value="05">Mayo</option>
           <option value="06">Junio</option>
           <option value="07">Julio</option>
           <option value="08">Agosto</option>
           <option value="09">Setiembre</option>
           <option value="10">Octubre</option>
           <option value="11">Noviembre</option>
           <option value="12">Diciembre</option>
      </select>
    </div>
    <div class="col-lg-2">
      <select class="form-control input-sm" id="select_año" onchange="listar()">
         <option value="0">Año</option>
          <?php for($i=date('Y')-1;$i<=date('Y');$i++){ ?>
             <option value="<?php echo $i;?>"><?php echo $i;?></option>
           <?php } ?>
      </select>
    </div>
  </div>
</div>

<div class="container" style="border:2px solid #EAEDED;padding:10px 5px;border-radius:5px;overflow-y:auto;height:400px;">
  <table class="table">
   <thead style="border-bottom:2px solid #ABB2B9;">
     <td><label style="font-weight:600;">N°</label></td>
     <td><label style="font-weight:600;">FECHA CREACIÓN</label></td>
     <td><label style="font-weight:600;">EMPLEADO</label></td>
     <td><label style="font-weight:600;">LIBRO</label></td>
     <td><label style="font-weight:600;">PERIODO</label></td>
     <td><label style="font-weight:600;">TOTAL</label></td>
  
   </thead>
   <tbody id="tbl-libro-compras"></tbody>
 </table>
</div>




<script>


$(function(){
   listar();
})

  function generar(tipo){
    var mes = $("#mes").val();
    var anio = $("#anio").val();
    var operaciones = $("#operaciones").val();
    var error = 0;
    if(mes==0 || anio==0 || operaciones==3){error++;}
    if(error==0){
      $.post("<?PHP echo base_url()?>index.php/Libros_electronicos/generar_lecompras",{tipo,mes,anio,operaciones})
       .done(function(res){
         if(res=='lleno'){
             alert("Ya se generó ese libro electrónico");
         }else if(res==1){
            alert("Se generó correctamente los libros : \n- Registro de Compras\n- Registro de compras Información de operaciones \n  con sujetos no domiciliados");
         }else{
           alert("Se generó correctamente los libros : \n- Registro de Compras Simplificado");
         }

           listar();
       })
    }else{
      alert("Seleccione todas las opciones : \n- Mes\n- Año\n- Indicador de Operaciones");
    }

  }

  function listar(){
     var fecha_insert_1 = $("#fecha_insert_1").val();
     var fecha_insert_2 = $("#fecha_insert_2").val();
     var select_mes = $("#select_mes").val();
     var select_anio = $("#select_año").val();
     $.getJSON("<?PHP echo base_url()?>index.php/Libros_electronicos/listar_lecompras",{fecha_insert_1,fecha_insert_2,select_mes,select_anio})
      .done(function(json){
        var html = '';
         if(json.length>0){
            $.each(json,function(index,value){
               html+= '<tr><td>'+(index + 1) +'</td>';
               html+= '<td>'+value.fecha_insert+'</td>';
               html+= '<td>'+value.nombre+' '+value.apellido_paterno+'</td>';
               if(value.libro_id=='080100'){
                 html+= '<td>Registro de compras</td>';
               }else if(value.libro_id=='080200'){
                 html+= '<td>Registro de Compras Información de operaciones con sujetos no domiciliados</td>';
               }else if(value.libro_id=='080300'){
                 html+= '<td>Registro de Compras Simplificado</td>';
               }
               html+= '<td>'+value.mes+' del '+value.anio+'</td>';
               html+= '<td>'+value.total+'</td>';


               html+= '</tr>';
            });
         }else{
            html+= '<tr><td colspan="3">No se encontraron registros</td>';
         }

         $('#tbl-libro-compras').html(html);

     })

  }




</script>
