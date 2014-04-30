<?php
/**
 * Class F4h_TicketConverter_QueueBuilder_Cli_Csv
 */
class F4h_TicketConverter_QueueBuilder_Cli_Csv extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{
    const DELIMITER = ';';

    protected $filename;
    protected $fileContent;

	/**
	 * @param $filename
	 */
	public function __construct($filename)
    {
        if ($filename !== null) {
            $this->setFilename($filename);
        }
        return $this;
    }

	/**
	 * @param $filename
	 * @return $this
	 */
	protected function setFilename($filename)
    {
        if (!$this->filename) {
            $this->filename = $filename;
        }
        return $this;
    }

	/**
	 * @return mixed
	 */
	protected function getFilename()
    {
        return $this->filename;
    }

	/**
	 * @return $this
	 */
	protected function setFileContent()
    {
        if (!$this->fileContent) {
            $this->fileContent = file_get_contents($this->getFilename());
        }
        return $this;
    }

	/**
	 * @return mixed
	 */
	protected function getFileContent()
    {
        return $this->fileContent;
    }

	/**
	 * @return array
	 */
	public function getIds()
    {
        if (empty($this->ids)) {
            $this->ids = str_getcsv($this->getFileContent(), self::DELIMITER);
        }
        return $this->ids;
    }

	/**
	 * @return bool
	 */
	protected function inputIsValid()
    {
        if (preg_match('/^[0-9;]+$/', $this->getFileContent())) {
            return true;
        }
        return false;
    }

	/**
	 * @return F4h_TicketConverter_Model_Queue|void
	 */
	public function build()
    {
        $this->setFileContent();

        if (!$this->inputIsValid()) {
            F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
                            "FAILURE: invalid data in file '" . $this->getFilename() . "'", F4h_TicketConverter_Model_Message::ERROR
            ));
            return;
        }

        parent::build();
    }

}
