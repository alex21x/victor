function consulta_dniPaciente(){
        var num = $("#paciente").val();
        if(num!=''){
        if(num.length == 8){//DNI
            $.getJSON('https://mundosoftperu.com/reniec/consulta_reniec.php',{dni:num})
             .done(function(json){                
                if(json[0].length!=undefined){
                    var dni = json[0];
                    var nombres = json[1]+' '+json[2]+' '+json[3];                                    
                    $("#ruc").val(dni);
                    $("#paciente").val(nombres);
                    $("#paciente_id").val('P');
                    $("#lugar_nacimiento").val('');
                    $("#fecha_nacimiento").val('');
                    $("#edad").val('');
                    $("#mes").val('');
                    $("#dia").val('');
                    $("#telefono").val('');
                    $("#alergia").val('');
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe');
                 }
             });     
        }else if(num.length == 11){//RUC
            toast("info",4000, 'Buscando . . .');
            $.getJSON('https://mundosoftperu.com/sunat/sunat/consulta.php',{nruc:num})
             .done(function(json){
      
                 if(json.result.RUC.length!=undefined){                          
                    $("#paciente").val(json.result.RazonSocial);
                    $("#paciente_id").val('P');
                    $("#domicilio1").val(json.result.Direccion); 
                    $("#fecha_nacimiento").val('');
                    $("#edad").val('');
                    $("#mes").val('');
                    $("#dia").val('');
                    $("#telefono").val('');
                    $("#alergia").val('');                   
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe en SUNAT');
                 }
             });
        }else{
            toast("error",3000, 'DEBE DE INGRESAR UN DNI/RUC CORRECTO');            
        }} else{         
             toast("error",3000, 'Ingrese número de documento de búsqueda');
	}}