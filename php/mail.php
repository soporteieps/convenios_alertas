<?php
include_once('class.phpmailer.php');
include_once('dbconfig.php');

enviarMail();
//function enviarMailDetalle($mail_custodio,$mail_encargado,$body){
function enviarMail(){
//	echo "enviarMailDetalle= $mail_destino , $body <br>";
	//servidor de correo
//	$autorizacion = $_POST['autorizacion'];
	$email_host = "mail.ieps.gob.ec";
	$email_port = 587;
	$email_tipo = "smtp";
	//informacion de la cuenta
	$email_remintente = "planificacion@ieps.gob.ec";
	$email_remintente_nombre = "PLANIFICACION - IEPS";
	$email_remintente_usuario = "planificacion"; 
	$email_remintente_contrasena = "admin0Ugt6";
	$email_asunto = "Alerta de Actividades";
	//bandera
	$bandera = false;
	
	$mail = new PHPMailer();
	//$body = $mail->getFile('contents.html');
	//$body = eregi_replace("[\]",'',$body);
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Mailer   = $email_tipo;//"smtp";
	$mail->Host     = $email_host;//"mail.ieps.gob.ec"; // SMTP server
	$mail->Port     = $email_port;//587;
	$mail->SMTPAuth = true;
	$mail->Username = $email_remintente_usuario;//"conductores";//"compras"; 
	$mail->Password = $email_remintente_contrasena;//"ieps000"; 
	$mail->From     = $email_remintente;//"conductores@ieps.gob.ec";//"compras@ieps.gob.ec";
	$mail->FromName = $email_remintente_nombre;//"IEPS compras";
	$mail->Subject  = $email_asunto;//"PHPMailer Test Subject via smtp";

	$sqlDespartamento = "select * from departamento where activo = 1";
	$resDepartamento = query($sqlDespartamento);
	$arrayDepartamentos = array();
	while($filaDepartamento = mysql_fetch_array($resDepartamento))
	{
		array_push($arrayDepartamentos, $filaDepartamento['iddepartamento']);
	}
	// $arrayInfoJefes = array();
	// foreach ($arrayDepartamentos as $valor) 
	// {
	// 	$sqlJefes = "select nombre, apellido, correo from persona where perfil = 1 and iddepartamento = ". $valor;
	// 	$resJefes = query($sqlJefes);
	// 	while($filaJefes = mysql_fetch_array($resJefes))
	// 	{
	// 		array_push($arrayInfoJefes, $valor);
	// 		array_push($arrayInfoJefes, $filaJefes['apellido'] . " " . $filaJefes['nombre']);
	// 		array_push($arrayInfoJefes, $filaJefes['correo']);
	// 	}
	// }

	// // print_r($arrayInfoJefes);

	// $tamInfoJefes = count($arrayInfoJefes);
	
	foreach ($arrayDepartamentos as $valor) 
	{
		$message = '<html><body><h2>ALERTA DE ACTIVIDADES EN SU DEPARTAMENTO</h2>';	
		$message .= '<table border="0" cellpadding="20" cellspacing="0" width="1200" id="emailContainer" style=style="border: 1px solid black;">';
		$message .= '<tr>
						<td style="border: 1px solid black;">Indice</td>
						<td style="border: 1px solid black;">Responsable</td>
						<td style="border: 1px solid black;">Correo</td>
						<td style="border: 1px solid black;">Actividad</td>
						<td style="border: 1px solid black;">Fecha Limite</td>
						<td style="border: 1px solid black;">Estado</td>
					</tr>';

		# code...
	
		$sqlMensaje = "select * from actividad where estado = 1 and iddepartamento = " . $valor;
		$resMensaje = query($sqlMensaje);
		$numFilas = mysql_num_rows($resMensaje);
		// echo $numFilas . "<br>";
		if($numFilas > 0)
		{
			$indice = 0;
			while($filaMensaje = mysql_fetch_array($resMensaje))
			{
				$indice++;
				$actividadDescripcion = $filaMensaje['descripcion'];
				$idResponsable = $filaMensaje['idpersona'];
				$arrayResponsable = GetInfoResponsable($idResponsable);
				// print_r($arrayResponsable);
				$nombreResponsable = $arrayResponsable[0];
				$correoResponsable = $arrayResponsable[1];
				$correoJefeResponsable = $arrayResponsable[2];
				$estado = 'Abierto';
				if($filaMensaje['estado'] == 2)
				{
					$estado = 'Cerrado';
				}

				$message .= "<tr>
								<td style='border: 1px solid black;'>" . $indice ."</td>
								<td style='border: 1px solid black;'>" . $nombreResponsable ."</td>
								<td style='border: 1px solid black;'>" . $correoResponsable ."</td>
								<td style='border: 1px solid black;'>" . $actividadDescripcion ."</td>
								<td style='border: 1px solid black;'>" . $filaMensaje['fechaFin'] ."</td>
								<td style='border: 1px solid black;'>" . $estado ."</td>
							</tr>";				

			}

			$message .= "</table>";
			$message .= "</body></html>";
			$mail->MsgHTML($message);		
			$mail->ClearAddresses();
			$mail->AddAddress($correoJefeResponsable,"ACTIVIDADES PENDIENTES");
			if(!$mail->Send()) 
			{
			  echo "Mailer Error: " . $mail->ErrorInfo;
			  
			} 
			else 
			{
			//  echo "Message sent!";
			  echo "Enviada Alerta";
			} 	
		}
	}
		
	

		

}

function GetInfoResponsable($idResponsable)
{
	$sqlResponsable = "select nombre, apellido, correo, idpersonajefe from persona where idpersona = " . $idResponsable;
	$resResponsable = query($sqlResponsable);
	$nombres = '';
	$arrayInfo = array();
	while($filaResponsable = mysql_fetch_array($resResponsable))
	{
		$nombres = $filaResponsable['apellido'] . " " . $filaResponsable['nombre'];
		$correo = $filaResponsable['correo'];
		$idjefe = $filaResponsable['idpersonajefe'];
		$correoJefe = GetInfoJefe($idjefe);
		array_push($arrayInfo, $nombres);
		array_push($arrayInfo, $correo);
		array_push($arrayInfo, $correoJefe);
	}
	// print_r($arrayInfo);
	return $arrayInfo;
}

function GetInfoJefe($idJefe)
{
	$sqlJefe = "select correo from persona where idpersona = '" . $idJefe . "'";
	// echo $sqlJefe . "<br>";
	$resJefe = query($sqlJefe);
	$correoJefe = '';
	while($filaJefe = mysql_fetch_array($resJefe))
	{
		$correoJefe = $filaJefe['correo'];
	} 
	return $correoJefe;
}



?>