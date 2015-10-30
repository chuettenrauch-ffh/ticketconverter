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
 * Class F4h_TicketConverter_Data_Serializer
 */
class F4h_TicketConverter_Data_Serializer
{
	protected $ticketQueue;
	protected $buffer;

	/**
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * @return mixed
	 */
	protected function getTicketQueue()
	{
		return $this->ticketQueue;
	}

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 * @return $this
	 */
	protected function setTicketQueue(F4h_TicketConverter_Model_Queue $queue)
	{
		$this->ticketQueue = $queue;
		return $this;
	}

	/**
	 * @return XMLWriter
	 */
	protected function getBuffer()
	{
		if (!$this->buffer) {
			$this->buffer = new XMLWriter();
			$this->buffer->openMemory();
		}
		return $this->buffer;
	}

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 * @return string
	 */
	public function serialize(F4h_TicketConverter_Model_Queue $queue)
	{
		$this->setTicketQueue($queue);

		$buffer = $this->getBuffer();
		$buffer->writeRaw('<?xml version="1.0" encoding="utf-8"?>');
		$buffer->startElement('tickets');

		foreach ($queue as $ticket) {
			$buffer->startElement('ticket');
			$this->createTicketElement($ticket);
			$buffer->endElement();

			F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message($ticket->getKey(), F4h_TicketConverter_Model_Message::SUCCESS));
		}

		$buffer->endElement();

		return $buffer->outputMemory();
	}

	/**
	 * @param $ticket
	 */
	protected function createTicketElement($ticket)
	{
		$buffer = $this->getBuffer();

		$buffer->writeElement('key', $ticket->getKey());
		$buffer->writeElement('assignee', $ticket->getAssignee());
		$buffer->writeElement('reporter', $ticket->getReporter());
		$buffer->writeElement('type', $ticket->getType());
		$buffer->writeElement('hasSubtasks', $ticket->getHasSubtasks() ? 1 : 0);
		$buffer->writeElement('summary', $ticket->getSummary());
		$buffer->writeElement('devteam', $ticket->getDevTeam());
		$buffer->writeElement('storypoints',$ticket->getStorypoints());
		$buffer->writeElement('sprintname',$ticket->getSprintname());

		if ($parent = $ticket->getParent()) {
			$buffer->startElement('parent');
			$buffer->writeElement('key', $parent->getKey());
			$buffer->writeElement('summary', $parent->getSummary());
			$buffer->endElement();
		}

		if ($epic = $ticket->getEpic()) {
			$buffer->startElement('epic');
			$buffer->writeElement('key', $epic->getKey());
			$buffer->writeElement('name', $epic->getEpicname());
			$buffer->endElement();
		}
	}
}
