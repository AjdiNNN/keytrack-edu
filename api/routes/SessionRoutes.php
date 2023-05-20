<?php

Flight::route('POST /session', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::sessionService()->add($data));
});

?>