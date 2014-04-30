<?php
/**
 * Class Autoloader
 */
class Autoloader
{
	/**
	 * @param $classname
	 */
	public static function load($classname)
	{
		$path = str_replace('_', DIRECTORY_SEPARATOR, $classname);
		$filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $path . '.php';
		if (file_exists($filename)) {
			require_once $filename;
		}
	}
}

?>
