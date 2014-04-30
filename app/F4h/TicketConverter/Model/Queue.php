<?php
/**
 * Class F4h_TicketConverter_Model_Queue
 */
class F4h_TicketConverter_Model_Queue extends SplQueue
{
	/**
	 * @param $key
	 * @return bool
	 */
	public function removeByKey($key)
	{
		if ($this->offsetExists($key)) {
			$this->offsetUnset($key);
			return true;
		}
		return false;
	}

}
