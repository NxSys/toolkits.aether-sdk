<?php
/**
 * $BaseName: Coroutine.php $
 * $Id: Coroutine.php 111 2018-04-07 00:39:15Z nxs.cfeamster $
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
 * @version $Revision: 111 $
 */

/** @namespace Native Namespace */
namespace NxSys\Frameworks\Aether\Execution\Job;

/** Local Project Dependencies **/
use NxSys\Frameworks\Aether;

/** Framework Dependencies **/


/** Library Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;


/**
 * Enables the execution of a Callable as a Coroutine
 *
 * Why does this exist? What does this do?
 *
 * @throws NxSys\Frameworks\Aether\IException Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @see Callable
 */
class Coroutine extends Async
{
	public $params;
	protected $_oTargetObject;
	public $return;
	// use ESC\DecoratingTrait

	public function __invoke()
	{
		$this->params=func_get_args();
		return $this->start();
	}

	public function setRoutine(callable $hRoutine)
	{
		$this->_oTargetObject=$hRoutine;
	}

	public function run()
	{
		//if $this->_oTargetObject object
			//if object instance of CoroutineAware
			//object->setThreadReference($this)

		//Target is prob Volatile
		$this->return=((array)$this->_oTargetObject)(...$this->params);
	}

	public function yield(mixed $value = null)
	{
		$this->mYieldedResult=$value;
	}
}
