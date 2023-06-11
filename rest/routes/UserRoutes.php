<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

Flight::route('POST /login', function(){
    $login = Flight::request()->data->getData();
    $user = Flight::userDao()->get_user_by_username($login['username']);
    if (isset($user['id'])){
      if(password_verify($login['password'],$user['password'])){
        unset($user['password']);
        $jwt = JWT::encode($user, Config::JWT_SECRET(), 'HS256');
        Flight::json(['token' => $jwt]);
      }else{
        Flight::json(["message" => "Wrong password"], 404);
      }
    }else{
      Flight::json(["message" => "User doesn't exist"], 405);
    }
});

Flight::route('POST /register', function(){
  $data = Flight::request()->data->getData();
  if(Flight::userDao()->get_user_by_username($data['username']))
  {
    Flight::json(["message" => "Username already registered"], 500);
  }
  else if(Flight::userDao()->get_user_by_email($data['email']))
  {
    Flight::json(["message" => "Email already registered"], 500);
  }
  else{
    $data['email']  = strtolower($data['email']);
    $data['password'] = password_hash($data['password'], PASSWORD_ARGON2I);
    Flight::userDao()->add($data);
    Flight::json(["message"=>"registered"]);
  }
});

?>