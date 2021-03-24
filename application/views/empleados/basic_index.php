<h2 align="center">Usuarios</h2>
<br>
<div class="container">
    <div class="row">
        <button tupe="button" id="btn_nuevo_empleado" class="btn btn-success">Nuevo Usuario</button>
    </div>
    <br>
    <table class="table tab-content">
        <tr>
            <td>Nombres</td>
            <td>Apellidos</td>
            <td>Contraseña</td>
            <td>Perfil</td>
            <td>Almacén</td>
            <td>Modificar</td>
            <td <?PHP echo $this->session->userdata('accesoEmpleado');?>>Eliminar</td>
        </tr>
        <?php foreach($empleados as $empleado):?>
            <tr>
                <td><?php echo $empleado['nombre']?></td>
                <td><?php echo $empleado['apellido_paterno']?></td>
                <td><?php echo $empleado['dni']?></td>
                <td><?php echo $empleado['tipo_empleado']?></td>
                <td><?php echo $empleado['alm_nombre']?></td>
                <td><a class="btn btn-default btn-sm" href="<?php echo base_url()?>index.php/empleados/basic_modificar/<?php echo $empleado['empleado_id']?>">Modificar</a></td>
                <td><a  class="btn btn-danger btn-sm" onclick="return confirm('Está seguro de eliminar el Usuario?');" <?PHP echo $this->session->userdata('accesoEmpleado');?> href="<?php echo base_url()?>index.php/empleados/basic_eliminar/<?php echo $empleado['empleado_id']?>">Eliminar</a></td>
            </tr>
        <?php endforeach?>    
    </table>
</div>
<script>
    $("#btn_nuevo_empleado").click(function(e){
        e.preventDefault();
        location.href= '<?php echo base_url()?>index.php/empleados/nuevo';
    });
</script>