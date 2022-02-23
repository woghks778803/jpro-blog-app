<?php

include_once __DIR__."/autoload_psr4.php";
use JProfile\Psr4AutoloaderClass;

$psr4Autoloader = new Psr4AutoloaderClass();
$psr4Autoloader->addNamespace("JProfile\Classes", __DIR__."/classes");
$psr4Autoloader->addNamespace("JProfile\Repository", __DIR__."/repository");

$psr4Autoloader->register();