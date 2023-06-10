<?php
Flight::route('POST /keyboard', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::keyboardService()->add($data));
});
?>