<?php
/**
 * Class F4h_TicketConverter_Message_Collection
 */
class F4h_TicketConverter_Message_Collection extends SplDoublyLinkedList
{
	/**
	 * clears the Message Collection
	 *
	 * @return $this
	 */
	public function clear()
	{
		while (!$this->isEmpty()) {
			$this->pop();
		}
		return $this;
	}
}

?>
