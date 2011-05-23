<?php
/*
 * Created on 02/05/2011
 *
 * Automotis DMS  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */

include("mpdf/mpdf.php");
 
include("config.php");
include("lang/$lang.php");
include("include/gen_functions.php");
 
$mpdf=new mPDF('win-1252','A4','','',20,15,20,20,10,10);
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetProtection(array('print'));
$mpdf->SetTitle("Informacion Comercial");
$mpdf->SetAuthor("Ibericar Motors");
//$mpdf->SetWatermarkText("Oferta");
//$mpdf->showWatermarkText = true;
//$mpdf->watermark_font = 'DejaVuSansCondensed';
//$mpdf->watermarkTextAlpha = 0.1;
$mpdf->SetDisplayMode('fullpage');
 
$idvehiculo = $_GET['id'];

$sql = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
$arrayvehiculo = mysql_fetch_array($sql);

$pvppercha = $arrayvehiculo['pvp']*1.03;
$pvppercha = (floor($pvppercha/100)*100)-1;

$fechamatric = fecha_normal($arrayvehiculo['fechamatric']);
 
//width: 95px; height: 72px;
$html = "

<table style='text-align: left; width: 100%;' border='0' cellpadding='2' cellspacing='2'>
	<tr>
		<td rowspan='3' style='vertical-align: middle; text-align: center; width: 250px;'><img style='' alt='' src='./imagenes/podl.png'></td>
		<td style='vertical-align: top text-align: center; text-align: center; width: 400px; background-color: silver;'><h4>1.Certificacion del Estado del Vehiculo</h4></td>
	</tr>
	<tr align='center'>
		<td style='vertical-align: top; text-align: center;'><h4>2. Revision de los 99 Puntos de Control</h4></td>
	</tr>
	<tr align='center'>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h4>3. Garantia Peugeot de hasta 24 Meses</h4></td>
	</tr>
	<tr>
		<td rowspan='3' style='vertical-align: middle; text-align: center; width: 250px;'><img style='' alt='' src='./imagenes/logo_ibericar.jpg'></td>
		<td style='vertical-align: top; text-align: center;'><h4>4. Servicio de Vehiculos de Sustitucion Opcional</h4></td>
	</tr>
	<tr align='center'>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h4>5. Primera Revision Gratuita a los 2.500KM</h4></td>
	</tr>
	<tr align='center'>
		<td style='vertical-align: top; text-align: center;'><h4>6. Precio 'Llave en Mano'</h4></td>
	</tr>
	<tr>
		<td rowspan='2' style='vertical-align: top; text-align: center;'><br></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h4>7. Oferta de Financiacion Especial Peugeot</h4></td>
	</tr>
	<tr align='center'>
		<td style='vertical-align: top; text-align: center;'><h4>8. El respaldo de una gran Marca: PEUGEOT</h4></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>			
	<tr align='center'>
		<td rowspan='1' style='vertical-align: top; text-align: center; background-color: silver;'><h2>Marca</h2></td>
		<td rowspan='1' style='vertical-align: top; text-align: center; background-color: silver;'><h2>".$arrayvehiculo['marca']."</h2></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr align='center'>
		<td rowspan='1' style='vertical-align: top; text-align: center; background-color: silver;'><h2>Modelo</h2></td>
		<td rowspan='1' style='vertical-align: top; text-align: center; background-color: silver;'><h2>".$arrayvehiculo['modelo']."</h2></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Matricula</h3></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Propietario</h3></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['matricula']."</h3></td>
		<td style='vertical-align: top; text-align: center;'><h3>Ibericar Motors</h3></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Fecha Matriculacion</h3></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Kilometraje</h3></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center;'><h3>".$fechamatric."</h3></td>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['kilometros']."</h3></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Servicio Anterior</h3></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Garantia</h3></td>
		
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['usoanterior']."</h3></td>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['garantia']."</h3></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Combustible</h3></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Potencia</h3></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['combustible']."</h3></td>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['potencia']." CV</h3></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>Cilindrada</h3></td>
		<td style='vertical-align: top; text-align: center; background-color: silver;'><h3>PVP</h3></td>
	</tr>
	<tr>
		<td style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['cilindrada']." mil cc</h3></td>
		<td style='vertical-align: top; text-align: center;'><h3>".floor($pvppercha)." EUR</h3></td>
	</tr>
	<tr align='center'>
		<td colspan='2'><br></td>
	</tr>
	<tr align='center'>
		<td colspan='2' rowspan='1' style='vertical-align: top; text-align: center; background-color: silver;'><h3>Extras</h3></td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1' style='vertical-align: top; text-align: center;'><h3>".$arrayvehiculo['extras']."</h3></td>
	</tr>

</table>
		
	";


		
$mpdf->WriteHTML($html);

$mpdf->Output(); exit;

exit;
 
?>
