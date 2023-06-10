<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/services/SessionService.class.php';
require_once __DIR__.'/services/MouseService.class.php';
require_once __DIR__.'/services/KeyboardService.class.php';
require_once __DIR__.'/dao/UserDao.class.php';

Flight::register('userDao', 'UserDao');
Flight::register('sessionService', 'SessionService');
Flight::register('mouseService', 'MouseService');
Flight::register('keyboardService', 'KeyboardService');



/* utility function for reading query parameters from URL */
Flight::map('query', function($name, $default_value = NULL){
$request = Flight::request();
$query_param = @$request->query->getData()[$name];
$query_param = $query_param ? $query_param : $default_value;
return urldecode($query_param);
});

Flight::route('/*', function(){
  //return TRUE;
  //perform JWT decode
  $path = Flight::request()->url;
  if ($path == '/login' || $path == '/register') return TRUE; // exclude login route from middleware

  $headers = getallheaders();
  if (@!$headers['Authorization']){
    Flight::json(["message" => "Authorization is missing"], 403);
    return FALSE;
  }else{
    try {
      $decoded = (array)JWT::decode($headers['Authorization'], new Key(Config::JWT_SECRET(), 'HS256'));
      Flight::set('user', $decoded);
      return TRUE;
    } catch (\Exception $e) {
      Flight::json(["message" => "Authorization token is not valid"], 402);
      return FALSE;
    }
  }
});

require_once __DIR__.'/routes/SessionRoutes.php';
require_once __DIR__.'/routes/KeyboardRoutes.php';
require_once __DIR__.'/routes/MouseRoutes.php';
require_once __DIR__.'/routes/UserRoutes.php';
Flight::start();

?>