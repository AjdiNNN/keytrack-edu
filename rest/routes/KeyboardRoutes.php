<?php

Flight::route('POST /keyboard', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::keyboardService()->add($data));
});

Flight::route('GET /newlines', function(){
    Flight::json(["data" => Flight::keyboardService()->get_total_new_lines(Flight::get('user')['id'])]);
});

Flight::route('GET /totalkeyboard', function(){
    Flight::json(["data" => Flight::keyboardService()->get_total_keyboard(Flight::get('user')['id'])]);
});

Flight::route('GET /mostfrequent', function(){
    Flight::json(Flight::keyboardService()->get_most_frequent_button(Flight::get('user')['id']));
});

Flight::route('GET /timebetweenpress', function(){
    Flight::json(Flight::keyboardService()->get_average_time_between_press(Flight::get('user')['id']));
});
?>