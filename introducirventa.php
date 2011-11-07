<?php
/*
 * Created on 06/11/2011
 *
 * Automotis DMS  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 $maxapuntes = 5;
 
 $usuario = $_SESSION['userid'];
 $idreserva = $_POST['idreserva'];
 
 $sql = mysql_query("SELECT * FROM reservas WHERE id = '$idreserva'");
 $arrayreserva = mysql_fetch_array($sql);
 
 $importeentrega = $arrayreserva['importe'];
 
 $idcliente = $arrayreserva['cliente'];
 $idvehiculo = $arrayreserva['vehiculo'];
 
 $sql2 = mysql_query("SELECT * FROM clientes WHERE id = '$idcliente'");
 $arraycliente = mysql_fetch_array($sql2);
 
 $sql3 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
 $arrayvehiculo = mysql_fetch_array($sql3);
  
?>

<form method='post' action='?'>
	<b><center>VENTA DE VEHICULO</center></b><br>

	<input type='hidden' name='idreserva' value="<?php echo "$idreserva"; ?>">
	<input type='hidden' name='impentrega' value="<?php echo "$importeentrega"; ?>">
	<input type='hidden' name='maxapuntes' value="<?php echo "$maxapuntes"; ?>">
	<input type='hidden' name='idvehiculo' value="<?php echo "$idvehiculo"; ?>">
	<input type='hidden' name='idcliente' value="<?php echo "$idcliente"; ?>">
	
	<table border="0" align ="center">
	
		<tr>
			<th colspan='4'>Datos del Cliente</th>
		</tr>
		
		<tr>
			<td>Codigo Cortesia:</td><td><?php echo $arraycliente['codigocortesia']; ?></td>
			<td>Nombre/Nombre Empresa:</td><td><?php echo $arraycliente['nombre']; ?></td>
		</tr>
	
		<tr>
			<td>Movil:</td><td><?php echo $arraycliente['movil']; ?></td>
			<td>Apellidos:</td><td><?php echo $arraycliente['apellidos']; ?></td>
		</tr>
		
		<tr>
			<td>E-Mail Cliente:</td><td><?php echo $arraycliente['email']; ?></td>
			<td>Telefono Fijo:</td><td><?php echo $arraycliente['fijo']; ?></td>
		</tr>
		
		<tr>
			<td>Codigo Postal Cliente:</td><td><?php echo $arraycliente['cp']; ?>></td>
			<td>Direccion Postal:</td><td> <?php echo $arraycliente['direccion']; ?></td>
			
		</tr>
		
		<tr>
			<td>DNI:</td><td> <?php echo $arraycliente['dni']; ?></td>
			<td>F.Nacimiento:</td><td> <?php echo $arraycliente['fechanacimiento']; ?></td>
		</tr>
	
	</table><br>
	
	<table border="0" align ="center">
		
		<tr>
			<th colspan='3'>Datos Economicos</th>
		</tr>
		
		<tr>
			<td>Referencia</td><td>Fecha</td><td>Importe</td>
		</tr>
		
		<tr>
			<td colspan='3'>Entrega a Cuenta:</td>
		</tr>
		<tr>
			<td> <input type='text' name='refentrega' value='' size=20 maxlength='20'></td>
			<td> 
				<input type='text' name='diaentrega' value='' size=2 maxlength='2'>
				<input type='text' name='mesentrega' value='' size=2 maxlength='2'>
				<input type='text' name='anyoentrega' value='' size=4 maxlength='4'>
			</td>
			<td> <?php echo $importeentrega; ?></td>
		</tr>
		<tr>
			<td colspan='3'>Bancos/Caja:</td>
		</tr>
		<?
		for($i=1;$i<=$maxapuntes;$i++)
		{
			echo "<tr>";
			echo "<td> <input type='text' name='ref".$i."' value='' size=20 maxlength='20'></td>";
			echo "<td>";
			echo "<input type='text' name='dia".$i."' value='' size=2 maxlength='2'>
				<input type='text' name='mes".$i."' value='' size=2 maxlength='2'>
				<input type='text' name='anyo".$i."' value='' size=4 maxlength='4'>";
			echo "</td>";
			echo "<td> <input type='text' name='importe".$i."' value='0' size=20 maxlength='20'></td>";
		}
		?>
		
		<tr>
			<td colspan='3'>Financiera:</td>
		</tr>
		<tr>
			<td colspan='2'><input type='text' name='nomfinanciera' value='' size=20 maxlength='20'></td>
			<td><input type='text' name='impfinanciera' value='0' size=20 maxlength='20'></td>
		</tr>
		<tr>
			<td>Entrega Vehiculo:</td>
			<td>Matricula</td>
			<td><input type='text' name='matriculaentrega' value='' size=20 maxlength='20'></td>
		</tr>
		
	</table>
	
	<table border="0" align ="center">
	
		<tr>
			<th colspan='2'>Gastos Varios</th>
		</tr>
		<tr>
			<td>Importe Garantia:</td>
			<td><input type='text' name='garantia' value='0' size=20 maxlength='20'></td>
		</tr>
		<tr>
			<td>Importe Cancelacion Financiera:</td>
			<td><input type='text' name='cancelacion' value='0' size=20 maxlength='20'></td>
		</tr>
		<tr>
			<td>Cuenta Cancelacion Financiera:</td>
			<td>
				<input type='text' name='bancocancelacion' value='' size=4 maxlength='4'>
				<input type='text' name='entidadcancelacion' value='' size=4 maxlength='4'>
				<input type='text' name='dccancelacion' value='' size=2 maxlength='10'>
				<input type='text' name='codigocancelacion' value='' size=10 maxlength='10'>
			</td>
		</tr>
		<tr>
			<td>Importe Devolver al Cliente:</td>
			<td><input type='text' name='devolucion' value='0' size=20 maxlength='20'></td>
		</tr>
		<tr>
			<td colspan='2'>Importante enviar Recibo del Banco escaneado que aparezca nombre del Cliente en el Recibo</td>
		</tr>
		
		<tr>
			<td colspan='2' align=right><input type='submit' value='Enviar' name='inventasubmit'></td>
		</tr>
		
	</table>
</form>

 
?>
