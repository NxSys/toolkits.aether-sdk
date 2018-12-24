<?php
/**
 * $BaseName$
 * $Id$
 *
 * DESCRIPTION
 *  A Core file for Aether.sh
 *
 * @link http://nxsys.org/spaces/aether
 * @link https://onx.zulipchat.com
 *
 * @package Aether
 * @subpackage SDK\Core
 * @license http://nxsys.org/spaces/aether/wiki/license
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2018 Nexus Systems, Inc.
 *
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @author $LastChangedBy$
 *
 * @version $Revision$
 */

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Core\Boot;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Core;

/** Library Dependencies **/
use Symfony\Component\Console as sfConsole;
use Monolog,
	Monolog\Logger;

/** System Dependencies **/
use Throwable;

const ARG_CONFIG='config';

/**
 * Configures and Executes Aether component
 *
 * We'll use this to allocate resources, pull config,
 * and initialize & launch the module (an ACN or a RCE).
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Executor extends sfConsole\Command\Command
{
	/**
	 * instance of the target class
	 *
	 * @var Main
	 */
	public $oTarget;

	/**
	 * short displayed name of the target
	 *
	 * @var string
	 */
	public $sTargetShortname;

	const ARG_CONFIG=ARG_CONFIG;

	/**
	 * ctor
	 *
	 * @param Main $oTarget
	 * @param string $sTargetShortname
	 */
	public function __construct(Main $oTarget, string $sTargetShortname)
	{
		$this->oTarget=$oTarget;
		$this->sTargetShortname=$sTargetShortname;
		parent::__construct();
	}


	public function configure()
	{
		$this
			->setName($this->sTargetShortname)
			->addOption(ARG_CONFIG, ARG_CONFIG[0],
						sfConsole\Input\InputOption::VALUE_OPTIONAL,
						'alternative configuration root (xml)',
						$this->sTargetShortname . '.xml');
	}

	/**
	 * Main entry point via sfConsole Application
	 *
	 * @param sfConsole\Input\InputInterface $oInput
	 * @param sfConsole\Output\OutputInterface $oPut
	 * @return integer
	 */
	public function execute(sfConsole\Input\InputInterface $oInput, sfConsole\Output\OutputInterface $oPut): int
	{
		$this->oInput = $oInput;
		$this->oOutput = $oPut;
		$this->oLogger = new Logger(strtoupper($this->sTargetShortname));
		$iRet=0;

		//start console logging
		#this is the bog standard ML logger
		$oLogHandler=new Monolog\Handler\StreamHandler('php://stderr', Logger::DEBUG); //@todo us sFCon for output
		$oFmttr=new Monolog\Formatter\LineFormatter;
		// $oFmttr-> @todo streplace \\ with \
		$oLogHandler->setFormatter($oFmttr);
		$this->oLogger->pushHandler($oLogHandler);
		// $this->oLogger->pushProcessor(new Monolog\Processor\HostnameProcessor); ML v2
		$this->oLogger->pushProcessor(new Monolog\Processor\ProcessIdProcessor);
		$this->oLogger->pushProcessor(new Monolog\Processor\MemoryPeakUsageProcessor);
		$this->oLogger->pushProcessor(new Monolog\Processor\MemoryUsageProcessor);
		// $this->oLogger->pushProcessor(new Monolog\Processor\IntrospectionProcessor(Logger::DEBUG, [], 3));

		//do allocation & resource checking
			//disk space? mem? dynamic modules/phars?

		//do config
			//load config

		#start configured logger
		//check to see if there are ACN Main exclusive configs
		//@todo make Container agnostic
		Container::boot($this->oInput->getOption(ARG_CONFIG));

		Container::setDependency('sys.log',
			(new class ([$this, "log"]) //extends \Psr\Log\AbstractLogger
			{
				function __construct($oTarget)
				{
					$this->oTarget=$oTarget;
				}
				function __call($sMethodName, $aArgs)
				{
					$oTarget = $this->oTarget;
					$oTarget(...$aArgs);
				}

			})
		);


			//yes, yes affecting state would indeed be passed through the Container
		$sRunMode = $this->oTarget->getRunMode();
		switch ($sRunMode)
		{
			case 'maintenance':
			{
				$iRet = $this->oTarget->maintenanceRun();
			}
			case 'default':
			{
				//do system initialization
				//DI
				//@todo setup Distributed Exception & Recovery System DXCR
				$iRet = $this->oTarget->start();
			}
			default:
			{
				$iRet = -1;
			}
		}
		//finalization
		#log output

		//deallocation/completion
		return (int) $iRet;
	}

	/**
	 * Global log message function
	 *
	 * @param string $sMsg
	 * @param [type] ...$aOpts
	 * @return void
	 */
	public function log($sMsg, ...$aOpts)
	{
		//$this->oOutput->writeln($aOpts[0]['context'] . ': ' . $sMsg);

		$this->oLogger->info($sMsg, $aOpts);
	}
}
