<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Comms;

use NxSys\Toolkits\Aether\SDK\Core;
use NxSys\Toolkits\Parallax;
use NxSys\Toolkits\Aether\SDK\Core\Boot\Event\EventManager;


class ListenerHostFiber extends Parallax\Job\Fiber implements Core\Boot\Event\EventHandlerInterface
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
		// echo "Doing work>>>>\n";

		$this->oListener->listenLoop();
		// echo "<<<<Done work";
	}

	public function onStartup()
	{
		$this->oListener->setThreadContext($this);
		//require_once APP_AUTOLOADER_FILE;
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
		//$oListener->setThreadContext($this);
		$this->oListener=$oListener;
	}

	public function handleEvent(Core\Boot\Event\Event $oEvent)
	{
		//printf(">>>CHECKPOINT %s::%s:%s<<<", __CLASS__, __METHOD__, __LINE__);
		//var_dump($this->getThreadId());
		//var_dump($oEvent->getEvent());

		//$this->pushIn(($oEvent));
		//var_dump($this->oListener::$oThruwayHandler->aTerminals);
		//$this->oListener->processEvents($this->oListener::$oThruwayHandler);
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
		Core\Boot\Event\EventManager::addEvent($oEvent);
	}
}