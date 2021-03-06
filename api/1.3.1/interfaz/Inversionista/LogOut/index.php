<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Inversionista.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['email']))
  die(json_encode(array("Status"=>"ERROR missing values")));
  
try{
  $email = SafeString::safe($_POST['email']);
  $investor  = new Investor();
  $investor->sendLogOut($email);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB"));
} 
?>