<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Cli_Csv_JiraExport
 */
class F4h_TicketConverter_QueueBuilder_Cli_Csv_JiraExport extends F4h_TicketConverter_QueueBuilder_Cli_Csv
{
	/**
	 * @return array
	 */
	public function getIds()
	{
		if (empty($this->ids)) {
			//read csv line-by-line -> save each line as array entry
			$lines = $this->getFileContent();
			$count = count($lines);

			$lineArray = array();
			//ignore 3 lines (header), 1 line (footer)
			for ($i = 3; $i < $count - 1; $i++) {
				//explode line to array
				$lineArray[] = str_getcsv($lines[$i], self::DELIMITER);
			}

			foreach ($lineArray as $line) {
				$this->ids[] = substr($line[1], 4);
			}
		}
		return $this->ids;
	}

	/**
	 * @return $this
	 */
	protected function setFileContent()
	{
		if (!$this->fileContent) {
			$this->fileContent = file($this->getFilename());
		}
		return $this;
	}

	/**
	 * @return bool
	 */
	protected function inputIsValid()
	{
		$lines = $this->getFileContent();
		if (preg_match('/^fashion4home/', $lines[0])) {
			return true;
		}
		return false;
	}

}

?>
