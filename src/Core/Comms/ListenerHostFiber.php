<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Comms;

use NxSys\Toolkits\Aether\SDK\Core;

class ListenerHostFiber extends Core\Execution\Job\Fiber
{
	public $aThreadData=[];

	/**
	 * $oListener
	 *
	 * @var IListener
	 */
	public $oListener;

	public function work()
	{
		echo 'Doing work';
		$this->oListener->listenLoop();	
		echo 'Done work';
	}

	public function onStartup()
	{
		require_once APP_AUTOLOADER_FILE;
		var_dump("Listener startup");
	}

	public function fiberSignal()
	{
		if($this->isSleep())
		{
			return 'sleep';
		}
		elseif($this->isHalted())
		{
			return 'halt';
		}
		return;
	}

	public function setListener(IListener $oListener)
	{
		var_dump("Setting Listener");
		$this->oListener=$oListener;
		$this->oListener->setThreadContext($this);
	}
}