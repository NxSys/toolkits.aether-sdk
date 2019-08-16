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
 * @copyright Copyright 2018 Nexus Systems, inc.
 *
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @author $LastChangedBy$
 *
 * @version $Revision$
 */

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Core\Comms;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Core;

/** Library Dependencies **/

use NxSys\Core\ExtensibleSystemClasses\SPL\SplObjectStorage;
// use SplObjectStorage
use Threaded;

/**
 * Undocumented class
 *
 * Why does this exist? What does this do?
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Comms\CommsExceptionType Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
abstract class BaseListener implements IListener
{
	/**
	 * @var Core\Execution\Job\Fiber
	 */
	public $oThreadContext;

	/**
	 * @var SplObjectStorage
	 */
	public $hHandlerQueue;

	/** @var string $sListenAddress listen interface */
	public $sListenAddresss='127.0.0.1';
	/** @var string $sListenPort description */
	public $sListenPort;

	/** @var array $aListenInterfaces [[ip, port]...] */
	public $aListenInterfaces=[];

	public function __construct()
	{
		// $this->hHandlerQueue=new SplObjectStorage;
	}

	public function configure(string $sListen)
	{
		# code...
	}

	abstract public function listenLoop(): void;

	abstract public function processEvents(): void;


	public function registerNewHandler(Callable $hHandler)
	{
		//$this->hHandlerQueue->attach($hHandler);
	}

	public function setThreadContext(Core\Comms\ListenerHostFiber $oThread)
	{
		$this->oThreadContext = $oThread;
	}

	public function getThreadContext()//: Core\Comms\ListenerHostFiber
	{
		return $this->oThreadContext;
	}

}
