<?php

/*********************************************************************
En esta pagina se procesa la informacion que es enviada de los filtros para 
mostrar una tabla con los indicadores y sus valores
*********************************************************************/

include 'dbconfig.php';

$zonas = $_POST['zonas'];
$indicador = $_POST['indicador'];
$mes = $_POST['mes'];
$siglasDept = $_POST['siglasDept'];
$anio = $_POST['anio'];

$sqlIndicadores = "SELECT i.cod_indicador, i.indicador, izm.meta_programada, iz.cod_zona , izm.mes FROM indicador i INNER JOIN indicador_zona iz ON (iz.cod_indicador = i.cod_indicador) INNER JOIN indicador_zona_mes izm ON (izm.cod_indicador_zona = iz.cod_indicador_zona and izm.anio_indicador = " . $anio . ")";

if($zonas != 0)
{
	$sqlIndicadores .= " WHERE iz.cod_zona = " . $zonas;

	if($mes != 0)
	{
		$sqlIndicadores .= " AND izm.mes = " . $mes;

		if($indicador != 0)
			$sqlIndicadores .= " AND i.cod_indicador = " . $indicador;
	}
	else
	{
		if($indicador != 0)
			$sqlIndicadores .= " AND i.cod_indicador = " . $indicador;
	}
}
else
{
	if($mes != 0)
	{
		$sqlIndicadores .= " AND izm.mes = " . $mes;

		if($indicador != 0)
			$sqlIndicadores .= " AND i.cod_indicador = " . $indicador;
	}
	else
	{
		if($indicador != 0)
			$sqlIndicadores .= " AND i.cod_indicador = " . $indicador;	
	}
}

$sqlIndicadores .= " and i.estado = 1 and i.departamento = '" . $siglasDept . "'";

//echo $sqlIndicadores . "<br>";

$mes = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
$resIndicadores = query($sqlIndicadores);
$valores = "<table>
				<tr>
					<th>Indice</th>
					<th>Indicador</th>
					<th>Zona</th>
					<th>Mes</th>
					<th>Meta Programada</th>
				</tr>";

$cont = 0;
while($fila = mysql_fetch_array($resIndicadores))
{
	$cont++;
	$indMes = $fila['mes'];
	if($cont % 2)
		$valores .= "<tr class = 'color1'>
						<td>" . $cont . "</td>
						<td>" . $fila['indicador'] . "</td>
						<td>" . $fila['cod_zona'] . "</td>
						<td>" . $mes[$indMes - 1] . "</td>
						<td><input id='" . $fila['cod_indicador'] . "-" . $fila['cod_zona'] . "-" . $indMes . "' value='" . $fila['meta_programada'] . "'></input></td>						
					</tr>";
	else
		$valores .= "<tr class = 'color2'>
						<td>" . $cont . "</td>
						<td>" . $fila['indicador'] . "</td>
						<td>" . $fila['cod_zona'] . "</td>
						<td>" . $mes[$indMes - 1] . "</td>
						<td><input id='"  . $fila['cod_indicador'] . "-" . $fila['cod_zona'] . "-" . $indMes . "' value='" . $fila['meta_programada'] . "'></input></td>						
					</tr>";


}

$valores .= '</table>';

echo $valores;




?>