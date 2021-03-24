   

<script type="text/javascript">
    $(document).ready(function() {
        $("#cliente").autocomplete({
            source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_cliente',
            minLength: 2,
            select: function(event, ui) {
                var data_cli ='<input type="hidden" value="' + ui.item.id + '" name = "cliente_id" id = "cliente_id" >';
                $('#data_cli').html(data_cli);
            }
        });

        //Autocomplete Empleado
        $("#empleado").autocomplete({
            source: '<?PHP echo base_url(); ?>index.php/comprobantes/buscador_empleado',
            minLength: 2,
            select: function(event, ui) {
                var data_epl ='<input type="hidden" value="' + ui.item.id + '" name = "empleado_id" id = "empleado_id" >';
                $('#data_epl').html(data_epl);
            }
        });
    })
    ;</script>

<style type="text/css">

label{
    width: 100%;
    text-align: left;
}

.clase_tahoma{
        font-family: Tahoma, Verdana, Segoe, sans-serif;
}  

 .container5 a{

 display: inline-block;
 margin-right: 20px;

}      


</style>

<div align="center">
    <h3>Clientes</h3>
</div>
<p class="bg-info">
    <?PHP echo $this->session->flashdata('mensaje_cliente_index'); ?>
</p>
<form method="post" action="<?PHP echo base_url()?>index.php/clientes/index" name="form1" id="form1">    
    <div class="container">    

        <div class="row buscarCliente">                                                                         
                <div class="col-xs-12 col-md-6 col-lg-6">

                    <label for="cliente">Cliente:
                    <input type="text" value="<?PHP if(isset($cliente_select)){ echo $cliente_select;}?>" class="form-control" id="cliente" name="cliente" placeholder="Cliente">
                    </label>
                </div>     
                <?PHP                
                $cliente_select = (isset($cliente_select_id))? '<input id="cliente_id" type="hidden" name="cliente_id" value="'. $cliente_select_id . '">' : '';
                ?>
                <div id="data_cli"><?PHP echo $cliente_select?></div>                                    
                <div id="data_epl"><?PHP echo $empleado_select?></div>
                <div class="col-xs-6 col-md-3 col-lg-3">
                    <label><br>
                    <select name="estado_cliente" id="estado_cliente" class="form-control">
                        <option value="todos">Todos</option>
                        <?PHP   
                        $selected = '';
                        $tipo_activo = (isset($tipo_activo_select)) ? $tipo_activo_select : '';                        
                        foreach ($activos as $value_activos) {
                            $selected = (($tipo_activo == $value_activos->id) && ($tipo_activo != '')) ? 'SELECTED' : '';
                            ?>
                            <option <?PHP echo $selected; ?> value="<?PHP echo $value_activos->id; ?>"><?PHP echo $value_activos->activo; ?></option>
                        <?PHP } ?>
                    </select>
                    </label>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-3">
                        <label for="tipo_cliente">Tipo Cliente:
                        <select name="tipo_cliente" id="tipo_cliente" class="form-control">
                            <option value="todos">Todos</option>
                            <?PHP                               
                            $tipo_cliente = (isset($tipo_clientes_select)) ? $tipo_clientes_select : '';
                            
                            foreach ($tipo_clientes as $value_tipo_clientes) {
                                $selected = (($tipo_cliente == $value_tipo_clientes['id']) && ($tipo_cliente != '')) ? 'SELECTED' : '';
                                ?>
                                <option <?PHP echo $selected; ?> value="<?PHP echo $value_tipo_clientes['id']; ?>"><?PHP echo $value_tipo_clientes['tipo_cliente']; ?></option>
                            <?PHP }?>
                        </select>
                        </label>
                </div>      
         </div>
         <div class="row buscarCliente" >                                 
                <div class="col-xs-9 col-md-6 col-lg-6"> 
                    <label>Usuario:                   
                      <input type="text" class="form-control" name="empleado" id="empleado" placeholder="Usuario"></label>
                </div><br>
                <div class="col-xs-2 col-md-6 col-lg-6">
                    <input type="submit" class="btn btn-primary" id="boton1" value="Buscar" />
                </div> 
        </div>                                
        </div>
