<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Claudia Hüttenrauch <claudia.hüttenrauch@fashionforhome.de>
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
 * Class F4h_TicketConverter_Jira_Request
 */
class F4h_TicketConverter_Jira_Request
{

	const USERAGENT = 'TicketConverter';

	protected $curlHandler;
	protected $options = array(
		CURLOPT_USERAGENT => self::USERAGENT,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FOLLOWLOCATION => 1,
	);

	/**
	 * @return resource
	 */
	protected function getCurlHandler()
	{
		if (!$this->curlHandler) {
			$this->curlHandler = curl_init();
		}
		return $this->curlHandler;
	}

	/**
	 * @return array
	 */
	protected function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param $key
	 * @param $value
	 * @return $this
	 */
	public function addOption($key, $value)
	{
		$this->options[$key] = $value;
		curl_setopt($this->getCurlHandler(), $key, $value);
		return $this;
	}

	/**
	 * @param array $options
	 * @return $this
	 */
	public function init(array $options)
	{
		foreach ($options as $key => $value) {
			$this->options[$key] = $value;
		}

		foreach ($this->options as $key => $value) {
			$this->addOption($key, $value);
		}

		return $this;
	}

	/**
	 * @return bool|mixed
	 */
	public function execute($isEpic = false)
	{
		$response = curl_exec($this->getCurlHandler());
		if ($this->isSuccessful($isEpic)) {
			return $response;
		}
		return false;
	}

	/**
	 * @return $this
	 */
	public function close()
	{
		curl_close($this->getCurlHandler());
		return $this;
	}

	/**
	 * @param bool $isEpic
	 * @return bool
	 * @throws F4h_TicketConverter_Exception_Jira_Request
	 */
	protected function isSuccessful($isEpic = false)
	{
		$requestInfo = curl_getinfo($this->getCurlHandler());
		switch ($requestInfo['http_code']) {
			case '200':
				return true;
				break;
			case '404':
				$ticketUrl = $requestInfo['url'];
				$tmp = substr(strrchr($ticketUrl, '/'), 1);
				$ticketKey = substr($tmp, 0, strrpos($tmp, '.'));
				$message = $ticketKey . ' existiert nicht';
				$errorlevel = F4h_TicketConverter_Model_Message::NOTICE;
				if($isEpic){
					$message = 'Das Epic konnte nicht auf das Ticket gedruckt werden ';
					$errorlevel = F4h_TicketConverter_Model_Message::INFORMATIONAL;
				}
				F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message($message, $errorlevel));
				return false;
				break;
			case '401':
				F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
						'Es ist ein Problem aufgetreten. Die Jira Login Daten scheinen fehlerhaft zu sein.', F4h_TicketConverter_Model_Message::ERROR
				));
				throw new F4h_TicketConverter_Exception_Jira_Request('HTTP 401 -> invalid jira login data');
				break;
			default:
				F4h_TicketConverter_Runner::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
						'Es scheint zur Zeit ein Problem mit Jira zu geben. Bitte versuchen Sie es später erneut.', F4h_TicketConverter_Model_Message::ERROR
				));
				throw new F4h_TicketConverter_Exception_Jira_Request('FEHLER: Es scheint zur Zeit ein Problem mit Jira zu geben. Bitte versuchen Sie es später erneut.');
				break;
		}
		exit;
	}

}