<?php
/*
 * Created on 08/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 	$matricula=$_POST['matricula'];
	$userid=$_SESSION['userid'];
	$idtasacion=$_POST['idtasacion'];
	$ofertadefecto = 0 ;
	$maxreacondicionamientos = 10;
	
	$sql = mysql_query("SELECT * FROM tasaciones WHERE id = '$idtasacion'");
		
	$array = mysql_fetch_array($sql);
	
	if ( $array['esfuerzocomercial3']) 
	{
		$esfuerzoctual = $array['esfuerzocomercial3'];
		$tipoinsercion = '3';
	}
	elseif ($array['esfuerzocomercial2']) 
	{ 
		$esfuerzoactual = $array['esfuerzocomercial2'];
		$tipoinsercion = '2';
	}
	else 
	{
		$esfuerzoactual = $array['esfuerzocomercial1'];
		$tipoinsercion = '1';
	}
	 	
 	$nombrecliente = $array['nombrecliente'];

 	$fecha = $array['fechamatric'];
 	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
    $dia = $mifecha[3];
    $mes = $mifecha[2];
    $anyo = $mifecha[1];
 
?>

<table border="1">

	<tr>
		<td>Matricula:</td><td> <?php echo $matricula; ?></td>
	</tr>

	<tr>
		<td>Marca Vehiculo:</td><td> <?php echo $array['marca']; ?></td>
	</tr>
	
	<tr>
		<td>Modelo Vehiculo EUROTAX:</td><td><?php echo $array['modelo']; ?></td>
	</tr>

	<tr>
		<td>Nombre Cliente:</td><td><?php echo $nombrecliente; ?></td>
	</tr>
	
	<tr>
		<td>Fecha Matriculacion (DD/MM/AAAA):</td>
		<td><?php echo $dia."/".$mes."/".$anyo; ?></td>
	</tr>
	
	<tr>
	<td>Kilometros:</td><td><?php echo $array['kilometros']; ?></td>
	</tr>
	
	<tr>
	<td>Color:</td><td><?php echo $array['color']; ?></td>
	</tr>
	
	<tr>
	<td>Combustible:</td><td><?php echo $array['combustible']; ?></td>
	</tr>
	
	<tr>
	<td>Potencia:</td><td><?php echo $array['potencia']; ?></td>
	</tr>
	
	<tr>
	<td>Cilindrada:</td><td><?php echo $array['cilindrada']; ?></td>
	</tr>
	
	<tr>
	<td>Carroceria (Num. Puertas):</td><td><?php echo $array['carroceria']; ?></td>
	</tr>
	
	<tr>
	<td>Numero Plazas:</td><td><?php echo $array['plazas']; ?></td>
	</tr>
	
	<tr>
		<td>Uso Anterior:</td><td><?php echo $array['usoanterior']; ?></td>
	</tr>
	
	<tr>
		<td>Estimacion PVP:</td><td><?php echo $array['pvpestimado']; ?></td>
	</tr>
	
	<tr>
	<td>Valor de Mercado EUROTAX:</td><td><?php echo $array['valormercado']; ?></td>
	</tr>
	
	<tr>
	<td>Esfuezo Comercial:</td><td><?php echo $esfuerzoactual; ?></td>
	</tr>
	
	<? 
		$sql3 = mysql_query("SELECT * FROM reacondicionamientos WHERE tasacion = '$idtasacion'");
 		$lineas = mysql_num_rows($sql3);
 			
 		for ($i=0;$i < $lineas; $i++)	
 		{
 			$arraytemp = mysql_fetch_array($sql3);
 			$idreacon =  $arraytemp['id'];
 			$arrayreacon[$idreacon] = $arraytemp;
 		}
 			
		for($i=0; $i<$maxreacondicionamientos; $i++) 
		{
			echo ("
	   		<tr>
				<td>Descripcion Reacondicionamiento:</td><td>".$arrayreacon[$i]['descripcion']."</td>
			</tr> 
			<tr>
				<td>Valor Reacondicionamiento:</td><td> ".$arrayreacon[$i]['valor']."</td>
			</tr>
			");
		}
	?>
	
	
	<tr>
		<td>Nombre Extra</td><td>Codigo Extra</td>
	</tr>
	<?
	if ($array['pm'])
	{ 
	?>
	<tr>
		<td>Pintura Metalizada</td><td>PM</td>
	</tr>
	<?
	} 
	 if ($array['ll'])
	{ 
	?>
	<tr>
		<td>Llantas Aleacion</td><td>LL</td>
	</tr>
	<?
	} 
	 if ($array['abs'])
	{ 
	?>
	<tr>
		<td>ABS</td><td>ABS</td>
	</tr>
	<?
	} 
	 if ($array['esp'])
	{ 
	?>
	<tr>
		<td>ESP</td><td>ESP</td>
	</tr>
	<?
	} 
	 if ($array['ct'])
	{ 
	?>
	<tr>
		<td>Control Traccion</td><td>CT</td>
	</tr>
	<?
	} 
	 if ($array['fourwd'])
	{ 
	?>
	<tr>
		<td>4WD</td><td>4WD</td>
	</tr>
	<?
	} 
	 if ($array['ac'])
	{ 
	?>
	<tr>
		<td>Airbag Conductor</td><td>AC</td>
	</tr>
	<?
	} 
	 if ($array['ap'])
	{ 
	?>
	<tr>
		<td>Airbag Pasajero</td><td>AP</td>
	</tr>
	<?
	} 
	 if ($array['al'])
	{ 
	?>
	<tr>
		<td>Airbags Laterales</td><td>AL</td>
	</tr>
	<?
	} 
	 if ($array['ala'])
	{ 
	?>
	<tr>
		<td>Alarma</td><td>ALA</td>
	</tr>
	<?
	} 
	 if ($array['an'])
	{ 
	?>
	<tr>
		<td>Antiniebla</td><td>AN</td>
	</tr>
	<?
	} 
	 if ($array['in'])
	{ 
	?>
	<tr>
		<td>Inmovilizador</td><td>IN</td>
	</tr>
	<?
	} 
	 if ($array['cc'])
	{ 
	?>
	<tr>
		<td>Cierre Centralizado</td><td>CC</td>
	</tr>
	<?
	} 
	 if ($array['aa'])
	{ 
	?>
	<tr>
		<td>Aire Acondicionado</td><td>AA</td>
	</tr>
	<?
	} 
	 if ($array['cl'])
	{ 
	?>
	<tr>
		<td>Climatizador</td><td>CL</td>
	</tr>
	<?
	} 
	 if ($array['ts'])
	{ 
	?>
	<tr>
		<td>Techo Solar</td><td>TS</td>
	</tr>
	<?
	} 
	 if ($array['da'])
	{ 
	?>
	<tr>
		<td>Direccion Asistida</td><td>DA</td>
	</tr>
	<?
	} 
	 if ($array['ee'])
	{ 
	?>
	<tr>
		<td>Elevalunas Electrico</td><td>EE</td>
	</tr>
	<?
	} 
	 if ($array['ae'])
	{ 
	?>
	<tr>
		<td>Asiento Electrico</td><td>AE</td>
	</tr>
	<?
	} 
	 if ($array['cv'])
	{ 
	?>
	<tr>
		<td>Control de Velocidad</td><td>CV</td>
	</tr>
	<?
	} 
	 if ($array['fx'])
	{ 
	?>
	<tr>
		<td>Faros Xenon</td><td>FX</td>
	</tr>
	<?
	} 
	 if ($array['apk'])
	{ 
	?>
	<tr>
		<td>Ayuda Parking</td><td>APK</td>
	</tr>
	<?
	} 
	 if ($array['rcd'])
	{ 
	?>
	<tr>
		<td>Radio CD</td><td>RCD</td>
	</tr>
	<?
	} 
	 if ($array['gps'])
	{ 
	?>
	<tr>
		<td>Navegador GPS</td><td>GPS</td>
	</tr>
	<?
	} 
	 if ($array['cu'])
	{ 
	?>
	<tr>
		<td>Tapiceria Cuero</td><td>CU</td>
	</tr>
	<?
	} 
	 if ($array['aca'])
	{ 
	?>
	<tr>
		<td>Asientos Calefactados</td><td>ACA</td>
	</tr>
	<?
	} 
	 if ($array['ba'])
	{ 
	?>
	<tr>
		<td>Baca</td><td>BA</td>
	</tr>
	<?
	} 
	 if ($array['br'])
	{ 
	?>
	<tr>
		<td>Bola Remolque</td><td>BR</td>
	</tr>
	<?
	} 
	 if ($array['tu'])
	{ 
	?>
	<tr>
		<td>Tunning</td><td>TU</td>
	</tr>
	<?
	} 
	?>

	<tr>
	<td>Observaciones:</td><td> <?php echo $array['observaciones']; ?></td>
	</tr>

</table>