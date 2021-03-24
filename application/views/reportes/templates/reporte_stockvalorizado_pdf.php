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
		<th style="background-color:#dddddd; font-size: 12px;padding: 5px">Reporte stock valorizado</th>
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

<table>
	<tr>
		<td><strong>CATEGORIA:</strong></td>
		<td><?php echo $categoria?></td>
	</tr>
	<tr>
		<td><strong>PRODUCTO:</strong></td>
		<td><?php echo $producto?></td>
	</tr>
	
</table>
 <br>
<table border=1 >
	<tr class="rowCabecera">
		<th class="rowCodigo">LINEA</th>							
		<th class="rowProducto">MARCA</th>		
		<th class="rowProducto_">DESCRIPCION</th>
		<th class="rowProducto_des">ALMACEN</th>
		<th class="rowProducto_des">STOCK</th>
		<th class="rowProducto_des">COSTO</th>
		<th class="rowProducto_des">PRECIO S/</th>
		<th class="rowProducto_des">UTILIDAD</th>
		<th class="rowProducto_des">STOCK VALORIZADO</th>

	</tr>


	<?PHP		
    	$rsTotal_cantidad = 0;				
		$rsTotal_stockValorizado = 0;
		
     foreach($reporteTotal  as $value) { 
     $rsUtilidad = number_format($value->prod_stock*($value->prod_precio_publico - $value->prod_precio_compra),2);
			$rsStockValorizado = number_format($value->prod_stock*$value->prod_precio_compra,2);
     	?>

			  <tr>
				<td><?= $value->lin_nombre?></td>
                <td><?= $value->mar_nombre ?></td>
                <td><?= $value->prod_nombre ?></td>
                <td><?= $value->alm_nombre ?></td>
                <td><?= $value->prod_stock ?></td>
                <td><?= $value->prod_precio_compra ?></td>
                <td><?= $value->prod_precio_publico ?></td>
                <td><?= $rsUtilidad ?></td>
                <td><?= $rsStockValorizado ?></td>
             </tr>

        <?php   
             $rsTotal_cantidad += $value->prod_stock;
			 $rsTotal_stockValorizado += $value->prod_stock*$value->prod_precio_compra;			     
		} 
		?>
		 		 <tr>
		 		   <td>&nbsp;</td>
		 		   <td>&nbsp;</td>
		 		   <td>&nbsp;</td>
		 	       <td>TOTAL</td>
		 	       <td><?= $rsTotal_cantidad ?></td>
		 	       <td>&nbsp;</td>
		 		   <td>&nbsp;</td>
		 		   <td>&nbsp;</td>
		 	       <td><?= $rsTotal_stockValorizado ?></td>
							
		 		 </tr>
		 		 		
		</table>
</body>
</html>

