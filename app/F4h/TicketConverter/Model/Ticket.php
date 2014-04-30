<?php
/**
 * Class F4h_TicketConverter_Model_Ticket
 */
class F4h_TicketConverter_Model_Ticket
{
	protected $_resource = null;
	protected $assignee;
	protected $devTeam;
	protected $id;
	protected $key;
	protected $parent;
	protected $reporter;
	protected $summary;
	protected $type;
	protected $hasSubtasks = false;
	protected $epic;
	protected $epicname;
	protected $storypoints;
	protected $sprintname;
	protected $_project;

	/**
	 * constructor
	 */
	public function __construct()
	{

	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setAssignee($data)
	{
		$this->assignee = $data;
		return $this;
	}

	/**
	 * @return $this->assignee
	 */
	public function getAssignee()
	{
		return $this->assignee;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setDevTeam($data)
	{
		$this->devTeam = $data;
		return $this;
	}

	/**
	 * @return $this->devTeam
	 */
	public function getDevTeam()
	{
		return $this->devTeam;
	}

	/**
	 * @param $id
	 * @return $this
	 */
	public function setId($id)
	{
		if (!$this->id) {
			$this->id = $id;
		}
		return $this;
	}

	/**
	 * @return $this->id
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setKey($data)
	{
		$this->key = $data;
		return $this;
	}

	/**
	 * @return $this->key
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * @param F4h_TicketConverter_Model_Ticket $parent
	 * @return $this
	 */
	public function setParent(F4h_TicketConverter_Model_Ticket $parent)
	{
		$this->parent = $parent;
		return $this;
	}

	/**
	 * @return $this->parent
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setReporter($data)
	{
		$this->reporter = $data;
		return $this;
	}

	/**
	 * @return $this->reporter
	 */
	public function getReporter()
	{
		return $this->reporter;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setSummary($data)
	{
		$this->summary = $data;
		return $this;
	}

	/**
	 * @return $this->summary
	 */
	public function getSummary()
	{
		return $this->summary;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setType($data)
	{
		$this->type = $data;
		return $this;
	}

	/**
	 * @return $this->type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param $data
	 * @return $this
	 */
	public function setHasSubtasks($data)
	{
		$this->hasSubtasks = $data;
		return $this;
	}

	/**
	 * @return $this->hasSubtasks
	 */
	public function getHasSubtasks()
	{
		return $this->hasSubtasks;
	}

	/**
	 * @param F4h_TicketConverter_Model_Ticket $data
	 * @return $this
	 */
	public function setEpic(F4h_TicketConverter_Model_Ticket $data)
	{
		$this->epic = $data;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEpic()
	{
		return $this->epic;
	}

	/**
	 * @param string $data
	 * @return $this
	 */
	public function setEpicname($data)
	{
		$this->epicname = utf8_encode(html_entity_decode($data));
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEpicname()
	{
		return $this->epicname;
	}

	/**
	 * @param float $data
	 * @return $this
	 */
	public function setStorypoints($data)
	{
		$this->storypoints = $data;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getStorypoints()
	{
		return $this->storypoints;
	}

	/**
	 * @param string $data
	 * @return $this
	 */
	public function setSprintname($data)
	{
		$this->sprintname = $data;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSprintname()
	{
		return $this->sprintname;
	}

	/**
	 * @return string
	 */
	public function getProject()
	{
		if (!$this->_project) {
			$key = $this->getKey();
			$this->_project = substr($key, 0, strpos($key, '-', 1));
		}
		return $this->_project;
	}

	/**
	 * save ticket in db
	 */
	public function save()
	{
		$this->_getResource()->save();
	}

	/**
	 * @return F4h_TicketConverter_Model_Resource_Ticket_Collection
	 */
	public function getCollection()
	{
		return new F4h_TicketConverter_Model_Resource_Ticket_Collection();
	}

	/**
	 * @return F4h_TicketConverter_Model_Resource_Ticket
	 */
	protected function _getResource()
	{
		if (!$this->_resource) {
			return new F4h_TicketConverter_Model_Resource_Ticket($this);
		}
		return $this->_resource;
	}

}