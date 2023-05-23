<?php

Flight::route('POST /session', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::sessionService()->add($data));
});

Flight::route('PUT /session/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::sessionService()->update($id,$data));
});

?>