<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Claudia Hüttenrauch <claudia.hüttenrauch@fashionforhome.de>
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

/**
 * Class F4h_TicketConverter_Log_Exception_Handler
 */
class F4h_TicketConverter_Log_Exception_Handler
{
	const FATAL = 0;
	const WARNING = 1;
	const NOTICE = 2;
	const UNKNOWN = 3;

	/**
	 * @param Exception $exception
	 */
	public static function trap(Exception $exception)
	{
		$container = F4h_TicketConverter_Log_Container::getInstance();

		$code = $exception->getCode();
		switch ($code) {
			case (self::FATAL):
				$container->addFatal($exception);
				break;
			case (self::WARNING):
				$container->addWarning($exception);
				break;
			case (self::NOTICE):
				$container->addNotice($exception);
				break;
			case (self::UNKNOWN):
				$container->addUnknown($exception);
				break;
		}

		if (F4h_TicketConverter_Config::LOGGING === 1) {
			self::log($exception);
		}
	}

	/**
	 * @param Exception $exception
	 */
	public static function log(Exception $exception)
	{
		$logFile = F4h_TicketConverter_Config::getInstance()->getLogFile();

		$level = '';
		switch ($exception->getCode()) {
			case (self::FATAL):
				$level = 'FATAL';
				break;
			case (self::WARNING):
				$level = 'WARNING';
				break;
			case (self::NOTICE):
				$level = 'NOTICE';
				break;
			default:
				$level = 'UNKNOWN EXCEPTION';
				break;
		}

		$timestamp = date(DATE_ATOM, time());
		$string = $timestamp . PHP_EOL . $level . ': ' . $exception->__toString() . PHP_EOL . PHP_EOL;
		file_put_contents($logFile, $string, FILE_APPEND);
	}

	/**
	 * @param Exception $exception
	 */
	public static function printOut(Exception $exception)
	{

	}

}

?>
