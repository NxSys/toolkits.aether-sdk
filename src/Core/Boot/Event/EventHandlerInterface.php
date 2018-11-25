<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;


interface EventHandlerInterface
{
	public function handleEvent(Event $oEvent);

	public function getChannels() : array;

	public function getEvents() : array;

	public function getPriority() : int;
}