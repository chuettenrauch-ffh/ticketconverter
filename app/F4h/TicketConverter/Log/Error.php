<?php
/**
 * Class F4h_TicketConverter_Log_Error
 */
class F4h_TicketConverter_Log_Error extends Exception
{
	/**
	 * @param string    $code
	 * @param int       $message
	 * @param Exception $filename
	 * @param           $lineno
	 */
	public function __construct($code, $message, $filename, $lineno)
	{
		$this->message = $message;
		$this->code = $code;
		$this->file = $filename;
		$this->line = $lineno;
	}
}