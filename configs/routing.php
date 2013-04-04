<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

return array(
	'home' => array(
		'pattern'    => '/',
		'controller' => 'Home::index',
		//'template'   => 'Home/index.html.tpl',
		'present'    => 'html'
	),
	'home_invite' => array(
		'pattern'    => '/invite.html',
		'controller' => 'Home::invite',
		'present'    => 'html'
	),
);