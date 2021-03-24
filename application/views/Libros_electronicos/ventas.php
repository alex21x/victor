
<div class="container" style="margin-bottom:10px;">
  <h3>Libro Electrónico : <b>Registro de Compras y Ventas</b></h3>
</div>



<div class="container">
  <div class="row" style="border:3px solid #17A589;padding-top:10px;padding-bottom: 20px;border-radius: 5px;">
    <div class="col-lg-12"><h4>Parametros Obligatorios</h4></div>
    <div class="col-lg-4">
       <select id="mes" class="form-control input-sm">
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
    <div class="col-lg-4">
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
    
  </div>
</div>




<br>
<div class="container">
  <div class="row">
    <div class="col-lg-12" style="padding: 0;">
      <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="background: #17A589;color:#fff;border:0;width: 100%;padding: 10px;font-size: 20px;"> Generar 
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
   <tbody id="tbl-libro-ventas"></tbody>
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

       borrar_le(tipo);
       
       
    }else{
      alert("Seleccione todas las opciones : \n- Mes\n- Año\n- Indicador de Operaciones");
    }

  }



  function borrar_le(tipo){
    var mes = $("#mes").val();
    var anio = $("#anio").val();
    var operaciones = $("#operaciones").val();
  
      $.post("<?PHP echo base_url()?>index.php/Libros_electronicos/borrar_le",{tipo,mes,anio,operaciones})
       .done(function(res){
         if(res==1){

            generar_c(tipo);
            generar_v(tipo);
            listar();
         }
           
       })
   

  }

  function generar_c(tipo){
    var mes = $("#mes").val();
    var anio = $("#anio").val();
    var operaciones = $("#operaciones").val();

    
      $.post("<?PHP echo base_url()?>index.php/Libros_electronicos/generar_lecompras",{tipo,mes,anio,operaciones})
       .done(function(res){
         
         var zip = JSON.parse(res);

         if(zip['le_res']=='lleno'){
             alert("Ya se generó ese libro electrónico");
         }else if(zip['le_res']==1){
            window.location.href = "<?PHP echo base_url()?>index.php/Libros_electronicos/descargar_le/COMPRAS/"+zip['le_mes']+'/'+zip['le_anio']+'/'+zip['le_tipo']+'/'+zip['le_libro']+'/'+zip['le_libro2'];
            alert("Se generó correctamente los libros : \n- Registro de Compras\n- Registro de compras Información de operaciones \n  con sujetos no domiciliados");
         }else{
           window.location.href = "<?PHP echo base_url()?>index.php/Libros_electronicos/descargar_le/COMPRAS/"+zip['le_mes']+'/'+zip['le_anio']+'/'+zip['le_tipo']+'/'+zip['le_libro']+'/'+zip['le_libro2'];
           alert("Se generó correctamente los libros : \n- Registro de Compras Simplificado");
         }

          
       })
  

  }

  function generar_v(tipo){
    var mes = $("#mes").val();
    var anio = $("#anio").val();
    var operaciones = $("#operaciones").val();
  
      $.post("<?PHP echo base_url()?>index.php/Libros_electronicos/generar_leventas",{tipo,mes,anio,operaciones})
       .done(function(res){

        var zip = JSON.parse(res);

         if(zip['le_res']=='lleno'){
             alert("Ya se generó ese libro electrónico");
         }else if(zip['le_res']==1){
            window.location.href = "<?PHP echo base_url()?>index.php/Libros_electronicos/descargar_le/VENTAS/"+zip['le_mes']+'/'+zip['le_anio']+'/'+zip['le_tipo']+'/'+zip['le_libro']+'/'+zip['le_libro2'];
            alert("Se generó correctamente el libro : \n- Registro de ventas e ingresos");
            
          
         }else{
           window.location.href = "<?PHP echo base_url()?>index.php/Libros_electronicos/descargar_le/VENTAS/"+zip['le_mes']+'/'+zip['le_anio']+'/'+zip['le_tipo']+'/'+zip['le_libro']+'/'+zip['le_libro2'];
           alert("Se generó correctamente el libro : \n- Registro de ventas e ingresos Simplificado");
         
         }
           
       })

   

  }



  function listar(){
     var fecha_insert_1 = $("#fecha_insert_1").val();
     var fecha_insert_2 = $("#fecha_insert_2").val();
     var select_mes = $("#select_mes").val();
     var select_anio = $("#select_año").val();
     $.getJSON("<?PHP echo base_url()?>index.php/Libros_electronicos/listar_le",{fecha_insert_1,fecha_insert_2,select_mes,select_anio})
      .done(function(json){

        var html = '';
         if(json.length>0){
            $.each(json,function(index,value){
               html+= '<tr><td>'+(index + 1)+'</td>';
               html+= '<td>'+value.fecha_insert+'</td>';
               html+= '<td>'+value.nombre+' '+value.apellido_paterno+'</td>';

               if(value.libro_id=='140100'){
                   html+= '<td>Registro de Ventas e Ingresos</td>';
               }else if(value.libro_id=='140200'){
                  html+= '<td>Registro de Ventas e Ingresos Simplificado</td>';
               }else if(value.libro_id=='080100'){
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

         $('#tbl-libro-ventas').html(html);

     })

  }




</script>
