<style type="text/css">
	table{
		font-size: 8px;
		text-align: left;
		border: 0px solid #dddddd;
		width: 100%;
	}

	table tr th,td{
		border: 0px solid #dddddd;
 		text-align: left;
 		line-height: 8px;
		letter-spacing: 1px;
		padding: 0px;
	}

	table tr td.rowProducto_xs{
		width: 50px;
	}
	table tr td.rowProducto{
		width: 100px;
		
	}
	table tr td.rowProducto_des{
		width: 60px;
	}

	table tr td.espaciado{
		font-size: 8px;
		padding: 2px;
	}
	table tr.rowCabecera{
		font-size: 8px;
		background-color: #dddddd;
	}

	
.cabecera{
	text-align: center;
}
.detalle{
	text-align: right;

}
p{
	font-size: 8px;
	line-height: 2px;
	letter-spacing: 2px;
}

.desc{
	width:200px;
	background-color: #dddddd;
}
img{
	float: left;
	padding-left: 15px;
}

</style>
<img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" width="80" style="text-align:center;" border="0">
<table>
	<tr>
		<th style="text-align: center;" width="80">.</th>
		<th style="background-color:#dddddd; font-size: 12px;padding: 5px">Reporte de Utilidades</th>
	</tr>
</table>

<table>
  <tr>
  	<th width="80" style="text-align: center;">.</th>
  	<th>EMPRESA</th>
  	<th>:<?php echo $empresa['empresa'];?></th>
  </tr>
	<tr>
	<td></td>
  	<td>DIRECCION</td>
  	<td>:<?php echo $empresa['domicilio_fiscal'];?></td>
  </tr>
  <tr>
  	<td></td>
  	<td>EMPLEADO</td>
  	<td>:<?php echo $caja->empleado;?></td>
  </tr>
  <tr>
  	<td></td>
  	<td>LOCAL</td>
  	<td>:<?php echo $this->session->userdata('almacen_nom');?></td>
  </tr>

</table>
<!--
		<p>RUC:<?php echo $empresa['ruc'];?></p>
		<p>EMPRESA:<?php echo $empresa['empresa'];?></p>
		<p>DIRECCION:<?php echo $empresa['domicilio_fiscal'];?></p>
		<p>EMPLEADO:<?php echo $caja->empleado;?></p>
		<p>LOCAL:<?php echo $this->session->userdata('almacen_nom');?></p>


table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 80%;
}

td, th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 6px;
}

tr:nth-child(even) {
  background-color: #dddddd;
}
<div align="center">
		<img src="<?PHP echo FCPATH;?>images/<?php echo $empresa['foto'];?>" width="150" style="text-align:center;" border="0"></td></div>-->
<!--
<table class="cabecera">
	
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
</table><br>

<div>
		<p>RUC:<?php echo $empresa['ruc'];?></p>
		<p>EMPRESA:<?php echo $empresa['empresa'];?></p>
		<p>DIRECCION:<?php echo $empresa['domicilio_fiscal'];?></p>
		<p>EMPLEADO:<?php echo $caja->empleado;?></p>
		<p>LOCAL:<?php echo $this->session->userdata('almacen_nom');?></p>
</div>
-->

<table>
	<tr>
		<td><strong>FECHA DESDE:</strong></td>
		<td><?php echo $fecha_desde?></td>
		<td><strong>FECHA HASTA:</strong></td>
		<td><?php echo $fecha_hasta?></td>
	</tr>
</table>
<table border=1 >
	<tr class="rowCabecera">
		<th class="rowCodigo">CODIGO</th>							
		<th class="desc">DESCRIPCION</th>		
		<th class="rowProducto_">CATEGORIA</th>
		<th class="rowProducto_des">CANTIDAD</th>										
		<th class="rowProducto_des">TOTAL VENTAS</th>				
		<th class="rowProducto_des">TOTAL COMPRAS</th>
		<th class="rowProducto_des">UTILIDAD</th>
		<th class="rowProducto_des">COMISION NETA</th>
		<th class="rowProducto_des">UTILIDAD NETA</th>
	</tr>


	<?PHP 
	$rsTotal_venta = 0;		
	$rsTotal_compra = 0;
	$rsTotal_utilidad = 0;
	$rsTotal_comision = 0;
	$rsTotal_utilidadNeta = 0;			

	foreach ($reporteTotal as $value) {?>			
			<tr>
				<td class="espaciado"><?= $value['prod_codigo']?></td>
                <td><?= $value['prod_nombre'] ?></td>
                <td><?= $value['cat_nombre'] ?></td>
                <td><?= $value['cantidad'] ?></td>
                <td><?= $value['total_venta'] ?></td>
                <td><?= $value['total_compra'] ?></td>
                <td><?= $value['utilidad'] ?></td>
                <td><?= $value['comision'] ?></td>
                <td><?= $value['utilidadNeta'] ?></td>
             </tr>
        <?php   	
        $rsTotal_venta += $value['total_venta'];		
		$rsTotal_compra += $value['total_compra'];
		$rsTotal_utilidad += $value['utilidad'];
		$rsTotal_comision += $value['comision'];
		$rsTotal_utilidadNeta += $value['utilidadNeta']; 
		} ?>
			
			
		 			<tr class="rowCabecera"> 
		 			    <td>&nbsp;</td>
		 			    <td>&nbsp;</td>
		 				<td>&nbsp;</td>
		 				<td>TOTAL</td>			 							 				
		 				<td><?= $rsTotal_venta ?></td>		 				
		 				<td><?= $rsTotal_compra ?></td>
		 				<td><?= $rsTotal_utilidad ?></td>
		 				<td><?= $rsTotal_comision ?></td>
		 				<td><?= $rsTotal_utilidadNeta ?></td>
		 			</tr>
		  <?php  ?>
	
			
									
		</table>
</body>
</html>

