        <br>
        <?php if($this->session->flashdata('mensaje')!=''){ ?>
                <p class="bg-info" style="text-align: center;font-weight: normal;padding: 5px 0;background: #0277BD;color: #fff;">
                    <?PHP echo $this->session->flashdata('mensaje'); ?>
                </p>
         <?php } ?>       
        <div align="center">
         <h2>Correo</h2>
        </div>
        <div class="container">                    
                    
            <form data-toggle="validator" method="POST" action="<?= base_url()?>index.php/correo/guardar">
            
                    <table class="table tabla">
                        <thead>
                        <tr>
                            <td><h4>Parámetros</h4></td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Servidor del Correo</td>
                                <td><input type="text" class="form-control" value="<?php echo $correo->correo_host;?>" name="host"></td>
                            </tr>
                            <tr>
                                <td>Puerto del Servidor</td>
                                <td><input type="text" class="form-control" value="<?php echo $correo->correo_port;?>" name="port"></td>
                            </tr>
                            <tr>
                                <td>Tipo de cifrado</td>
                                <td><input type="text" class="form-control" value="<?php echo $correo->correo_cifrado;?>" name="cifrado"></td>
                            </tr>
                            <tr>
                                <td>Nombre de Usuario</td>
                                <td><input type="text" class="form-control" value="<?php echo $correo->correo_user;?>" name="user"></td>
                            </tr>
                            <tr>
                                <td>Contraseña</td>
                                <td><input type="password" class="form-control" value="<?php echo $correo->correo_pass;?>" name="pass"></td>
                            </tr>
                        </tbody>
                </table>
                <div align="center">
                    <input type="hidden" name="empresa_id" value="<?php echo $empresa['id']?>"/>
                    <input type="submit" class="btn btn-primary" value="Guardar">
                </div>
               </form> 
        </div>    