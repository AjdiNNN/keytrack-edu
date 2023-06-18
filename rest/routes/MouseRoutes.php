<?php

Flight::route('POST /mouse', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::mouseService()->add($data));
});


Flight::route('GET /totalmouse', function(){
    Flight::json(["data" => Flight::mouseService()->get_total_mouse(Flight::get('user')['id'])]);
});

Flight::route('GET /distance', function(){
    Flight::json(Flight::mouseService()->get_total_mouse_movement_distance(Flight::get('user')['id']));
});
?>