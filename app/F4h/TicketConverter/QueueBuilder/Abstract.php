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
 * Class F4h_TicketConverter_QueueBuilder_Abstract
 */
abstract class F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{

	protected $ids = array();
	protected $queue;
	protected $inputArgs;

	/**
	 * @param $inputArgs
	 */
	public function __construct($inputArgs)
	{
		$this->setInputArgs($inputArgs);
	}

	/**
	 * @param $args
	 * @return $this
	 */
	protected function setInputArgs($args)
	{
		$this->inputArgs = $args;
		return $this;
	}

	/**
	 * @return mixed
	 */
	protected function getInputArgs()
	{
		return $this->inputArgs;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	protected function getQueue()
	{
		if (!$this->queue) {
			$this->queue = new F4h_TicketConverter_Model_Queue();
		}
		return $this->queue;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	public function build()
	{
		$ids = $this->getIds();
		$queue = $this->getQueue();

		foreach ($ids as $id) {
			$queue->enqueue($id);
		}
		return $queue;
	}

	/**
	 * @param array $ids
	 * @return $this
	 */
	public function setIds(array $ids)
	{
		$this->ids = $ids;
		return $this;
	}

	/**
	 * @param $key
	 * @return bool
	 */
	public function removeId($key)
	{
		$ids = $this->getIds();
		if (array_key_exists($key, $ids)) {
			unset($ids[$key]);
			$this->ids = array_values($ids);
			return true;
		}
		return false;
	}

}
