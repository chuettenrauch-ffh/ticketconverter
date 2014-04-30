<?php
/**
 * Class F4h_TicketConverter_Log_Exception_Handler
 */
class F4h_TicketConverter_Log_Exception_Handler
{
	const FATAL = 0;
	const WARNING = 1;
	const NOTICE = 2;
	const UNKNOWN = 3;

	/**
	 * @param Exception $exception
	 */
	public static function trap(Exception $exception)
	{
		$container = F4h_TicketConverter_Log_Container::getInstance();

		$code = $exception->getCode();
		switch ($code) {
			case (self::FATAL):
				$container->addFatal($exception);
				break;
			case (self::WARNING):
				$container->addWarning($exception);
				break;
			case (self::NOTICE):
				$container->addNotice($exception);
				break;
			case (self::UNKNOWN):
				$container->addUnknown($exception);
				break;
		}

		if (F4h_TicketConverter_Config::LOGGING === 1) {
			self::log($exception);
		}
	}

	/**
	 * @param Exception $exception
	 */
	public static function log(Exception $exception)
	{
		$logFile = F4h_TicketConverter_Config::getInstance()->getLogFile();

		$level = '';
		switch ($exception->getCode()) {
			case (self::FATAL):
				$level = 'FATAL';
				break;
			case (self::WARNING):
				$level = 'WARNING';
				break;
			case (self::NOTICE):
				$level = 'NOTICE';
				break;
			default:
				$level = 'UNKNOWN EXCEPTION';
				break;
		}

		$timestamp = date(DATE_ATOM, time());
		$string = $timestamp . PHP_EOL . $level . ': ' . $exception->__toString() . PHP_EOL . PHP_EOL;
		file_put_contents($logFile, $string, FILE_APPEND);
	}

	/**
	 * @param Exception $exception
	 */
	public static function printOut(Exception $exception)
	{

	}

}

?>
