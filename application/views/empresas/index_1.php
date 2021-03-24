<script>

    $(document).ready(function () {                
        var url_select = <?php echo "'" . base_url() . "'"; ?> + "index.php/empresas/select";
        
        $.getJSON(url_select, function (json) {            
            tabla_empresas(json);
        });
        
        function listar_empresas(){
            $.getJSON(url_select, function (json) {            
                tabla_empresas(json);
            });
        }        
        
        function tabla_empresas(objDatos){
                    var conten_html = '';
            
                    conten_html += `<table class="table table-hover table-bordered">`;
                    conten_html += `<thead>
                                    <tr>
                                        <th>N.</th>
                                        <th>Razón Social</th>
                                        <th>Nombre<br>Corto</th>
                                        <th>Modificar</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                    var ii = 0;
                    for (var i = 0 in objDatos) {
                        ii ++;
                        conten_html += `<tr>`
                        conten_html += `<td>${ii}</td>`
                        conten_html += `<td>${objDatos[i].empresa}</td>`
                        conten_html += `<td>${objDatos[i].descripcion1}</td>`                        
                        conten_html += `</tr>`
                    }
                    conten_html += `</tbody></table>`;
                    $('#contenido').html(conten_html);
        }

        $("#btnSave").click(function () {
            var empresa = document.getElementById("empresa").value;
            var nombre_comercial = document.getElementById("nombre_comercial").value;
            var descripcion1 = document.getElementById("descripcion1").value;
            var ruc = document.getElementById("ruc").value;
            var domicilio_fiscal = document.getElementById("domicilio_fiscal").value;
            var telefono_fijo = document.getElementById("telefono_fijo").value;
            var telefono_fijo2 = document.getElementById("telefono_fijo2").value;
            var telefono_movil = document.getElementById("telefono_movil").value;
            var telefono_movil2 = document.getElementById("telefono_movil2").value;
            var activo = document.getElementById("activo").value;

            var url = <?php echo "'" . base_url() . "'"; ?> + "index.php/empresas/guardar";
            
            $.ajax({
                type: "post",
                url: url,
                data: {empresa: empresa, nombre_comercial: nombre_comercial, descripcion1: descripcion1, ruc: ruc, domicilio_fiscal: domicilio_fiscal, telefono_fijo: telefono_fijo, telefono_fijo2: telefono_fijo2, telefono_movil: telefono_movil, telefono_movil2: telefono_movil2, activo: activo},
                success: function (datos) {
                    listar_empresas();
                    $('#miventana').modal('hide');
                    $('#insert_form')[0].reset();
                }
            });
        });
    });
</script>
<h2 align='center'>Empresas</h2>
<br>
<br>
<div class="container">       
    <button class="pull-right btn btn-success" data-toggle='modal' data-target='#miventana'>Nueva empresa</button>
    <!-- Modal -->
    <div class="modal fade" id="miventana" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Nueva empresa</h4>
                </div>
                <div class="modal-body">
                    <form id="insert_form">
                        <div class="form-group">
                            <label for="empresa">Empresa (Nombre corto)</label>
                            <input type="texto" class="form-control" id="empresa" name="empresa" placeholder="Empresa">
                        </div>
                        <div class="form-group">
                            <label for="empresa">Nombre Comercial</label>
                            <input type="texto" class="form-control" id="nombre_comercial" name="nombre_comercial" placeholder="Nombre Comercial">
                        </div>
                        <div class="form-group">
                            <label for="descripcion1">Descripción</label>
                            <input type="texto" class="form-control" id="descripcion1" name="descripcion1" placeholder="Nombre Comercial">
                        </div>
                        <div class="form-group">
                            <label for="ruc">RUC</label>
                            <input type="texto" class="form-control" id="ruc" name="ruc" placeholder="RUC">
                        </div>
                        <div class="form-group">
                            <label for="domicilio_fiscal">Domicilio Fiscal</label>
                            <input type="texto" class="form-control" id="domicilio_fiscal" name="domicilio_fiscal" placeholder="Domicilio Fiscal">
                        </div>
                        <div class="form-group">
                            <label for="telefono_fijo">Telefono Fijo</label>
                            <input type="texto" class="form-control" id="telefono_fijo" name="telefono_fijo" placeholder="Telefono Fijo">
                        </div>
                        <div class="form-group">
                            <label for="telefono_fijo2">Telefono Fijo 2</label>
                            <input type="texto" class="form-control" id="telefono_fijo2" name="telefono_fijo2" placeholder="Telefono fijo2">
                        </div>

                        <div class="form-group">
                            <label for="telefono_movil">Telefono movil</label>
                            <input type="texto" class="form-control" id="telefono_movil" name="telefono_movil" placeholder="Telefono movil">
                        </div>
                        <div class="form-group">
                            <label for="telefono_movil2">Telefono movil 2</label>
                            <input type="texto" class="form-control" id="telefono_movil2" name="telefono_movil2" placeholder="Telefono movil2">
                        </div>

                        <div class="form-group">
                            <label for="activo">Activo</label>
                            <select name="activo" id="activo" class="form-control">
                                <?php foreach ($activo as $value_activo) { ?>
                                    <option value="<?php echo $value_activo['activo']; ?>"><?php echo $value_activo['activo']; ?></option>
                                    <?php
                                }                                                                
                                ?>                                
                            </select>                            
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btnSave" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div id="contenido"></div>
    
</div>

<div id="mostrardatos"></div>