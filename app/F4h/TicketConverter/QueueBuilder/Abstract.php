<?php
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
