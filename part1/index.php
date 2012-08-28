<?php
require_once dirname(__FILE__).'/Framework/Factory.php';
$factory = new \Framework\Factory(dirname(__FILE__));

$controller = !empty($_GET['controller']) ? $_GET['controller'] : 'Home';
$action     = !empty($_GET['action'])     ? $_GET['action']     : 'index';

// инициализация контроллера
$controller_class = '\Framework\Controller\\'.$controller;
$controller = new $controller_class($factory->getModel());
$action_name = '__'.$action;
// вызов экшена
$data = $controller->$action_name();

// отрисовка результата
echo $factory->View()->render('home.tpl', $data);