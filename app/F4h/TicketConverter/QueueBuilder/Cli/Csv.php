<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Tino Stöckel <tino.stoeckel@fashionforhome.de>
 *
 * @copyright (c) 2012 by fashion4home GmbH <www.fashionforhome.de>
 * @license GPL-3.0
 * @license http://opensource.org/licenses/GPL-3.0 GNU GENERAL PUBLIC LICENSE
 *
 * @version 1.0.0
 *
 * Date: 30.10.2015
 * Time: 01:30
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
