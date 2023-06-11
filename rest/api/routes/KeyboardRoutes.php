<?php
Flight::route('POST /keyboard', function(){
    $data = Flight::request()->data->getData();
    Flight::halt(Flight::keyboardService()->add($data));
});
?>