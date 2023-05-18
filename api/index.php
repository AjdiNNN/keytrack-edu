<?php
require 'flight/Flight.php';
require 'vendor/autoload.php';

// middleware method for login
Flight::route('/*', function(){
    echo 'hello world';
});

Flight::start();
?>