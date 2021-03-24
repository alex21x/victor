<style>
    .titulo_27{
        font-size: 27px;
        text-align: center;        
    }    
    
    .titulo_23{
        font-size: 23px;
    }

    .titulo_21{
        font-size: 23px;
    }
    
    .rowClienteDatos{
        margin-top: 60px;
    }
</style>

<div class="container">    
    <div class="row rowClienteDatos">
        <div class="col-xs-4 col-md-4 col-lg-4"></div>
        <div class="col-xs-8 col-md-8 col-md-8">
            <span class="titulo_27">Cobros Efectuados:&nbsp;<?PHP echo $cliente['razon_social'];?></span>    
        </div>        
    </div>
    <div class="row rowCliente">  
        <div class="col-xs-4 col-md-4 col-md-4"></div>
        <div class="col-xs-6 col-md-6 col-lg-6">            
        </div>
    </div>
    <div class="row rowClienteDatos">
        <div class="col-md-1">
        </div>
        <div class="col-md-9">
            <table class="table table-striped">
                <tr>
                    <td>N°</td>
                    <td>MONTO</td>
                    <td>TIPO PAGO</td>
                    <td>FECHA</td>
                    <td>USUARIO</td>                    
                </tr>
                <?PHP $i = 1; foreach($cobros as $value){?>
                <tr>
                    <td><?PHP echo $i;?></td>
                    <td><?PHP echo $value->monto;?></td>
                    <td><?PHP echo $value->tipo_pago;?></td>
                    <td><?PHP echo $value->fecha;?></td>
                    <td><?PHP echo $value->empleado;?></td>
                </tr>
                <?PHP $i++;}?>
            </table>
        </div>
        
    </div>
</div><br><br>
