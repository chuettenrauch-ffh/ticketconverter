<?php

class F4h_TicketConverter_QueueBuilder_Cli_Single extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{
	/**
	 * @return array
	 * @throws F4h_TicketConverter_QueueBuilder_Exception
	 */
	public function getIds()
	{
		$input = $this->getInputArgs();
		$ids = $input['ids'];
		$project = $input['project'];
		if (is_array($ids)) {
			foreach ($ids as $entry) {
				if (!is_numeric($entry)) {
					F4h_TicketConverter_Runner::getMsgContainer()->
						push(new F4h_TicketConverter_Model_Message("FAILURE: '" . $entry . "' expected to be numeric. input ignored."));
					continue;
				}
				$this->ids[] = array('project' => $project, 'ticket_id' => $entry);
			}
		} else {
			if (!is_numeric($ids)) {
				throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: '" . $ids . "' expected to be numeric.");
			}
			$this->ids[] = array('project' => $project, 'ticket_id' => $entry);
		}

		if (empty($this->ids)) {
			throw new F4h_TicketConverter_QueueBuilder_Exception("FAILURE: no valid ids found");
		}
		return $this->ids;
	}

}