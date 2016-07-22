<?php
require_once dirname(__FILE__)."/../../../DBConnect/SafeString.php";
require_once dirname(__FILE__)."/../../../DBConnect/User.php";
require_once dirname(__FILE__)."/../../../DBConnect/Car.php";
require_once dirname(__FILE__)."/../../../DBConnect/Service.php";
require_once dirname(__FILE__)."/../../../DBConnect/Payment.php";

if (!isset($_POST['email']) || !isset($_POST['password']))
  die(json_encode(array("Satus"=>"ERROR missing values")));


try{
  $email = SafeString::safe($_POST['email']);
  $password = SafeString::safe($_POST['password']);
  $user  = new User();
  $car  = new Car();
  $service  = new Service();
  $userInfo = $user->sendLogIn($email, $password);
  $clientId = $userInfo['idCliente'];
  $carsList = $car->getCarsList($clientId);
  $servicesHistory = $service->getHistory($clientId,1);
  echo json_encode(array("Status"=>"OK","User Info"=>$userInfo,
                         "carsList"=>$carsList,"History"=>$servicesHistory,"cards" => Payment::readClient($clientId)));
} catch(userNotFoundException $e)
{
  echo json_encode(array("Status"=>"ERROR user"));
} catch(errorWithDatabaseException $e)
{
  echo json_encode(array("Status"=>"ERROR database"));
} catch(carsNotFoundException $e)
{
  echo json_encode(array("Status"=>"OK","carsList"=>null));
}

?>