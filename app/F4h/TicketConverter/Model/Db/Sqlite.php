<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
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

/**
 * Class F4h_TicketConverter_Model_Db_Sqlite
 */
class F4h_TicketConverter_Model_Db_Sqlite
{

	const DBPATH = 'db/ticketconverter.db';

	static protected $_connection = null;

	/**
	 * connects to the sqlite database
	 *
	 * @return PDO
	 */
	static public function getConnection()
	{
		if (!self::$_connection) {
			self::$_connection = new PDO('sqlite:' . self::_getConnectionString());
			self::$_connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

		}
		return self::$_connection;
	}

	/**
	 * gives path to sqlite database
	 *
	 * @return string
	 */
	static protected function _getConnectionString()
	{
		$config = F4h_TicketConverter_Config::getInstance();
		$path = $config->getApplicationPath() . self::DBPATH;
		return $path;
	}
}