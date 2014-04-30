<?php
/**
 * Class F4h_TicketConverter_Data_Converter_Xsl_ToFo
 */
class F4h_TicketConverter_Data_Converter_Xsl_ToFo extends F4h_TicketConverter_Data_Converter_Xsl_Abstract
{
	/**
	 * @param DOMDocument $doc
	 * @param             $path
	 * @return mixed
	 * @throws F4h_TicketConverter_Exception_File_Write
	 */
	protected function _save(DOMDocument $doc, $path)
	{
		try {
			$doc->save($path);
		} catch (Exception $e) {
			throw new F4h_TicketConverter_Exception_File_Write();
		}
		return $path;
	}
}