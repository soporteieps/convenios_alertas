<?php
include 'dbconfig.php';

//recibimos los valores a grabar
$indicador = $_POST['indicador'];
$siglasDept = $_POST['siglasDept'];
$anio = $_POST['anioIndicador'];

//reviso cuantos indicadores existen antes de la insercion del nuevo indicador
$sqlCon = "SELECT count(*)numIndicadores FROM indicador where departamento = '" . $siglasDept . "'";

$resSqlCon = query($sqlCon);
$numIndicadores = 0;
while($fila = mysql_fetch_array($resSqlCon))
{
	$numIndicadores = $fila['numIndicadores'];
} 

//sql de ingreso del indicador
$sqlNuevoIndicador = "INSERT INTO indicador(indicador, estado, departamento) values ('" . $indicador ."', 1, '" . $siglasDept ."')";

//sql para revisar si ya existe el indicador
$sqlExisteIndicador = "SELECT count(*) as sumIndicador FROM indicador where indicador = '" . $indicador . "' and departamento = '" . $siglasDept . "'";

//reviso la existencia del nuevo indicador en la base de datos
$resIndicadorExistente = query($sqlExisteIndicador);
$existeIndicador = 0;

while($fila = mysql_fetch_array($resIndicadorExistente))
{
	$existeIndicador = $fila['sumIndicador'];
}
	
if($existeIndicador == 0)
{
	//ejecuto la sentencia
	$resNuevoIndicador = query($sqlNuevoIndicador);

	

	$selCodIndicador = "select cod_indicador from indicador where indicador = '" . $indicador . "' and estado = 1 and departamento = '" . $siglasDept . "'";

	$resCodIndicador = query($selCodIndicador);
	$codIndicador = 0;
	while($fila = mysql_fetch_array($resCodIndicador))
	{
		$codIndicador = $fila['cod_indicador'];
	}

	//insertar el indicador a la zona
	$sqlIndicadorZona = "insert into indicador_zona(cod_zona, cod_indicador, zona) values (1, " . $codIndicador . ", NULL),(2, " . $codIndicador . ", NULL),(3, " . $codIndicador . ", NULL),(4, " . $codIndicador . ", NULL),(5, " . $codIndicador . ", NULL),(6, " . $codIndicador . ", NULL),(7, " . $codIndicador . ", NULL),(8, " . $codIndicador . ", NULL),(9, " . $codIndicador . ", NULL)";

	$resIndicadorZona = query($sqlIndicadorZona);

	//seleccionamos los nuevos codigo indicazor ingresados
	$sqlCodIndicadorZona = "select cod_indicador_zona from indicador_zona where cod_indicador = " . $codIndicador;

	$resCodIndicadorZona = query($sqlCodIndicadorZona);

	while($fila = mysql_fetch_array($resCodIndicadorZona))
	{
		$auxCodIndicadorZona = $fila['cod_indicador_zona'];

		$sqlInsertMetas = "insert into indicador_zona_mes(cod_indicador_zona, mes, meta_programada, anio_indicador) values (" . $auxCodIndicadorZona . ", 1, 0," . $anio ."), (" . $auxCodIndicadorZona . ", 2, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 3, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 4, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 5, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 6, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 7, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 8, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 9, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 10, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 11, 0," . $anio ."),(" . $auxCodIndicadorZona . ", 12, 0," . $anio .")";

		$resAux = query($sqlInsertMetas);
	}

	//vuelvo a revisar los indicadores y ver si aumento el numero de los mismos
	$resSqlCon = query($sqlCon);
	$numNuevosIndicadores = 0;
	while($fila = mysql_fetch_array($resSqlCon))
	{
		$numNuevosIndicadores = $fila['numIndicadores'];
	}



	if($numNuevosIndicadores > $numIndicadores)
		echo 1;
	else 
		echo 0;
}
else
	echo -1;


?>