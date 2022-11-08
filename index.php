<?php
declare(strict_types=1);
session_start();

use DB\ConnectionFactory;
use Dispatch\Dispatcher;

require './vendor/autoload.php';

ConnectionFactory::setConfig("config.ini");

try{
    $dispatcher = new Dispatcher();
    $dispatcher->run();
}catch (Exception $exception){
    echo $exception->getTraceAsString()."<br>".$exception->getMessage();
}catch (Error $error){
    echo $error->getTraceAsString()."<br>".$error->getMessage();
}






