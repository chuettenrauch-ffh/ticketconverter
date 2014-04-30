<?php
/**
 * Class F4h_TicketConverter_Model_Resource_Collection
 */
abstract class F4h_TicketConverter_Model_Resource_Collection implements Iterator, Countable
{

	protected $_items = array();
	protected $_position = 0;
	protected $_connection = null;
	protected $_filter = array();
	protected $_columns = array();

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->_items);
	}

	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->_items[$this->_position];
	}

	/**
	 * @return int|mixed
	 */
	public function key()
	{
		return $this->_position;
	}

	/**
	 *
	 */
	public function next()
	{
		++$this->_position;
	}

	/**
	 *
	 */
	public function rewind()
	{
		$this->_position = 0;
	}

	/**
	 * @return bool
	 */
	public function valid()
	{
		return isset($this->_items[$this->_position]);
	}

	/**
	 * @return mixed
	 */
	public function load()
	{
		$this->_items = $this->_load();
		return $this->_items;
	}

	/**
	 * @param $column
	 * @param $value
	 * @return $this
	 */
	public function addFilter($column, $value)
	{
		$this->_filter[$column] = $value;
		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeFilter()
	{
		$this->_filter = array();
		return $this;
	}

	/**
	 * @return null
	 */
	public function getConnection()
	{
		if (!$this->_connection) {
			$this->_connection = $this->_getConnection();
		}
		return $this->_connection;
	}

	/**
	 * @return string
	 */
	protected function _getQueryString()
	{
		$query = 'SELECT * FROM ' . static::TABLENAME;
		if (!empty($this->_filter)) {
			$query .= ' WHERE ' . _getAndCondition($this->_filter);
		}
		$query .= ';';
		return $query;
	}

	/**
	 * @param       $key
	 * @param array $values
	 * @return string
	 */
	protected function _getOrCondition($key, array $values)
	{
		$parts = array();
		foreach ($values as $value) {
			if (in_array($key, $this->_columns)) {
				$parts[] = $key . ' = "' . $value . '"';
			}
		}

		if (empty($parts)) {
			return '';
		}
		return '(' . implode(' OR ', $parts) . ')';
	}

	/**
	 * @param array $array
	 * @return string
	 */
	protected function _getAndCondition(array $array)
	{
		$parts = array();
		foreach ($array as $key => $value) {
			if (in_array($key, $this->_columns)) {
				if (is_array($value)) {
					$parts[] = _getOrCondition($key, $value);
				} else {
					$parts[] = $key . ' = "' . $value . '"';
				}
			}
		}

		if (empty($parts)) {
			return '';
		}
		return implode(' AND ', $parts);
	}

	/**
	 * @return mixed
	 */
	abstract protected function _load();

	/**
	 * @return mixed
	 */
	abstract protected function _getConnection();

}