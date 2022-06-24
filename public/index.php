<?php

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Delloys\Auth\controller\controllerClass;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$loader = new FilesystemLoader(dirname(__DIR__) . "/src/template/");
$twig = new Environment($loader);
$cntrl = new controllerClass($twig);

$cntrl->goToPage();
