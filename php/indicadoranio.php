<?php

/*********************************************************************
En esta pagina se procesa la informacion que es enviada de los filtros para 
mostrar una tabla con los indicadores y sus valores
*********************************************************************/

include 'dbconfig.php';


$accion = $_POST['accion'];
$anio = $_POST['anio'];
$departamento = $_POST['departamento'];


if($accion == 'consultar')
{
	$sqlIndicadorAnio = "select * from indicador where anio_inicio <= " . $anio . " and anio_fin >= " . $anio . " and departamento = '" . $departamento . "' and estado = 1";
	$resIndicadorAnio = query($sqlIndicadorAnio);
	$htmlRespuesta = "<option value='0'>Seleccione Un Indicador</option>";
	while($filaIndicadorAnio = mysql_fetch_array($resIndicadorAnio))
	{
		$htmlRespuesta .= "<option value='" . $filaIndicadorAnio['cod_indicador'] . "'>" . $filaIndicadorAnio['indicador'] . "</option>";
	}
	echo $htmlRespuesta;
}





?>