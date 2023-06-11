<?php
Flight::route('POST /session', function(){
    $data = Flight::request()->data->getData();
    $data['user_id'] = Flight::get('user')['id'];
    Flight::halt(Flight::sessionService()->add($data));
});
Flight::route('PUT /session/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::halt(Flight::sessionService()->update($id,$data));
});
?>