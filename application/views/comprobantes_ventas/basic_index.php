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

#passwordContent{
    display: none;
}

</style>
<div class="container">
<form id="formComprobanteVenta">

<div class="form-group">	

	<div class="col-xs-12 col-sm-6 col-md-4 col-sm-offset-3">                            
                <ul class="list-group">                    
                    <li class="list-group-item">
                        PASSWORD
                        <div class="material-switch pull-right">
                        	<?PHP $CHECKED = ($comprobanteVenta->passwordDelete == 1) ? 'CHECKED': '';
                                $DISPLAY =  ($comprobanteVenta->passwordDelete == 1) ? 'style=display:block': ''?>
                            <input name="password" id="password" type="checkbox" <?= $CHECKED?>>
                            <label for="password" class="label-primary"></label>
                        </div><br><br>                        
                        <div id="passwordContent" <?= $DISPLAY?>>
                            <input class="form-control" type="text" id="textPasswordDelete" name="textPasswordDelete" value="<?php echo $comprobanteVenta->textPasswordDelete?>"><br>
                            <button type="button" class="btn btn-primary btn_guardar_pass">GUARDAR</button>
                        </div>
                    </li>                    
                </ul>
    </div>
</div><br><br><br>
</form>
</div>

<script type="text/javascript">	

    function guardarCambios(){
        $.ajax({
            url: '<?= base_url()?>index.php/comprobantes_ventas/guardarComprobanteVenta',
            dataType : 'JSON',
            method: 'POST',
            data : $("#formComprobanteVenta").serialize(),
            success: function(response){
                if(response.status == STATUS_FAIL){
                        if(response.tipo == 1){
                            var errores = response.errores;
                            toast('error', 1500, 'Faltan ingresar datos.');
                            $.each(errores, function(index, value){
                                $("#"+index).parent().addClass('has-error');
                            });
                        }
                }
                if(response.status == STATUS_OK)
                {
                    toast('success', 1500, 'se actualizó Configuración');
                }
            }
        })
    }

	$("#password").on("click",function(){
        if($(this).is(":checked")){
            $("#passwordContent").css("display","block");
        } else{
            $("#passwordContent").css("display","none");}
            guardarCambios();	
	});


    $(".btn_guardar_pass").on("click",function(){
        guardarCambios();
    });

</script>
<br><br><br><br><br><br>
<br><br><br><br><br><br>