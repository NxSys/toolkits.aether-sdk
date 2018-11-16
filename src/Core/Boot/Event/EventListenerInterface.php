<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;


interface EventListenerInterface
{
	public function handleEvent(Event $oEvent);

	static function getChannels() : array;

	static function getEvents() : array;

	static function getPriority() : int;
}