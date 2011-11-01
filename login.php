<?php
/*
 * Created on 18/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
if (isset ($_POST['submit'])) 
{

	$user = $_POST['user'];
	$password = $_POST['password'];

	if((!$user) || (!$password))
	{
		echo "$lang_login_missing <br />";
		exit();
	}

	// Convierte una contraseña a Hash MD5
	$password = md5($password);
	
	
	$sql = mysql_query("SELECT * FROM usuarios WHERE usuario='$user' AND password='$password'");
		
	$login_check = mysql_num_rows($sql);
	
	if($login_check > 0)
	{
		while($row = mysql_fetch_array($sql))
		{
			foreach( $row AS $key => $val )
			{
				$$key = stripslashes( $val );
			}	
			// Registramos Varibles de Sesion
			session_register('userid');
			$_SESSION['userid'] = $userid;
			session_register('nombre');
			$_SESSION['nombre'] = $nombre;
			session_register('nombreusuario');
			$_SESSION['nombreusuario'] = $usuario;
			session_register('nivelusuario');
			$_SESSION['nivelusuario'] = $nivelusuario;
			session_register('concesionario');
			$_SESSION['concesionario'] = $concesionario;
			session_register('email');
			$_SESSION['email'] = $email;
			session_register('telefono');
			$_SESSION['telefono'] = $telefono;
			session_register('vtigerpassword');
			$_SESSION['vtigerpassword'] = $vtigerpassword;
		
			mysql_query("UPDATE usuarios SET `last_login`=now() WHERE `userid`='$userid'");
			header("Location: index.php");		
		}
	} 
	else 
	{
		echo "$lang_login_fail<br />";
	}
}

include("topheader.php");

?>


<center><? echo $lang_index_ident; ?></center><br>

<form method='post' action='?'>
  <table width="25%" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr> 
      <td width="22%">Usuario</td>
      <td width="78%"><input name="user" type="text" id="usuario"></td>
    </tr>
    <tr> 
      <td>Contrase&ntilde;a</td>
      <td><input name="password" type="password" id="password"></td>
    </tr>
    <tr> 
      <td>&nbsp;</td>
      <td><input type="submit" name="submit" value="OK"></td>
    </tr>
  </table>
</form>