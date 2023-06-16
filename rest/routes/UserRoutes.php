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
Flight::route('GET /totalsessions', function(){
  Flight::json(["data" =>  Flight::userDao()->get_total_sessions(Flight::get('user')['id'])]);
});
Flight::route('GET /averagesessions', function(){
  Flight::json(["data" =>  Flight::userDao()->get_average_session(Flight::get('user')['id'])]);
});
Flight::route('GET /totalkeyboard', function(){
  Flight::json(["data" =>  Flight::userDao()->get_total_keyboard(Flight::get('user')['id'])]);
});
Flight::route('GET /totalmouse', function(){
  Flight::json(["data" =>  Flight::userDao()->get_total_mouse(Flight::get('user')['id'])]);
});
Flight::route('GET /newlines', function(){
  Flight::json(["data" =>  Flight::userDao()->get_total_new_lines(Flight::get('user')['id'])]);
});
Flight::route('GET /mostfrequent', function(){
  Flight::json(Flight::userDao()->get_most_frequenet_button(Flight::get('user')['id']));
});
Flight::route('GET /activeday', function(){
  Flight::json(Flight::userDao()->get_most_active_day(Flight::get('user')['id']));
});
Flight::route('GET /distance', function(){
  Flight::json(Flight::userDao()->get_total_mouse_movement_distance(Flight::get('user')['id']));
});
Flight::route('GET /timebetweenpress', function(){
  Flight::json(Flight::userDao()->get_average_time_between_press(Flight::get('user')['id']));
});
Flight::route('GET /session/@id', function($id){
  $actualOwner = Flight::userDao()->get_session_owner($id);
  if($actualOwner['user_id'] != Flight::get('user')['id'])
    return;
  $mouseData = Flight::userDao()->get_mouse_from_session($id);
  $keyboardData = Flight::userDao()->get_keyboard_from_session($id);
  Flight::json(["mouse" => $mouseData, "keyboard" => $keyboardData]);
});
Flight::route('GET /longestsession', function(){
  Flight::json(Flight::userDao()->get_longest_session(Flight::get('user')['id']));
});
Flight::route('GET /earlystart', function(){
  Flight::json(Flight::userDao()->get_early_start_session(Flight::get('user')['id']));
});
Flight::route('GET /latestend', function(){
  Flight::json(Flight::userDao()->get_latest_end_session(Flight::get('user')['id']));
});
Flight::route('GET /mostunique', function(){
  Flight::json(Flight::userDao()->get_unique_session(Flight::get('user')['id']));
});
Flight::route('GET /usersessions', function(){
  Flight::json(Flight::userDao()->get_sessions(Flight::get('user')['id']));
});
?>