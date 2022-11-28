<?php

header('Content-Type:application/json');
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

 require_once __DIR__ . '/../vendor/autoload.php';  
 use App\Router\Router;

 $routerInit = new Router();
 $apiResponse = $routerInit->router();

 echo json_encode($apiResponse);
?>