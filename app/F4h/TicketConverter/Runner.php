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
 * Class F4h_TicketConverter_Runner
 */
class F4h_TicketConverter_Runner
{

	protected static $msgCollection;

	/**
	 * @return F4h_TicketConverter_Message_Collection
	 */
	public static function getMsgContainer()
	{
		if (!self::$msgCollection) {
			self::$msgCollection = new F4h_TicketConverter_Message_Collection();
		}
		return self::$msgCollection;
	}

	/**
	 * @return array
	 */
	protected static function getInput()
	{
		$input = '';
		if (F4h_TicketConverter_Config::getInstance()->getEnvironment() === F4h_TicketConverter_Config::ENV_WEB) {
			$input = $_POST;
		} else {
			$input = getopt('h::p:i:s');
		}
		return $input;
	}

	/**
	 * @return bool
	 */
	public static function go()
	{
		session_start();
		session_regenerate_id();
		$_SESSION['notices'] = '';
		$_SESSION['errors'] = '';
		$_SESSION['success'] = '';
		$_SESSION['informational'] = '';

		try {
			//check dependencies, e.g. needed php extensions
			F4h_TicketConverter_Dependency_Verifier::checkDependencies(F4h_TicketConverter_Config::getInstance()->getDependencies());

			//get QueueBuilder
			$queueBuilder = F4h_TicketConverter_QueueBuilder_Factory::getQueueBuilder(self::getInput());
			if ($queueBuilder) {
				//build requestQueue
				$queue = $queueBuilder->build();

				if (!isset($_SESSION['tickets_to_confirm'])) {
					self::_checkCollection($queue);
				} else {
					unset($_SESSION['tickets_to_confirm']);
				}
				if (count($queue) !== 0) {
					//pass requestQueue to Manager -> invoke output creation process
					$manager = new F4h_TicketConverter_Manager($queue);
					$buildingOutputIsSuccessful = $manager->buildOutput();

					//return output depending on output type set in Config Class -> either send directly to printer or show as html
					if ($buildingOutputIsSuccessful) {
						$outputPath = F4h_TicketConverter_Config::getInstance()->getOutputFile();
						if (F4h_TicketConverter_Config::getInstance()->getEnvironment() === F4h_TicketConverter_Config::ENV_WEB) {
							switch (F4h_TicketConverter_Config::getInstance()->getOutputType()) {
								//build pdf with apache fop and send it directly to printer
								case F4h_TicketConverter_Config::OUTPUT_PDF:
									exec('sh ' . F4h_TicketConverter_Config::getInstance()->getApacheFopPath() . ' ' . $outputPath . ' output.pdf');
									//comment the following line out for testing purpose
									exec('lp -d ' . F4h_TicketConverter_Config::getInstance()->getPrinterName() . ' -o media=A6 -o landscape output.pdf');
									$outputPath = substr($outputPath, 0, strrpos($outputPath, '.')) . '.pdf';
									break;
								//redirect to output.html
								case F4h_TicketConverter_Config::OUTPUT_HTML;
									header('Location: output.html');
									break;
							}
						} else {
							self::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
											"output-path: " . $outputPath, F4h_TicketConverter_Model_Message::NOTICE
							));
						}
					}
				} else {
					self::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
									'In der Liste der zu druckenden Tickets wurden nur bereits gedruckte oder ungültigen Ticket-IDs angegeben.', F4h_TicketConverter_Model_Message::ERROR));
				}
			}
		} catch (F4h_TicketConverter_Exception_Dependency $dependencyException) {
			self::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
							'Der Ticketconverter kann nicht gestartet werden, da eine oder mehrere Abhängigkeiten fehlen.', F4h_TicketConverter_Model_Message::ERROR
			));
			F4h_TicketConverter_Log_Exception_Handler::trap($dependencyException);
		} catch (F4h_TicketConverter_Exception_Abstract $tcException) {
			if ($tcException->getLogLevel() === F4h_TicketConverter_Log_Exception_Handler::FATAL) {
				self::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
					$tcException->getMessage(), F4h_TicketConverter_Model_Message::ERROR));
			}
			F4h_TicketConverter_Log_Exception_Handler::trap($tcException);
		} catch (Exception $exception) {
			self::getMsgContainer()->push(new F4h_TicketConverter_Model_Message(
				$exception->getMessage() , F4h_TicketConverter_Model_Message::ERROR));
			F4h_TicketConverter_Log_Exception_Handler::trap($exception);
		}

		self::_response();
		
		return true;
	}

	/**
	 *
	 */
	protected static function _response()
	{
		if (F4h_TicketConverter_Config::getInstance()->getEnvironment() === F4h_TicketConverter_Config::ENV_CLI) {
			//print collected messages
			foreach (self::getMsgContainer() as $message) {
				echo $message->getMessage() . PHP_EOL;
			}
		} else {
			foreach (self::getMsgContainer() as $message) {
				switch ($message->getType()) {
					case F4h_TicketConverter_Model_Message::SUCCESS:
						$_SESSION['success'] = $_SESSION['success'] . '<li>' . $message->getMessage() . '</li>';
						break;
					case F4h_TicketConverter_Model_Message::NOTICE:
						$_SESSION['notices'] = $_SESSION['notices'] . '<li>' . $message->getMessage() . '</li>';
						break;
					case F4h_TicketConverter_Model_Message::INFORMATIONAL:
						$_SESSION['informational'] = $_SESSION['informational'] . '<li>' . $message->getMessage() . '</li>';
						break;
					case F4h_TicketConverter_Model_Message::ERROR:
						$_SESSION['errors'] = $_SESSION['errors'] . $message->getMessage() . '<br />';
						break;
				}
			}
			header('Location: index.php');
		}
	}

	/**
	 * @param $queue
	 */
	protected static function _checkCollection($queue)
	{
		$ticketModel = new F4h_TicketConverter_Model_Ticket();
		$collection = $ticketModel->getCollection()->load();

		$ticketsToConfirm = '';
		for ($i = 0; $i < count($queue); $i++) {
			$item = $queue->offsetGet($i);
			$ticketsToConfirm['project'] = $item['project'];
			if (in_array($item, $collection)) {
				$ticketsToConfirm['ticket_ids'][] = $item['ticket_id'];
				$queue->offsetUnset($i);
				$i--;
			}
		}
		
		if (!empty($ticketsToConfirm['ticket_ids'])) {
			$_SESSION['tickets_to_confirm'] = serialize($ticketsToConfirm);
		}
		print_r($_SESSION);
	}

}
