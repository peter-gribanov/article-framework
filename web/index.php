<?php
/**
 * Framework package
 * 
 * @package Framework
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

// обход политик IE
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

require realpath(__DIR__.'/../autoload.php');

$app = new \Framework\AppCore();
$app->setRequest(\Framework\Request::buildFromGlobal());
$response = $app->execute();
$response->transmit();