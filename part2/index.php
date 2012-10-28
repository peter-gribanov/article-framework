<?php
require 'framework.php';

$factory = new \Framework\Factory(__DIR__);

$controller = !empty($_GET['controller']) ? $_GET['controller'] : 'Home';
$action     = !empty($_GET['action'])     ? $_GET['action']     : 'index';

// инициализация контроллера
$controller_class = '\Framework\Controller\\'.$controller;
$controller = new $controller_class($factory->getModel());
$action_name = '__'.$action;
// вызов экшена
$data = $controller->$action_name();

// отрисовка результата
echo $factory->View()->assign($data)->render(array('html.tpl','layouts/simple.tpl','home/index.tpl'), true);