<?php
/******************************************************************************
//En este archivo se pondran las sentencias para cargar datos de la pagina web
*******************************************************************************/

include 'dbconfig.php';


$accion = $_POST['accion'];

if($accion == 'mostrar')
{
	CargarElementosDiv();
}

if($accion == 'cargarPersonal')
{
	CargarPesonal();
}

if($accion == 'IngresarActividad')
{
	IngresarActividad();
}

if($accion == 'cargarActividad')
{
	CargarActividad();
}

if($accion == 'cerrarActividad')
{
	CerrarActividad();
}

function CargarElementosDiv()
{
	$divMostrar = $_POST['div'];
	$htmlResponse = "<option value='0'>Elija un departamento</option>";
	
	$sqlConsulta = "select * from departamento where activo = 1";
	$resConsulta = query($sqlConsulta);
	while($filaConsulta = mysql_fetch_array($resConsulta))
	{
		$htmlResponse .= "<option value='" . $filaConsulta['iddepartamento'] . "'>" . $filaConsulta['nombre'] . "</option>"; 
	}
	echo $htmlResponse;

}

function CargarPesonal()
{
	$departamento = $_POST['departamentoSel'];
	$sqlConsulta = "select * from persona where iddepartamento = " . $departamento . " and activo = 1";
	$resConsulta = query($sqlConsulta);
	$htmlResponse = "<option value='0'>Elija Responsable</option>";
	while($filaConsulta = mysql_fetch_array($resConsulta))
	{
		$htmlResponse .= "<option value='" . $filaConsulta['idpersona'] . "'>" . $filaConsulta['apellido'] . " " . $filaConsulta['nombre'] . "</option>"; 
	}
	echo $htmlResponse;
}

function IngresarActividad()
{
	$departamentoSel = $_POST['deptActividad'];
	$responsableSel = $_POST['responsable'];
	$fechaSel = $_POST['fecha'];
	$actividad = $_POST['actividad'];

	$sqlInsert = "insert into actividad(idpersona, iddepartamento, descripcion, fechaFin, estado) values (" . $responsableSel . ", " . $departamentoSel . ",'" . $actividad . "', '" . $fechaSel . "', 1)";
	query($sqlInsert);

	echo "Actividad Ingresada";
	// echo $sqlInsert;
}

function CargarActividad()
{
	$departamentoSel = $_POST['valorDepertamento'];
	$tablaResponse = "<table>";
	$tablaResponse .= "<tr>";
	$tablaResponse .= "<td>N°</td>
						<td>Id Actividad</td>
						<td>Responsable</td>
						<td>Fecha Cumplimiento</td>
						<td>Estado</td>
						<td>Controles</td>";
	$tablaResponse .= "</tr>";

	$sqlConsulta = "select * from actividad where iddepartamento = " . $departamentoSel;
	$resConsulta = query($sqlConsulta);
	$indice = 0;
	$tableBody = "";
	while($filaConsulta = mysql_fetch_array($resConsulta))
	{
		$indice++;
		$tableBody .= "<tr>";
		$tableBody .= "<td>" . $indice . "</td>";
		$tableBody .= "<td>" . $filaConsulta['idactividad'] . "</td>";
		$tableBody .= "<td>" . GetResponsable($filaConsulta['idpersona']) . "</td>";
		$tableBody .= "<td>" . $filaConsulta['fechaFin'] . "</td>";
		$estadoActividad = $filaConsulta['estado'];
		if($estadoActividad == 1)
		{
			$tableBody .= "<td>Abierto</td>";
			$tableBody .= "<td><button id='". $filaConsulta['idactividad'] ."' onclick='CerrarActividad(" . $filaConsulta['idactividad'] . ")'>Cerrar</buttor></td>";
		}
		if($estadoActividad == 2)
		{
			$tableBody .= "<td>Cerrado</td>";
			$tableBody .= "<td></td>";
		}		
		$tableBody .= "</tr>";
	}

	$tablaResponse .= $tableBody;
	$tablaResponse .= "</table>";

	echo $tablaResponse;
}

function GetResponsable($idResponsable)
{
	$sqlResponsable = "select nombre, apellido from persona where idpersona = " . $idResponsable;
	$resResponsable = query($sqlResponsable);
	$nombres = '';
	while($filaResponsable = mysql_fetch_array($resResponsable))
	{
		$nombres = $filaResponsable['apellido'] . " " . $filaResponsable['nombre'];
	}
	return $nombres;
}

function CerrarActividad()
{
	$idactividad = $_POST['idActividad'];
	$sqlActividad = "update actividad set estado = 2 where idActividad = " . $idactividad;
	query($sqlActividad);
	echo "Actividad Cerrada  -  Id Actividad = " . $idactividad;
}
// $instruccion = $_POST['instruccion'];
// $anio = $_POST['anio'];

// // determino la sentencia que se ejecutara dependiendo de la instruccion
// if($instruccion == 'cargarIndicadorNuevo')
// {
// 	// Realizo la consulta de los indicadores a partir de la sigla del departamento
// 	$sqlDept = "SELECT cod_indicador, indicador from indicador WHERE estado = 1 and departamento = '" . $siglasDept . "'" ;

