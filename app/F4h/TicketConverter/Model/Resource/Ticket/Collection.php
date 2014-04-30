<?php
/**
 * Class F4h_TicketConverter_Model_Resource_Ticket_Collection
 */
class F4h_TicketConverter_Model_Resource_Ticket_Collection extends F4h_TicketConverter_Model_Resource_Collection
{

	const TABLENAME = 'tickets_printed';
	protected $_columns = array('project', 'ticket_id');

	/**
	 * @return mixed
	 */
	protected function _load()
	{
		$connection = $this->getConnection();
		$query = $connection->prepare($this->_getQueryString());
		$query->execute();

		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/**
	 * @return PDO
	 */
	protected function _getConnection()
	{
		return F4h_TicketConverter_Model_Db_Sqlite::getConnection();
	}

}