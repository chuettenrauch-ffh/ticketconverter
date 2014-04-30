<?php

//register Autoloader class
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Autoloader.php';
spl_autoload_register(array('Autoloader', 'load'));


//set current ApplicationPath in Config Singleton
$config = F4h_TicketConverter_Config::getInstance();
$config->setApplicationPath(dirname(__FILE__));

//register Exception Handler for uncaught exception
set_exception_handler('F4h_TicketConverter_Log_Exception_Handler::trap');

//register Error Handler
/**
 * @todo funktioniert der?
 */
set_error_handler('F4h_TicketConverter_Log_Error_Handler::trap', E_ALL);

if (PHP_SAPI === 'cli') {
	F4h_TicketConverter_Config::getInstance()->setEnvironment(F4h_TicketConverter_Config::ENV_CLI);
} else {
	F4h_TicketConverter_Config::getInstance()->setEnvironment(F4h_TicketConverter_Config::ENV_WEB);
}

//invoke Convertingprocess
F4h_TicketConverter_Runner::go();
?>
