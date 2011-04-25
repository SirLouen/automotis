<?php
/*
 * Created on 22/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
include("mpdf/mpdf.php");
 
include("config.php");
include("lang/$lang.php");
include("include/gen_functions.php");
 
$idoferta = $_GET['oferta'];
$sql = mysql_query("SELECT * FROM ofertas WHERE id = $idoferta");
$arrayoferta = mysql_fetch_array($sql);

$usuario = $arrayoferta['usuario'];
$sql2 = mysql_query("SELECT * FROM usuarios WHERE userid = $usuario");
$arrayusuario = mysql_fetch_array($sql2);

$concesionario = $arrayusuario['concesionario'];
$sql21 = mysql_query("SELECT * FROM concesionarios WHERE id = $concesionario");
$arrayconcesionario = mysql_fetch_array($sql21);

$cpconcesionario = $arrayconcesionario['cp'];
$sql22 = mysql_query("SELECT poblacion FROM codigospostales WHERE cp = $cpconcesionario");
$poblacionconcesionario = mysql_fetch_row($sql22);

$tasacion = $arrayoferta['tasacion'];
$sql3 = mysql_query("SELECT * FROM tasaciones WHERE id = $tasacion");
$arraytasacion = mysql_fetch_array($sql3);

$vehiculo = $arrayoferta['vehiculo'];
$sql4 = mysql_query("SELECT * FROM vehiculos WHERE id = $vehiculo");
$arrayvehiculo = mysql_fetch_array($sql4);

$cliente = $arrayoferta['cliente'];
$sql5 = mysql_query("SELECT * FROM clientes WHERE id = $cliente");
$arraycliente = mysql_fetch_array($sql5);

$cpcliente = $arraycliente['cp'];
$sql51 = mysql_query("SELECT poblacion FROM codigospostales WHERE cp = $cpcliente");
$poblacioncliente = mysql_fetch_row($sql51);

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

if ($arraytasacion['esfuerzocomercial3'])
	$esfuerzocomercial = $arraytasacion['esfuerzocomercial3'];
elseif ($arraytasacion['esfuerzocomercial2'])
	$esfuerzocomercial = $arraytasacion['esfuerzocomercial2'];
else
	$esfuerzocomercial = $arraytasacion['esfuerzocomercial1'];

$totaldescuentos = $arrayoferta['descuento'] + $esfuerzocomercial - $arraytasacion['totalreacondicionamiento'] + $arraytasacion['valormercado'];
$preciofinal = $arrayvehiculo['pvp'] - $totaldescuentos;

$fechacreacion = $arrayoferta['fechacreacion'];
$fechavehiculo = $arrayvehiculo['fechamatric'];

$fechaoferta = convertir_fecha_normal($fechacreacion);
$fechamatric = fecha_normal($fechavehiculo);

$condicioneslegales = "AUTORIZACIÓN DEL CLIENTE: Al firmar este documento, el cliente autoriza expresamente la incorporación
de sus datos personales a los ficheros informatizados de ".$arrayconcesionario['nombre']." para su tratamiento y uso con finalidad
comercial, incluido el envio de comunicaciones comerciales por parte de ".$arrayconcesionario['nombre']." a traves de cualquiera de
los medios, pudiendo revocar su autorización con solo remitir un escrito a la direccion del Concesionario ".$arrayconcesionario['nombre']."
mostrada en esta oferta; asimismo, autoriza la cesión (revocable) de dichos datos a las empresas, filiales y Red Comercial
del GRUPO IBERICAR, para que sean usados en sus ficheros automatizados con la msima finalidad. igualmente podrá ejercitar
en cualquier momento su derecho de acceso a los citados ficheros a los efectos de rectificación, cancelación u oposición
a sus datos, con solo ponerlo en conocimiento de ".$arrayconcesionario['nombre'].".";

$tipofirma[0] = "Copia para el Cliente";
$tipofirma[1] = "Copia para el Concesionario";

for($i=0;$i<2;$i++)
{
	
$html[$i] = "

<table style='text-align: left; width: 100%;' border='0' cellpadding='2' cellspacing='2'>
	<tr>
		<td colspan='2' rowspan='4'><img style='width: 100px; height: 72px;' alt='' src='./imagenes/logo_peugeot.png'>
		<img style='width: 215px; height: 72px;' alt='' src='./imagenes/logo_ibericar.jpg'></td>
		<td colspan='2' rowspan='1' style=' background-color: silver;'>".$arrayconcesionario['nombre']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1' style=' background-color: silver;'>".$arrayconcesionario['direccion']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1' style=' background-color: silver;'>".$poblacionconcesionario[0]." - ".$arrayconcesionario['cp']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1' style=' background-color: silver;'>".$arrayconcesionario['telefono']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='6'><img style='width: 192px; height: 144px;' alt='' src='./imagenes/".$arrayvehiculo['matricula']."/tn/1.jpg'></td>
		<td colspan='2' rowspan='1'>Nombre Vendedor: ".$arrayusuario['nombre']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1'>Telefono Vendedor: ".$arrayusuario['telefono']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1'>E-Mail Vendedor: ".$arrayusuario['email']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1' style=' background-color: silver;'><b>INFORMACION COMERCIAL</b></td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1'>Numero Oferta: ".$arrayoferta['id']."</td>
	</tr>
	<tr>
		<td colspan='2' rowspan='1'>Fecha Oferta: ".$fechaoferta."</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1'>Datos del Solicitante</th>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Nombre: ".$arraycliente['nombre']."</td>
		<td colspan='2' rowspan='1'>Apellidos: ".$arraycliente['apellidos']."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Movil: ".$arraycliente['movil']."</td>
		<td colspan='2' rowspan='1'>Localidad: ".$poblacioncliente[0]."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Telefono: ".$arraycliente['fijo']."</td>
		<td colspan='2' rowspan='1'>E-Mail: ".$arraycliente['email']."</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1'>Datos del Vehiculo Ofertado</th>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Marca: ".$arrayvehiculo['marca']."<br></td>
		<td colspan='2' rowspan='1'>Modelo: ".$arrayvehiculo['modelo']."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Fecha Matricula: ".$fechamatric."</td>
		<td colspan='2' rowspan='1'>Kilometros: ".$arrayvehiculo['kilometros']."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Potencia: ".$arrayvehiculo['potencia']." CV</td>
		<td colspan='2' rowspan='1'>Cilindrada: ".$arrayvehiculo['cilindrada']."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Carroceria: ".$arrayvehiculo['carroceria']."</td>
		<td colspan='2' rowspan='1'>Combustible: ".$arrayvehiculo['combustible']."</td>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='2' rowspan='1'>Color: ".$arrayvehiculo['color']."</td>
		<td colspan='2' rowspan='1'>Garantia: ".$arrayvehiculo['garantia']."</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1'>Opciones Incluidas:<br></th>
	</tr>
	<tr style=' background-color: silver;'>
		<td colspan='4' rowspan='1'>".$arrayvehiculo['extras']."</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1'>Financiacion<br></th>
	</tr>
	<tr style=' background-color: silver;'>
		<th>Entrada:</th>
		<th>Meses:</th>
		<th>Importe:</th>
		<td><br></td>
	</tr>
	<tr style=' background-color: silver;'>
		<td>".$arrayoferta['entrada1']." €</td>
		<td>".$arrayoferta['meses1']." </td>
		<td>".$arrayoferta['importe1']." €</td>
		<td><br></td>
	</tr>
	<tr style=' background-color: silver;'>
		<td>".$arrayoferta['entrada2']." €</td>
		<td>".$arrayoferta['meses2']."</td>
		<td>".$arrayoferta['importe2']." €</td>
		<th>Precio Vehiculo: ".$arrayvehiculo['pvp']." €</th>
	</tr>
	<tr style=' background-color: silver;'>
		<td>".$arrayoferta['entrada3']." €</td>
		<td>".$arrayoferta['meses3']." </td>
		<td>".$arrayoferta['importe3']." €</td>
		<th>Total Descuentos ".$totaldescuentos." €</th>
	</tr>
	<tr>
		<td colspan='4' rowspan='1'><br></td>
	</tr>
	<tr style=' background-color: silver;'>
		<th colspan='2' rowspan='1' >PRECIO FINAL</th>
		<th>".$preciofinal." Euros</th>
		<td>Impuestos y gastos de transferencia no incluidos</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1'>Oferta solo valida hasta final de mes</th>
	</tr>
	<tr>
		<td colspan='4' rowspan='1'><br></td>
	</tr>
	<tr style=' background-color: silver;'>
		<th colspan='2' rowspan='1'>Firma Cliente:</th>
		<th colspan='2' rowspan='1'>Firma Vendedor:</th>
	</tr>
	<tr>
		<td colspan='2' rowspan='1'><br><br><br><br><br><br><br><br></td>
		<td colspan='2' rowspan='1'><br><br><br><br><br><br><br><br></td>
	</tr>
	<tr>
		<td colspan='4' rowspan='1' style='font-size:8px;'>".$condicioneslegales."</td>
	</tr>
	<tr>
		<th colspan='4' rowspan='1' style=' text-align: right;'><br>".$tipofirma[$i]."</th>
	</tr>

</table>

";

}

$concatenahtml = "
<html>
<head>
	<style type='text/css'>
	body
	{
		font-size:12px;
	}
	td
	{
		vertical-align: middle;
		text-align: center;
	}
	</style>
	</head>
	<body>
	".$html[0].$html[1]."
</body>
</html>
		";

$mpdf->WriteHTML($concatenahtml);

$mpdf->Output(); exit;

exit;
 
?>
