<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Web_Link
 */
class F4h_TicketConverter_QueueBuilder_Web_Link extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{

	protected $inputArgs;

	/**
	 * @return bool|mixed
	 */
	protected function request()
	{
		$options = array(
			CURLOPT_URL => $this->getInputArgs(),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . F4h_TicketConverter_Config::getInstance()->getAuthorizationString(),
				'Content-Type: text/xml'
			),
			CURLOPT_RETURNTRANSFER => true,
		);

		$requester = new F4h_TicketConverter_Jira_Request();
		$requester->init($options);

		$response = $requester->execute();
		$requester->close();
		return $response;
	}

	/**
	 * @return array
	 */
	public function getIds()
	{
		if (empty($this->ids)) {
			$response = $this->request();
			if ($response !== false) {
				$xmlDoc = new DOMDocument();
				$xmlDoc->loadXML($response);
				$xpath = new DomXPath($xmlDoc);

				$items = $xpath->query('//item/key');
				for ($i = 0; $i < $items->length; $i++) {
					$this->ids[] = substr($items->item($i)->nodeValue, 4);
				}
			}
		}
		return $this->ids;
	}

}

?>
