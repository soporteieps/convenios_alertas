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
						<td>Detalle</td>
						<td>Observacion</td>
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
			$tableBody .= "<td>" . $filaConsulta['descripcion'] . "</td>";
			$tableBody .= "<td>" . $filaConsulta['observacion'] . "</td>";
			$tableBody .= "<td><a href='#popup'><button type='button' id='". $filaConsulta['idactividad'] ."' onclick='SetIndicador(this.id)'>Cerrar</button></a></td>";
			// <button id='". $filaConsulta['idactividad'] ."' onclick='CerrarActividad(" . $filaConsulta['idactividad'] . ")'>Cerrar</buttor>
		}
		if($estadoActividad == 2)
		{
			$tableBody .= "<td>Cerrado</td>";
			$tableBody .= "<td>" . $filaConsulta['descripcion'] . "</td>";
			$tableBody .= "<td>" . $filaConsulta['observacion'] . "</td>";
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
	$observacionActividad = $_POST['observacionActividad'];
	$sqlActividad = "update actividad set estado = 2, observacion = '" . $observacionActividad . "' where idActividad = " . $idactividad;
	query($sqlActividad);
	echo "Actividad Cerrada  -  Id Actividad = " . $idactividad;
}

?>