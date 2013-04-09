<?php
/**
 * Framework package
 *
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
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
	'home_update' => array(
		'pattern'    => '/update.json',
		'controller' => 'Home::update',
		'present'    => 'json'
	),
	'home_update_cli' => array(
		'pattern'    => 'update',
		'controller' => 'Home::updateCli',
		'present'    => 'cli'
	),
);