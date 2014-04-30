<?php
/**
 * Class F4h_TicketConverter_Data_Converter_Xsl_Abstract
 */
abstract class F4h_TicketConverter_Data_Converter_Xsl_Abstract
{

    protected $stylesheet;
    protected $overview;

    public function __construct()
    {
        
    }

    public function setStylesheet($stylesheetPath)
    {
        //load xsl stylesheet to DOM Object
        $this->stylesheet = new DOMDocument();
        try {
			$this->stylesheet->load($stylesheetPath);
		} catch (Exception $e) {
			throw new F4h_TicketConverter_Exception_Xsl_Stylesheet($e->getMessage());
		}
        return $this;
    }

    public function getStylesheet()
    {
        return $this->stylesheet;
    }

    public function setOverview($overviewPath)
    {
        //load file collector xml to DOM Object
        $this->overview = new DOMDocument();
        $this->overview->load($overviewPath);
        return $this;
    }

    public function getOverview()
    {
        return $this->overview;
    }

    public function convert(DOMDocument $dom)
    {
        $outputPath = '';

        try {
            //assign stylesheet DOM to xslt processor, 
            $proc = new XSLTProcessor();
            $proc->importStylesheet($this->getStylesheet());

            //start converting & save to html
			$doc = $proc->transformToDoc($dom);
        } catch (Exception $e) {
            throw new F4h_TicketConverter_Exception_Xsl_Stylesheet($e->getMessage());
        }
		
		$path = F4h_TicketConverter_Config::getInstance()->getOutputFile();
		
		if (!$this->_checkFilePermissions($path)) {
			throw new F4h_TicketConverter_Exception_File_Permission('FAILURE: ' . $path . ' has wrong file permissions');
		}
		
		$outputPath = $this->_save($doc, $path);
		if (!file_exists($outputPath) || !filesize($outputPath) > 0) {
			throw new F4h_TicketConverter_Exception_File_Write();
		}

        return $outputPath;
    }
	
	protected function _checkFilePermissions($path)
	{
		$grpInfo = posix_getgrgid(filegroup($path));
		$correctGroup = $this->_getNeededGroupForEnvironment();
		
		if ($grpInfo['name'] !== $correctGroup || floor(fileperms($path) / 10) % 10 !== 7) {
			return false;
		}
		return true;
	}
	
	protected function _getNeededGroupForEnvironment()
	{
		$correctGroup = null;
		switch (F4h_TicketConverter_Config::getInstance()->getEnvironment()) {
			case (F4h_TicketConverter_Config::ENV_WEB):
				$correctGroup = 'www-data';
				break;
			case (F4h_TicketConverter_Config::ENV_CLI):
				$output = array();
				exec('whoami', $output);
				$correctGroup = $output[0];
				break;
		}
		
		return $correctGroup;
	}
	
	abstract protected function _save(DOMDocument $doc, $path);

}
