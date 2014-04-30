<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Web_List
 */
class F4h_TicketConverter_QueueBuilder_Web_List extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{
	/**
	 * @return array
	 */
	public function getIds()
	{
		if (empty($this->ids)) {
			$input = $this->getInputArgs();
			$ids = preg_split('/\s+/', $input['list']);
			foreach ($ids as $id) {
				$this->ids[] = array('project' => $input['project'], 'ticket_id' => $id);
			}
		}
		return $this->ids;
	}
}