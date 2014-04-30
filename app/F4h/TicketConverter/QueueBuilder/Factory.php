<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Factory
 */
class F4h_TicketConverter_QueueBuilder_Factory
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
	 * @param array $input
	 * @return F4h_TicketConverter_QueueBuilder_Cli_Csv_JiraExport|F4h_TicketConverter_QueueBuilder_Cli_Single|F4h_TicketConverter_QueueBuilder_Web_Link|F4h_TicketConverter_QueueBuilder_Web_List
	 * @throws F4h_TicketConverter_QueueBuilder_Exception
	 */
	public static function getQueueBuilder(array $input)
	{
		// webinterface via link
		if (key_exists('link', $input)) {
			$input['link'] = trim($input['link']);
			$check = 'https://fashion4home.jira.com/sr/jira.issueviews:searchrequest-xml/temp/SearchRequest.xml';
			if (strncmp($input['link'], $check, 88) === 0) {
				return new F4h_TicketConverter_QueueBuilder_Web_Link($input['link']);
			} else {
				throw new F4h_TicketConverter_QueueBuilder_Exception('Invalid URL');
			}

			// webinterface via list of ids & project select-input
		} else if (key_exists('project', $input) && key_exists('list', $input)) {
			$input['list'] = trim(preg_replace('/[^0-9\s]/', '', $input['list']));

			F4h_TicketConverter_Config::getInstance()->setProject($input['project']);

			return new F4h_TicketConverter_QueueBuilder_Web_List($input);

			// cli interface
		} else {
			if (key_exists('h', $input)) {
				throw new F4h_TicketConverter_QueueBuilder_Exception("---help---" . PHP_EOL . "      usage: php ticketconverter.php [-s] -i [filename.csv | id]" . PHP_EOL . "			   -p	   define project, e.g DMF" . PHP_EOL . "              -s      set to single-input mode" . PHP_EOL . "              -i      declare input; value: filename required" . PHP_EOL . "                      in single-input mode: id required" . PHP_EOL);
			}

			//check for required parameter p
			if (!key_exists('p', $input)) {
				throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: parameter -p is required." . PHP_EOL . "please define project" . PHP_EOL);
			}

			//check for required parameter i
			if (!key_exists('i', $input)) {
				throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: parameter -i is required." . PHP_EOL . "for multi input via csv use: php ticketconverter.php -i filename.csv" . PHP_EOL . "for single input use: php ticketconverter.php -p project -s -i id" . PHP_EOL);
			}

			$inputValue = array('project' => $input['p'], 'ids' => $input['i']);

			//check if singlemode is set, if yes: build queue, if no: invoke queuebuilding
			switch (key_exists('s', $input)) {

				case true:
					return new F4h_TicketConverter_QueueBuilder_Cli_Single($inputValue);
					break;

				case false:

					if (!file_exists($inputValue)) {
						throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: cannot find '" . $inputValue . "'");
					}
					return F4h_TicketConverter_QueueBuilder_Cli_Factory::getQueueBuilder($inputValue);
					break;

				default:
					break;
			}
		}

	}

}
