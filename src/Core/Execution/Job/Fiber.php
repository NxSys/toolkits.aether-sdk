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
namespace NxSys\Toolkits\Aether\SDK\Core\Execution\Job;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Core;

/** Framework Dependencies **/


/** Library Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;


/**
 * Implements a nominally cooperativly scheduled thread.
 * IE this "thread" should not run its own loops and instead periodically
 * check for interuption signals.
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\IException Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
abstract class Fiber extends BaseJob
{
	final public function start()
	{
		do
		{
			if(!$this->isSleep())
			{
				$this->work();
			}
			$bContinue=$this->isHalted();
		}while(true===$bContinue);
		return;
	}

	public function sleep($bStatus=true): bool
	{
		return $this->bIsSleep=$bStatus;
	}

	public function halt(): bool
	{
		return $this->bIsHalted=true;
	}

	protected function isSleep(): bool
	{
		return $this->bIsSleep;
	}

	protected function isHalted(): bool
	{
		return $this->bIsHalted;
	}



	protected abstract function work();
}
