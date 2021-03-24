
<style type="text/css">
	
.cabecera{
	text-align: center;
}
.detalle{
	text-align: left;
}
</style>

<table class="cabecera">
	<tr>
		<td><img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" height="160" width="380" style="text-align:center;" border="0"></td></tr>
	<tr>
		<td><?php echo $empresa['ruc'];?></td></tr>
	<tr>
		<td><?php echo $empresa['empresa'];?></td></tr>
	<tr>
		<td><?php echo $empresa['domicilio_fiscal'];?></td></tr>
	<tr>
		<td><?php echo $caja->empleado;?></td></tr>
	<tr>
		<td><?php echo $this->session->userdata('almacen_nom');?></td></tr>
	<tr>
		<td>Documento <?php echo $serNum;?></td></tr>	
	<tr>
		<td>Monto Crédito <?php echo $totalCredito;?></td></tr>	
</table>

<h4>Señores</h4>
<table class="detalle">
	<tr>				
		<td><?= $proveedor->prov_razon_social?></td>
	</tr>		
	<tr>		
		<td>Ruc: <?= $proveedor->prov_ruc?></td>
	</tr>		
	<tr>		
		<td>-----------------------------</td>		
	</tr>
	<tr>		
		<td>Usuario: <?= $vendedor['nombre']?></td>		
	</tr>	
	<tr>		
		<td>-----------------------------</td>		
	</tr>			
	<tr>		
		<td>Fecha</td>
		<td>Monto</td>
		<td>Saldo</td>
	</tr>	
	<?PHP $montoTotal= 0; foreach($pago as $value){
		 $totalCredito -= $value->monto;?>
	<tr>		
		<td><?= $value->fecha?></td>
		<td><?= $value->monto?></td>
		<td><?= $totalCredito?></td>
	</tr>	
	<?PHP $montoTotal += $value->monto;}?>
	<tr>		
		<td>-----------------------------</td>		
	</tr>	
	<tr>		
		<td>Total</td>
		<td><?= number_format($montoTotal,2)?></td>
	</tr>	
	<tr>		
		<td>-----------------------------</td>		
	</tr>
	<tr>		
		<td>Fecha de Cancelación</td>
		<td>_____/_____/______</td>
	</tr>
	<tr>		
		<td>&nbsp;</td>			
	</tr>
	<tr>		
		<td>&nbsp;</td>		
	</tr>
	<tr>		
		<td>-----------------------</td>
	</tr>
	<tr>
		<td>Recibí Conforme</td>
	</tr>	

	<tr>		
		<td>&nbsp;</td>			
	</tr>
	<tr>		
		<td>&nbsp;</td>		
	</tr>
	<tr>		
		<td>---------------------------------</td>
	</tr>
	<tr>
		<td><?php echo $empresa['empresa'];?></td></tr>
	<tr>	
</table>

