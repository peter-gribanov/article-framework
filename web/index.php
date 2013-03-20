<?php
/**
 * Microsoft package
 * 
 * @package Microsoft
 * @author  Peter Gribanov <gribanov@professionali.ru>
 */

// обход политик IE
header('P3P: CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

require '../autoload.php';

$app = new \Microsoft\AppCore();
$app->setRequest(\Microsoft\Request::buildFromGlobal());
$response = $app->execute();
$response->transmit();