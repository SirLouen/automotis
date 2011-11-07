<?php
/*
 * Created on 18/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
 
 include("config.php");
 include("lang/$lang.php");
 include_once("include/gen_functions.php");
 include_once("include/vtiger_functions.php");
 
 session_start();
 
 if ($_SESSION['nivelusuario'] >= 1)
 {
 
 $maxofertas = 10;
 
 
 if (isset ($_POST['ofertasubmit']))
 {
	include("introduciroferta.php"); 	
	include("footer.php");
 } 
 elseif (isset ($_POST['inofertasubmit']))
 {
 	
 	$usuariooferta = $_SESSION['userid'];
 	
 	$nombre_cliente = $_POST['nombre_cliente'];
 	$apellidos_cliente = $_POST['apellidos_cliente'];
 	$cp_cliente = $_POST['cp_cliente'];
 	$movil_cliente = $_POST['movil_cliente'];
 	$fijo_cliente = $_POST['fijo_cliente'];
 	$email_cliente = $_POST['email_cliente'];
 	$codigocortesia_cliente = $_POST['codigocortesia_cliente'];
 	$tipo_cliente = $_POST['tipo_cliente'];
 	
 	// Insertamos primero el cliente en la tabla de clientes si no existe, sino actualizamos
 	// Vamos a buscar a ver si el ciente ya existe y se le dio una oferta antes 
 	
 	$consulta = "SELECT * FROM clientes WHERE ";
 	
 	if ($email_cliente != "")
 		$consulta = $consulta."email = '$email_cliente'";
 	elseif ($movil_cliente != "")
 		$consulta = $consulta."movil = '$movil_cliente'";
 	elseif ($nombre_cliente != "" && $fijo_cliente != "")
 		$consulta = $consulta."nombre LIKE '$nombre_cliente' AND fijo = '$fijo_cliente'";
 	else
 		$consulta = 0;
 	
	$sql = mysql_query($consulta);
 
	$login_check = mysql_num_rows($sql);
	
	if($login_check == 0)
	{
		
		// Insercion de usuario en Vtiger
		// NOTA: De momento las Empresas se guardaran como Contactos, y no como cuentas, 
		// porque el tratamiento general se volveria Un pelin complejo. En un futuro, pte de revisar esto.
		
		$sqlcp = mysql_query("SELECT * FROM codigospostales WHERE cp = $cp_cliente");
		$arraycps = mysql_fetch_array($sqlcp);
		$localidad_cliente = $arraycps['poblacion'];
		
		$module = 'Contacts';
		$record = $client->doCreate($module, Array('firstname'=>$nombre_cliente, 'lastname' => $apellidos_cliente,
		'mailingzip'=>$cp_cliente, 'mobile'=>$movil_cliente, 'phone' => $fijo_cliente, 'email' => $email_cliente,
		'salutation'=>$codigocortesia_cliente, 'mailingcity' => $localidad_cliente, 'leadsource' => 'Exposicion'));
		if($record) 
		{
			$vtigerid = $client->getRecordId($record['id']);
		}
		
		// Insercion en Automotis	
		
		$sql = "INSERT INTO clientes (nombre, apellidos, cp, movil, fijo, email, usuario, fechacreacion, codigocortesia, tipo, vtigerid)
 			VALUES ('$nombre_cliente', '$apellidos_cliente', '$cp_cliente', '$movil_cliente', '$fijo_cliente', 
 					'$email_cliente', '$usuariooferta', now(), '$codigocortesia_cliente', '$tipo_cliente', '$vtigerid')";
 		if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}				
		
			$valorinsertado = mysql_insert_id();
			$consulta = "SELECT id FROM clientes WHERE id = '$valorinsertado'";
 	
 		$sql = mysql_query($consulta);
 		
 		$rowcliente = mysql_fetch_row($sql);
 		$cliente = $rowcliente[0];
		
	}
	else
	{
		$existe = 0;
		$array = mysql_fetch_array($sql);
		$cliente = $array['id'];
		if ($array['nombre']){ $nombre_cliente =  $array['nombre']; $existe++; }
		if ($array['apellidos']){ $apellidos_cliente =  $array['apellidos']; $existe++; }
		if ($array['movil']){ $movil_cliente =  $array['movil']; $existe++; }
		if ($array['fijo']){ $fijo_cliente =  $array['fijo']; $existe++; }
		if ($array['email']){ $email_cliente =  $array['email']; $existe++; }
		if ($array['cp']){ $cp_cliente =  $array['cp']; $existe++; }
		if ($array['codigocortesia']){ $codigocortesia_cliente =  $array['codigocortesia']; $existe++; }
		if ($array['tipo']){ $tipo_cliente =  $array['tipo']; $existe++; }
		
		$vtigerid = $array['vtigerid'];
		
		if ($existe != 8)
		{
		$sql2 = "UPDATE clientes
		    	SET nombre = '$nombre_cliente', apellidos = '$apellidos_cliente', movil = '$movil_cliente',
		    	fijo = '$fijo_cliente',
		    	email = '$email_cliente', cp = '$cp_cliente', fechacreacion = now(), usuario = '$usuariooferta' 
		    	WHERE (id = '$cliente')";	
		    	if (!(mysql_query($sql2,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		}
		    	
	}
 	
 	$matricula = $_POST['matricula'];
 	$vehiculo = $_POST['idvehiculo'];
 	$descuento = $_POST['descuento'];
 	$tasacion = $_POST['tasacion'];
 	$entrada1 = $_POST['entrada1'];
 	$meses1 = $_POST['meses1'];
 	$importe1 = $_POST['importe1'];
 	$entrada2 = $_POST['entrada2'];
 	$meses2 = $_POST['meses2'];
 	$importe2 = $_POST['importe2'];
 	$entrada3 = $_POST['entrada3'];
 	$meses3 = $_POST['meses3'];
 	$importe3 = $_POST['importe3'];
 	$sobreprecio = $_POST['sobreprecio'];
 	
 	// Insercion de Oferta en VTIGER como Oportunidad
 	
 	$sqlveh = mysql_query("SELECT * FROM vehiculos WHERE id = $vehiculo");
 	$arrayveh = mysql_fetch_array($sqlveh);
 	$modelo = $arrayveh['modelo'];
 	$pvp = $arrayveh['pvp'];
 	
 	$nomusuario = $_SESSION['nombre'];
 	
 	$potentialname = $matricula." - ".$modelo." - ".$nomusuario;
 	$closingdate = date('Y')."-".date('m')."-".date('t'); 
		
	$vtigerid = '4x'.$vtigerid;
	
	$useridvtiger = $client->_userid;
		
	$module = 'Potentials';
	$record = $client->doCreate($module, Array('related_to'=>$vtigerid, 'potentialname' => $potentialname,
	'amount'=>$pvp, 'closingdate'=>$closingdate, 'sales_stage' => 'Prospecting', 'leadsource' => 'Exposicion',
	'assigned_user_id' => $useridvtiger));
	if($record) 
	{
		$vtigerofertaid = $client->getRecordId($record['id']);
	}
	
	$potentialid = '5x'.$vtigerofertaid;
	
	$wasError= $client->lastError();
	if($wasError) {
		echo $wasError['code'] . ':' . $wasError['message'];
	}
	
	// Creacion de Actividad Calendario en VTIGER
	
	$manyana = time() + (24 * 60 * 60);
	$mayanadiezmin = $mayana + (10 * 60);
	$programdate = date('Y',$manyana)."-".date('m',$manyana)."-".date('d',$manyana);
	$programhour = date('H',$manyana).":".date('i',$manyana);
	$nextprogramhour = date('H',$mayanadiezmin).":".date('i',$mayanadiezmin);
	
	$module = 'Calendar';
	$record = $client->doCreate($module, 
			Array(	'assigned_user_id' => $useridvtiger, 
					'date_start' => $programdate,
					'time_start' => $programhour, 
					'time_end' => $nextprogramhour,
					'due_date' => $programdate,
					'parent_id' => $potentialid,
					'contact_id' => $vtigerid,
					'eventstatus' => 'Planned',
					'taskstatus' => 'Not Started',
					'priority' => 'High',
					'sendnotification' => '1',
					'activitytype' => 'Call',
					'visibility' => 'Private',
					'duration_hours' => '0',
					'duration_minutes' => '10',
					'reminder_time' => '30',
					'subject' => 'Llamada Seguimiento. Movil: '.$movil_cliente.' - Fijo: '.$fijo_cliente ));
 	
 	// Insercion de Oferta en Automotis
 	
 	$sql = "INSERT INTO ofertas (vehiculo, cliente, tasacion, usuario, descuento, fechacreacion, entrada1, meses1,
				importe1, entrada2, meses2, importe2, entrada3, meses3, importe3, sobreprecio, vtigerid)
	 			VALUES ('$vehiculo', '$cliente', '$tasacion', '$usuariooferta', '$descuento', now(), '$entrada1', '$meses1',
				'$importe1', '$entrada2', '$meses2', '$importe2', '$entrada3', '$meses3', '$importe3', '$sobreprecio','$vtigerofertaid')";
	 	
	 	if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
		else
		{
			$sql2 = mysql_query("SELECT id FROM ofertas WHERE vehiculo = '$vehiculo' AND cliente = '$cliente' AND 
			tasacion = '$tasacion' AND usuario = '$usuariooferta' ORDER BY fechacreacion DESC ");
			$rowoferta = mysql_fetch_row($sql2);
			
			echo "<a target='_blank' href='imprimiroferta.php?oferta=".$rowoferta[0]."'> Imprimir Oferta</a> <br><br>";
		}
		include("footer.php");
 } 
 elseif (isset ($_POST['reservasubmit']))
 {
	include("introducirreserva.php"); 	
	include("footer.php");
 } 
 
 elseif (isset ($_POST['inreservasubmit']))
 {
 	
 	$usuarioreserva = $_SESSION['userid'];
 	
 	$nombre_cliente = $_POST['nombre_cliente'];
 	$apellidos_cliente = $_POST['apellidos_cliente'];
 	$cp_cliente = $_POST['cp_cliente'];
 	$movil_cliente = $_POST['movil_cliente'];
 	$fijo_cliente = $_POST['fijo_cliente'];
 	$email_cliente = $_POST['email_cliente'];
 	$direccion_cliente = $_POST['direccion_cliente'];
 	$dni_cliente = $_POST['dni_cliente'];
 	$dia_cliente = $_POST['dia_cliente'];
 	$mes_cliente = $_POST['mes_cliente'];
 	$anyo_cliente = $_POST['anyo_cliente'];
 	$fechanacimiento = $anyo_cliente."-".$mes_cliente."-".$dia_cliente;
 	$codigocortesia_cliente = $_POST['codigocortesia_cliente'];
 	$tipo_cliente = $_POST['tipo_cliente'];
 	
 	$idcliente = $_POST['idcliente'];
 	$importe = $_POST['importe'];
 	$importeideal = $_POST['importeideal'];
 	$idvehiculo = $_POST['idvehiculo'];
 	$idoferta = $_POST['idoferta'];
 	
 	
 	// ACTUALIZACION Automotis de Datos de Cliente con la Reserva
 	
	$sql2 = "UPDATE clientes
		    	SET nombre = '$nombre_cliente', apellidos = '$apellidos_cliente', movil = '$movil_cliente',
		    	fijo = '$fijo_cliente',	email = '$email_cliente', cp = '$cp_cliente', fechacreacion = now(), usuario = '$usuarioreserva',
		    	direccion = '$direccion_cliente', dni = '$dni_cliente', fechanacimiento = '$fechanacimiento',
		    	codigocortesia = '$codigocortesia_cliente', tipo = '$tipo_cliente'
		    	WHERE (id = '$idcliente')";	
		    	if (!(mysql_query($sql2,$conexion)))
		    	{
		    		die('Error: '.mysql_error());
		    	}
		    	
	// ACTUALIZACION de Datos de Contacto en VTIGER con los datos de Reserva
	
	$sqlv = mysql_query("SELECT * FROM clientes WHERE id = '$idcliente'");
	$arraycliente = mysql_fetch_array($sqlv);
	$vtigerusu = $arraycliente['vtigerid'];
	
	$sqlcp = mysql_query("SELECT * FROM codigospostales WHERE cp = $cp_cliente");
	$arraycps = mysql_fetch_array($sqlcp);
	$localidad_cliente = $arraycps['poblacion'];
	
	$codigousuario = "4x".$vtigerusu; 
	$contact = $client->doRetrieve($codigousuario); 
	$update_client = array(	'mailingstreet' => $direccion_cliente, 
							'birthday' => $fechanacimiento,
							'firstname'=>$nombre_cliente, 
							'lastname' => $apellidos_cliente,
							'mailingzip'=>$cp_cliente,
							'mobile'=>$movil_cliente,
							'phone' => $fijo_cliente,
							'email' => $email_cliente,
							'salutation'=>$codigocortesia_cliente,
							'mailingcity' => $localidad_cliente,
							);
	 
	foreach($update_client as $key => $value)
	{ 
		$contact[$key] = $value; 
	} 
	$update = $client->doUpdate($contact);

	// CREACION Reserva con datos de la reserva en Automotis
 	
 	$sql = "INSERT INTO reservas (oferta, cliente, vehiculo, usuario, importe, fechareserva)
	 			VALUES ('$idoferta', '$idcliente', '$idvehiculo', '$usuarioreserva', '$importe', now())";
	 	
	 	if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
		else
		{
			
			// ENVIO DE EMAIL CON LA RESERVA
			
			$sql2 = mysql_query("SELECT * FROM usuarios WHERE userid = '$usuarioreserva'");
			$arrayusuario = mysql_fetch_array($sql2);
			$nombreusuario = $arrayusuario['nombre'];
			
			$sql3 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
			$arrayvehiculo = mysql_fetch_array($sql3);
			$matricula = $arrayvehiculo['matricula'];
			
			$sql4 = mysql_query("SELECT * FROM clientes WHERE id = '$idcliente'");
			$arraycliente = mysql_fetch_array($sql4);

			
			$cabeceras  = "MIME-Version: 1.0\r\n";
			$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
			$cabeceras .= "To: $adminname <$adminemail>\r\n";
			$cabeceras .= "From: Peugeot Ibericar <$adminemail>\r\n";

			$asunto = "Reserva de ".$matricula." por ".$nombreusuario;

			$mensaje = "Datos de la Reserva:<br><br>
						Tipo Cliente :".$arraycliente['tipo']."<br>
						Codigo Cortesia Cliente: ".$arraycliente['codigocortesia']."<br>
						Nombre Cliente: ".$arraycliente['nombre']."<br>
						Apellido Cliente: ".$arraycliente['apellidos']."<br>
						Direccion Cliente: ".$arraycliente['direccion']."<br>
						CP Cliente: ".$arraycliente['cp']."<br>
						DNI Cliente: ".$arraycliente['dni']."<br>
						F.Nacimiento Cliente: ".$arraycliente['fechanacimiento']."<br>
						Movil Cliente: ".$arraycliente['movil']."<br>
						Fijo Cliente: ".$arraycliente['fijo']."<br>
						E-Mail Cliente: ".$arraycliente['email']."<br>
						Matricula Vehiculo: $matricula <br>
						Usuario de la Reserva: $nombreusuario <br>
						Importe de la Reserva: $importe <br>
						Importe Ideal de la Reserva: $importeideal <br>";
	
		    mail ($adminemail,$asunto,$mensaje,$cabeceras);
			
			$numreserva = mysql_insert_id();
			$sql3 = "INSERT INTO reservas_activas (vehiculo, fecha, reserva) VALUES ('$idvehiculo', now(), '$numreserva')";
			if (!(mysql_query($sql3,$conexion)))
			{
			die('Error: '.mysql_error());
			}
			
			echo "<a target='_blank' href='imprimirreserva.php?reserva=".$numreserva."'> Imprimir Reserva</a> <br><br>";
		}
		include("footer.php");
 } 
 
 
 elseif (isset ($_POST['ventasubmit']))
 {
	include("introducirventa.php"); 	
	include("footer.php");
 } 
 
 elseif (isset ($_POST['inventasubmit']))
 {
 	
 	$usuarioventa = $_SESSION['userid'];
 	$idreserva = $_POST['idreserva'];
 	$idvehiculo = $_POST['idvehiculo'];
 	$idcliente = $_POST['idcliente'];
 	
 	$refentrega = $_POST['refentrega'];
 	$fechaentrega = $_POST['anyoentrega']."-".$_POST['mesentrega']."-".$_POST['diaentrega'];
 	$impentrega = $_POST['impentrega'];
 	
 	$maxapuntes = $_POST['maxapuntes'];
 	
 	for ($i=1;$i<=$maxapuntes;$i++)
 	{
 		$ref[$i] = $_POST['ref'.$i];
 		$fecha[$i] = $_POST['anyo'.$i]."-".$_POST['mes'.$i]."-".$_POST['dia'.$i];
 		$importe[$i] = $_POST['importe'.$i];
 	}
 	
 	$nomfinanciera = $_POST['nomfinanciera'];
 	$impfinanciera = $_POST['impfinanciera'];
 	$garantia = $_POST['garantia'];
 	$cancelacion = $_POST['cancelacion'];
 	$cuentacancelacion = $_POST['bancocancelacion']."-".$_POST['entidadcancelacion']."-".$_POST['dccancelacion']."-".$_POST['codigocancelacion'];
 	$devolucion = $_POST['devolucion'];
 	
 	$matriculaentrega = $_POST['$matriculaentrega'];
 	
 	$sqltas = mysql_query("SELECT * FROM tasaciones WHERE (matricula = '$matriculaentrega' AND usuario = '$usuarioventa'");
 	$arraytasacion = mysql_fetch_array($sqltas);
 	
 	if ($arraytasacion['esfuerzocomercial3'])
		$esfuerzocomercial = $arraytasacion['esfuerzocomercial3'];
	elseif ($arraytasacion['esfuerzocomercial2'])
		$esfuerzocomercial = $arraytasacion['esfuerzocomercial2'];
	else
		$esfuerzocomercial = $arraytasacion['esfuerzocomercial1'];

	$valortasacion = $esfuerzocomercial - $arraytasacion['totalreacondicionamiento'] + $arraytasacion['valormercado'];
  	
	// CREACION Venta con datos de la reserva en Automotis
 	
 	$sql = "INSERT INTO ventas (reserva, refentrega, fechaentrega, impentrega, ref1, fecha1, importe1, ref2, fecha2, importe2, 
 			ref3, fecha3, importe3, ref4, fecha4, importe4, ref5, fecha5, importe5, nomfinanciera, impfinanciera, tasacion,
 			garantia, cancelacion, cuentacancelacion, devolucion, usuario, fechaventa )
	 			VALUES ('$idreserva', '$refentrega', '$fechaentrega', '$impentrega', '".$ref[1]."', '".$fecha[1]."', '".$importe[1]."',
	 			'".$ref[2]."', '".$fecha[2]."', '".$importe[2]."', '".$ref[3]."', '".$fecha[3]."', '".$importe[3]."',
	 			'".$ref[4]."', '".$fecha[4]."', '".$importe[4]."', '".$ref[5]."', '".$fecha[5]."', '".$importe[5]."',
	 			'$nomfinanciera', '$impfinanciera', '$tasacion', '$garantia', '$cancelacion', '$cuentacancelacion',
	 			'$devolucion', '$usuarioventa', now())";
	 	
	 	if (!(mysql_query($sql,$conexion)))
		{
			die('Error: '.mysql_error());
		}
		else
		{
			
			// ENVIO DE EMAIL CON LA VENTA
			
			$sql2 = mysql_query("SELECT * FROM usuarios WHERE userid = '$usuarioventa'");
			$arrayusuario = mysql_fetch_array($sql2);
			$nombreusuario = $arrayusuario['nombre'];
			
			$sql3 = mysql_query("SELECT * FROM vehiculos WHERE id = '$idvehiculo'");
			$arrayvehiculo = mysql_fetch_array($sql3);
			$matricula = $arrayvehiculo['matricula'];
			
			$sql4 = mysql_query("SELECT * FROM clientes WHERE id = '$idcliente'");
			$arraycliente = mysql_fetch_array($sql4);
			
			$importetotal = $impentrega + $imp1 + $imp2 + $imp3 + $imp4 + $imp5 + $impfinanciera + $valortasacion;
			

			
			$cabeceras  = "MIME-Version: 1.0\r\n";
			$cabeceras .= "Content-type: text/html; charset=UTF-8\r\n";
			$cabeceras .= "To: $adminname <$adminemail>\r\n";
			$cabeceras .= "From: Peugeot Ibericar <$adminemail>\r\n";

			$asunto = "Venta de ".$matricula." por ".$nombreusuario;

			$mensaje = "Datos de la Venta:<br><br>
						Tipo Cliente :".$arraycliente['tipo']."<br>
						Codigo Cortesia Cliente: ".$arraycliente['codigocortesia']."<br>
						Nombre Cliente: ".$arraycliente['nombre']."<br>
						Apellido Cliente: ".$arraycliente['apellidos']."<br>
						Direccion Cliente: ".$arraycliente['direccion']."<br>
						CP Cliente: ".$arraycliente['cp']."<br>
						DNI Cliente: ".$arraycliente['dni']."<br>
						F.Nacimiento Cliente: ".$arraycliente['fechanacimiento']."<br>
						Movil Cliente: ".$arraycliente['movil']."<br>
						Fijo Cliente: ".$arraycliente['fijo']."<br>
						E-Mail Cliente: ".$arraycliente['email']."<br>
						Matricula Vehiculo: $matricula <br>
						Usuario de la Venta: $nombreusuario <br>
						Importe de la Venta: $importetotal <br>
						Vehiculo Entrega: ".$arraytasacion['marca']." ".$arraytasacion['modelo']."<br> 
						Importe Garantia: $garantia <br>
						Cancelacion al Banco: $importetotal <br>
						Cuenta Cancelacion: $cuentacancelacion <br>
						Importe de la Cancelacion: $cancelacion <br>
						Importe de la Devolucion Cliente: $devolucion <br>";
								
	
		    mail ($adminemail,$asunto,$mensaje,$cabeceras);
			
			$numventa = mysql_insert_id();
			
			echo "<a target='_blank' href='imprimirventa.php?reserva=".$numventa."'> Imprimir Venta</a> <br><br>";
		}
		include("footer.php");
 }  
 
 elseif (isset ($_POST['subastasubmit']))
 {
 	$vehiculo = $_POST['idvehiculo'];
 	$dialimite = $_POST['dialimite'];
 	$meslimite = $_POST['meslimite'];
 	$anolimite = $_POST['anolimite'];
 	$horafinal = date("H:i:s");
 	$fechalimite = $anolimite."-".$meslimite."-".$dialimite." ".$horafinal;
 	
 	$activa = "1";
 	
 	$sql = "INSERT INTO subastas (vehiculo, fechalimite, fechacreacion, activa)
	 			VALUES ('$vehiculo', '$fechalimite', now(), '$activa')";
	 	
	if (!(mysql_query($sql,$conexion)))
	{
		die('Error: '.mysql_error());
	}
 	else
 	{
 		$idsubasta = mysql_insert_id();
 			
 		$sql2 = mysql_query("UPDATE vehiculos SET subasta = '$idsubasta' WHERE id = '$vehiculo'");
 		
 		echo "Vehiculo Insertado en Subasta<br><br>";
 		include("footer.php");
 	} 	
 	
 	
 }
 
 elseif (isset ($_POST['borrarsubastasubmit']))
 { 		
 	$vehiculo = $_POST['idvehiculo'];
 	$subasta = $_POST['idsubasta'];
 	$sql = mysql_query("UPDATE subastas SET activa = '0' WHERE id = '$subasta'");
 	$sql2 = mysql_query("UPDATE vehiculos SET subasta = '' WHERE id = '$vehiculo'");
 	echo "Vehiculo Borrado de Subasta<br><br>";
 	include("footer.php"); 
 }
  
 else
 {
 
	 // Extensiones validas
	 $extensions = array('jpg','jpeg','gif','png','bmp','JPG');
	 $matricula = $_GET['matricula'];
	
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
	 
	 
	 $matricula = $_GET['matricula'];
	 
	 $sql = mysql_query("SELECT * FROM vehiculos WHERE (matricula = '$matricula')");
			
	 $login_check = mysql_num_rows($sql);
	 
	 if($login_check > 0)
		{
			while($row = mysql_fetch_array($sql))
			{
				foreach( $row AS $key => $val )
				{
					$$key = stripslashes( $val );
				}
				
				
			?>
			
			
				<table style="text-align: left; width: 800px;" border="1" cellpadding="2" cellspacing="2">
					<tr>
						<td colspan="2" rowspan="6" style="vertical-align: top; width: 320px;"><a target="_blank" href="imagenes.php?i=0&amp;matricula=<?php echo $matricula;?>"><img style="width: 330px; height: 240px;" alt="" src="./imagenes/<? echo $matricula; ?>/tn/1.jpg"></a><br></td>
						<td style="vertical-align: top;"><?php echo $lang_file_matricula.': '.$matricula; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_marca.': '.$marca; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_modelo.': '.$modelo; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_kilometros.': '.$kilometros; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_fechamatric.': '.fecha_normal($fechamatric); ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php
						if ($_SESSION['concesionario']=="10")
							echo $lang_file_pvd.': '.$pvd.' Euros'; 	
						else
						 	echo $lang_file_pvp.': '.$pvp.' Euros';						
						?><br></td>
					</tr>
					<tr>
						<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=1&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['1'];?>"></a><br></td>
						<td colspan="1" rowspan="3" style="vertical-align: top; width: 160px;"><a target="_blank" href="imagenes.php?i=2&amp;matricula=<?php echo $matricula;?>"><img style="width: 160px; height: 120px;" alt="" src="./imagenes/<? echo $matricula; ?>/<? echo $images['2'];?>"></a><br></td>
						<td style="vertical-align: top;"><?php echo $lang_file_combustible.': '.$combustible; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_potencia.': '.$potencia.' CV'; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top;"><?php echo $lang_file_cilindrada.': '.$cilindrada; ?><br></td>
					</tr>
					<tr>
						<td colspan="2" rowspan="1" style="vertical-align: middle; width: 320px; height: 40px;"><?php echo $lang_file_extras; ?><br></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_carroceria.': '.$carroceria; ?><br></td>
					</tr>
					<tr>
						<td colspan="2" rowspan="5" style="vertical-align: middle; width: 320px; height: 160px;"><?php echo $extras; ?><br></td>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_color.': '.$color; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_plazas.': '.$plazas; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_ubicacion.': '.$ubicacion; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_categoria.': '.$categoria; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_usoanterior.': '.$usoanterior; ?><br></td>
					</tr>
					<tr>
						<td style="vertical-align: middle; height: 40px;">
						<? if ($_SESSION['nivelusuario'] >= '2') { ?>
						 <form method='post' action='fichavehiculo.php'>
						 <input type='hidden' name='matricula' value='<?php echo "$matricula"; ?> '>
						 <input type='hidden' name='idvehiculo' value='<?php echo "$id"; ?> '>
						 <input type=submit name='ofertasubmit' value='Crear Oferta'>
						<?	}
						echo "</td>";
						echo "<td style='vertical-align: middle; height: 40px;''>";
						if ($_SESSION['nivelusuario'] >= '2')
						{
							echo "<a target='_blank' href=imprimirpercha.php?id=".$id.">Imprimir Percha</a>";
						}
						echo "</td>";
						?>
						<td style="vertical-align: top; height: 40px;"><?php echo $lang_file_garantia.': '.$garantia; ?><br></td>
					<tr>
				</table>
				<br>
				<? 
				if ($_SESSION['nivelusuario'] >= '2') 
				{
					echo "<table border ='1'>";
				
						$sql = mysql_query("SELECT * FROM ofertas WHERE vehiculo = $id ORDER BY id DESC");
						$filas = mysql_num_rows($sql);
						echo "<tr>";
						echo "<td>Oferta</td><td>Usuario</td><td>Total</td><td>Imprimir</td>";
						echo "</tr>";
 	
						for($i=0;$i<$filas&&$i<$maxofertas;$i++)
						{	
							echo "<tr>";
							$arrayofertas = mysql_fetch_array($sql);
							$total = $pvp + $arrayofertas ['sobreprecio']- $arrayofertas['descuento'];
							$usuariooferta = $arrayofertas['usuario'];
							$sql2 = mysql_query("SELECT nombre FROM usuarios WHERE userid = $usuariooferta");
							$rowusuario = mysql_fetch_row($sql2);
							
							echo "<td>".$arrayofertas['id']."</td>";
							echo "<td>".$rowusuario[0]."</td>";
							echo "<td>".$total."</td>";
							echo "<td><a target='_blank' href='imprimiroferta.php?oferta=".$arrayofertas['id']."'> Imprimir Oferta</a></td>";
							
							// Comprobar si ya esta reservado
							$sql3 = mysql_query("SELECT vehiculo FROM reservas_activas WHERE vehiculo = '$id'");
							if (mysql_num_rows($sql3) == 0)
							{
								if ($_SESSION['userid'] == $arrayofertas['usuario']) 
								{
									echo "<td>";	 
								 	echo "<form method='post' action='fichavehiculo.php'>
								 	<input type='hidden' name='idoferta' value='".$arrayofertas['id']."'>
								 	<input type='hidden' name='idvehiculo' value='".$id."'>
								 	<input type=submit name='reservasubmit' value='Reserva'>";
								 	echo "</form>";
									echo "</td>";
								} 
							}
							else
							{
								$arrayreservaactiva = mysql_fetch_row($sql3);
								$idreserva = $arrayreservaactiva['reserva'];
								$sql4 = mysql_query("SELECT * FROM reservas WHERE id = '$idreserva'");
								$arrayreserva = mysql_fetch_row($sql4);
								if ($_SESSION['userid'] == $arrayreserva['usuario'])
								{
									echo "<td>";	 
								 	echo "<form method='post' action='fichavehiculo.php'>
								 	<input type='hidden' name='idreserva' value='".$idreserva."'>
								 	<input type=submit name='ventasubmit' value='Venta'>";
								 	echo "</form>";
									echo "</td>";
								}
								 
							}
							echo "</tr>";
						}
						
			
					echo "</table>";
				
				// IF NIVEL USUARIO PARA OFERTAS
				} 
				echo "<br><br>";
				
				if ($_SESSION['nivelusuario'] == '2')
				{ 
					echo "<table border='1'>";
					echo "<tr>";
					echo "<td>$lang_file_comision</td><td>".$comision."</td>";
					echo "</tr>";
					echo "</table>";
				}
				elseif ($_SESSION['nivelusuario'] == '1' && $_SESSION['concesionario'] != '10')
				{
					$comisionavisador = $comision*0.40;
					echo "<table border='1'>";
					echo "<tr>";
					echo "<td>$lang_file_comision</td><td>".$comisionavisador."</td>";
					echo "</tr>";
					echo "</table>";
				}
				
				echo "<br><br>";
				
				$sql = mysql_query("SELECT subasta FROM vehiculos WHERE id = '$id'");
				$rowvehiculo = mysql_fetch_row($sql);
				$idsubasta = $rowvehiculo[0];
				
				if ($_SESSION['nivelusuario'] >= '6' && $categoria == "COMPRAVENTA")
				{
					if (!$idsubasta)
					{
						echo "<table border='1'>";
						echo "<tr>";
						echo "<td colspan='2'>$lang_file_subasta</td>";
						echo "</tr>";
						echo "<tr>";
						echo "<td>$lang_file_diassubasta</td>";
						echo "<td>";
						echo "<form method='post' action='fichavehiculo.php'>
							  <input type='hidden' name='idvehiculo' value='".$id."'>
							  <input type='text' size=2 maxlength='2' name='dialimite'>
							  <input type='text' size=4 maxlength='4' name='meslimite'>
							  <input type='text' size=4 maxlength='4' name='anolimite'>
							  <input type=submit name='subastasubmit' value='Crear'>";
						echo "</td>";
						echo "</tr>";
						echo "</table>";
					}
					
					else
					{					
						echo "<table border='1'>";
						echo "<tr>";
						echo "<td>$lang_file_borrarsubasta</td>";
						echo "<td>Id: $idsubasta</td>";
						echo "<td>";
						echo "<form method='post' action='fichavehiculo.php'>
							  <input type='hidden' name='idvehiculo' value='".$id."'>
							  <input type='hidden' name='idsubasta' value='".$idsubasta."'>
							  <input type=submit name='borrarsubastasubmit' value='Borrar'>";
						echo "</td>";
						echo "</tr>";
						echo "</table>";
					}
				}
				
			}
		// FIN del While	
		}
		else 
		{
			echo "$lang_file_fail<br />";
		}
		
  }
 // Fin Nivel de Usuario
 }
 else
{
	echo "Sesion Finalizada, Vuelva a acceder al Sistema<br>";
	include("footer.php");
}
 
?>
