<!DOCTYPE html>
<html lang="es">
<head>
    <title>Dinamicos</title>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="http://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.min.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-es.js"></script>
	<style>
		.top-buffer { 
			margin-top:20px; 
		}
	</style>
</head>
<body> 

</head>
<body>
<div id="container ">
    <div class="row-fluid top-buffer">
        <div class="col-lg-6 col-lg-offset-3 text-center">
        <form id="miform" method="post" name="miform" >
                <table id="tblprod" class="table table-hover table-bordered">
          <thead>
                <tr>
                  <th>Codigo Producto</th>
                  <th>Nombre</th>
                </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
               <td>
                    <div class="form-group col-lg-12">
                            <input class="form-control validate[required]"name="prod[]" />
                    </div>
             </td>
            </tr>
          </tbody>
        </table>
        <button id="btnadd" class="btn btn-primary">Agregar Nuevo</button>
        <button id="btnsubmit" type="submit" class="btn btn-success">Guardar</button>
        </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function() {
    var count = 1;
    jQuery("#miform").validationEngine({promptPosition : "centerRight:0,-5"});
	
   $(document).on("click","#btnadd",function( event ) {  
	  count++;
      $('#tblprod tr:last').after('<tr><td>'+count+'</td><td><div class="form-group col-lg-12"><input class="form-control validate[required]" name="prod[]" /></div></td></tr>');
      event.preventDefault();
   });
   
   $( "#miform" ).submit(function( event ) {
          var frm = $(this);
	  var formulario = $(this).serialize();
	  
	  if($('#miform').validationEngine('validate')){
	  $.post( "guardar.php", formulario)
		        .done(function(data){
		          alert(data);
			  $(frm)[0].reset();
			})
			.fail(function() {
            alert( "error no pude enviar los datos" );
			});
	  }
	  event.preventDefault();
	});

});
	</script>

</body>
</html>