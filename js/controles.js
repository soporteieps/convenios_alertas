// Se inicializa las variables

btnConsultarActividades = document.getElementById('btConsulta');
btnIngresarActividades = document.getElementById('btIngresar');
selDepartamentoConsulta = document.getElementById('departamentoConsulta');
selDepartamento = document.getElementById('departamento');
btnAddActividad = document.getElementById('btIngresoActividad');
btnAlertar = document.getElementById('btAlertar');

btnConsultarActividades.addEventListener('click', CargarDepartamento);
btnIngresarActividades.addEventListener('click', CargarFormIngreso);
selDepartamentoConsulta.addEventListener('change', CargarActividades);
selDepartamento.addEventListener('change', CargarPersonal);
btnAddActividad.addEventListener('click', IngresarActividad);
btnAlertar.addEventListener('click', AlertarActividades);


function crearAjax()
{
	var res;

	if(window.XMLHttpRequest)
		res = new XMLHttpRequest();
	else
		res = new ActiveXObject("Microsoft.XMLHTTP");

	return res;
}

function CargarDepartamento()
{
	//console.log("generar indicador");
	// Objeto ajax
	var request = crearAjax();	

	// // Objeto fdata que toma los valores del formulario
	var fdata = new FormData();

	// // Se toma los datos de consulta
	// var codIndicadorConsulta = document.getElementById('indicador');
	// codIndicadorConsulta = codIndicadorConsulta.options[codIndicadorConsulta.selectedIndex].value;
	
	
	fdata.append('div', 'divConsulta');
	fdata.append('accion', 'mostrar');
	


	request.open('POST', 'php/cargarDatos.php', true);
	// console.log(request);
	// document.getElementById('listaActividades').innerHTML = "<div class='imgCargar'><img src='../../img/loading.gif'/></div>";
	// document.getElementById('barraProgreso').innerHTML = "25%";
	// document.getElementById('barraProgreso').style.width = "25%";

	// request.upload.addEventListener('progress', BarraProgreso, false);
	
	request.onreadystatechange = function()
	{

		if( request.readyState == 4)
		{
			// document.getElementById('barraProgreso').innerHTML = "100%";
			// document.getElementById('barraProgreso').style.width = "100%";
			document.getElementById('divConsulta').style.display = "block";
			document.getElementById('departamentoConsulta').innerHTML = request.responseText;
			
			document.getElementById('divIngreso').style.display = "none";
			// console.log(request.responseText);
		}	
		
	}

	request.send(fdata);
	
}

function CargarFormIngreso()
{
	//console.log("generar indicador");
	// Objeto ajax
	var request = crearAjax();	

	// // Objeto fdata que toma los valores del formulario
	var fdata = new FormData();

	// // Se toma los datos de consulta
	// var codIndicadorConsulta = document.getElementById('indicador');
	// codIndicadorConsulta = codIndicadorConsulta.options[codIndicadorConsulta.selectedIndex].value;
	
	
	fdata.append('div', 'divIngreso');
	fdata.append('accion', 'mostrar');
	


	request.open('POST', 'php/cargarDatos.php', true);
	// console.log(request);
	// document.getElementById('listaActividades').innerHTML = "<div class='imgCargar'><img src='../../img/loading.gif'/></div>";
	// document.getElementById('barraProgreso').innerHTML = "25%";
	// document.getElementById('barraProgreso').style.width = "25%";

	// request.upload.addEventListener('progress', BarraProgreso, false);
	
	request.onreadystatechange = function()
	{

		if( request.readyState == 4)
		{
			// document.getElementById('barraProgreso').innerHTML = "100%";
			// document.getElementById('barraProgreso').style.width = "100%";
			document.getElementById('divConsulta').style.display = "none";			
			document.getElementById('divIngreso').style.display = "block";
			document.getElementById('departamento').innerHTML = request.responseText;
			document.getElementById('listaActividades').innerHTML = '';
			// console.log(request.responseText);
		}	
		
	}

	request.send(fdata);
}

