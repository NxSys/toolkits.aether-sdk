<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;


use SplQueue;
use Threaded;

class EventQueue extends Threaded
{
	/** @var Event[] $aQueue Event Queue */
	public $aQueue;

	public function __construct()
	{
		//Threaded::extend('SplDoublyLinkedList'); //because reasons.....
		$this->aQueue=new SplQueue;
	}

	public function add(Event $oEv): void
	{
		//Dereferencing, because.
		$q = $this->aQueue;
		$q->enqueue($oEv);
		$this->aQueue = $q;
		return;
	}

	public function getQueue(): array
	{
		return (array)$this->aQueue;
	}

	public function hasEvents(): bool
	{
		if ($this->aQueue->isEmpty())
		{
			return false;
		}
		return true;
	}

	public function next(): Event
	{
		//Dereferencing, because.
		$q = $this->aQueue;
		$ret = $q->dequeue();
		$this->aQueue = $q;
		return $ret;
	}
}

class EventQueueArray extends SplQueue 
{
	
}
