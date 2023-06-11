<?php

Flight::route('POST /mouse', function(){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::mouseService()->add($data));
});
?>