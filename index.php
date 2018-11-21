<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Actividades IEPS</title>
	<link rel="stylesheet" href="css/estilos.css">

</head>
<body>
	<header id="header" class="">
		<h1>Actividades IEPS</h1>		
	</header><!-- /header -->
	<div id="alertar">
		<button id="btAlertar">A L E R T A R!!!</button>
	</div>
	<div id="controles">
		<button id="btConsulta">Consultar</button>
		<button id="btIngresar">Añadir Actividad</button>		
	</div>
	<div id="divConsulta">
		<select id="departamentoConsulta">
			<option value="0">Elegir Departamento</option>
		</select>
		<div>
			<label for="actividadAbierta">Abierta</label>			
			<input type="radio" name="filtroActividad" id="actividadAbierta" value="1" >
			<label for="actividadCerrada">Cerrada</label>
			<input type="radio" name="filtroActividad" id="actividadCerrada" value="2">
		</div>
	</div>
	<div id="divIngreso">
		<select id="departamento">
			<option value="0">Elegir Departamento</option>
		</select>
		<select id="personaDepartamento">
			<option value="0">Elegir Responsable Actividad</option>
		</select>
		<div>
			<h4>Ingrese Fecha Cumplimiento Actividad</h4>
			<input type="date" name="fecha" id="fecha">					
		</div>		
		<div>				
			<textarea id="actividad" placeholder="Ingresar la actividad"></textarea>	
		</div>		
		<button id="btIngresoActividad">Ingresar Actividad</button>				
		
	</div>
	<div id="listaActividades"></div>
	<script src="js/controles.js" type="text/javascript"></script>
</body>
</html>