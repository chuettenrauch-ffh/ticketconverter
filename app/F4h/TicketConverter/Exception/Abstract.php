<?php
/**
 * Class F4h_TicketConverter_Exception_Abstract
 */
abstract class F4h_TicketConverter_Exception_Abstract extends Exception
{
	protected $_logLevel = F4h_TicketConverter_Log_Exception_Handler::FATAL;

	/**
	 * @return mixed
	 */
	public function getLogLevel()
	{
		return $this->_logLevel;
	}
	
}
