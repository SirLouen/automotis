<?php
/*
 * Created on 23/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 $maxreacondicionamientos = 10;
 $tipoinsercion = 0;
  
?>

<form method='post' action='?'>
	<b><center>Matricula :<?php echo "$matricula" ; ?></center></b><br>

	<input type="hidden" name="matricula" value="<?php echo "$matricula"; ?>">
	<input type='hidden' name='tipoinsercion' value='<?php echo $tipoinsercion; ?>'>
	<input type='hidden' name='idvaloracion' value='<?php echo $idvaloracion; ?>'>
	
<table border="0" align ="center">
	
	<tr>
		<td>Marca Vehiculo:</td><td> <input type='text' name='marca' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Modelo Vehiculo EUROTAX:</td><td> <input type='text' name='modelo' value='' size=50 maxlength='50'></td>
	</tr>

	<tr>
		<td>Nombre Cliente:</td><td> <input type='text' name='nombrecliente' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Fecha Matriculacion (DD/MM/AAAA):</td>
		<td><input type='text' name='dia' maxlength='2' size='2'>
		<input type='text' name='mes' maxlength='2' size='2'>
		<input type='text' name='anyo' maxlength='4' size='4'></td>
	</tr>
	
	<tr>
	<td>Kilometros:</td><td> <input type='text' name='kilometros' value=''></td>
	</tr>
	
	<tr>
	<td>Color:</td><td> <input type='text' name='color' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
	<td>Combustible:</td><td> <input type='text' name='combustible' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
	<td>Potencia:</td><td> <input type='text' name='potencia' value='' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Cilindrada:</td><td> <input type='text' name='cilindrada' value='' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Carroceria (Num. Puertas):</td><td> <input type='text' name='carroceria' value='' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Numero Plazas:</td><td> <input type='text' name='plazas' value='' size=5 maxlength='5'></td>
	</tr>
	
	<tr>
	<td>Uso Anterior:</td><td> <input type='text' name='usoanterior' value='' size=20 maxlength='20'></td>
	</tr>
	
	<tr>
		<td>Estimacion PVP:</td><td> <input type='text' name='pvpestimado' value=''></td>
	</tr>
	
	<tr>
	<td>Valor de Mercado EUROTAX:</td><td> <input type='text' name='valormercado' value=''></td>
	</tr>
	
	<tr>
	<td>Esfuezo Comercial:</td><td> <input type='text' name='esfuerzocomercial' value=''></td>
	</tr>
	
	<? 
		
		for($i=0; $i<$maxreacondicionamientos; $i++) 
		{
			echo ("
	   		<tr>
				<td>Descripcion Reacondicionamiento:</td><td> <input type='text' name='desreacon[]' value='' size=40 maxlength='40'></td>
			</tr> 
			<tr>
				<td>Valor Reacondicionamiento:</td><td> <input type='text' name='valorreacon[]' value=''></td>
			</tr>
			");
		}
	?>
	
	
	
	<tr>
		<td>Extras:</td><td></td>
	</tr>
	<tr>
		<td>Pintura Metalizada</td><td> <input type=checkbox name='pm' value='1'></td>
	</tr>
	<tr>
		<td>Llantas Aleacion</td><td> <input type=checkbox name='ll' value='1'></td>
	</tr>
	<tr>
		<td>ABS</td><td> <input type=checkbox name='abs' value='1'></td>
	</tr>
	<tr>
		<td>ESP</td><td> <input type=checkbox name='esp' value='1'></td>
	</tr>
	<tr>
		<td>Control Traccion</td><td> <input type=checkbox name='ct' value='1'></td>
	</tr>
	<tr>
		<td>4WD</td><td> <input type=checkbox name='fourwd' value='1'></td>
	</tr>
	<tr>
		<td>Airbag Conductor</td><td> <input type=checkbox name='ac' value='1'></td>
	</tr>
	<tr>
		<td>Airbag Pasajero</td><td> <input type=checkbox name='ap' value='1'></td>
	</tr>
	<tr>
		<td>Airbags Laterales</td><td> <input type=checkbox name='al' value='1'></td>
	</tr>
	<tr>
		<td>Alarma</td><td> <input type=checkbox name='ala' value='1'></td>
	</tr>
	<tr>
		<td>Antiniebla</td><td> <input type=checkbox name='an' value='1'></td>
	</tr>
	<tr>
		<td>Inmovilizador</td><td> <input type=checkbox name='inm' value='1'></td>
	</tr>
	<tr>
		<td>Cierre Centralizado</td><td> <input type=checkbox name='cc' value='1'></td>
	</tr>
	<tr>
		<td>Aire Acondicionado</td><td> <input type=checkbox name='aa' value='1'></td>
	</tr>
	<tr>
		<td>Climatizador</td><td> <input type=checkbox name='cl' value='1'></td>
	</tr>
	<tr>
		<td>Techo Solar</td><td> <input type=checkbox name='ts' value='1'></td>
	</tr>
	<tr>
		<td>Direccion Asistida</td><td> <input type=checkbox name='da' value='1'></td>
	</tr>
	<tr>
		<td>Elevalunas Electrico</td><td> <input type=checkbox name='ee' value='1'></td>
	</tr>
	<tr>
		<td>Asiento Electrico</td><td> <input type=checkbox name='ae' value='1'></td>
	</tr>
	<tr>
		<td>Control de Velocidad</td><td> <input type=checkbox name='cv' value='1'></td>
	</tr>
	<tr>
		<td>Faros Xenon</td><td> <input type=checkbox name='fx' value='1'></td>
	</tr>
	<tr>
		<td>Ayuda Parking</td><td> <input type=checkbox name='apk' value='1'></td>
	</tr>
	<tr>
		<td>Radio CD</td><td> <input type=checkbox name='rcd' value='1'></td>
	</tr>
	<tr>
		<td>Navegador GPS</td><td> <input type=checkbox name='gps' value='1'></td>
	</tr>
	<tr>
		<td>Tapiceria Cuero</td><td> <input type=checkbox name='cu' value='1'></td>
	</tr>
	<tr>
		<td>Asientos Calefactados</td><td> <input type=checkbox name='aca' value='1'></td>
	</tr>
	<tr>
		<td>Baca</td><td> <input type=checkbox name='ba' value='1'></td>
	</tr>
	<tr>
		<td>Bola Remolque</td><td> <input type=checkbox name='br' value='1'></td>
	</tr>
	<tr>
		<td>Tunning</td><td> <input type=checkbox name='tu' value='1'></td>
	</tr>

	<tr>
	<td>Observaciones:</td><td> <textarea cols='40' rows='3' name='observaciones'></textarea></td>
	</tr>

	<tr>
		<td align=right><input type='submit' value='Enviar' name='intasasubmit'></td>
	</tr>
	
</table>