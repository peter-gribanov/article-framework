<?php
return array(
	array(
		'pattern' => '/',
		'action'  => 'Home::index',
		'layout'  => array('html.tpl', 'layout.tpl', 'home.tpl'),
		'present' => 'html'
	),
);