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
require_once("include/mensatek.inc");
session_start();

if ($_SESSION['concesionario'] == 10 || $_SESSION['nivelusuario'] >= 5 )
{
	$usuario = $_SESSION['userid'];
	$sql = mysql_query("SELECT * FROM subastas WHERE activa = '1'");
	
	$lineas = mysql_num_rows($sql);

	
	if (isset ($_POST['subastasubmit']))
	{
				
		$subasta = $_POST['idsubasta'];
		$puja = $_POST['puja'];
	 	$pujamin = $_POST['pujamin'];
	 	$usuariosubasta = $_POST['usuariosubasta'];
	 	 	
	 	if ($puja > $pujamin)
	 	{
	 	
		 	$sql2 = "INSERT INTO subastas_pujas (subasta, fechainsercion, puja, usuario)
			 			VALUES ('$subasta', now(), '$puja',  '$usuario')";
			 	
			if (!(mysql_query($sql2,$conexion)))
			{
				die('Error: '.mysql_error());
			}
		 	else
		 	{
		 		echo "Puja Enviada!<br><br>";
		 		
		 		$sql3 = mysql_query("SELECT * FROM usuarios WHERE userid = $usuariosubasta");
		 		$arrayusuario = mysql_fetch_array($sql3);
		 		$sql4 = mysql_query("SELECT * FROM subastas WHERE id = $subasta");
		 		$arraysubasta = mysql_fetch_array($sql4);
		 		$vehiculo = $arraysubasta['vehiculo'];
		 		$sql5 = mysql_query("SELECT * FROM vehiculos WHERE id = $vehiculo");
		 		$arrayvehiculo = mysql_fetch_array($sql5);
		 		
		 		$marca = $arrayvehiculo['marca'];
		 		$modelo = $arrayvehiculo['modelo'];
		 		
		 		$telefono = $arrayusuario['telefono'];
		 		$mensajesms = "Subastas Peugeot. Has sido sobrepujado $puja euros en el $marca que habias pujado. Vuelve a pujar cuanto antes!";
		 		
		 		if($telefono)
		 		{
			 		// Crear instancia Clase
					$Mensatek=new cMensatek($correomensatek,$passwordmensatek);
					$variables=array(
					"Remitente"=>"638000836",  //Remitente que aparece, puede ser número de móvil o texto (hasta 11 caracteres)
					"Destinatarios"=>$telefono, // Destinatarios del mensaje, si es más de 1 sepárelos por punto y coma
					"Mensaje"=>$mensajesms, //Mensaje, si se envían más de 160 caracteres se enviará en varios mensajes
					"Flash"=>0, // Formato Flash 
					"Report"=>1,  //Report de entrega al correo electrónico por defecto
					"Descuento"=>0 // Si utiliza descuento o no
					);
			 		
					$res=$Mensatek->enviar($variables);
		
		 		}
		 		$email = $arrayusuario['email'];
		 		
		 		if($email)
		 		{
					$cabeceras  = "MIME-Version: 1.0\r\n";
					$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
					$cabeceras .= "To: ".$arrayusuario['nombre']." <$email>\r\n";
					$cabeceras .= "From: Peugeot Ibericar <$adminemail>\r\n";
		
					$asunto = "Subastas Peugeot Ibericar: Acabas de ser Sobre Pujado";
		
					$mensaje = "Acabas de ser sobrepujado en el siguiente vehiculo:<br>
							   Marca: $marca<br>
							   Modelo: $modelo<br>
							   Fecha Matriculacion: ".fecha_normal($arrayvehiculo['fechamatric'])."<br>
							   Kilometros: ".$arrayvehiculo['kilometros']."<br>
							   El Importe de la sobrepuja es de $puja euros.<br>
							   Si crees que puedes pagar mas por el coche, entra en www.ibericarcadiz.com<br>
							   Para introducir tu nueva puja al sistema";
							   
				    mail ($email,$asunto,$mensaje,$cabeceras);
		 		}
		 	
		 	} 	
	 	}
	 	else
	 	{
	 		$puja = $pujamin + 50;
	 		echo "ERROR: Has Pujado por debajo de la Puja Minima: ".$pujamin." euros<br>";
	 		echo "<table><tr>";
	 		echo "<td>Importe Nueva Puja:</td>";
	 		echo "<td><form method='post' action='?'>
				  <input type='hidden' name='idsubasta' value='".$subasta."'>
				  <input type='text' name='puja' value='$puja'>
				  <input type='hidden' name='pujamin' value='".$pujamin."'>
				  <input type=submit name='subastasubmit' value='Lanzar Puja'>
				  </form>";
		    echo "</td>";
		    echo "</tr></table><br><br>";
	 	}
	 	
	}
	
	elseif($lineas)
	{
		
		$objfechahoy = new DateTime(date("Y-m-d H:i:s"));
		
		 // Extensiones validas
	 	$extensions = array('jpg','jpeg','gif','png','bmp','JPG');

		for($i=0;$i<$lineas;$i++)
		{
		
			$arraysubasta = mysql_fetch_array($sql);
			$fechalimite = $arraysubasta['fechalimite'];

			$objfechalimite = new DateTime($fechalimite);
			
			$fechalimitelegible = convertir_fecha_normal_completa($fechalimite);
			$idsubasta = $arraysubasta['id'];
			$idvehiculo = $arraysubasta['vehiculo'];
			$sql2 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
			$arrayvehiculo = mysql_fetch_array($sql2);
			$matricula = $arrayvehiculo['matricula'];
			
			// PARA CARGAR IMAGENES ALTERNATIVAS
			$folder_image_name = "/automotis/imagenes/$matricula/";
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
			
			if ($objfechalimite > $objfechahoy)
			{

				$fechamatric = $arrayvehiculo['fechamatric'];
				$pvd = $arrayvehiculo['pvd'];
	
			?>
	
				<table style="text-align: left; width: 800px;" border="1" cellpadding="2" cellspacing="2">
					<tr>
						<td colspan = "4" align="center" style="vertical-align: middle;"> <h2><?php echo $i+1; ?>.- Vehiculo en Subasta Actual</h2></td>
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
						<td colspan="2" rowspan="2" style="vertical-align: middle; width: 320px; height: 160px;"><?php echo $arrayvehiculo['extras']; ?><br></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_color.': '.$arrayvehiculo['color']; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_plazas.': '.$arrayvehiculo['plazas']; ?><br></td>
					</tr>
					<tr>
						<td colspan="2" style="vertical-align: middle; height: 40px;">
						<?php
							$usuariosubasta = 0;
							$primerapuja = 0;
							$sql3 = mysql_query("SELECT * FROM subastas_pujas WHERE subasta = '$idsubasta' ORDER BY fechainsercion DESC");
							if (mysql_num_rows($sql3))
							{
								$arraysubasta = mysql_fetch_array($sql3);
								$puja = $arraysubasta['puja'];
								$usuariosubasta = $arraysubasta['usuario'];
							}
							else
							{
								$puja = $pvd;
								$primerapuja = 1;
							}
							echo "Maxima Puja Actual: ".$puja;
						?>
						
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_subasta_usoanterior.': '.$arrayvehiculo['usoanterior'];?><br></td>
					</tr>
					<tr>
									
						<td colspan ="2" style="vertical-align: middle; height: 40px;">
						
						<?php
						// $sql2 = mysql_query("SELECT * FROM subastas_pujas WHERE usuario = '$usuario' AND subasta = '$idsubasta'");
						if($usuariosubasta != $usuario)
						{
							$pvdpuja = $puja + 50;
							echo "
						 	<form method='post' action='?'>
						 	<input type='hidden' name='idsubasta' value='".$idsubasta."'>
						 	Importe: <input type='text' name='puja' value='$pvdpuja'>";
						 	
						 	if ($primerapuja)
						 	{
						 		$pujamin = $puja-300;
						 		echo "<input type='hidden' name='pujamin' value='".$pujamin."'>";
						 	}
						 	else
						 	{
						 		$pujamin = $puja;
						 	}
						 	echo "<input type='hidden' name='pujamin' value='".$pujamin."'>
						 		  <input type='hidden' name='usuariosubasta' value='".$usuariosubasta."'>
						 		  <input type=submit name='subastasubmit' value='Lanzar Puja'>
						 		  </form>
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
					<br>
					<hr>
			<?php
			
			}
			else
			{
				echo "Se ha acabado el tiempo de la subasta del vehiculo matricula $matricula.<br><hr>";			
			}
			

		}
		
	}
	else
	{
		echo "No hay vehiculos en subasta.<br><br>";
		
	}
	include("footer.php");
}
	
	?>
