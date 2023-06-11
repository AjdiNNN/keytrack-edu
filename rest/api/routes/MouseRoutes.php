<?php
Flight::route('POST /mouse', function(){
    $data = Flight::request()->data->getData();
    Flight::halt(Flight::mouseService()->add($data));
});
?>