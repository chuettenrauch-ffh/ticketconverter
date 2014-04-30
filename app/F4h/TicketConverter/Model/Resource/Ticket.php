<?php
/**
 * Class F4h_TicketConverter_Model_Resource_Ticket
 */
class F4h_TicketConverter_Model_Resource_Ticket extends F4h_TicketConverter_Model_Resource_Abstract
{
	const TABLENAME = 'tickets_printed';

	/**
	 *
	 */
	protected function _save()
	{
		$connection = $this->_getConnection();
		
		$ticket = $this->_getModel();
		try {
		$query = $connection->prepare('INSERT INTO ' . self::TABLENAME . ' (project, ticket_id) VALUES (:project, :ticket_id);');
//		$query->bindParam(':table', self::TABLENAME);
		$query->bindParam(':project', $ticket->getProject());
		$query->bindParam(':ticket_id', $ticket->getId());
		$query->execute();
		} catch (Exception $e) {
			print_r($e->getMessage());
		}
	}

	/**
	 * does nothing
	 */
	protected function _delete()
	{
		
	}

	/**
	 * @return PDO
	 */
	protected function _getConnection()
	{
		return F4h_TicketConverter_Model_Db_Sqlite::getConnection();
	}

}
