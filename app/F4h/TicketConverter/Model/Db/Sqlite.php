<?php
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