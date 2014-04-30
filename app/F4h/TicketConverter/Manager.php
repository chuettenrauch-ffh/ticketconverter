<?php
/**
 * Class F4h_TicketConverter_Manager
 */
class F4h_TicketConverter_Manager
{

	/**
	 *
	 * @var F4h_TicketConverter_Model_Queue (containing Ids)
	 */
	protected $idQueue;

	/**
	 *
	 * @var F4h_TicketConverter_Model_Queue (containing F4h_TicketConverter_Model_Ticket)
	 */
	protected $ticketQueue;

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 */
	public function __construct(F4h_TicketConverter_Model_Queue $queue)
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
	 * @return F4h_TicketConverter_Model_Queue
	 */
	protected function getIdQueue()
	{
		return $this->idQueue;
	}

	/**
	 * @param $queue
	 * @return $this
	 */
	protected function setTicketQueue($queue)
	{
		$this->ticketQueue = $queue;
		return $this;
	}

	/**
	 * @return F4h_TicketConverter_Model_Queue
	 */
	public function getTicketQueue()
	{
		if (!$this->ticketQueue) {
			$mapper = new F4h_TicketConverter_Data_Mapper($this->getIdQueue());
			$this->ticketQueue = $mapper->map();
		}
		return $this->ticketQueue;
	}

	/**
	 * @return bool
	 */
	public function buildOutput()
	{
		$responseQueue = $this->getTicketQueue();
		if (count($responseQueue) == 0) {
			F4h_TicketConverter_Runner::getMsgContainer()->clear();
			F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
							'Es wurden keine gültigen Ticketnummern eingegeben.', F4h_TicketConverter_Model_Message::ERROR
			));
			return false;
		}
		
		foreach($responseQueue as $ticket) {
			$ticket->save();
		}
		
		//get serialized xml buffer
		$serializer = new F4h_TicketConverter_Data_Serializer();
		$xmlString = $serializer->serialize($responseQueue);

		//convert 
		$dom = new DOMDocument();
		$dom->loadXML($xmlString);

		$converter = F4h_TicketConverter_Data_Converter_Factory::getConverter();
		$converter->setStylesheet(F4h_TicketConverter_Config::getInstance()->getStylesheetPath());
		$outputPath = $converter->convert($dom);

		return true;
	}

}