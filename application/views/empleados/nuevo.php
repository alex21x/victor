<h2 align="center">NUEVO USUARIO</h2>
<br>
<div class="container">    
    <form method="post" action="<?php echo base_url()?>index.php/empleados/basic_guardar_g">
        <table class="table table-hover">
            <tr>
                <td>Nombres</td>
                <td><input class="form-control" type="text" name="nombre"" ></td>
            </tr>
            <tr>
                <td>Apellido Paterno</td>
                <td><input class="form-control" type="text" name="apellido_paterno" ></td>
            </tr>
            <tr>
                <td>Apellido Materno</td>
                <td><input class="form-control" type="text" name="apellido_materno"></td>
            </tr>
            <tr>
                <td>Contraseña</td>
                <td><input class="form-control" type="text" name="dni" ></td>
            </tr>
            <tr>
                <td>Email</td>
                <td><input class="form-control" type="text" name="email"></td>
            </tr>
            <tr>
                <td>Perfil</td>
                <td>
                    <select name="perfil" class="form-control" required>
                        
                        <?php foreach($perfiles as $perfil):?>
                            <option value="<?php echo $perfil->id?>"><?php echo $perfil->tipo_empleado?></option>
                        <?php endforeach?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Almacén</td>
                <td>
                    <select name="almacen" class="form-control">
                        <option value="">Seleccione almacén</option>
                        <?php foreach($almacenes as $almacen):?>
                            <option value="<?php echo $almacen->alm_id?>" <?php if($almacen->alm_id==$empleados['almacen_id']):?> selected <?php endif?>  ><?php echo $almacen->alm_nombre?></option>
                        <?php endforeach?>
                    </select>
                </td>
            </tr>      
            <tr>
                <td align="center" colspan="2">
                    <input class="btn btn-info" type="submit" value="Registrar"/>
                </td>
            </tr>
        </table>
    </form>
</div>