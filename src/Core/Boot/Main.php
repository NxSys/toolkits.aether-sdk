<?php
/**
 * $BaseName$
 * $Id$
 *
 * DESCRIPTION
 *  Application Entrypoint for ACN
 *
 * @link http://nxsys.org/spaces/aether
 * @link https://onx.zulipchat.com
 *
 * @package Aether
 * @subpackage System
 * @license http://nxsys.org/spaces/aether/wiki/license
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2018 Nexus Systems, inc.
 *
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @author $LastChangedBy$
 *
 * @version $Revision$
 */

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Core\Boot;

//Domestic Namespaces
use NxSys\Applications\Aether;
use NxSys\Toolkits\Aether\SDK\Core;

//Framework Namespaces
use Symfony\Component\Console as sfConsole;
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;

abstract class Main
{
	/**
	 * Returns the short command name for the component
	 * @return string Short command name
	 */
	abstract public function getShortName(): string;

	/**
	 * Allows an simple overide to process any startup options/events 
	 * which Executor doesn't handle
	 *
	 * @return void
	 */
	public function maintenanceRun()
	{
		return;
	}

	abstract function proccessEvent() /* ¯\_(ツ)_/¯ */;

	/**
	 * Should at least return 'default'
	 *
	 * @return string
	 */
	abstract public function getRunMode(): string;
	abstract public function start(): int;

	#---	

	#---
	public function log(string $sMsg)
	{
		Container::getDependency('sys.log')->log($sMsg,
		['channel'=> get_called_class()]
	);
	}	
}
