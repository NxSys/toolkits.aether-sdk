<?php
/**
 * $BaseName: IJob.php $
 * $Id: IJob.php 108 2018-04-07 00:04:43Z nxs.cfeamster $
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
 * @author $LastChangedBy: nxs.cfeamster $
 *
 * @version $Revision: 108 $
 */

/** @namespace Native Namespace */
namespace NxSys\Frameworks\Aether\Execution\Job;

/** Local Project Dependencies **/
use NxSys\Frameworks\Aether;

/** Framework Dependencies **/


/** Library Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;


/**
 * Interface for executable/runable object
 *
 * Why does this exist? What does this do?
 *
 * @throws NxSys\Frameworks\Aether\IException Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
 interface IJob extends CoreEsc\pthreads\IThread
 {

 }
