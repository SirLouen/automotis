<?php
/*
 * Created on 18/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 include("config.php");
 include("lang/$lang.php");
 
 session_start();
 
 $maxofertas = 10;
 
 
 if (isset ($_POST['ofertasubmit']))
 {
	include("introduciroferta.php"); 	
	include("footer.php");
 } 
 elseif (isset ($_POST['inofertasubmit']))
 {
 	
 	$usuariooferta = $_SESSION['userid'];
 	
 	$nombre_cliente = $_POST['nombre_cliente'];
 	$apellidos_cliente = $_POST['apellidos_cliente'];
 	$cp_cliente = $_POST['cp_cliente'];
 	$movil_cliente = $_POST['movil_cliente'];
 	$fijo_cliente = $_POST['fijo_cliente'];
 	$email_cliente = $_POST['email_cliente'];
 	$codigocortersia_cliente = $_POST['codigocortersia_cliente'];
 	$tipo_cliente = $_POST['tipo_cliente'];
 	
 	// Insertamos primero el cliente en la tabla de clientes
 	
 	$sql = mysql_query("SELECT * FROM clientes
		 WHERE (movil = '$movil_cliente') OR ((fijo = '$fijo_cliente') AND (nombre LIKE '%$nombre_cliente%')) OR
		 	   (email = '$email_cliente')");
		 	   
	$login_check = mysql_num_rows($sql);
	
	if($login_check == 0)
	{
		$sql = "INSERT INTO clientes (nombre, apellidos, cp, movil, fijo, email, usuario, fechacreacion, codigocortesia, tipo)
 			VALUES ('$nombre_cliente', '$apellidos_cliente', '$cp_cliente', '$movil_cliente', '$fijo_cliente', 
 					'$email_cliente', '$usuariooferta', now(), '$codigocortesia_cliente', '$tipo_cliente')";
 		if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}				
		
		$sql = mysql_query("SELECT id FROM clientes
			   WHERE (movil = '$movil_cliente') OR ((fijo = '$fijo_cliente') AND (nombre LIKE '%$nombre_cliente%')) OR
		 	   (email = '$email_cliente')");
 	
 		$rowcliente = mysql_fetch_row($sql);
 		$cliente = $rowcliente[0];
		
	}
	else
	{
		$existe = 0;
		$array = mysql_fetch_array($sql);
		$cliente = $array['id'];
		if ($array['nombre']){ $nombre_cliente =  $array['nombre']; $existe++; }
		if ($array['apellidos']){ $apellidos_cliente =  $array['apellidos']; $existe++; }
		if ($array['movil']){ $movil_cliente =  $array['movil']; $existe++; }
		if ($array['fijo']){ $fijo_cliente =  $array['fijo']; $existe++; }
		if ($array['email']){ $email_cliente =  $array['email']; $existe++; }
		if ($array['cp']){ $cp_cliente =  $array['cp']; $existe++; }
		if ($array['codigocortersia']){ $codigocortersia_cliente =  $array['codigocortersia']; $existe++; }
		if ($array['tipo']){ $tipo_cliente =  $array['tipo']; $existe++; }
		
		if ($existe != 8)
		{
		$sql2 = "UPDATE clientes
		    	SET nombre = '$nombre_cliente', apellidos = '$apellidos_cliente', movil = '$movil_cliente',
		    	fijo = '$fijo_cliente',
		    	email = '$email_cliente', cp = '$cp_cliente', fechacreacion = now(), usuario = '$usuariooferta' 
		    	WHERE (id = '$cliente')";	
		    	if (!(mysql_query($sql2,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		}
		    	
	}
 	
 	$matricula = $_POST['matricula'];
 	$vehiculo = $_POST['idvehiculo'];
 	$descuento = $_POST['descuento'];
 	$tasacion = $_POST['tasacion'];
 	$entrada1 = $_POST['entrada1'];
 	$meses1 = $_POST['meses1'];
 	$importe1 = $_POST['importe1'];
 	$entrada2 = $_POST['entrada2'];
 	$meses2 = $_POST['meses2'];
 	$importe2 = $_POST['importe2'];
 	$entrada3 = $_POST['entrada3'];
 	$meses3 = $_POST['meses3'];
 	$importe3 = $_POST['importe3'];
 	
 	$sql = "INSERT INTO ofertas (vehiculo, cliente, tasacion, usuario, descuento, fechacreacion, entrada1, meses1,
				importe1, entrada2, meses2, importe2, entrada3, meses3, importe3)
	 			VALUES ('$vehiculo', '$cliente', '$tasacion', '$usuariooferta', '$descuento', now(), '$entrada1', '$meses1',
				'$importe1', '$entrada2', '$meses2', '$importe2', '$entrada3', '$meses3', '$importe3')";
	 	
	 	if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
		else
		{
			$sql2 = mysql_query("SELECT id FROM ofertas WHERE vehiculo = '$vehiculo' AND cliente = '$cliente' AND 
			tasacion = '$tasacion' AND usuario = '$usuariooferta'");
			$rowoferta = mysql_fetch_row($sql2);
			
			echo "<a target='_blank' href='imprimiroferta.php?oferta=".$rowoferta[0]."'> Imprimir Oferta</a> <br><br>";
		}
		include("footer.php");
 } 
 else
 {
 
	 // Extensiones validas
	 $extensions = array('jpg','jpeg','gif','png','bmp','JPG');
	 $matricula = $_GET['matricula'];
	
	 $folder_image_name = "/webdms/imagenes/$matricula/";
	 $images_folder_path = $_SERVER['DOCUMENT_ROOT'].$folder_image_name;
	 $url_to_folder = 'http://'.$_SERVER["SERVER_NAME"].$folder_image_name;
	
	 $images = array();
	
	 // Introducir las imagenes en un array
	 if ($handle = opendir($images_folder_path)) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != "..") {
	
	          $ext = strtolower(substr(strrchr($file, "."), 1));
	                
	          if(in_array($ext, $extensions)){
	            $images[] = $file;
	          }
	        }
	    }
		closedir($handle);
	 }
	 
	 
	 $matricula = $_GET['matricula'];
	 
	 $sql = mysql_query("SELECT * FROM vehiculos WHERE (matricula = '$matricula')");
			
	 $login_check = mysql_num_rows($sql);
	 
	 if($login_check > 0)
		{
			while($row = mysql_fetch_array($sql))
			{
				foreach( $row AS $key => $val )
				{
					$$key = stripslashes( $val );
				}
				
				
			?>
			
			
				<table style="text-align: left; width: 800px;" border="1" cellpadding="2" cellspacing="2">
					<tr>
						<td colspan="2" rowspan="6" style="vertical-align: top; width: 320px;"><a target="_blank" href="imagenes.php?i=0&amp;matricula=<?php echo $matricula;?>"><img style="width: 330px; height: 240px;" alt="" src="./imagenes/<? echo $matricula; ?>/tn/1.jpg"></a><br></td>
						<td style="vertical-align: top;"><?php echo $lang_file_matricula.': '.$matricula; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_marca.': '.$marca; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_modelo.': '.$modelo; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_kilometros.': '.$kilometros; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_fechamatric.': '.fecha_normal($fechamatric); ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php
						if ($_SESSION['concesionario']=="10")
							echo $lang_file_pvd.': '.$pvd.' Euros'; 	
						else
						 	echo $lang_file_pvp.': '.$pvp.' Euros';						
						?><br></td>
					</tr>
					<tr>
						<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=1&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['1'];?>"></a><br></td>
						<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=2&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['2'];?>"></a><br></td>
						<td style="vertical-align: top;"><?php echo $lang_file_combustible.': '.$combustible; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_potencia.': '.$potencia.' CV'; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_cilindrada.': '.$cilindrada; ?><br></td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1" style="vertical-align: middle; width: 320px; height: 40px;"><?php echo $lang_file_extras; ?><br></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_carroceria.': '.$carroceria; ?><br></td>
					</tr>
					<tr>
						<td colspan="2" rowspan="4" style="vertical-align: middle; width: 320px; height: 160px;"><?php echo $extras; ?><br></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_color.': '.$color; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_ubicacion.': '.$ubicacion; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_categoria.': '.$categoria; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_usoanterior.': '.$usoanterior; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: middle; height: 40px;">
						<? if ($_SESSION['nivelusuario'] >= '2') { ?>
						 <form method='post' action='fichavehiculo.php''>
						 <input type='hidden' name='matricula' value='<?php echo "$matricula"; ?> '>
						 <input type='hidden' name='idvehiculo' value='<?php echo "$id"; ?> '>
						 <input type=submit name='ofertasubmit' value='Crear Oferta'>
						<?	} ?>
						</td>
						<td style="vertical-align: middle; height: 40px;"><strike>Crear Reserva Proximamente</strike></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_garantia.': '.$garantia; ?><br></td>
					<tr>
				</table>
				<br>
				<? if ($_SESSION['nivelusuario'] >= '2') { ?>
				<table border ='1'>
					<?php 
						$sql = mysql_query("SELECT * FROM ofertas WHERE vehiculo = $id");
						$filas = mysql_num_rows($sql);
						echo "<tr>";
						echo "<td>Oferta</td><td>Usuario</td><td>Total</td><td>Imprimir</td>";
						echo "</tr>";
						for($i=0;$i<$filas&&$i<$maxofertas;$i++)
						{	
							echo "<tr>";
							$arrayofertas = mysql_fetch_array($sql);
							$total = $pvp - $arrayofertas['descuento'];
							$usuariooferta = $arrayofertas['usuario'];
							$sql2 = mysql_query("SELECT nombre FROM usuarios WHERE userid = $usuariooferta");
							$rowusuario = mysql_fetch_row($sql2);
							
							echo "<td>".$arrayofertas['id']."</td>";
							echo "<td>".$rowusuario[0]."</td>";
							echo "<td>".$total."</td>";
							echo "<td><a target='_blank' href='imprimiroferta.php?oferta=".$arrayofertas['id']."'> Imprimir Oferta</a></td>";
//							echo "<td>";
//							if ($_SESSION['userid'] == $arrayofertas['usuario']) 
//							{ 
//						 		echo "<form method='post' action='imprimiroferta.php''>
//						 		<input type='hidden' name='matricula' value='".$arrayofertas['id']."'>
//						 		<input type='hidden' name='idvehiculo' value='".$id."'>
//						 		<input type=submit name='modificarofertasubmit' value='Reserva'>";
//							} 
//							echo "</td>";
							echo "</tr>";
						}
						
					?>
				</table>
				<?	} ?>
				<br>
			
			<?php
					
			}
		}
		else 
		{
			echo "$lang_file_fail<br />";
		}
		
 }
?>
