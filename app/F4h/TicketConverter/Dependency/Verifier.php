<?php
/**
 * Class F4h_TicketConverter_Dependency_Verifier
 */
class F4h_TicketConverter_Dependency_Verifier
{
	/**
	 * @return bool
	 * @throws F4h_TicketConverter_Exception_Dependency
	 */
	public static function checkDependencies()
	{
		$missing = array();

		try {
			self::_checkExtensions();
		} catch (Exception $e) {
			$missing[] = $e->getMessage();
		}

		try {
			self::_checkJre();
		} catch (Exception $e) {
			$missing[] = $e->getMessage();
		}

		try {
			self::_checkApacheFop();
		} catch (Exception $e) {
			$missing[] = $e->getMessage();
		}

		if (!empty($missing)) {
			throw new F4h_TicketConverter_Exception_Dependency('The following dependencies are missing: ' . implode('; ', $missing));
		}

		return true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	protected static function _checkJre()
	{
		$cliOutput = shell_exec('which java'); //"java -version" doesn't work, because printed output lines aren't transfered to $cliOutput
		if (!$cliOutput) {
			throw new Exception('Java Runtime Environment');
		}
		return true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	protected static function _checkExtensions()
	{
		$dependencies = F4h_TicketConverter_Config::getInstance()->getDependencies();
		$missing = array();

		foreach ($dependencies as $dependency) {
			if (!extension_loaded($dependency)) {
				$missing[] = $dependency;
			}
		}

		if (!empty($missing)) {
			throw new Exception('PHP Extensions: ' . implode(', ', $missing));
		}
		return true;
	}

	/**
	 * @return bool
	 * @throws Exception
	 */
	protected static function _checkApacheFop()
	{
		$path = F4h_TicketConverter_Config::getInstance()->getApacheFopPath();
		$cliOutput = shell_exec($path . ' -version');
		if (!$cliOutput) {
			throw new Exception('Apache FOP in ' . $path);
		}
		return true;
	}

}

?>
