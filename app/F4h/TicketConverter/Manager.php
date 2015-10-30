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
 * Class F4h_TicketConverter_Manager
 */
class F4h_TicketConverter_Manager
{

	/**
	 *
	 * @var F4h_TicketConverter_Model_Queue (containing Ids)
	 */
	protected $idQueue;

	/**
	 *
	 * @var F4h_TicketConverter_Model_Queue (containing F4h_TicketConverter_Model_Ticket)
	 */
	protected $ticketQueue;

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 */
	public function __construct(F4h_TicketConverter_Model_Queue $queue)
	{
		$this->setIdQueue($queue);
		return $this;
	}

	/**
	 * @param $queue
	 * @return $this
	 */
	protected function setIdQueue($queue)
	{
		$this->idQueue = $queue;
		return $this;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	protected function getIdQueue()
	{
		return $this->idQueue;
	}

	/**
	 * @param $queue
	 * @return $this
	 */
	protected function setTicketQueue($queue)
	{
		$this->ticketQueue = $queue;
		return $this;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	public function getTicketQueue()
	{
		if (!$this->ticketQueue) {
			$mapper = new F4h_TicketConverter_Data_Mapper($this->getIdQueue());
			$this->ticketQueue = $mapper->map();
		}
		return $this->ticketQueue;
	}

	/**
	 * @return bool
	 */
	public function buildOutput()
	{
		$responseQueue = $this->getTicketQueue();
		if (count($responseQueue) == 0) {
			F4h_TicketConverter_Runner::getMsgContainer()->clear();
			F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
							'Es wurden keine gültigen Ticketnummern eingegeben.', F4h_TicketConverter_Model_Message::ERROR
			));
			return false;
		}
		
		foreach($responseQueue as $ticket) {
			$ticket->save();
		}
		
		//get serialized xml buffer
		$serializer = new F4h_TicketConverter_Data_Serializer();
		$xmlString = $serializer->serialize($responseQueue);

		//convert 
		$dom = new DOMDocument();
		$dom->loadXML($xmlString);

		$converter = F4h_TicketConverter_Data_Converter_Factory::getConverter();
		$converter->setStylesheet(F4h_TicketConverter_Config::getInstance()->getStylesheetPath());
		$outputPath = $converter->convert($dom);

		return true;
	}

}