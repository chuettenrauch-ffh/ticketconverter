<?php

//register Autoloader class
$path = dirname(__FILE__);
require_once $path . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Autoloader.php';
spl_autoload_register(array('Autoloader', 'load'));


//set current ApplicationPath in Config Singleton
$config = F4h_TicketConverter_Config::getInstance();
$config->setApplicationPath(dirname(dirname(__FILE__)));
//set Environment & $_POST array
$config->setEnvironment(F4h_TicketConverter_Config::ENV_WEB);

//register Exception Handler for uncaught exception
set_exception_handler('F4h_TicketConverter_Log_Exception_Handler::trap');
//register Error Handler
/**
 * @todo funktioniert der?
 */
set_error_handler('F4h_TicketConverter_Log_Error_Handler::trap', E_ALL);

?>

