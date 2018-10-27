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
 * Action based thread that executes the ::tick method based on 
 * temporial criteria
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\IException Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @see Callable
 * @see hrtime
 */
class Timer extends Fiber
{
	public function tick()
	{
		# code...
	}
}
