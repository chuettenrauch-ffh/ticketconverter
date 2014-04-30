<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Cli_Factory
 */
class F4h_TicketConverter_QueueBuilder_Cli_Factory
{
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
	 * @param $filename
	 * @return F4h_TicketConverter_QueueBuilder_Cli_Csv_JiraExport
	 */
	public static function getQueueBuilder($filename)
	{
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		switch ($ext) {
			case 'csv':
				return new F4h_TicketConverter_QueueBuilder_Cli_Csv_JiraExport($filename);
				break;
			default:
				F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message('FAILURE: unsupported filetype', F4h_TicketConverter_Model_Message::ERROR));
				break;
		}
	}

}