function CargarActividades()
{
	valorSeleccionado = selDepartamentoConsulta.options[selDepartamentoConsulta.selectedIndex].value;
	request = crearAjax();
	fdata = new FormData();

	fdata.append('valorDepertamento', valorSeleccionado);
	fdata.append('accion', 'cargarActividad');
	request.open('POST', 'php/cargarDatos.php', true);

	request.onreadystatechange = function()
	{
		if(request.readyState == 4)
		{			
			document.getElementById('listaActividades').innerHTML = request.responseText;
		}
	}

	request.send(fdata);

	console.log(valorSeleccionado);
}

function CargarPersonal()
{
	valorSeleccionado = selDepartamento.options[selDepartamento.selectedIndex].value;
	request = crearAjax();
	fdata = new FormData();

	fdata.append('departamentoSel', valorSeleccionado);
	fdata.append('accion', 'cargarPersonal');
	request.open('POST', 'php/cargarDatos.php', true);

	request.onreadystatechange = function()
	{
		if(request.readyState == 4)
		{			
			document.getElementById('personaDepartamento').innerHTML = request.responseText;
		}
	}

	request.send(fdata);

	console.log(valorSeleccionado);
}

function IngresarActividad()
{
	request = crearAjax();
	valorDepertamento = selDepartamento.options[selDepartamento.selectedIndex].value;
	if(valorDepertamento == 0)
	{
		alert('Debe elegir un departamento');
		return false;
	}
	responsable = document.getElementById('personaDepartamento');
	responsable = responsable.options[responsable.selectedIndex].value;
	if(responsable == 0)
	{
		alert('Debe elegir un responsable de la actividad');
		return false;
	}

	fechaIngresada = document.getElementById('fecha').value;
	if(fechaIngresada == '')
	{
		alert('Debe ingresar una fecha valida');
		return false;
	}

	actividad = document.getElementById('actividad').value;
	if(actividad == '')
	{
		alert('Debe ingresar una actividad');
		return false;
	}
	// console.log(actividad);

	fdata = new FormData();
	fdata.append('accion', 'IngresarActividad');
	fdata.append('deptActividad', valorDepertamento);
	fdata.append('responsable', responsable);
	fdata.append('actividad', actividad);
	fdata.append('fecha', fechaIngresada);

	request.open('POST', 'php/cargarDatos.php', true);

	request.onreadystatechange = function()
	{
		if(request.readyState == 4)
		{			
			alert(request.responseText);
			selDepartamento.value = 0;
			document.getElementById('actividad').value = '';
			document.getElementById('fecha').value = '';
			
		}
	}

	request.send(fdata);
	// console.log(fechaIngresada);
	
}

function CerrarActividad()
{
	// console.log(idActividad);
	request = crearAjax();
	idActividad = document.getElementById('actividadModificar').innerHTML;
	observacionActividad = document.getElementById('textoObservacion').value;
	
	// console.log(actividad);

	fdata = new FormData();
	fdata.append('accion', 'cerrarActividad');
	fdata.append('idActividad', idActividad);
	fdata.append('observacionActividad', observacionActividad);
	
	request.open('POST', 'php/cargarDatos.php', true);

	request.onreadystatechange = function()
	{
		if(request.readyState == 4)
		{			
			alert(request.responseText);
			document.getElementById(idActividad).style.visibility = "hidden";
			CargarActividades();

		}
	}

	request.send(fdata);
}

function AlertarActividades()
{
	request = crearAjax();		
	
	request.open('POST', 'php/mail.php', true);

	request.onreadystatechange = function()
	{
		if(request.readyState == 4)
		{			
			alert(request.responseText);
			redColor = Math.floor((Math.random() * 255) + 1);
			greenColor = Math.floor((Math.random() * 255) + 1);
			blueColor = Math.floor((Math.random() * 255) + 1);
			document.getElementById('btAlertar').style.background = "rgb(" + redColor + ", " + greenColor + ", " + blueColor + ")";
			// document.getElementById(idActividad).style.visibility = "hidden";
		}
	}

	request.send();
}

function SetIndicador(id)
{
	document.getElementById('actividadModificar').innerHTML = id;
	document.getElementById('textoObservacion').value = "";
}