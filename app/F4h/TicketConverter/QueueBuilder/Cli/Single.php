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
class F4h_TicketConverter_QueueBuilder_Cli_Single extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{
	/**
	 * @return array
	 * @throws F4h_TicketConverter_QueueBuilder_Exception
	 */
	public function getIds()
	{
		$input = $this->getInputArgs();
		$ids = $input['ids'];
		$project = $input['project'];
		if (is_array($ids)) {
			foreach ($ids as $entry) {
				if (!is_numeric($entry)) {
					F4h_TicketConverter_Runner::getMsgContainer()->
						push(new F4h_TicketConverter_Model_Message("FAILURE: '" . $entry . "' expected to be numeric. input ignored."));
					continue;
				}
				$this->ids[] = array('project' => $project, 'ticket_id' => $entry);
			}
		} else {
			if (!is_numeric($ids)) {
				throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: '" . $ids . "' expected to be numeric.");
			}
			$this->ids[] = array('project' => $project, 'ticket_id' => $entry);
		}

		if (empty($this->ids)) {
			throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: no valid ids found");
		}
		return $this->ids;
	}

}