<?php
/**
 * Framework package
 * 
 * @package   Framework
 * @author    Peter Gribanov <info@peter-gribanov.ru>
 * @copyright Copyright (c) 2012, Peter Gribanov
 * @license   http://opensource.org/licenses/GPL-3.0 GPL v3
 */

require dirname(__DIR__).'/autoload.php';

$request = \Framework\Request::buildFromGlobal();
$app = new \Framework\AppCore();
$app->setRequest($request);
$response = $app->execute();

// обход политик IE
if (!$request->isCli()) {
	$response->addHeader('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
}

$response->transmit();