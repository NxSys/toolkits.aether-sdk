<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Comms;

use NxSys\Toolkits\Aether\SDK\Core;

class ListenerHostFiber extends Core\Execution\Job\Fiber implements Core\Boot\Event\EventListenerInterface
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
		//var_dump("Listener startup");
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
		$this->oListener=$oListener;
		$this->oListener->setThreadContext($this);
	}

	public function setEventQueue(Core\Boot\Event\EventQueue $oEventQueue)
	{
		$this->oEventQueue=$oEventQueue;
	}

	public function handleEvent(Core\Boot\Event\Event $oEvent)
	{
		// $this->oListener->
		//printf(">>>CHECKPOINT %s::%s:%s<<<", __CLASS__, __METHOD__, __LINE__);
		//var_dump($oEvent->getEvent());
		$this->pushIn(($oEvent));
	}
	public function getInEvent()
	{
		return $this->shiftIn();
	}

	public function getChannels() : array
	{
		return [];
	}

	public function getEvents() : array
	{
		return [];
	}

	public function getPriority() : int
	{
		return 0;
	}

	public function addEvent(Core\Boot\Event\Event $oEvent)
	{
		$this->oEventQueue->add($oEvent);
	}
}