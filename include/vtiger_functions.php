<?php
/*
 * Created on 01/11/2011
 *
 * Automotis DMS  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 // Compatibilidad con VTIGER

include_once('vtwsclib/Vtiger/WSClient.php');
session_start();

$url = 'http://automotis.com/crm';
$client = new Vtiger_WSClient($url);

$vtigeruser = $_SESSION['userid'];

$sql = mysql_query("SELECT vtigerpassword FROM usuarios WHERE userid = '7'");
$arrayusuario = mysql_fetch_array($sql);

$vtigerdefaultpass = $arrayusuario['vtigerpassword'];

$vtigeruser = $_SESSION['nombre'];
$vtigerpassword = $_SESSION['vtigerpassword'];

if($vtigerdefaultpass == $vtigerpassword)
	$vtigeruser = 'mcamargo';

$login = $client->doLogin($vtigeruser, $vtigerpassword);
if(!$login)
	echo 'Acceso a Vtiger Fallo';
 
?>
