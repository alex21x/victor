<style type="text/css">
.material-switch > input[type="checkbox"] {
    display: none;   
}

.material-switch > label {
    cursor: pointer;
    height: 0px;
    position: relative; 
    width: 40px;  
}

.material-switch > label::before {
    background: rgb(0, 0, 0);
    box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
    border-radius: 8px;
    content: '';
    height: 16px;
    margin-top: -8px;
    position:absolute;
    opacity: 0.3;
    transition: all 0.4s ease-in-out;
    width: 40px;
}
.material-switch > label::after {
    background: rgb(255, 255, 255);
    border-radius: 16px;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
    content: '';
    height: 24px;
    left: -4px;
    margin-top: -8px;
    position: absolute;
    top: -4px;
    transition: all 0.3s ease-in-out;
    width: 24px;
}
.material-switch > input[type="checkbox"]:checked + label::before {
    background: inherit;
    opacity: 0.5;
}
.material-switch > input[type="checkbox"]:checked + label::after {
    background: inherit;
    left: 20px;
}
</style>

<div class="container">
    <form id="formEmpresa">
    <input type="hidden" name="empresa" id="empresa" value="<?= $empresa['activo']?>">
    <input type="hidden" name="pseToken" id="pseToken" value="<?= $empresa['pse_token']?>">
    <div class="row">        
        <div class="col-xs-12 col-sm-6 col-md-4">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading"><h3>Empresa</h3></div>
                <!-- List group -->
                <ul class="list-group">                    
                    <li class="list-group-item">
                        Estado
                        <div class="material-switch pull-right">
                            <input name="estado" id="estado" type="checkbox"/>
                            <label for="estado" class="label-primary"></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!--
        <div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3">
            <div class="panel panel-default">
                <!-- Default panel contents >
                <div class="panel-heading"><h3>PSE/TOKEN</h3></div>
                <!-- List group 
                <ul class="list-group">                    
                    <li class="list-group-item">
                        Estado
                        <div class="material-switch pull-right">
                            <input name="estadoPseToken" id="estadoPseToken" type="checkbox"/>
                            <label for="estadoPseToken" class="label-primary"></label>
                        </div>
                    </li>
                </ul>
            </div>
        </div>-->
    </div><br>    
    <br>    
    <table class="table tab-content">
        <tr>
            <td>Razón social</td>
            <td>RUC</td>
            <td>Dirección</td>
            <td>Logo</td>
            <td>Modificar</td>
        </tr>
        <tr>
            <td><?php echo $empresa['empresa']?></td>
            <td><?php echo $empresa['ruc']?></td>
            <td><?php echo $empresa['domicilio_fiscal']?></td>
            <td><a class="btn btn-default" href="<?php echo base_url()?>index.php/empresas/logo">Logo</a></td>
            <td><a class="btn btn-default" href="<?php echo base_url()?>index.php/empresas/modificar">Modificar</a></td>
        </tr>
    </table>
    </form>
</div>

<script type="text/javascript">  


$(document).on("ready",function(){


    $("#estado").on("click",function(){         
        $.ajax({
                url: '<?= base_url()?>index.php/empresas/cambiarEstado',
                data: $("#formEmpresa").serialize(),
                dataType : 'JSON',
                method: 'POST',
                success: function(response){
                  if(response.status == STATUS_OK){
                      $("#ruc").val(response.dni_auto);
                  }
                }
            })
    }); 

     $("#estadoPseToken").on("click",function(){         
        $.ajax({
                url: '<?= base_url()?>index.php/empresas/cambiarEstadoPseToken',
                data: $("#formEmpresa").serialize(),
                dataType : 'JSON',
                method: 'POST',
                success: function(response){
                  if(response.status == STATUS_OK){
                      $("#ruc").val(response.dni_auto);
                  }
                }
            })
    }); 


    function cambiarEstado(){        
        estado =  $("#empresa").val();
        if (estado == 'activo'){
            $("#estado").prop("checked",true);
        }
    }
    function cambiarEstadoPseToken(){        
        estado =  $("#pseToken").val();
        if (estado == 'activo'){
            $("#estadoPseToken").prop("checked",true);
        }
    }
                
    cambiarEstadoPseToken();
    cambiarEstado();
 });    

</script>