<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']))
{
	die(json_encode(array(
			"estado"=>"error",
			"clave"=>"valores",
			"explicacion"=>"Faltan valores"
	)));
} 
try
{
  $email = SafeString::safe($_POST['email']);
  $user  = new User();
  $user->sendLogOut($email);
  echo json_encode(array(
  		"estado"=>"ok"
  ));
} 
catch(errorWithDatabaseException $e)
{
	echo json_encode(array(
			"estado"=>"error",
			"clave"=>"db",
			"explicacion"=>$e->getMessage()
	));
} 
?>