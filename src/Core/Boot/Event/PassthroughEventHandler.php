<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;

class PassthroughEventHandler implements EventHandlerInterface
{
    public function __construct(object $oService, string $sMethodName, array $aChannels, array $aEvents, int $iPriority)
    {
        $this->oService = $oService;
        $this->sMethodName = $sMethodName;
        $this->aChannels = $aChannels;
        $this->aEvents = $aEvents;
        $this->iPriority = $iPriority;
    }

	public function handleEvent(Event $oEvent)
	{
        $this->oService->{$this->sMethodName}($oEvent);
	}

    public function getChannels() : array
    {
        return $this->aChannels;
    }

	public function getEvents() : array
    {
        return $this->aEvents;
    }

    public function getPriority() : int
    { 
        return $this->iPriority;
    }

}
