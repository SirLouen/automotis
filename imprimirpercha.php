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
 
$tipofirma[0] = "Copia para el Cliente";
$tipofirma[1] = "Copia para el Concesionario";

for($i=0;$i<2;$i++)
{
$html[$i] = "


		
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
