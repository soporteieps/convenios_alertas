<?php session_start(); 
function query($sql)
{
	$server = "10.2.74.50";//Dirección del servidor
	$user = "root";
	$pwd = "n1md0gm11i3p5";
	$basedatos = "formularios";//Base de datos a ser utilizada
	$connect = mysql_connect($server,$user,$pwd);
       mysql_set_charset('utf8', $connect); 
	mysql_select_db($basedatos, $connect);
	$result=mysql_query ($sql,$connect);//Realizo el query
	return ($result);//Retorno el resultado del query*/
	
}



?>