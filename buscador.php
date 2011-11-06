<?php
/*
 * Created on 18/04/2011
 *
 * automotis  Copyright (C) <2011>  Manuel Camargo
 * This program comes with ABSOLUTELY NO WARRANTY; 
 * This is free software, and you are welcome to redistribute it
 * under certain conditions; Read README file for more information

 */
?>

<form method='post' action='?'>
  <table border="0" align="center" cellpadding="4" cellspacing="0">
  	<tr> 
      <td><? echo $lang_find_matricula; ?></td>
      <td><input name="matricula" type="text" id="matricula" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_marca; ?></td>
      <td><input name="marca" type="text" id="marca" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_modelo; ?></td>
      <td><input name="modelo" type="text" id="modelo" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_precio; ?></td>
      <td><input name="preciomenor" type="text" id="preciomenor" value="">
      <input name="preciomayor" type="text" id="preciomayor" value=""></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_combustible; ?></td>
      <td><select name="combustible">
      <option selected value="TODOS">Cualquiera</option>
      <option value="DIESEL">Diesel</option>
      <option value="GASOLINA">Gasolina</option>
      </select></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_tipovo; ?></td>
      <td><select name="tipovo">
      <option selected value="todos">Cualquiera</option>
      <option value="usado">Vehiculo Usado</option>
      <option value="ocasion">Vehiculo Ocasion</option>
      <option value="seminuevo">Vehiculo Seminuevo</option>
      </select></td>
    </tr>
    <tr> 
      <td><? echo $lang_find_ubicacion; ?></td>
      <td><select name="ubicacion">
      <option selected value="TODOS">Cualquiera</option>
      <option value="EXP. CADIZ">Cadiz</option>
      <option value="EXP. JEREZ">Jerez</option>
      <option value="EXP. PUERTO">Puerto</option>
      </select></td>
    </tr>
    <? if ($_SESSION['nivelusuario'] >= 5)
       {
       	echo "<tr>
    	<td>".$lang_find_categoria."</td>
       	<td><select name='categoria'>
      	<option value='COMPRAVENTA'>Compraventa</option>
      	<option selected value='PARTICULAR'>Particular</option>
      	</select></td>
    	<tr>";
       }
       else
       {
       	echo "<tr><td><input type='hidden' name='categoria' value='PARTICULAR'></td></tr>";
       }
     ?>
      
      <td><input type="submit" name="submit" value="OK"></td>
    </tr>
  </table>
</form>

<?

