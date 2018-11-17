<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;


interface EventListenerInterface
{
	public function handleEvent(Event $oEvent);

	public function getChannels() : array;

	public function getEvents() : array;

	public function getPriority() : int;
}