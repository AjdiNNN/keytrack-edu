<?php

Flight::route('POST /session', function(){
    $data = Flight::request()->data->getData();
    $data['user_id'] = Flight::get('user')['id'];
    Flight::json(Flight::sessionService()->add($data));
});

Flight::route('GET /longestsession', function(){
    Flight::json(Flight::sessionService()->get_longest_session(Flight::get('user')['id']));
});

Flight::route('GET /earlystart', function(){
    Flight::json(Flight::sessionService()->get_early_start_session(Flight::get('user')['id']));
});

Flight::route('GET /latestend', function(){
    Flight::json(Flight::sessionService()->get_latest_end_session(Flight::get('user')['id']));
});

Flight::route('GET /mostunique', function(){
    Flight::json(Flight::sessionService()->get_unique_session(Flight::get('user')['id']));
});

Flight::route('GET /usersessions', function(){
    Flight::json(Flight::sessionService()->get_sessions(Flight::get('user')['id']));
});

Flight::route('GET /activeday', function(){
    Flight::json(Flight::sessionService()->get_most_active_day(Flight::get('user')['id']));
});
  
Flight::route('GET /totalsessions', function(){
    Flight::json(["data" => Flight::sessionService()->get_total_sessions(Flight::get('user')['id'])]);
});

Flight::route('GET /averagesessions', function(){
    Flight::json(["data" => Flight::sessionService()->get_average_session(Flight::get('user')['id'])]);
});

Flight::route('GET /session/@id', function($id){
    $actualOwner = Flight::sessionService()->get_session_owner($id);
    if($actualOwner['user_id'] != Flight::get('user')['id'])
        return;
    $mouseData = Flight::mouseService()->get_mouse_from_session($id);
    $keyboardData = Flight::keyboardService()->get_keyboard_from_session($id);
    Flight::json(["mouse" => $mouseData, "keyboard" => $keyboardData]);
});
?>