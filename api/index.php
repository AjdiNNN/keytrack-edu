<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/services/SessionService.class.php';

Flight::register('sessionService', 'SessionService');

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

Flight::route('/*', function(){
  return TRUE; 
});

require_once __DIR__.'/routes/SessionRoutes.php';
Flight::start();

?>