<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

return array(
	array(
		'pattern'    => '/',
		'controller' => 'Home::index',
		'templates'  => array('html.html.tpl', 'layout.html.tpl', 'home.html.tpl'),
		'present'    => 'html'
	),
);