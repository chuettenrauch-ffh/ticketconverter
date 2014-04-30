<?php
/**
 * Class F4h_TicketConverter_Data_Converter_Factory
 */
class F4h_TicketConverter_Data_Converter_Factory
{

    private function __construct()
    {
        
    }

    private function __clone()
    {
        
    }

	/**
	 * @return F4h_TicketConverter_Data_Converter_Xsl_ToFo|F4h_TicketConverter_Data_Converter_Xsl_ToHtml
	 */
	public static function getConverter()
    {
		switch (F4h_TicketConverter_Config::getInstance()->getOutputType()) {
			case F4h_TicketConverter_Config::OUTPUT_PDF:
				return new F4h_TicketConverter_Data_Converter_Xsl_ToFo();
				break;
			case F4h_TicketConverter_Config::OUTPUT_HTML:
				return new F4h_TicketConverter_Data_Converter_Xsl_ToHtml();
				break;
		}
	}
}