</form>
<hr>
<div class="container-fluid">
    <!-- Example row of columns -->
    <div class="row">        
        <div class="col-xs-0 col-md-7 col-lg-8">
            <a href="<?php echo base_url()?>files/xlsx/descargar_formato/formato_cliente.xlsx" class="btn btn-default">Descargar Formato xls</a>   
        </div>       
        <div class="col-xs-12 col-md-5 col-lg-4" >
                <a href="<?PHP echo base_url() ?>index.php/clientes/nuevo" class="btn btn-success colbg" role="button">Agregar</a>
                <a id="exportar_cli" href="#" class="btn btn-primary colbg">Eportar excel</a>
                <button type="button" id="btn_importar_clientes"  class="btn btn-primary btn-sm colbg" data-toggle="modal" data-target="#myModal">Importar Clientes</button>
        </div>        
    </div>
    <div class="row"><br>
        <div class="col-md-12">
            <table class="table table-striped">
                <tr>
                    <th>N°</th>
                    <th>T.Cliente</th>
                    <th>Ruc/Dni</th>
                    <th>Razón Social/Apellidos y Nombres</th>
                    <th>Usuario</th>
                    <?PHP if (($this->session->userdata('tipo_empleado_id') == 1)) { ?>
                        <th><span class="glyphicon glyphicon-pencil"></span></th>
                        <th><span class="glyphicon glyphicon-trash"></span></th>
                    <?PHP } ?>
                </tr>
                <?PHP
                $i = 0;
                foreach ($clientes as $value) {
                    $i++
                    ?>
                    <tr>
                        <td><?PHP echo $value['cliente_id'] ?></td>
                        <td><?PHP echo $value['tipo_cliente'] ?></td>
                        <td><a onclick="javascript:window.open('<?PHP echo base_url() ?>index.php/clientes/perfil/<?PHP echo $value['cliente_id']; ?>', '', 'width=750,height=600,scrollbars=yes,resizable=yes')" href="#"><?PHP echo $value['ruc'] ?></a></td>
                        <?PHP if($value['tipo_cliente_id']=='1') {?>
                        <td><?PHP echo $value['razon_social']." ".$value['nombres']; ?></td>                        
                        <?PHP } else { ?>
                        <td><?PHP echo $value['razon_social']; ?></td>
                        <?PHP } ?>
                        <td><?PHP echo $value['empleado'] ?></td>
                       
                      <?php if($value['cliente_id']!=1){ ?>  
                        <td>
                            <a class="btn btn btn-primary btn-xs" title="Modificar" href="<?PHP echo base_url() ?>index.php/clientes/modificar/<?PHP echo $value['cliente_id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>

                            <?PHP if($this->session->userdata('tipo_empleado_id') == 1) {?>
                        <td>
                            <a class="btn btn btn-danger btn-xs" title="Eliminar" href="<?PHP echo base_url() ?>index.php/clientes/eliminar/<?PHP echo $value['cliente_id']; ?>">
                            <span class="glyphicon glyphicon-trash"></span></a>
                        </td>                        
                      <?php }}else{ ?> 
                        <td></td>
                        <td></td>
                      <?php } ?> 
                    </tr>
                    <?PHP }
                ?>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <?PHP echo $pagination;?>
        </div>
        
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {

        
    $('#exportar_cli').click(function() {
        var clienteid =$('#cliente_id').val();
        var estadocliente = $('#estado_cliente').val();
        var tipocliente = $('#tipo_cliente').val();
        if(clienteid ==''){
            clienteid =0;
        }
        if(estadocliente =='todos'){
            estadocliente =0;
        }
        if(tipocliente =='todos'){
            tipocliente = 0;
        }
        console.log(clienteid);
        var url ='<?PHP echo base_url() ?>index.php/clientes/ExportarExcel/'+clienteid+'/'+estadocliente+'/'+tipocliente+'/';

        window.open(url, '_blank');

    });

    $("#btn_importar_clientes").click(function(e){

        
         e.preventDefault();
        $("#myModal").load('<?php echo base_url()?>index.php/clientes/subirclientesUi',{});       
    });
});

</script>