<style>
    .row .con-img {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
    }
    .con-img {
        width: 40%;
        margin: 0 auto;
        display: flex;
        flex-direction: column;   
    }
    #log_empresa {
        width: 100%;
        height: 35vh;
        margin: 0 auto;
    }
</style>

<h2 align="center">Registrar Logo</h2>
<br>
<div class="container">
    <div class="row ">
        <div class="con-img">
            <?php 
              if ($empresa['foto']!='') {
                echo "<img id='log_empresa' src='".base_url()."images/".$empresa['foto']."' alt=''>";
              } else {
                echo "<img style='display:none;' id='log_empresa' src='' alt=''>";
              }
             ?>
            
        </div>
    </div>
    <form method="post" enctype="multipart/form-data" action="<?php echo base_url();?>index.php/empresas/logo_g"> 
        <div class="row alert alert-info">
            <div class="col-sm-2">
                <span>Seleccionar Logo</span>
            </div>
            <div class="col-sm-4"><input type="file" id="foto" name="foto"/></div>
            <div class="col-sm-1">
                <input class="btn btn-info" type="submit" value="subir logo"/>
            </div>
        </div>     
        
    </form>
</div>

