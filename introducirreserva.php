<?php
/*
 * Created on 02/05/2011
 *
 * Automotis DMS Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 $usuario = $_SESSION['userid'];
 $idoferta = $_POST['idoferta'];
 $idvehiculo = $_POST['idvehiculo'];
 
 $sql = mysql_query("SELECT * FROM ofertas WHERE id = '$idoferta'");
 $arrayoferta = mysql_fetch_array($sql);
 
 $idcliente = $arrayoferta['cliente'];
 
 $sql2 = mysql_query("SELECT * FROM clientes WHERE id = '$idcliente'");
 $arraycliente = mysql_fetch_array($sql2);
 
 $sql3 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
 $arrayvehiculo = mysql_fetch_array($sql3);
 
 $porcentajeentcuenta = 0.015; 
  
 $importeideal = floor(($arrayvehiculo['pvp'] + $arrayoferta['sobreprecio'] - $arrayoferta['descuento'])*$porcentajeentcuenta);
  
?>

<form method='post' action='?'>
	<b><center>RESERVA DE VEHICULO</center></b><br>

	<input type='hidden' name='idvehiculo' value="<?php echo "$idvehiculo"; ?>">
	<input type='hidden' name='idoferta' value="<?php echo "$idoferta"; ?>">
	<input type='hidden' name='idcliente' value="<?php echo "$idcliente"; ?>">
	
<table border="0" align ="center">

	<tr>
		<th colspan='2'>Datos del Cliente</th>
	</tr>
	
	<tr>
		<td>Tipo Cliente:</td>
		<td><select name='tipo_cliente'> 
		 <option value="E" <?php if($arraycliente['tipo'] == "E"){ echo "selected"; } ?>>Empresa</option>
		 <option value="P" <?php if($arraycliente['tipo'] == "P"){ echo "selected"; } ?>>Particular</option></select></td>
	</tr>
	
	<tr>
		<td>Codigo Cortesia:</td>
		<td><select name = 'codigocortesia_cliente'> 
		<option value="sr" <?php if($arraycliente['codigocortesia'] == "sr"){ echo "selected"; } ?>>Sr.</option>
		<option value="sra" <?php if($arraycliente['codigocortesia'] == "sra"){ echo "selected"; } ?>>Sra.</option>
		<option value="srta" <?php if($arraycliente['codigocortesia'] == "srta"){ echo "selected"; } ?>>Srta.</option></select></td>
	</tr>
	
	<tr>
		<td>Nombre/Nombre Empresa:</td><td> <input type='text' name='nombre_cliente' value='<?php echo $arraycliente['nombre']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Apellidos (Vacio si es Empresa):</td><td> <input type='text' name='apellidos_cliente' value='<?php echo $arraycliente['apellidos']; ?>' size=30 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Movil:</td><td> <input type='text' name='movil_cliente' value='<?php echo $arraycliente['movil']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Telefono Fijo:</td><td> <input type='text' name='fijo_cliente' value='<?php echo $arraycliente['fijo']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>E-Mail Cliente:</td><td> <input type='text' name='email_cliente' value='<?php echo $arraycliente['email']; ?>' size=40 maxlength='40'></td>
	</tr>
	
	<tr>
		<td>Codigo Postal Cliente:</td><td> <input type='text' name='cp_cliente' value='<?php echo $arraycliente['cp']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<th colspan='2'>Datos de Reserva</th>
	</tr>
	
	<tr>
		<td>Direccion Postal:</td><td> <input type='text' name='direccion_cliente' value='' size=40 maxlength='40'></td>
	</tr>
	
	<tr>
		<td>DNI/Pasaporte con letra:</td><td> <input type='text' name='dni_cliente' value='' size=10 maxlength='10'></td>
	</tr>
	
	<tr>
		<td>Fecha Nacimiento:</td>
		<td> 
		<input type='text' name='dia_cliente' value='' size=2 maxlength='2'>
		<input type='text' name='mes_cliente' value='' size=2 maxlength='2'>
		<input type='text' name='anyo_cliente' value='' size=4 maxlength='4'>
		</td>
	</tr>
	
	<tr>
		<th colspan='2'>Entrega a Cuenta</th>
	</tr>
	
	<tr>
		<td>
		<input type='hidden' name='importeideal' value='<?php echo $importeideal; ?>' size=20 maxlength='20'>
		Importe Ideal:</td><td><?php echo $importeideal; ?> euros
		</td>
	</tr>
	<tr>
		<td>Importe:</td><td> <input type='text' name='importe' value='' size=20 maxlength='20'></td>
	</tr>	

	<tr>
		<td align=right><input type='submit' value='Enviar' name='inreservasubmit'></td>
	</tr>
	
</table>
</form>