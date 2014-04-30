<?php
/**
 * Class F4h_TicketConverter_Log_Error_Handler
 */
class F4h_TicketConverter_Log_Error_Handler
{
	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 * @throws F4h_TicketConverter_Log_Error
	 */
	public static function trap($errno, $errstr, $errfile, $errline)
	{
		switch ($errno) {
//            case E_NOTICE:
//            case E_USER_NOTICE:
//            case E_DEPRECATED:
//            case E_USER_DEPRECATED:
//            case E_STRICT:
//                $level = 'NOTICE';
//                break;
//
//            case E_WARNING:
//            case E_USER_WARNING:
//                $level = 'WARNING';
//                break;

			case E_ERROR:
			case E_USER_ERROR:
				throw new F4h_TicketConverter_Log_Error(0, $errstr, $errfile, $errline);
				break;
			default:
				throw new F4h_TicketConverter_Log_Error(3, $errstr, $errfile, $errline);
				break;
		}

		if (F4h_TicketConverter_Config::LOGGING === 1) {
			self::log($errno, $errstr, $errfile, $errline);
		}
	}

	/**
	 * @param $errno
	 * @param $errstr
	 * @param $errfile
	 * @param $errline
	 */
	public static function log($errno, $errstr, $errfile, $errline)
	{
		$level = '';
		switch ($errno) {
			case E_NOTICE:
			case E_USER_NOTICE:
			case E_DEPRECATED:
			case E_USER_DEPRECATED:
			case E_STRICT:
				$level = 'NOTICE';
				break;
			case E_WARNING:
			case E_USER_WARNING:
				$level = 'WARNING';
				break;
			case E_ERROR:
			case E_USER_ERROR:
				$level = 'FATAL';
				break;
			default:
				$level = 'UNKNOWN ERROR';
				break;
		}

		$timestamp = date(DATE_ATOM, time());
		$string = $timestamp . PHP_EOL . $level . ': ' . $errstr . 'at' . $errfile . ':' . $errline . PHP_EOL . PHP_EOL;
	}

}