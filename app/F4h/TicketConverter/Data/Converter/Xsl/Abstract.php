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
 * @copyright (c) 2015 by fashion4home GmbH <www.fashionforhome.de>
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
 * Class F4h_TicketConverter_Data_Converter_Xsl_Abstract
 */
abstract class F4h_TicketConverter_Data_Converter_Xsl_Abstract
{

    protected $stylesheet;
    protected $overview;

    public function __construct()
    {
        
    }

	/**
	 * @param $stylesheetPath
	 * @return $this
	 * @throws F4h_TicketConverter_Exception_Xsl_Stylesheet
	 */
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

	/**
	 * @return mixed
	 */
    public function getStylesheet()
    {
        return $this->stylesheet;
    }

	/**
	 * @param $overviewPath
	 * @return $this
	 */
    public function setOverview($overviewPath)
    {
        //load file collector xml to DOM Object
        $this->overview = new DOMDocument();
        $this->overview->load($overviewPath);

        return $this;
    }

	/**
	 * @return mixed
	 */
    public function getOverview()
    {
        return $this->overview;
    }

	/**
	 * @param DOMDocument $dom
	 * @return string
	 * @throws F4h_TicketConverter_Exception_File_Permission
	 * @throws F4h_TicketConverter_Exception_File_Write
	 * @throws F4h_TicketConverter_Exception_Xsl_Stylesheet
	 */
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

	/**
	 * @param $path
	 * @return bool
	 */
	protected function _checkFilePermissions($path)
	{
		$grpInfo = posix_getgrgid(filegroup($path));
		$correctGroup = $this->_getNeededGroupForEnvironment();
		
		if ($grpInfo['name'] !== $correctGroup || floor(fileperms($path) / 10) % 10 !== 7) {
			return false;
		}
		return true;
	}

	/**
	 * @return null|string
	 */
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

	/**
	 * @param DOMDocument $doc
	 * @param $path
	 * @return mixed
	 */
	abstract protected function _save(DOMDocument $doc, $path);

}
