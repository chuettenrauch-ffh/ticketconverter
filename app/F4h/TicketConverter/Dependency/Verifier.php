<?php
/**
 * This file is part of Ticketconverter.
 *
 * @category developer tool
 * @package ticketconverter
 *
 * @author Christoph Jaecks <christoph.jaecks@fashionforhome.de>
 * @author Tino Stöckel <tino.stoeckel@fashionforhome.de>
 *
 * @copyright (c) 2012 by fashion4home GmbH <www.fashionforhome.de>
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
