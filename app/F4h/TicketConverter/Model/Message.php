<?php
/**
 * Class F4h_TicketConverter_Model_Message
 */
class F4h_TicketConverter_Model_Message
{
	const SUCCESS = 0;
	const NOTICE = 1;
	const ERROR = 2;

	protected $_message;
	protected $_type;

	/**
	 * @param $message
	 * @param $type
	 */
	public function __construct($message, $type)
	{
		$this->setMessage($message);
		$this->setType($type);
	}

	/**
	 * @param $message
	 * @return $this
	 */
	public function setMessage($message)
	{
		$this->_message = $message;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getMessage()
	{
		return $this->_message;
	}

	/**
	 * @param $type
	 * @return $this
	 */
	public function setType($type)
	{
		$this->_type = $type;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->_type;
	}

}
