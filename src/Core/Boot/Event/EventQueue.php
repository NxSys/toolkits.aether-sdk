<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;

class EventQueue
{
	/** @var Event[] $aQueue Event Queue */
	public $aQueue=[];

	public function add(Event $oEv): void
	{
		array_push($this->aQueue, $oEv);
		return;
	}

	public function getQueue(): array
	{
		return $this->aQueue;
	}
}