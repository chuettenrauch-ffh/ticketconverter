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
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
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
 * Class F4h_TicketConverter_Log_Error_Handler
 */
class F4h_TicketConverter_Log_Error_Handler
{
	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 * @throws F4h_TicketConverter_Log_Error
	 */
	public static function trap($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
			case E_ERROR:
			case E_USER_ERROR:
				throw new F4h_TicketConverter_Log_Error(0, $errstr, $errfile, $errline);
				break;
			default:
				throw new F4h_TicketConverter_Log_Error(3, $errstr, $errfile, $errline);
				break;
		}

		if (F4h_TicketConverter_Config::LOGGING === 1) {
			self::log($errno, $errstr, $errfile, $errline);
		}
	}

	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 */
	public static function log($errno, $errstr, $errfile, $errline)
	{
		$level = '';
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
			case E_STRICT:
				$level = 'NOTICE';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$level = 'WARNING';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$level = 'FATAL';
				break;
			default:
				$level = 'UNKNOWN ERROR';
				break;
		}

		$timestamp = date(DATE_ATOM, time());
		$string = $timestamp . PHP_EOL . $level . ': ' . $errstr . 'at' . $errfile . ':' . $errline . PHP_EOL . PHP_EOL;
	}

}