// 	$resIndicador = query($sqlDept);
// 	$opcIndicadores = "";

// 	while($fila = mysql_fetch_array($resIndicador))
// 	{
// 		$opcIndicadores .="<option value='" . $fila['cod_indicador'] . "'>" . $fila['indicador'] . "</option>";
// 	} 

// 	echo $opcIndicadores;
// }

// if($instruccion == 'borrar')
// {
// 	$codIndBorrar = $_POST['indicador'];

// 	//tomar los indicadores antes de que se desactivar uno
// 	$sqlNumInd = "select count(*) as numIndicadores from indicador where departamento = '" . $siglasDept . "' and estado = 1";

// 	$resNumInd = query($sqlNumInd);
// 	$totalNumInd = 0;

// 	while($fila = mysql_fetch_array($resNumInd))
// 	{
// 		$totalNumInd = $fila['numIndicadores'];
// 	}

// 	//update del indicador
// 	$sqlIndDept = "update indicador set estado = 0 where cod_indicador = " . $codIndBorrar . " and departamento = '". $siglasDept . "'";

// 	$resCodBorrar = query($sqlIndDept);

// 	$resNumInd = query($sqlNumInd);
// 	$auxTotal = 0;

// 	while($fila = mysql_fetch_array($resNumInd))
// 	{
// 		$auxTotal = $fila['numIndicadores'];
// 	}

// 	if($auxTotal == ($totalNumInd - 1))
// 		echo 1;
// 	else
// 		echo 0;
// }

// if($instruccion == 'guardarValor')
// {
// 	/******************************************************
// 	Se guarda los datos de metas programadas con respecto
// 	a un indicador por zona y por mes
// 	*******************************************************/

// 	//se almacena los datos enviados de metas programadas
// 	$valorEne = $_POST['valorIndicadorEne'];
// 	$valorFeb = $_POST['valorIndicadorFeb'];
// 	$valorMar = $_POST['valorIndicadorMar'];
// 	$valorAbr = $_POST['valorIndicadorAbr'];
// 	$valorMay = $_POST['valorIndicadorMay'];
// 	$valorJun = $_POST['valorIndicadorJun'];
// 	$valorJul = $_POST['valorIndicadorJul'];
// 	$valorAgo = $_POST['valorIndicadorAgo'];
// 	$valorSep = $_POST['valorIndicadorSep'];
// 	$valorOct = $_POST['valorIndicadorOct'];
// 	$valorNov = $_POST['valorIndicadorNov'];
// 	$valorDic = $_POST['valorIndicadorDic'];

// 	//recibe el codigo de indicador y de zona
// 	$indicador = $_POST['selIndicador'];
// 	$zona = $_POST['zona'];

// 	//
// 	$selIndZona = "select cod_indicador_zona from indicador_zona where cod_indicador = " . $indicador . " and cod_zona = " . $zona;

// 	$resIndZona = query($selIndZona);
// 	$codIndZona = 0;

// 	//adquiero el codigo indicador zona
// 	while($fila = mysql_fetch_array($resIndZona))
// 	{
// 		$codIndZona = $fila['cod_indicador_zona'];
// 	}

// 	$resAux = "";
	
// 	for($i = 0; $i < 12; $i++)
// 	{

// 		switch ($i) 
// 		{
// 			case 0:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorEne . " where cod_indicador_zona = " . $codIndZona . " and mes = 1 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 1:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorFeb . " where cod_indicador_zona = " . $codIndZona . " and mes = 2 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 2:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorMar . " where cod_indicador_zona = " . $codIndZona . " and mes = 3 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 3:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorAbr . " where cod_indicador_zona = " . $codIndZona . " and mes = 4 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 4:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorMay . " where cod_indicador_zona = " . $codIndZona . " and mes = 5 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 5:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorJun . " where cod_indicador_zona = " . $codIndZona . " and mes = 6 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 6:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorJul . " where cod_indicador_zona = " . $codIndZona . " and mes = 7 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 7:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorAgo . " where cod_indicador_zona = " . $codIndZona . " and mes = 8 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 8:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorSep . " where cod_indicador_zona = " . $codIndZona . " and mes = 9 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 9:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorOct . " where cod_indicador_zona = " . $codIndZona . " and mes = 10 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 10:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorNov . " where cod_indicador_zona = " . $codIndZona . " and mes = 11 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 			case 11:
// 				$sql = "update indicador_zona_mes set meta_programada = " . $valorDic . " where cod_indicador_zona = " . $codIndZona . " and mes = 12 and anio_indicador = " . $anio;

// 				$resAux = query($sql);
// 				break;
// 		}
// 	}


// 	echo $valorEne . "-" . $valorFeb . "-" . $valorMar . "-" . $valorAbr . "-" . $valorMay . "-" . $valorJun . "-" . $valorJul . "-" . $valorAgo . "-" . $valorSep . "-" . $valorOct . "-" . $valorNov . "-" . $valorDic . "******" . $indicador . "+++++" . $zona;
// }
// ?>