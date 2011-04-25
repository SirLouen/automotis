<?php
/*
 * Created on 23/04/2011
 *
 * webdms  Copyright (C) <2011>  mcamargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 include_once("config.php");
 include_once("lang/$lang.php");
 
 session_start();
 
?>



<form method='post' action='consultatasacion.php'>

<table border="0" align="center">

	<tr>
	<td colspan="2" align="center"> <? echo ("$lang_index_tasacion"); ?> </td>
	</tr>
	<tr>
	<td>Matricula:</td><td><input type='text' id='matricula' name='matricula' size=30></td>	
	</tr>
	<tr>
		<td colspan="2" align="center"><input type=submit name='tasacionsubmit' value="Buscar"></td> 
	</tr>

	</table>
</form>

<?

if (isset ($_POST['tasacionsubmit'])) 
{

	$matricula = $_POST['matricula'];
	$userid = $_SESSION['userid'];
	$fechadefecto = '0000-00-00 00:00:00';
	$mostrardatos2 = false;
	$mostrardatos3 = false;
	$nuevainsercion = $_POST['nuevainsercion'];
	
	$sql = mysql_query("SELECT * FROM tasaciones WHERE matricula = '$matricula'");
	
	$filas = mysql_num_rows($sql);
	
	// Si no existe la tasacion, formulario de introduccion de nueva tasacion
	if ( $filas == 0 || $nuevainsercion == 1)
	{
		include("introducirtasacion.php");
	}
	
	// Si existe la tasacion, mostramos las tasaciones existentes
	else 
	{
		echo ("
	
		<body>
	
		<table align='center' width='55%' height='20%' border='1'>");
	
		// Metemos el resultado completo primero en un vector para poder mostrarlo en forma de matriz.
		
		for($i=0; $i<$filas; $i++) 
		{
	 		$array2d[$i] = mysql_fetch_array($sql);
		}
	
		echo "<table border='1'>";
	
			echo "<tr><td>Matricula</td>";
			echo "<td>".$matricula."</td>";
			echo "</tr>";
			
			echo "<tr><td>Marca</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['marca'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Modelo</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['modelo'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Registro</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['registro'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Nombre Cliente</td>";
			for($i=0; $i<$filas; $i++) 
			{
				$cliente = $array2d[$i]['cliente'];
				$rowcliente = mysql_fetch_row(mysql_query("SELECT nombre FROM clientes WHERE id = '$cliente'")); 
				echo "<td>". $rowcliente[0] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Localidad</td>";
			for($i=0; $i<$filas; $i++) 
			{
				$cliente = $array2d[$i]['cliente'];
				$rowcliente = mysql_fetch_row(mysql_query("SELECT cp FROM clientes WHERE id = '$cliente'")); 
				$cp = $rowcliente[0];
				$rowcp = mysql_fetch_row(mysql_query("SELECT poblacion FROM codigospostales WHERE cp = '$cp'")); 
				echo "<td>". $rowcp[0] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Fecha Matric</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['fechamatric'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Kilometros</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['kilometros'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Color</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['color'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>V. Mercado VO</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['valormercado'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Reacond.</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['totalreacondicionamiento'] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Oferta Compra 1</td>";
			for($i=0; $i<$filas; $i++) 
			{
				$totaltasacion1 = $array2d[$i]['esfuerzocomercial1'] - $array2d[$i]['totalreacondicionamiento'] + 
								  $array2d[$i]['valormercado'];
				echo "<td>". $totaltasacion1 ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Fecha 1</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td>". $array2d[$i]['fecha1'] ."</td>";
			}
			echo "</tr>";
			
			// Comprobamos si existe una segunda Oferta para una tasacion en concreto
			// Y en caso que exista, ampliamos una fila mas a la tabla
			for($i=0; $i<$filas; $i++) 
			{
				if ($array2d[$i]['esfuerzocomercial2']) 
				{
					$mostrardatos2 = true ;
				}
			}
			
			if ($mostrardatos2) 
			{
			
				echo "<tr><td>Oferta Compra 2</td>";
				for($i=0; $i<$filas; $i++) 
				{
					if ($array2d[$i]['esfuerzocomercial2'])
					{
						$totaltasacion2 = $array2d[$i]['esfuerzocomercial2'] - $array2d[$i]['totalreacondicionamiento'] + 
									  $array2d[$i]['valormercado'];
						echo "<td>". $totaltasacion2 ."</td>"; 
					}
					else 
					{
						echo "<td></td>"; 
					}
				}
				echo "</tr>";
				
				echo "<tr><td>Fecha 2</td>";
				for($i=0; $i<$filas; $i++) 
				{
					if ($array2d[$i]['fecha2'] != $fechadefecto)
					{
						echo "<td>". $array2d[$i]['fecha2'] ."</td>"; 
					}
					else 
					{
						echo "<td></td>"; 
					}
				}
					echo "</tr>";
			}
			
			// Comprobamos si existe una tercera Oferta para una tasacion en concreto
			// Y en caso que exista, ampliamos una fila mas a la tabla
			
			for($i=0; $i<$filas; $i++) 
			{
				if ($array2d[$i]['esfuerzocomercial3']) 
				{
					$mostrardatos3 = true;
				}
			}
			
			if ($mostrardatos3 && $mostrardatos2) 
			{
				echo "<tr><td>Oferta Compra 3</td>";
				for($i=0; $i<$filas; $i++) 
				{
					if ($array2d[$i]['esfuerzocomercial3'])
					{
						$totaltasacion3 = $array2d[$i]['esfuerzocomercial3'] - $array2d[$i]['totalreacondicionamiento'] + 
									  $array2d[$i]['valormercado'];
						echo "<td>". $totaltasacion3 ."</td>"; 
					}
					else 
					{
						echo "<td></td>"; 
					}
				}
				echo "</tr>";
				
				echo "<tr><td>Fecha 3</td>";
				for($i=0; $i<$filas; $i++) 
				{
					if ( $array2d[$i]['fecha3'] != $fechadefecto )
					{
						echo "<td>". $array2d[$i]['fecha3'] ."</td>"; 
					}
					else 
					{
						echo "<td></td>"; 
					}
				}
				echo "</tr>";
			}
			
			echo "<tr><td>Tasador</td>";
			for($i=0; $i<$filas; $i++) 
			{
				$rowusuario = mysql_fetch_row(mysql_query("SELECT nombre FROM usuarios WHERE userid = '$userid'")); 
				echo "<td>". $rowusuario[0] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr><td>Concesionario</td>";
			for($i=0; $i<$filas; $i++) 
			{
				$rowusuario = mysql_fetch_row(mysql_query("SELECT concesionario FROM usuarios WHERE userid = '$userid'"));
				echo "<td>". $rowusuario[0] ."</td>";
			}
			echo "</tr>";
			
			echo "<tr ><td rowpan='3' width='180'>Observaciones</td>";
			for($i=0; $i<$filas; $i++) {
			echo "<td rowpan='3' width='180'>". $array2d[$i]['observaciones'] ."</td>";
			}
			echo "</tr>";
			
			// Comprobamos si el usuario es dueño de la tasacion
			// En caso que si, permitimos modificar
			// En caso que no, permitimos crear nueva tasacion
			
			$puedemodificar=false;
			
			echo "<tr>";
			echo "<td>Modificar Tasacion</td>";
			for($i=0; $i<$filas; $i++) 
			{
				if ($array2d[$i]['usuario'] == $userid ) 
				{
					echo "<td><form method='post' action='consultatasacion.php'><input type='hidden' name='matricula' value=".$matricula.">";
					echo "<input type='hidden' name='idtasacion' value=".$array2d[$i]['id'].">";	
					echo "<input type=submit name='tasacionmodificar' value='Modificar'></td>";
					$puedemodificar=true;
				} 
				else 
				{
					echo "<td>-</td>"; 
				}
			}
			echo "</form>";
			echo "</tr>";
			
			if (!$puedemodificar) 
			{
				
				echo "<tr>";
				echo "<td>Nueva Tasacion</td>";
				echo "<td><form method='post' action='consultatasacion.php''><input type='hidden' name='matricula' value='".$matricula."'>
					 <input type='hidden' name='nuevainsercion' value='1'>
					 <input type=submit name='tasacionsubmit' value='Insertar'></td>"; 
				echo "</form>";
				echo "</tr>";
			}
	
		echo "</table>";
	
	}
	include("footer.php");

}

elseif (isset ($_POST['intasasubmit'])) 
{
	$matricula = $_POST['matricula'];
	$registro = $_POST['registro'];
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$fechamatric = $_POST['anyo']."-".$_POST['mes']."-".$_POST['dia'];
	$kilometros = $_POST['kilometros'];
	$color = $_POST['color'];
	$combustible = $_POST['combustible'];
	$potencia = $_POST['potencia'];
	$cilindrada = $_POST['cilindrada'];
	$carroceria = $_POST['carroceria'];
	$plazas = $_POST['plazas'];
	$usoanterior = $_POST['usoanterior'];
	$valormercado = $_POST['valormercado'];
	$esfuerzocomercial = $_POST['esfuerzocomercial'];
	$estimacionpvp = $_POST['estimacionpvp'];
	$observaciones = $_POST['observaciones'];
	$usuariotasacion = $_SESSION['userid'];
	$pvpestimado = $_POST['pvpestimado'];
	
	$nombre_cliente = $_POST['nombre_cliente'];
	$cp_cliente = $_POST['cp_cliente'];
	$movil_cliente = $_POST['movil_cliente'];
	$fijo_cliente = $_POST['fijo_cliente'];
	$email_cliente = $_POST['email_cliente'];
	
	$tipoinsercion = $_POST['tipoinsercion'];
		
	if ( $_POST['abs'] == "1" ){ $abs = 1; } else { $abs = 0; }
	if ( $_POST['esp'] == "1" ){ $esp = 1; } else { $esp = 0; }
	if ( $_POST['ct'] == "1" ){ $ct = 1; } else { $ct = 0; }
	if ( $_POST['fourwd'] == "1" ){ $fourwd = 1; } else { $fourwd = 0; }
	if ( $_POST['ac'] == "1" ){ $ac = 1; } else { $ac = 0; }
	if ( $_POST['ap'] == "1" ){ $ap = 1; } else { $ap = 0; }
	if ( $_POST['al'] == "1" ){ $al = 1; } else { $al = 0; }
	if ( $_POST['ala'] == "1" ){ $ala = 1; } else { $ala = 0; }
	if ( $_POST['an'] == "1" ){ $an = 1; } else { $an = 0; }
	if ( $_POST['inm'] == "1" ){ $inm = 1; } else { $inm = 0; }
	if ( $_POST['cc'] == "1" ){ $cc = 1; } else { $cc = 0; }
	if ( $_POST['aa'] == "1" ){ $aa = 1; } else { $aa = 0; }
	if ( $_POST['cl'] == "1" ){ $cl = 1; } else { $cl = 0; }
	if ( $_POST['ts'] == "1" ){ $ts = 1; } else { $ts = 0; }
	if ( $_POST['da'] == "1" ){ $da = 1; } else { $da = 0; }
	if ( $_POST['ee'] == "1" ){ $ee = 1; } else { $ee = 0; }
	if ( $_POST['ae'] == "1" ){ $ae = 1; } else { $ae = 0; }
	if ( $_POST['cu'] == "1" ){ $cu = 1; } else { $cu = 0; }
	if ( $_POST['aca'] == "1" ){ $aca = 1; } else { $aca = 0; }
	if ( $_POST['cv'] == "1" ){ $cv = 1; } else { $cv = 0; }
	if ( $_POST['fx'] == "1" ){ $fx = 1; } else { $fx = 0; }
	if ( $_POST['apk'] == "1" ){ $apk = 1; } else { $apk = 0; }
	if ( $_POST['rcd'] == "1" ){ $rcd = 1; } else { $rcd = 0; }
	if ( $_POST['gps'] == "1" ){ $gps = 1; } else { $gps = 0; }
	if ( $_POST['ba'] == "1" ){ $ba = 1; } else { $ba = 0; }
	if ( $_POST['br'] == "1" ){ $br = 1; } else { $br = 0; }
	if ( $_POST['ll'] == "1" ){ $ll = 1; } else { $ll = 0; }
	if ( $_POST['tu'] == "1" ){ $tu = 1; } else { $tu = 0; }
	if ( $_POST['pm'] == "1" ){ $pm = 1; } else { $pm = 0; }
	
	$idtasacion = $_POST['idtasacion'];
	$esfuerzoactual = $_POST['esfuerzoactual'];
	
	$sql = mysql_query("SELECT * FROM clientes
		 WHERE (movil = '$movil_cliente') OR ((fijo = '$fijo_cliente') AND (nombre LIKE '%$nombre_cliente%')) OR
		 	   (email = '$email_cliente')");
		 	   
	$login_check = mysql_num_rows($sql);
	
	if($login_check == 0)
	{
		$sql = "INSERT INTO clientes (nombre, cp, movil, fijo, email, usuario, fechacreacion)
 			VALUES ('$nombre_cliente', '$cp_cliente', '$movil_cliente', '$fijo_cliente', '$email_cliente', 
 					'$usuariotasacion', now())";
 		if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}				
		
		$sql = mysql_query("SELECT id FROM clientes
			   WHERE (movil = '$movil_cliente') OR ((fijo = '$fijo_cliente') AND (nombre LIKE '%$nombre_cliente%')) OR
		 	   (email = '$email_cliente')");
 	
 		$rowcliente = mysql_fetch_row($sql);
 		$cliente = $rowcliente[0];
		
	}
	else
	{
		$existe = 0;
		$array = mysql_fetch_array($sql);
		$cliente = $array['id'];
		if ($array['nombre']){ $nombre_cliente =  $array['nombre']; $existe++; }
		if ($array['movil']){ $movil_cliente =  $array['movil']; $existe++; }
		if ($array['fijo']){ $fijo_cliente =  $array['fijo']; $existe++; }
		if ($array['email']){ $email_cliente =  $array['email']; $existe++; }
		if ($array['cp']){ $cp_cliente =  $array['cp']; $existe++; }
		
		if($existe != 5)
		{
		$sql2 = "UPDATE clientes
		    	SET nombre = '$nombre_cliente', movil = '$movil_cliente', fijo = '$fijo_cliente',
		    	email = '$email_cliente', cp = '$cp_cliente', fechacreacion = now(), usuario = '$usuariotasacion' 
		    	WHERE (id = '$cliente')";	
		    	if (!(mysql_query($sql2,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		}
	}
	
	// Inicializamos a 0 los indices para insertar en la tabla de reacondicionamientos
	
	$numreacons = 0; 	
	$sumreacon = 0;
	
	// En caso que vengamos de una modificacion, el tipo de insercion tiene que ser 1,2 o 3, asi que vamos a hacer una actualizacion
	// de los registros de la tasacion
	
	
	if ($tipoinsercion != 0)
	{
	
		if (($tipoinsercion == '3') or (($tipoinsercion == '2') and ($esfuerzoactual != $esfuerzocomercial)))
		{
			$nombreesfuerzocomercial = 'esfuerzocomercial3';
			$nombrefecha = 'fecha3';
		}
		elseif (($tipoinsercion == '2') or (($tipoinsercion == '1') and ($esfuerzoactual != $esfuerzocomercial)))
		{
			$nombreesfuerzocomercial = 'esfuerzocomercial2';
			$nombrefecha = 'fecha2';
		}
		else
		{
			$nombreesfuerzocomercial = 'esfuerzocomercial1';
			$nombrefecha = 'fecha1';
		}
	
	
		
		$sql = "UPDATE tasaciones
				SET marca = '$marca', modelo = '$modelo', matricula = '$matricula', cliente = '$cliente', fechamatric = '$fechamatric',
				kilometros = '$kilometros', color = '$color', combustible = '$combustible',	potencia = '$potencia',
				cilindrada = '$cilindrada', carroceria = '$carroceria', plazas = '$plazas', usoanterior = '$usoanterior',
				valormercado = '$valormercado',".$nombreesfuerzocomercial." = '$esfuerzocomercial',
				usuario = '$usuariotasacion', ".$nombrefecha." = now(), observaciones = '$observaciones', pvpestimado = $pvpestimado,
				registro = '$registro', abs = '$abs', esp = '$esp', ct = $ct, fourwd = '$fourwd', ac = '$ac', ap = '$ap', al = '$al',
				ala = '$ala', an = '$an', inm = '$inm', cc = '$cc', aa = '$aa', cl = '$cl', ts = '$ts', da = '$da',	ee = '$ee',
				ae = '$ae', cu = '$cu', aca = '$aca', cv = '$cv', fx = '$fx', apk = '$apk', rcd = '$rcd', gps = '$gps', ba = '$ba',
				br = '$br', ll = '$ll', tu = '$tu', pm = '$pm'
		    	WHERE (id = '$idtasacion')";	
		    	if (!(mysql_query($sql,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		    	
		  $tasacion = $idtasacion;
	} 	
	
	// En caso que vengamos de una insercion el tipo de insercion sera igual a 0 asi que hacemos una insercion en la tasacion

	
 	else
	{	
		$sql = "INSERT INTO tasaciones (marca, modelo, matricula, cliente, fechamatric, kilometros, color, combustible,
				potencia, cilindrada, carroceria, plazas, usoanterior, valormercado, esfuerzocomercial1, usuario, fecha1,
				observaciones, pvpestimado, registro, abs, esp, ct, fourwd, ac, ap, al, ala, an, inm, cc, aa, cl, ts, da,
				ee, ae, cu, aca, cv, fx, apk, rcd, gps, ba, br, ll, tu, pm)
	 			VALUES ('$marca', '$modelo', '$matricula', '$cliente', '$fechamatric', '$kilometros', '$color',
	 			'$combustible',	'$potencia', '$cilindrada', '$carroceria', '$plazas', '$usoanterior', '$valormercado',
	 			'$esfuerzocomercial', '$usuariotasacion', now(), '$observaciones', '$pvpestimado', '$registro', '$abs',
	 			'$esp', '$ct', '$fourwd', '$ac', '$ap', '$al', '$ala', '$an', $inm, '$cc', '$aa', '$cl', '$ts', '$da', '$ee', '$ae',
	 			'$cu', '$aca', '$cv', '$fx', '$apk', '$rcd', '$gps', '$ba', '$br', '$ll', '$tu', '$pm')";
	 	
	 	if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
		
		// Sacamos el numero de la tasacion tras insertarla
		
		$sql = mysql_query("SELECT id FROM tasaciones WHERE (matricula = '$matricula') AND (kilometros = '$kilometros')");
		$rowtasacion = mysql_fetch_row($sql);
	 	$tasacion = $rowtasacion[0];		
	}
	
	// Tenemos que actualizar la tabla de reacondicionamientos, insertando si no existe o actualizando.
	
	$sqlreacon = mysql_query("SELECT * FROM reacondicionamientos WHERE tasacion = '$tasacion'");
 	$lineas = mysql_num_rows($sqlreacon);
 			
 	for ($i=0;$i < $lineas; $i++)	
 	{
 		$arrayreacontemp = mysql_fetch_array($sqlreacon);
 		$idreacon =  $arrayreacontemp['id'];
 		$arrayreacon[$idreacon] = $arrayreacontemp;
 	}
	
	while($_POST['desreacon'][$numreacons] && $numreacons < 10)
	{
		$desreacon = $_POST['desreacon'][$numreacons];
		$valorreacon = $_POST['valorreacon'][$numreacons];
		$sumreacon = $sumreacon + $valorreacon;
			
		if ($arrayreacon[$numreacons]['descripcion'])
		{
			$sql = "UPDATE reacondicionamientos SET usuario = '$usuariotasacion',
				   descripcion = '$desreacon', valor = '$valorreacon'
				   WHERE (id = '$numreacons' AND tasacion = '$tasacion')";
		}
		else
		{
			$sql = "INSERT INTO reacondicionamientos (id, tasacion, usuario, descripcion, valor)
				VALUES ('$numreacons', '$tasacion', '$usuariotasacion', '$desreacon', '$valorreacon')";
		}
			
		if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
			
	 	$numreacons++;
	 }
	
	
	// Finalmente actualizamos el total que cuestan los reacondicionamientos en la tabla principal
 	 	
	$sql = "UPDATE tasaciones SET totalreacondicionamiento = '$sumreacon' WHERE (id = '$tasacion')";	
	if (!(mysql_query($sql,$conexion)))
	{
		die('Error: '.mysql_error());
	}

	include("footer.php");


}

elseif (isset ($_POST['tasacionmodificar'])) 
{
	include("modificartasacion.php");
	include("footer.php");
	
}



?> 