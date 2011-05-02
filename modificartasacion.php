<?php
/*
 * Created on 24/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
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

<form method='post' action='?'>

<b>Matricula :</b> <? echo "$matricula" ; ?> <br><br>

<input type="hidden" name="matricula" value="<?php echo "$matricula"; ?>">
<input type='hidden' name='tipoinsercion' value='<?php echo $tipoinsercion; ?>'>
<input type='hidden' name='idtasacion' value='<?php echo $idtasacion; ?>'>
<input type='hidden' name='esfuerzoactual' value='<?php echo $esfuerzoactual; ?>'>

<table border="0">

	<tr>
		<td>Marca Vehiculo:</td><td> <input type='text' name='marca' value='<?php echo $array['marca']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Modelo Vehiculo EUROTAX:</td><td> <input type='text' name='modelo' value='<?php echo $array['modelo']; ?>' size=50 maxlength='50'></td>
	</tr>

	<tr>
		<td>Nombre Cliente:</td><td> <input type='text' name='nombrecliente' value='<?php echo $nombrecliente; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Fecha Matriculacion (DD/MM/AAAA):</td>
		<td><input type='text' name='dia' maxlength='2' size='2' value = '<?php echo $dia; ?>'>
		<input type='text' name='mes' maxlength='2' size='2' value = '<?php echo $mes; ?>'>
		<input type='text' name='anyo' maxlength='4' size='4' value = '<?php echo $anyo; ?>'></td>
	</tr>
	
	<tr>
	<td>Kilometros:</td><td> <input type='text' name='kilometros' value='<?php echo $array['kilometros']; ?>'></td>
	</tr>
	
	<tr>
	<td>Color:</td><td> <input type='text' name='color' value='<?php echo $array['color']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
	<td>Combustible:</td><td> <input type='text' name='combustible' value='<?php echo $array['combustible']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
	<td>Potencia:</td><td> <input type='text' name='potencia' value='<?php echo $array['potencia']; ?>' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Cilindrada:</td><td> <input type='text' name='cilindrada' value='<?php echo $array['cilindrada']; ?>' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Carroceria (Num. Puertas):</td><td> <input type='text' name='carroceria' value='<?php echo $array['carroceria']; ?>' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Numero Plazas:</td><td> <input type='text' name='plazas' value='<?php echo $array['plazas']; ?>' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
		<td>Uso Anterior:</td><td> <input type='text' name='usoanterior' value='<?php echo $array['usoanterior']; ?>' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Estimacion PVP:</td><td> <input type='text' name='pvpestimado' value='<?php echo $array['pvpestimado']; ?>'></td>
	</tr>
	
	<tr>
	<td>Valor de Mercado EUROTAX:</td><td> <input type='text' name='valormercado' value='<?php echo $array['valormercado']; ?>'></td>
	</tr>
	
	<tr>
	<td>Esfuezo Comercial:</td><td> <input type='text' name='esfuerzocomercial' value='<?php echo $esfuerzoactual; ?>'></td>
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
				<td>Descripcion Reacondicionamiento:</td><td> <input type='text' name='desreacon[]' value='".$arrayreacon[$i]['descripcion']."' size=40 maxlength='40'></td>
			</tr> 
			<tr>
				<td>Valor Reacondicionamiento:</td><td> <input type='text' name='valorreacon[]' value='".$arrayreacon[$i]['valor']."'></td>
			</tr>
			");
		}
	?>
	
	
	<tr>
		<td>Extras:</td><td></td>
	</tr>
	<tr>
		<td>Pintura Metalizada</td><td> <input type=checkbox name='pm' value='1' <?php if ($array['pm']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Llantas Aleacion</td><td> <input type=checkbox name='ll' value='1' <?php if ($array['ll']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>ABS</td><td> <input type=checkbox name='abs' value='1' <?php if ($array['abs']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>ESP</td><td> <input type=checkbox name='esp' value='1' <?php if ($array['esp']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Control Traccion</td><td> <input type=checkbox name='ct' value='1' <?php if ($array['ct']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>4WD</td><td> <input type=checkbox name='fourwd' value='1' <?php if ($array['fourwd']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Airbag Conductor</td><td> <input type=checkbox name='ac' value='1' <?php if ($array['ac']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Airbag Pasajero</td><td> <input type=checkbox name='ap' value='1' <?php if ($array['ap']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Airbags Laterales</td><td> <input type=checkbox name='al' value='1' <?php if ($array['al']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Alarma</td><td> <input type=checkbox name='ala' value='1' <?php if ($array['ala']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Antiniebla</td><td> <input type=checkbox name='an' value='1' <?php if ($array['an']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Inmovilizador</td><td> <input type=checkbox name='inm' value='1' <?php if ($array['inm']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Cierre Centralizado</td><td> <input type=checkbox name='cc' value='1' <?php if ($array['cc']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Aire Acondicionado</td><td> <input type=checkbox name='aa' value='1' <?php if ($array['aa']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Climatizador</td><td> <input type=checkbox name='cl' value='1' <?php if ($array['cl']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Techo Solar</td><td> <input type=checkbox name='ts' value='1' <?php if ($array['ts']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Direccion Asistida</td><td> <input type=checkbox name='da' value='1' <?php if ($array['da']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Elevalunas Electrico</td><td> <input type=checkbox name='ee' value='1' <?php if ($array['ee']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Asiento Electrico</td><td> <input type=checkbox name='ae' value='1' <?php if ($array['ae']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Control de Velocidad</td><td> <input type=checkbox name='cv' value='1' <?php if ($array['cv']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Faros Xenon</td><td> <input type=checkbox name='fx' value='1' <?php if ($array['fx']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Ayuda Parking</td><td> <input type=checkbox name='apk' value='1' <?php if ($array['apk']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Radio CD</td><td> <input type=checkbox name='rcd' value='1' <?php if ($array['rcd']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Navegador GPS</td><td> <input type=checkbox name='gps' value='1' <?php if ($array['gps']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Tapiceria Cuero</td><td> <input type=checkbox name='cu' value='1' <?php if ($array['cu']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Asientos Calefactados</td><td> <input type=checkbox name='aca' value='1' <?php if ($array['aca']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Baca</td><td> <input type=checkbox name='ba' value='1' <?php if ($array['ba']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Bola Remolque</td><td> <input type=checkbox name='br' value='1' <?php if ($array['br']){ echo "checked";} ?>></td>
	</tr>
	<tr>
		<td>Tunning</td><td> <input type=checkbox name='tu' value='1' <?php if ($array['tu']){ echo "checked";} ?>></td>
	</tr>

	<tr>
	<td>Observaciones:</td><td> <textarea cols='40' rows='3' name='observaciones'><?php echo $array['observaciones']; ?></textarea></td>
	</tr>

	<tr>
		<td align=right><input type='submit' value="Modificar" name = "intasasubmit"></td>
	</tr>
</table>
</form>