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
 * Class F4h_TicketConverter_Model_Resource_Abstract
 */
abstract class F4h_TicketConverter_Model_Resource_Abstract
{
	protected $_connection = null;
	protected $_model = null;

	/**
	 * @param $model
	 */
	public function __construct($model) {
		$this->_setModel($model);
	}

	/**
	 *
	 */
	public function save()
	{
		$this->_save();
	}

	/**
	 *
	 */
	public function delete()
	{
		$this->_delete();
	}

	/**
	 * @return mixed|null
	 */
	public function getConnection()
	{
		if (!$this->_connection) {
			$this->_connection = $this->_getConnection();
		}
		return $this->_connection;
	}

	/**
	 * @return $this->_model
	 */
	protected function _getModel() {
		return $this->_model;
	}

	/**
	 * @param $model
	 * @return $this
	 */
	protected function _setModel($model) {
		$this->_model = $model;
		return $this;
	}

	/**
	 * @return mixed
	 */
	abstract protected function _getConnection();

	/**
	 * @return mixed
	 */
	abstract protected function _save();

	/**
	 * @return mixed
	 */
	abstract protected function _delete();
}
