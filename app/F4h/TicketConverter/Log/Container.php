<?php
/**
 * Class F4h_TicketConverter_Log_Container
 */
class F4h_TicketConverter_Log_Container
{
	/**
	 * @var
	 */
	private static $instance;
	private $fatal = array(); //exception code = 0
	private $warning = array(); //exception code = 1
	private $notice = array(); //exception code = 2
	private $unknown = array();

	/**
	 *
 	 */
	private function __construct()
	{

	}

	/**
	 *
	 */
	private function __clone()
	{

	}

	/**
	 * @return F4h_TicketConverter_Log_Container
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new F4h_TicketConverter_Log_Container();
		}
		return self::$instance;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addFatal(Exception $exception)
	{
		$this->fatal[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getFatal()
	{
		return $this->fatal;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addWarning(Exception $exception)
	{
		$this->warning[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getWarning()
	{
		return $this->warning;
	}

	/**
	 * @param $notice
	 * @return $this
	 */
	public function addNotice($notice)
	{
		$this->notice[] = $notice;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getNotice()
	{
		return $this->notice;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addUnknown(Exception $exception)
	{
		$this->unknown[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getUnknown()
	{
		return $this->unknown;
	}

}

?>
