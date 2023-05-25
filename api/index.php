<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/services/SessionService.class.php';
require_once __DIR__.'/services/MouseService.class.php';
require_once __DIR__.'/services/KeyboardService.class.php';

Flight::register('sessionService', 'SessionService');
Flight::register('mouseService', 'MouseService');
Flight::register('keyboardService', 'KeyboardService');

Flight::map('error', function(Exception $ex){
  // Handle error
  Flight::json(['message' => $ex->getMessage()], 500);
});

/* utility function for reading query parameters from URL */
Flight::map('query', function($name, $default_value = NULL){
$request = Flight::request();
$query_param = @$request->query->getData()[$name];
$query_param = $query_param ? $query_param : $default_value;
return urldecode($query_param);
});

Flight::route('/*', function()
{
  $headers = getallheaders();
  if (!array_key_exists('Authorization', $headers) && !array_key_exists('authorization', $headers)){
    Flight::json(["message" => "Authorization is missing"], 403);
    return FALSE;
  }
  if($headers['Authorization'] != Config::PASSCODE()){
    Flight::json(["message" => "Authorization key is wrong"], 403);
    return FALSE;
  }
  return TRUE;
});

require_once __DIR__.'/routes/SessionRoutes.php';
require_once __DIR__.'/routes/KeyboardRoutes.php';
require_once __DIR__.'/routes/MouseRoutes.php';
Flight::start();

?>