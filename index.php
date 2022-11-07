<?php
declare(strict_types=1);
session_start();

use DB\ConnectionFactory;
use Dispatch\Dispatcher;

require './vendor/autoload.php';

ConnectionFactory::setConfig("config.ini");

$dispatcher = new Dispatcher();
$dispatcher->run();






