<h2 align="center">Modificar</h2>
<br>

<div class="container">    
    <form method="post" id="form_empresa" enctype="multipart/form-data">
        <input type="hidden" name="sfs" value="<?php echo $empresa['sfs']?>" required>
        <table class="table table-hover">
            <tr>
                <td>Razón Social</td>
                <td><input class="form-control" type="text" name="empresa" id="empresa" value="<?php echo $empresa['empresa']?>" required></td>
            </tr>
            <tr>
                <td>Nombre Comercial</td>
                <td><input class="form-control" type="text" name="nomcom" id="nomcom" value="<?php echo $empresa['nombre_comercial']?>" required></td>
            </tr>
            <tr>
                <td>RUC</td>
                <td><input class="form-control" type="text" name="ruc" id="ruc" value="<?php echo $empresa['ruc']?>" id="ruc" required>
                    <button type="button" id="nuevo_cliente" class="btn btn-primary btn-sm" onclick="consulta_sunat()">SUNAT</button>
                </td>
            </tr>
            <tr>
                <td>Dirección</td>
                <td><input class="form-control" type="text" name="domicilio_fiscal" id="domicilio_fiscal" value="<?php echo $empresa['domicilio_fiscal']?>" required></td>
            </tr>
            <tr>
                <td>Departamento</td>
                <td><input class="form-control" type="text" name="dep" value="<?php echo $empresa['departamento']?>"></td>
            </tr>
            <tr>
                <td>Provincia</td>
                <td><input class="form-control" type="text" name="pro" value="<?php echo $empresa['provincia']?>"></td>
            </tr>
            <tr>
                <td>Distrito</td>
                <td><input class="form-control" type="text" name="dis" value="<?php echo $empresa['distrito']?>"></td>
            </tr>
            <tr>
                <td>Urbanización</td>
                <td><input class="form-control" type="text" name="urb" value="<?php echo $empresa['urb']?>"></td>
            </tr>
            <tr>
                <td>Ubigeo</td>
                <td><input class="form-control" type="number" name="ubigeo" value="<?php echo $empresa['ubigeo']?>"></td>
            </tr>
          
             <tr>
                <td>Certificado Digital</td>
                <td><input class="form-control" type="file" name="certificado" required></td>
            </tr>
             <tr>
                <td>Clave Certificado Digital</td>
                <td><input class="form-control" type="password" name="pass_certificado" required value="<?php echo $empresa['pass_certificate']?>"></td>
            </tr>
            <tr>
                <td>correo</td>
                <td><input class="form-control" type="text" name="correo" value="<?php echo $empresa['correo']?>" ></td>
            </tr>
            <tr>
                <td>Telefonos Móvil</td>
                <td><input class="form-control" type="text" name="telefono_movil" value="<?php echo $empresa['telefono_movil']?>" ></td>
            </tr>  
            <tr>
                <td>Telefonos Fijo</td>
                <td><input class="form-control" type="text" name="telefono_fijo" value="<?php echo $empresa['telefono_fijo']?>" ></td>
            </tr>                       
            <tr>
                <td>Usuario Secundario</td>
                <td><input class="form-control" type="text" name="user" value="<?php echo $empresa['user']?>"></td>
            </tr>                       
            <tr>
                <td>Password Usuario Secundario</td>
                <td><input class="form-control" type="text" name="pass" value="<?php echo $empresa['pass']?>"></td>
            </tr>                       

            <tr>
                <td>Números de Cuenta</td>
                <td><textarea class="form-control" type="text" name="numero_de_cuenta" rows="6"><?php echo $empresa['numero_de_cuenta']?></textarea></td>
            </tr>
            <tr>
                <td>Pie de Página</td>
                <td><textarea class="form-control" type="text" name="pie_pagina"><?php echo $empresa['pie_pagina']?></textarea></td>
            </tr>                                   
            <tr>
                <td align="center" colspan="2">
                    <input class="btn btn-info" id="modificar_empresa" type="click" value="Modificar"/>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>

    function consulta_sunat(){
        var num = $("#ruc").val();
        var ruc_id = <?= $empresa['ruc']?>

        toast("info",4000, 'Buscando . . .');
        if(num!=''){
            $.getJSON('https://mundosoftperu.com/sunat/sunat/consulta.php',{nruc:num})
             .done(function(json){
      

                 if(json.result.RUC.length!=undefined){
                    $("#cliente_id").val(0);
                    $("#cliente").val("RUC "+json.result.RUC+" "+json.result.RazonSocial);
                    $("#domicilio_fiscal").val(json.result.Direccion);
                    $("#ruc_sunat").val(json.result.RUC);                    
                    $("#empresa").val(json.result.RazonSocial);
                    $("#nomcom").val(json.result.RazonSocial);
                     
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe en SUNAT');
                 }
             });
        }else{
             toast("error",3000, 'Ingrese número de documento de búsqueda');
        }        
    }

    $("#modificar_empresa").click(function(e){
        e.preventDefault();

       var formData = new FormData($("#form_empresa")[0]);
       $.ajax({
            method:'post',
            url:"<?php echo base_url()?>index.php/empresas/modificar_g",
            data:formData,
            dataType:'json',
            cache:false,
            contentType: false,
            processData: false,
            success:function(response){
                if(response.status == STATUS_FAIL)
                {
                    toast("error",3000, response.msg);
                }
                if(response.status == STATUS_OK)
                {
                    
                     $.ajax({
                            method:'post',
                            url:"<?php echo RUTA_API?>index.php/Sunat/register_CERT",
                            data:formData,
                            dataType:'json',
                            cache:false,
                            contentType: false,
                            processData: false,
                            success:function(response){
                                 if(response == 1){

                                        $.ajax({
                                                method:'post',
                                                url:"<?php echo RUTA_API?>index.php/Sunat/register_CERT",
                                                data:formData,
                                                dataType:'json',
                                                cache:false,
                                                contentType: false,
                                                processData: false,
                                                success:function(response){
                                                     if(response == 1){
                                                           toast("success", 1500, 'Datos guardados correctamente');
                                                           setTimeout(function() { 
                                                               location.href='<?PHP echo base_url()?>index.php/empresas'
                                                            }, 2000);
                                                                                                               
                                                     }
                                                   
                                                }
                                         }); 
                                      
                                 }else if(response == 0){
                                      toast("error", 1500, 'Clave del certificado incorrecto !');
                                 }else if(response == 3){
                                      toast("success", 1500, 'Datos guardados correctamente');
                                      setTimeout(function() { 
                                           location.href='<?PHP echo base_url()?>index.php/empresas'
                                        }, 2000);
                                 }else{
                                      toast("error", 1500, 'No existe certificado !');
                                 }
                               
                            }
                     });                                          
                }
            }
        });                  
    });
</script>