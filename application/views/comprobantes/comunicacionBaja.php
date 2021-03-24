<script type="text/javascript">
    $(document).on('ready',function(){
        $('#fecha_desde').datepicker();
        $('#fecha_hasta').datepicker();
    });    
</script>
<p class="bg-info">
    <?= $this->session->flashdata('mensaje');?>    
</p>

<form method="POST" action="#" >
<div class="container">    
    <h2 class="text-center">Relacion Documentos Baja</h2>   
    <div class="row">
        <table class="table table-striped">
            <tr>
                <td colspan="2">Desde:</td>
                <td><input class="form-control" type="text" id="fecha_desde"></td>
                <td colspan="2">Hasta:</td>
                <td><input class="form-control" type="text" id="fecha_hasta"></td>                
            </tr>            
            <tr><td colspan="6" class="text-center"><input class="btn btn-primary" value="Buscar"></td></tr>
        </table>    
    </div>        
</div>
</form>


<div class="container-fluid">
<table class="table table-striped">    
    <tr>
        <td>N°</td>
        <td>Fecha de Baja</td>
        <td>Fecha de Emisión</td>
        <td>Cliente</td>
        <td>Serie</td>
        <td>Numero</td>
        <td>Motivos</td>
        <td>Ticket Sunat</td>
        <td>PDF</td>
        <td>XML</td>
        <td>CDR</td>
        <td>Estado Sunat</td></tr>
    <?PHP $i=0;foreach($comprobante as $value){$i++;?>
        <tr>
            <td><?= $i;?></td>
            <td><?= $value['fecha_de_baja']?></td>
            <td><?= $value['fecha_de_emision']?></td>
            <td><?= $value['razon_social']?></td>
            <td><?= $value['serie']?></td>
            <td><?= $value['numero']?></td>
            <td>Motivos</td>
            <td>1</td>
            <td><a href="#"><img title="Ver Pdf " src="<?PHP echo base_url()."images/pdf.png"  ?>"></a></td>
            <!--<td class="col-xs-1"><a href="<?PHP echo base_url('index.php/comprobantes/xmlSunat/'.$value['comprobante_id'].'/'.$value['cliente_id']);?>" target="_blank"><span class="glyphicon glyphicon-file"></span></a></td>-->
            <td><a href="<?= base_url('index.php/comprobantes/estadoBaja/'.$value['comprobante_id'].'/'.$value['cliente_id'])?>" target="_blank">XML</a></td>
            <td>&nbsp;</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-remove"></span></td>
        </tr>
    <?PHP }?>
</table>
</div>