if (isset ($_POST['submit'])) 
{
	$matricula = $_POST['matricula'];
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
		
	if ($_POST['preciomenor'] > 0)
		$preciomenor = $_POST['preciomenor'];
	else
		$preciomenor = "0";
	if ($_POST['preciomayor'] > 0)
		$preciomayor = $_POST['preciomayor'];
	else
		$preciomayor = "100000";
		
	if ($_SESSION['concesionario'] == "10")
		$nombreprecio = 'pvd';
	else
		$nombreprecio = 'pvp';	
	
	$combustible = $_POST['combustible'];
		
	if ($combustible == "TODOS")
		$combustible = "";	
		
	$tipovo = $_POST['tipovo'];
	
	if ($tipovo == "seminuevo")
	{
		$kmmenor = "0";
		$kmmayor = "35000";
		$anyomenor = date("Y")-2;
		$anyomayor = date("Y");
		$fechamatricmenor = $anyomenor."-".date('n')."-".date('j');
		$fechamatricmayor = $anyomayor."-".date('n')."-".date('j');
	}
	elseif ($tipovo == "ocasion")
	{
		$kmmenor = "0";
		$kmmayor = "70000";
		$anyomenor = date("Y")-4;
		$anyomayor = date("Y")-2;
		$fechamatricmenor = $anyomenor."-".date('n')."-".date('j');
		$fechamatricmayor = $anyomayor."-".date('n')."-".date('j');
	}
	elseif ($tipovo == "usado")
	{
		$kmmenor = "0";
		$kmmayor = "500000";
		$anyomenor = date("Y")-100;
		$anyomayor = date("Y")-4;
		$fechamatricmenor = $anyomenor."-".date('n')."-".date('j');
		$fechamatricmayor = $anyomayor."-".date('n')."-".date('j');
	}
	else
	{
		$kmmenor = "0";
		$kmmayor = "500000";
		$anyomenor = date("Y")-100;
		$anyomayor = date("Y");
		$fechamatricmenor = $anyomenor."-".date('n')."-".date('j');
		$fechamatricmayor = $anyomayor."-".date('n')."-".date('j');
	}
	
	$categoria = $_POST['categoria'];
	
	if ($categoria == "TODOS")
		$categoria = "";	
	
	$ubicacion = $_POST['ubicacion'];
	
	if ($ubicacion == "TODOS")
		$ubicacion = "";	
	
	$sql = mysql_query("SELECT vehiculos.* FROM vehiculos_disponibles, vehiculos
			WHERE (vehiculos_disponibles.matricula = vehiculos.matricula) AND
		    (vehiculos.matricula LIKE '%$matricula%') AND (vehiculos.$nombreprecio >= '$preciomenor')
		    AND (vehiculos.$nombreprecio <= '$preciomayor') AND (combustible LIKE '%$combustible%')
		    AND (vehiculos.kilometros >= '$kmmenor') AND (vehiculos.kilometros <= '$kmmayor')
		    AND (vehiculos.fechamatric >= '$fechamatricmenor') AND (vehiculos.fechamatric <= '$fechamatricmayor')
			AND (marca LIKE '%$marca%') AND (modelo LIKE '%$modelo%') AND (vehiculos.categoria LIKE '%$categoria%')
			AND (ubicacion LIKE '%$ubicacion%')
			ORDER BY now()-fechaentrada DESC");
		
	$numrows = mysql_num_rows($sql);
	
	if($numrows > 0)
	{
		echo "<table border='1' align='center' cellpadding='4' cellspacing='0''>";
		echo "<tr>";
		echo "<td></td><td>".$lang_find_matricula."</td><td>".$lang_find_marca."</td><td>".$lang_find_modelo."</td>
			<td>".$lang_find_fechamatric."</td><td>".$lang_find_kilometros."</td>";
			
		if ($_SESSION['nivelusuario'] >= 3) 
			 echo "<td>".$lang_find_diasstock."</td>";

		echo "<td>".$lang_find_importe."</td>";

		echo "</tr>";
		
		for($i=0;$i<$numrows;$i++)
		{
			$arrayvehiculos = mysql_fetch_array($sql);
			
			$vehiculo = $arrayvehiculos['id'];
			$sql2 = mysql_query("SELECT * FROM reservas_activas WHERE vehiculo = '$vehiculo'");
			$numerovehiculos = 0;
			$numerovehiculos = mysql_num_rows($sql2);
			$sql3 = mysql_query("SELECT subasta FROM vehiculos WHERE id = '$vehiculo'");
			$rowvehiculos = mysql_fetch_row($sql3);
			$subastaactiva = $rowvehiculos[0];
			
			$today = juliantojd(date("n"),date("j"),date("Y"));
			$fechaentry = $arrayvehiculos['fechaentrada'];
			$fechadiv = split("-",$fechaentry);
			$fechaentrada = juliantojd($fechadiv[1],$fechadiv[2],$fechadiv[0]);
			$diasstock = $today - $fechaentrada;
			$fechamatricula = fecha_normal($arrayvehiculos['fechamatric']);
			
			
			if ($numerovehiculos > 0 || $subastaactiva)
				echo "<tr style='background-color:red;'>";
			elseif ($diasstock >= 120 && $_SESSION['nivelusuario'] == 3 )
				echo "<tr style='background-color:green;'>";
			else
				echo "<tr>";			
			
			echo "<td style='vertical-align: top; width: 80px; height: 60px'>
			<a href='index.php?matricula=".$arrayvehiculos['matricula']."'>
			<img style='width: 80px; height: 60px;' alt='' src='./imagenes/".$arrayvehiculos['matricula']."/tn/1.jpg'>
			</a></td><td><a href='index.php?matricula=".$arrayvehiculos['matricula']."'>".$arrayvehiculos['matricula']."</a></td>
			<td>".$arrayvehiculos['marca']."</td><td>".$arrayvehiculos['modelo']."</td>
			<td>".$fechamatricula."</td><td>".$arrayvehiculos['kilometros']."</td>";
			
			if ($_SESSION['nivelusuario'] >= 3) 
			{
			 	echo "<td>".$diasstock."</td>";
			}
			if ($_SESSION['concesionario'] == 10)
				echo "<td>".$arrayvehiculos['pvd']." &euro;</td>";
			else
				echo "<td>".$arrayvehiculos['pvp']." &euro;</td>";		
			
			echo "</tr>";
		}
		echo "</table>";
	}
	else 
	{
		echo "$lang_find_fail<br />";
	}
	
}

?>