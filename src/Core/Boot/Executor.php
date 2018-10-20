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

/** System Dependencies **/
use Throwable;

const ARG_CONFIG='config';

/**
 * Configures and Executes Aether component
 *
 * We'll use this to allocate resources, pull config, 
 * and initialize & launch the module.
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Executor extends sfConsole\Command\Command
{
	/**
	 * instance of the target class
	 *
	 * @var AbstractMain
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
	 * @param AbstractMain $oTarget
	 * @param string $sTargetShortname
	 */
	public function __construct(AbstractMain $oTarget, string $sTargetShortname)
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
						'alternative configuration root',
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
		$iRet=0;
		#start console logging
		//do allocation & resource checking
			//disk space? mem? dynamic modules/phars?

		//do config
			//load config
		
		#start configured logger
		//check to see if there are ACN Main exclusive events
		Container::boot($this->oInput->getOption(ARG_CONFIG));

		$sRunMode = $this->oTarget->getRunMode();
		switch ($sRunMode)
		{
			case 'maintenance':
			{
				$iRet = $this->oTarget->maintenanceRun();
			}
			case 'default':
			{
				$iRet = $this->start();
			}
			default:
			{
				$iRet = 1;
			}
		}
		return $iRet;
	}

	public function start(): int
	{
		$iErrCode=0;

		//do system initialization
			//DI
			//@todo setup Distributed Exception & Recovery System DXCR

		//do instantiation
		try
		{
			$return=$this->oTarget->proccessEvent();
		}
		catch(Throwable $e)
		{
			var_dump($e);
			$iErrCode= 1;
		}

		//finalization
		#log output

		//deallocation/completion
		return (int) $iErrCode;
	}
 }
