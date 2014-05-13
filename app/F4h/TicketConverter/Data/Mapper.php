<?php
/**
 * Class F4h_TicketConverter_Data_Mapper
 */
class F4h_TicketConverter_Data_Mapper
{

	protected $idQueue;
	protected $ticketQueue;
	protected $requester;
	protected $maskedUrl;
	protected $sprints;

	/**
	 * @param $queue
	 */
	public function __construct($queue)
	{
		$this->setIdQueue($queue);
		return $this;
	}

	/**
	 * @param $queue
	 * @return $this
	 */
	protected function setIdQueue($queue)
	{
		$this->idQueue = $queue;
		return $this;
	}

	/**
	 * @return mixed
	 */
	protected function getIdQueue()
	{
		return $this->idQueue;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	protected function getTicketQueue()
	{
		if (!$this->ticketQueue) {
			$this->ticketQueue = new F4h_TicketConverter_Model_Queue();
		}
		return $this->ticketQueue;
	}

	/**
	 * @return F4h_TicketConverter_Jira_Request
	 */
	protected function getRequester()
	{
		if (!$this->requester) {
			$options = array(
				CURLOPT_HTTPHEADER => array(
					'Authorization: Basic ' . F4h_TicketConverter_Config::getInstance()->getAuthorizationString()
				),
				CURLOPT_RETURNTRANSFER => true
			);

			$this->requester = new F4h_TicketConverter_Jira_Request();
			$this->requester->init($options);
		}
		return $this->requester;
	}

	/**
	 * @return mixed
	 */
	protected function getMaskedUrl()
	{
		if (!$this->maskedUrl) {
			$this->maskedUrl = F4h_TicketConverter_Config::getInstance()->getMaskedTicketUrl();
		}
		return $this->maskedUrl;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	public function map()
	{
		$ticketQueue = $this->getTicketQueue();
		foreach ($this->getIdQueue() as $ticketId) {
			$ticket = $this->getTicket($ticketId['ticket_id']);

			if ($ticket) {
				$ticketQueue->enqueue($ticket);
			}
		}
		return $ticketQueue;
	}

	/**
	 * @param $ticketId
	 * @return F4h_TicketConverter_Model_Ticket|null
	 * @throws F4h_TicketConverter_Exception_Jira_Xml
	 */
	protected function getTicket($ticketId , $gettingEpic = false)
	{
		$ticket = new F4h_TicketConverter_Model_Ticket();
		$xml = $this->getXml($ticketId, $gettingEpic);
		if ($xml) {
			$xmlData = new SimpleXMLElement($xml);

			//fill ticketmodel with data
			try {
				$item = $xmlData->channel->item;

				$this->getStoryPoints($item, $ticket);

				$this->getDevTeam($item, $ticket);

				$this->getEpic($item, $ticket);

				$this->getEpicname($item, $ticket);

				$this->getSprint($item, $ticket);

				$this->getSubtasks($item, $ticket);

				$this->getParents($item, $ticket);

				$ticket->setAssignee($item->assignee);

				$ticket->setId($ticketId);

				$ticket->setKey($item->key);

				$ticket->setReporter($item->reporter);

				$ticket->setSummary($item->summary);

				$ticket->setType($item->type);

			} catch (Exception $e) {
				if($gettingEpic){
					return '';
				}
				F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message('Es ist ein Fehler aufgetreten. Scheinbar hat sich die Jira XML Struktur geändert.', F4h_TicketConverter_Model_Message::ERROR));
				throw new F4h_TicketConverter_Exception_Jira_Xml('FEHLER: Struktur des Jira XML fehlerhaft.');
			}
		}

		if ($ticket->getId()) {
			return $ticket;
		}
		return null;
	}

	/**
	 * @param $ticketId
	 * @return bool|mixed|null
	 */
	protected function getXml($ticketId, $isEpic = false)
	{
		$requester = $this->getRequester();

		//replace wildcard '*' with Ticket ID
		$maskedUrl = $this->getMaskedUrl();
		$ticketUrl = str_replace('*', $ticketId, $maskedUrl);
		$requester->addOption(CURLOPT_HEADER , 'Content-Type: text/xml');
		$requester->addOption(CURLOPT_URL, $ticketUrl);

		if ($response = $requester->execute($isEpic)) {
			return $response;
		}

		return null;
	}

	/**
	 * @return Array Sprints | null
	 */
	protected function getSprints()
	{
		if($this->sprints != null)
		{
			return $this->sprints;
		}
		$requester = $this->getRequester();

		$requester->addOption(CURLOPT_HEADER , 'Content-Type: application/json');
		$requester->addOption(CURLOPT_URL, F4h_TicketConverter_Config::SPRINTSURL);

		if ($response = $requester->execute()) {
			$sprints = json_decode($response,true);
			$this->sprints = $sprints['sprints'];
			return $this->sprints;
		}

		return null;
	}

	/**
	 * @param $sprintID
	 * @return null
	 */
	protected function findSprintnameByID($sprintID)
	{
		$this->getSprints();
		foreach($this->sprints as $sprint){
			if($sprintID == $sprint['id']){
				return $sprint['name'];
			}
		}
		return null;
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getStoryPoints($item, $ticket)
	{
		$storypoints = $item->xpath('//customfield[@id="customfield_10023"]');

		if (key_exists(0, $storypoints) && $storypoints[0]->customfieldname == 'Story Points Estimate') {
			$ticket->setStorypoints(floatval($storypoints[0]->customfieldvalues->customfieldvalue));
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getDevTeam($item, $ticket)
	{
		$customfields = $item->xpath('//customfield[@id="customfield_10363"]');
		if (key_exists(0, $customfields) && $customfields[0]->customfieldname == 'Dev Team') {
			$ticket->setDevTeam($customfields[0]->customfieldvalues->customfieldvalue);
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getEpic($item, $ticket)
	{
		$epic = $item->xpath('//customfield[@id="customfield_10860"]');
		if (key_exists(0, $epic) && $epic[0]->customfieldname == 'Epic Link') {
			$ticket->setEpic($this->getTicket(substr($epic[0]->customfieldvalues->customfieldvalue, 4), true));
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getEpicname($item, $ticket)
	{
		$epicname = $item->xpath('//customfield[@id="customfield_10861"]');
		if (key_exists(0, $epicname) && $epicname[0]->customfieldname == 'Epic Name') {
			$ticket->setEpicname($epicname[0]->customfieldvalues->customfieldvalue);
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getSprint($item, $ticket)
	{
		$sprint = $item->xpath('//customfield[@id="customfield_10560"]');
		if (key_exists(0, $sprint) && $sprint[0]->customfieldname == 'Sprint') {
			$ticket->setSprintname($this->findSprintnameByID(intval($sprint[0]->customfieldvalues->customfieldvalue)));
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getSubtasks($item, $ticket)
	{
		if (count($item->subtasks->subtask) > 0) {
			$ticket->setHasSubtasks(true);
		}
	}

	/**
	 * @param $item
	 * @param $ticket
	 */
	protected function getParents($item, $ticket)
	{
		if ($parent = $item->parent) {
			$ticket->setParent($this->getTicket(substr($parent, 4)));
		}
	}
}