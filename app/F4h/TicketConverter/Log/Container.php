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
 * Class F4h_TicketConverter_Log_Container
 */
class F4h_TicketConverter_Log_Container
{
	/**
	 * @var
	 */
	private static $instance;
	private $fatal = array(); //exception code = 0
	private $warning = array(); //exception code = 1
	private $notice = array(); //exception code = 2
	private $unknown = array();

	/**
	 *
 	 */
	private function __construct()
	{

	}

	/**
	 *
	 */
	private function __clone()
	{

	}

	/**
	 * @return F4h_TicketConverter_Log_Container
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new F4h_TicketConverter_Log_Container();
		}
		return self::$instance;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addFatal(Exception $exception)
	{
		$this->fatal[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getFatal()
	{
		return $this->fatal;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addWarning(Exception $exception)
	{
		$this->warning[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getWarning()
	{
		return $this->warning;
	}

	/**
	 * @param $notice
	 * @return $this
	 */
	public function addNotice($notice)
	{
		$this->notice[] = $notice;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getNotice()
	{
		return $this->notice;
	}

	/**
	 * @param Exception $exception
	 * @return $this
	 */
	public function addUnknown(Exception $exception)
	{
		$this->unknown[] = $exception;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getUnknown()
	{
		return $this->unknown;
	}
}

?>
