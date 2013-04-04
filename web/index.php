<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

require dirname(__DIR__).'/autoload.php';

$app = new \Framework\AppCore();
$app->setRequest(\Framework\Request::buildFromGlobal());
$response = $app->execute();
// обход политик IE
$response->addHeader('P3P', 'CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
$response->transmit();
