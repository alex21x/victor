<script type="text/javascript">
    $(document).ready(function () {        

        $("#datos").hide();
        $("#limitado_detalle").hide();

        $("#tipo_cliente").change(function () {
            var op = $("#tipo_cliente option:selected").val();
            var array = op.split('xx-xx-xx');
            $("#datos").show();

            if (array[0] == 1) {
                $("#lbl_DNI_RUC").text('DNI');
                $("#ruc").attr("placeholder","DNI");
                $("#ruc").attr("maxlength","8");

                $("#lbl_RAZ_APE").text('Nombres');
                $("#razon_social").attr("placeholder","Nombres");
                $("#nombres").show();
            }else{
                $("#lbl_DNI_RUC").text('RUC');
                $("#ruc").attr("placeholder","RUC");
                $("#ruc").attr("maxlength","11");
                
                $("#lbl_RAZ_APE").text('Razon Social');
                $("#razon_social").attr("placeholder","razon_social");
                $("#nombres").hide();
            }

        });
    });
</script>
<br>
<div class="container">
    <!-- Example row of columns -->
    <div class="row">                
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <a href="<?PHP echo base_url() ?>index.php/clientes/index" class="btn btn-success btn-xs" role="button">&nbsp;&nbsp;Atras&nbsp;&nbsp;</a>
            <div align="center"><h2>Ingresar Cliente</h2></div>
            <form class="form-horizontal" enctype="multipart/form-data" action="<?PHP echo base_url() ?>index.php/clientes/grabar" method="POST">
                <div class="form-group">
                    <label for="tipo_cliente" class="col-xs-5 col-md-5 col-lg-5 text-right">Tipo Cliente:</label>
                    <div class="col-xs-7 col-md-7 col-lg-7">
                        <select class="form-control" name="tipo_cliente" id="tipo_cliente" required="">
                            <option>Seleccionar</option>
                            <?PHP foreach ($tipo_clientes as $value_tipo_clientes) { ?>
                                <option value="<?PHP echo $value_tipo_clientes['id'].'xx-xx-xx'.$value_tipo_clientes['tipo_cliente']; ?>"><?PHP echo $value_tipo_clientes['tipo_cliente']; ?></option>
                                <?PHP
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div id="datos">    
                    <div class="form-group">
                        <label id="lbl_DNI_RUC" for="ruc" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Ruc:</label>

                        <div class="col-xs-6 col-md-6 col-lg-6">
                            <input type="number" class="form-control" name="ruc" id="ruc" placeholder="RUC" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);">
                        </div>
                        <div class="col-xs-1">
                            <a href="#"><span class="glyphicon glyphicon-search searchCustomer"></span></a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label id="lbl_RAZ_APE" for="razon_social" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Razón Social</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="razon_social" id="razon_social" placeholder="razon_social" required="">
                        </div>
                    </div>
                    <!--<div id="nombres">
                        <div class="form-group">
                            <label for="nombres" class="col-sm-5 control-label">Nombres</label>
                            <div class="col-xs-7">
                                <input type="text" class="form-control" name="nombres" id="nombres" placeholder="nombres">
                            </div>
                        </div>
                    </div>-->
                    
                    <div class="form-group">
                        <label for="domicilio1" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Domicilio 1:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="domicilio1" id="domicilio1" placeholder="domicilio1">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Email:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="email" id="email" placeholder="email">
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="email2" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Email2:</label>
                    <div class="col-xs-7 col-md-7 col-lg-7">
                        <input type="text" class="form-control" name="email2" id="email2" value="<?PHP echo $cliente['email2']?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email3" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Email3:</label>
                    <div class="col-xs-7 col-md-7 col-lg-7">
                        <input type="text" class="form-control" name="email3" id="email3" value="<?PHP echo $cliente['email3']?>">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="pagina_web" class="col-xs-5 col-md-5 col-lg-5 control-label text-right"">Página web:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="pagina_web" id="pagina_web" placeholder="pagina_web">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="telefono_fijo_1" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Telefono fijo 1:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="number" class="form-control" name="telefono_fijo_1" id="telefono_fijo_1" placeholder="telefono_fijo_1" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="7">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono_movil_1" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Telefono movil 1:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="number" class="form-control" name="telefono_movil_1" id="telefono_movil_1" placeholder="telefono_movil_1" oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="9">
                        </div>
                    </div>

                        <div class="form-group">
                        <label  for="descuento%" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Descuento%:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="descuento" id="descuento" placeholder="Descuento%">
                        </div>
                    </div>

                       <div class="form-group">
                        <label for="línea de crédito " class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Línea De Crédito:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="linea_de_credito " id="linea_de_credito " placeholder="Línea De Crédito ">
                        </div>
                    </div>
                    
                        <div class="form-group">
                        <label for="zona " class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Zona:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="zona" id="zona" placeholder="Zona">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="puntos " class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Puntos:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="puntos" id="puntos" placeholder="Puntos">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="bonus" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Bonus:</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="text" class="form-control" name="bonus" id="bonus" placeholder="Bonus">
                        </div>
                    </div>
                        
                        <div class="form-group">
                        <label for="bonus" class="col-xs-5 col-md-5 col-lg-5 control-label text-right">Foto</label>
                        <div class="col-xs-7 col-md-7 col-lg-7">
                            <input type="file" class="form-control" name="foto" id="foto" placeholder="Foto">
                        </div>
                       </div>
                    </div>
                        <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-8">
                            <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-md-3">
        </div>
    </div>
</div>


<script type="text/javascript">  

$(document).ready(function(){
    $(".searchCustomer").on("click",function(){

        consulta_sunat();
    })

    function consulta_sunat(){
        var num = $("#ruc").val();

        if(num!=''){
        if(num.length == 8){//DNI
            $.getJSON('https://mundosoftperu.com/reniec/consulta_reniec.php',{dni:num})
             .done(function(json){                
                if(json[0].length!=undefined){
                    var dni = json[0];
                    var nombres = json[2]+' '+json[3]+' '+json[1];                    
                    $("#razon_social").val(nombres);
                    $("#domicilio1").val('LIMA');
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
                    $("#razon_social").val(json.result.RazonSocial);
                    $("#domicilio1").val(json.result.Direccion);                    
                    toast("success", 1500, 'Datos encontrados con exito');
                 }else{
                    toast("error",3000, 'Número no existe en SUNAT');
                 }
             });


        }else{
            toast("error",3000, 'DEBE DE INGRESAR UN DNI/RUC CORRECTO');            
        }} else{         
             toast("error",3000, 'Ingrese número de documento de búsqueda');
        }
    }

});        
</script>