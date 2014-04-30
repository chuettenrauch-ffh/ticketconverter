<?php
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
	
	public function save()
	{
		$this->_save();
	}

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
