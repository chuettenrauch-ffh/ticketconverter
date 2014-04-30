<?php
/**
 * Class F4h_TicketConverter_Data_Serializer
 */
class F4h_TicketConverter_Data_Serializer
{
	protected $ticketQueue;
	protected $buffer;

	/**
	 *
	 */
	public function __construct()
	{

	}

	/**
	 * @return mixed
	 */
	protected function getTicketQueue()
	{
		return $this->ticketQueue;
	}

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 * @return $this
	 */
	protected function setTicketQueue(F4h_TicketConverter_Model_Queue $queue)
	{
		$this->ticketQueue = $queue;
		return $this;
	}

	/**
	 * @return XMLWriter
	 */
	protected function getBuffer()
	{
		if (!$this->buffer) {
			$this->buffer = new XMLWriter();
			$this->buffer->openMemory();
		}
		return $this->buffer;
	}

	/**
	 * @param F4h_TicketConverter_Model_Queue $queue
	 * @return string
	 */
	public function serialize(F4h_TicketConverter_Model_Queue $queue)
	{
		$this->setTicketQueue($queue);

		$buffer = $this->getBuffer();
		$buffer->writeRaw('<?xml version="1.0" encoding="utf-8"?>');
		$buffer->startElement('tickets');

		foreach ($queue as $ticket) {
			$buffer->startElement('ticket');
			$this->createTicketElement($ticket);
			$buffer->endElement();

			F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message($ticket->getKey(), F4h_TicketConverter_Model_Message::SUCCESS));
		}

		$buffer->endElement();

		return $buffer->outputMemory();
	}

	/**
	 * @param $ticket
	 */
	protected function createTicketElement($ticket)
	{
		$buffer = $this->getBuffer();

		$buffer->writeElement('key', $ticket->getKey());
		$buffer->writeElement('assignee', $ticket->getAssignee());
		$buffer->writeElement('reporter', $ticket->getReporter());
		$buffer->writeElement('type', $ticket->getType());
		$buffer->writeElement('hasSubtasks', $ticket->getHasSubtasks() ? 1 : 0);
		$buffer->writeElement('summary', $ticket->getSummary());
		$buffer->writeElement('devteam', $ticket->getDevTeam());
		$buffer->writeElement('storypoints',$ticket->getStorypoints());
		$buffer->writeElement('sprintname',$ticket->getSprintname());

		if ($parent = $ticket->getParent()) {
			$buffer->startElement('parent');
			$buffer->writeElement('key', $parent->getKey());
			$buffer->writeElement('summary', $parent->getSummary());
			$buffer->endElement();
		}

		if ($epic = $ticket->getEpic()) {
			$buffer->startElement('epic');
			$buffer->writeElement('key', $epic->getKey());
			$buffer->writeElement('name', $epic->getEpicname());
			$buffer->endElement();
		}
	}
}
