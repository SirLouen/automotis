<?php
/*
 * Created on 21/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
include("config.php");
include("include/gen_functions.php");
include("lang/$lang.php");
session_start();

if ($_SESSION['concesionario'] == 10 || $_SESSION['nivelusuario'] >= 4 )
{
	$usuario = $_SESSION['userid'];
	$sql = mysql_query("SELECT * FROM subastas WHERE activa = '1'");
	
	if (isset ($_POST['subastasubmit']))
	{
		$subasta = $_POST['idsubasta'];
		$puja = $_POST['puja'];
	 	
	 	$sql2 = "INSERT INTO subastas_pujas (subasta, fechainsercion, puja, usuario)
		 			VALUES ('$subasta', now(), '$puja',  '$usuario')";
		 	
		if (!(mysql_query($sql2,$conexion)))
		{
			die('Error: '.mysql_error());
		}
	 	else
	 	{
	 		echo "Puja Enviada!<br><br>";
	 		include("footer.php");
	 	} 	
	}
	
	elseif(mysql_num_rows($sql))
	{
		$arraysubasta = mysql_fetch_array($sql);
		$fechalimite = $arraysubasta['fechalimite'];
		$objfechalimite = new DateTime($fechalimite);
		$objfechahoy = new DateTime(date("Y-m-d H:i:s"));
		$fechalimitelegible = convertir_fecha_normal_completa($fechalimite);
		$idsubasta = $arraysubasta['id'];
		
		if ($objfechalimite > $objfechahoy)
		{
			
			$idvehiculo = $arraysubasta['vehiculo'];
			$sql2 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
			$arrayvehiculo = mysql_fetch_array($sql2);
			$matricula = $arrayvehiculo['matricula'];
			$fechamatric = $arrayvehiculo['fechamatric'];
			$pvd = $arrayvehiculo['pvd'];

		?>

			<table style="text-align: left; width: 800px;" border="1" cellpadding="2" cellspacing="2">
				<tr>
					<td colspan = "4" align="center" style="vertical-align: middle;"> <h2>Vehiculo en Subasta Actual</h2></td>
				</tr> 
				<tr>
					<td colspan="2" rowspan="6" style="vertical-align: top; width: 320px;"><a target="_blank" href="imagenes.php?i=0&amp;matricula=<?php echo $matricula;?>"><img style="width: 330px; height: 240px;" alt="" src="./imagenes/<? echo $matricula; ?>/tn/1.jpg"></a><br></td>
					<td style="vertical-align: top;"><?php echo $lang_subasta_matricula.': '.$matricula; ?><br></td>
				</tr>
				<tr>
				<td style="vertical-align: top;"><?php echo $lang_subasta_marca.': '.$arrayvehiculo['marca']; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_modelo.': '.$arrayvehiculo['modelo']; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_kilometros.': '.$arrayvehiculo['kilometros']; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_fechamatric.': '.fecha_normal($fechamatric); ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_pvd.': '.$pvd.' Euros'; ?><br></td>
				</tr>
				<tr>
					<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=1&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['1'];?>"></a><br></td>
					<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=2&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['2'];?>"></a><br></td>
					<td style="vertical-align: top;"><?php echo $lang_subasta_combustible.': '.$arrayvehiculo['combustible'];; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_potencia.': '.$arrayvehiculo['potencia'].' CV'; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top;"><?php echo $lang_subasta_cilindrada.': '.$arrayvehiculo['cilindrada']; ?><br></td>
				</tr>
				<tr>
					<td colspan="2" rowspan="1" style="vertical-align: middle; width: 320px; height: 40px;"><?php echo $lang_subasta_extras; ?><br></td>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_carroceria.': '.$arrayvehiculo['carroceria']; ?><br></td>
				</tr>
				<tr>
					<td colspan="2" rowspan="3" style="vertical-align: middle; width: 320px; height: 160px;"><?php echo $arrayvehiculo['extras']; ?><br></td>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_color.': '.$arrayvehiculo['color']; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_plazas.': '.$arrayvehiculo['plazas']; ?><br></td>
				</tr>
				<tr>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_usoanterior.': '.$arrayvehiculo['usoanterior'];?><br></td>
				</tr>
				<tr>
								
					<td colspan ="2" style="vertical-align: middle; height: 40px;">
					
					<?php
					$sql = mysql_query("SELECT * FROM subastas_pujas WHERE usuario = '$usuario' AND subasta = '$idsubasta'");
					if(!mysql_num_rows($sql))
					{
						$pvdpuja = $pvd + 100;
						echo "
					 	<form method='post' action='subastas.php'>
					 	<input type='hidden' name='idsubasta' value='".$idsubasta."'>
					 	Importe: <input type='text' name='puja' value='$pvdpuja'>
					 	<input type=submit name='subastasubmit' value='Lanzar Puja'>
					 	";
					}
					else
					{
						echo "Ya has pujado";
					}
					?>
					</td>
					<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_tiempo.': '.$fechalimitelegible;?><br></td>
				<tr>
				</table>
				<br><br>
		<?php
		
		}
		else
		{
			echo "Se ha acabado el tiempo de subasta.<br><br>";			
		}
		
		include("footer.php");
		
	}
	else
	{
		echo "No hay vehiculos en subasta.<br><br>";
		include("footer.php");
	}
}
	
	?>
