<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter-tests
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Tino Stöckel <tino.stoeckel@fashionforhome.de>
 *
 * @copyright (c) 2012 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 30.10.2015
 * Time: 01:30
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

//register Autoloader class
$path = dirname(__FILE__);
require_once $path . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Autoloader.php';
spl_autoload_register(array('Autoloader', 'load'));


//set current ApplicationPath in Config Singleton
$config = F4h_TicketConverter_Config::getInstance();
$config->setApplicationPath(dirname(dirname(__FILE__)));
//set Environment & $_POST array
$config->setEnvironment(F4h_TicketConverter_Config::ENV_CLI);
$argv['s'] = '';
$argv['i'] = '4114';
$_SERVER['argv'] = $argv;

$arguments = getopt('h::i:s');
echo var_dump($_SERVER['argv']);


//register Exception Handler for uncaught exception
set_exception_handler('F4h_TicketConverter_Log_Exception_Handler::trap');
//register Error Handler
/**
 * @todo funktioniert der?
 */
set_error_handler('F4h_TicketConverter_Log_Error_Handler::trap', E_ALL);
?>
