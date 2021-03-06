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

use Thread;

abstract class Main implements Core\Boot\Event\EventHandlerInterface
{
	/** @var Core\Execution\WatchDog $oWatchDog ThreadInspector */
	protected $oWatchDog = null;

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

	abstract function handleEvent(Core\Boot\Event\Event $oEvent) /* ¯\_(ツ)_/¯ */;


	/**
	 * Should at least return 'default'
	 *
	 * @return string
	 */
	abstract public function getRunMode(): string;
	abstract public function start(): int;

	#---

	public function registerThreadOnWatchdog(Thread $hThread)
	{
		if (!$this->oWatchDog)
		{
			$this->oWatchDog=new Core\Execution\WatchDog;
		}
		$this->oWatchDog->registerThread($hThread);
		return;
	}
	/**
	 * 
	 * @return array of deadthread class names
	 */
	public function checkThreadWatchDog(): array
	{
		$errs=[];
		foreach($this->oWatchDog->inspectThreads() as $problems)
		{
			$errs[]=get_class($problems);
		}
		return $errs;
	}


	#---
	public function log(string $sMsg, array $context=[])
	{
		Container::getDependency('sys.log')->log($sMsg
			, $context
			// ,['from'=> get_called_class()]
		);
	}
}
