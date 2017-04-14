<?php

//detect mobile

require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();
$ismobile = $detect->isMobile();

$istablet = $detect->isTablet();
$UA = htmlentities($_SERVER['HTTP_USER_AGENT']);

?>