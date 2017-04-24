<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Cleaner.php";
header('Content-Type: text/html; charset=utf8');

if (!isset($_POST['serviceId']) || !isset($_POST['statusId']) || !isset($_POST['token']) 
		|| !isset($_POST['cancelCode']))
  die(json_encode(array("Status"=>"ERROR missing values")));
 
try{ 
  $serviceId = SafeString::safe($_POST['serviceId']);
  $statusId = SafeString::safe($_POST['statusId']);
  $cancelCode = SafeString::safe($_POST['cancelCode']);
  $token = SafeString::safe($_POST['token']);
  try{
  	$user  = new User();
  	$info = $user->userHasToken($token);
  } catch (noSessionFoundException $e){
  	$cleaner  = new Cleaner();
  	$info = $cleaner->readCleanerData($token);
  }
  $service  = new Service();
  $service->changeServiceStatus($serviceId, $statusId, $cancelCode);
  echo json_encode(array("Status"=>"OK"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR DB". $e->getMessage()));
} catch (noSessionFoundException $e){
	echo json_encode(array("Status" => "SESSION ERROR"));
} catch (serviceCantBeCanceled $e){
	echo json_encode(array("Status" => "CANCEL ERROR"));
}
?>