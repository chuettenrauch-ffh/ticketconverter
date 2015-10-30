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
 * Class F4h_TicketConverter_QueueBuilder_Web_Link
 */
class F4h_TicketConverter_QueueBuilder_Web_Link extends F4h_TicketConverter_QueueBuilder_Abstract implements F4h_TicketConverter_Interface_QueueBuilder
{

	protected $inputArgs;

	/**
	 * @return bool|mixed
	 */
	protected function request()
	{
		$options = array(
			CURLOPT_URL => $this->getInputArgs(),
			CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . F4h_TicketConverter_Config::getInstance()->getAuthorizationString(),
				'Content-Type: text/xml'
			),
			CURLOPT_RETURNTRANSFER => true,
		);

		$requester = new F4h_TicketConverter_Jira_Request();
		$requester->init($options);

		$response = $requester->execute();
		$requester->close();
		return $response;
	}

	/**
	 * @return array
	 */
	public function getIds()
	{
		if (empty($this->ids)) {
			$response = $this->request();
			if ($response !== false) {
				$xmlDoc = new DOMDocument();
				$xmlDoc->loadXML($response);
				$xpath = new DomXPath($xmlDoc);

				$items = $xpath->query('//item/key');
				for ($i = 0; $i < $items->length; $i++) {
					$this->ids[] = substr($items->item($i)->nodeValue, 4);
				}
			}
		}
		return $this->ids;
	}

}

?>
