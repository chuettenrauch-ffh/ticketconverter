<?php
/**
 * Class F4h_TicketConverter_Config
 */
class F4h_TicketConverter_Config
{
	const ENV_CLI = 1;
	const ENV_WEB = 2;
	const LOGGING = 1; //0 = off; 1 = on;
	const USERNAME = 'ticketprinter';
	const PASSWORD = 'j0fd8wfej333';
	const OUTPUT_PDF = 'fo';
	const OUTPUT_HTML = 'html';
	const SPRINTSURL = "https://fashion4home.jira.com/rest/greenhopper/1.0/sprintquery/13?includeFutureSprints=true";

	private static $instance;
	private $applicationPath;
	private $logFile = 'log.txt';
	private $outputFile;
	private $dependencies = array('curl', 'xsl');
	private $environment;
	private $ticketUrl = 'https://fashion4home.jira.com/si/jira.issueviews:issue-xml/§-*/§-*.xml';
	private $project = 'DMF';
	private $outputType = self::OUTPUT_PDF;
	private $stylesheet = 'ticket_to_fo.xsl';
	private $apacheFopPath = '/home/dev/lib/fop-1.0/fop';
	private $printerName = 'ticketprinter';

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
	 * @return F4h_TicketConverter_Config
	 */
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new F4h_TicketConverter_Config();
		}
		return self::$instance;
	}

	/**
	 * @param $path
	 * @return mixed
	 */
	public function setApplicationPath($path)
	{
		if (!$this->applicationPath) {
			$chunks = explode(DIRECTORY_SEPARATOR, $path);

			$size = count($chunks);
			for ($i = 0; $i < $size - 1; $i++) {
				$this->applicationPath .= $chunks[$i] . DIRECTORY_SEPARATOR;
			}
			$this->setLogFile()->setOutputFile();
		}
		return self::$instance;
	}

	/**
	 * @return mixed
	 */
	public function getApplicationPath()
	{
		return $this->applicationPath;
	}

	/**
	 * @return mixed
	 */
	private function setLogFile()
	{
		$this->logFile = $this->getApplicationPath() . 'log' . DIRECTORY_SEPARATOR . $this->logFile;
		return self::$instance;
	}

	/**
	 * @return string
	 */
	public function getLogFile()
	{
		return $this->logFile;
	}

	/**
	 * @return mixed
	 */
	private function setOutputFile()
	{
		$this->outputFile = $this->getApplicationPath() . 'public' . DIRECTORY_SEPARATOR . 'output.' . $this->getOutputType();
		return self::$instance;
	}

	/**
	 * @return mixed
	 */
	public function getOutputFile()
	{
		return $this->outputFile;
	}

	/**
	 * @return array
	 */
	public function getDependencies()
	{
		return $this->dependencies;
	}

	/**
	 * @param $env
	 * @return mixed
	 */
	public function setEnvironment($env)
	{
		$this->environment = $env;
		return self::$instance;
	}

	/**
	 * @return mixed
	 */
	public function getEnvironment()
	{
		return $this->environment;
	}

	/**
	 * @return string
	 */
	public function getAuthorizationString()
	{
		return base64_encode(self::USERNAME . ':' . self::PASSWORD);
	}

	/**
	 * @param $project
	 */
	public function setProject($project)
	{
		$this->project = $project;
	}

	/**
	 * @return string
	 */
	public function getProject()
	{
		return $this->project;
	}

	/**
	 * @return mixed
	 */
	public function getMaskedTicketUrl()
	{
		return str_replace('§', $this->project, $this->ticketUrl);
	}

	/**
	 * @return string
	 */
	public function getOutputType()
	{
		return $this->outputType;
	}

	/**
	 * @return string
	 */
	public function getStylesheetPath()
	{
		return $this->getApplicationPath() . 'xsl' . DIRECTORY_SEPARATOR . $this->stylesheet;
	}

	/**
	 * @return string
	 */
	public function getApacheFopPath()
	{
		return $this->apacheFopPath;
	}

	/**
	 * @return string
	 */
	public function getPrinterName()
	{
		return $this->printerName;
	}

}