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
