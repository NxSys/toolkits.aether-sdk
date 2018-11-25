<?php

namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;
/**
                                    xxxxxxx
                               x xxxxxxxxxxxxx x
                            x     xxxxxxxxxxx     x
                                   xxxxxxxxx
                         x          xxxxxxx          x
                                     xxxxx
                        x             xxx             x
                                       x
                       xxxxxxxxxxxxxxx   xxxxxxxxxxxxxxx
                        xxxxxxxxxxxxx     xxxxxxxxxxxxx
                         xxxxxxxxxxx       xxxxxxxxxxx
                          xxxxxxxxx         xxxxxxxxx
                            xxxxxx           xxxxxx
                              xxx             xxx
                                  x         x
                                       x
 * Undocumented class
 */

 use NxSys\Toolkits\Aether\SDK\Core\Boot\Event\Event;
 use NxSys\Toolkits\Aether\SDK\Core\Boot\Event\EventManager;
 use NxSys\Toolkits\Aether\SDK\Core\Boot\Event\PassthroughEventHandler;

const DEFAULT_HANDLE_METHOD = "handleEvent";

 class EventManagerFactory
{
    const DEFAULT_HANDLE_METHOD = DEFAULT_HANDLE_METHOD;

    private $oEventManager = null;

    public function __construct($oContainer)
    {
        $this->oEventManager = new EventManager();
        $aHandlers = $oContainer->findTaggedServiceIds('event.autohandle');
        foreach ($aHandlers as $sService => $aHandler)
        {
            //Service to call
            $oService = $oContainer->get($sService);
            
            $aHandler = array_shift($aHandler);
            
            //Method to call
            if (!array_key_exists('method', $aHandler)  ||
                 $aHandler['method'] === null           || 
                 $aHandler['method'] === "")
            {
                $sMethod = DEFAULT_HANDLE_METHOD;
            }
            else
            {
                $sMethod = $aHandler['method'];
            }

            //Channels to listen to
            if (!array_key_exists('channels', $aHandler)  ||
                 $aHandler['channels'] === null           || 
                 $aHandler['channels'] === "")
            {
                $aChannels = [];
            }
            else
            {
                $aChannels = explode("|", $aHandler['channels']);
            }
            
            //Events to listen to
            if (!array_key_exists('events', $aHandler)  ||
                 $aHandler['events'] === null           || 
                 $aHandler['events'] === "")
            {
                $aEvents = [];
            }
            else
            {
                $aEvents = explode("|", $aHandler['events']);
            }

            //Priority
            if (!array_key_exists('priority', $aHandler)  ||
                 $aHandler['priority'] === null           || 
                 $aHandler['priority'] === "")
            {
                $iPriority = Event::EVENT_PRIORITY_NORMAL;
            }
            elseif (is_string($aHandler['priority']))
            {
                //Nothing to see here, move along.
                $sPriorityConst = $aHandler['priority'];
                $iPriority = constant(Event::class . '::' . $sPriorityConst);
            }
            else
            {
                $iPriority = $aHandler['priority'];
            }
            $oHandler = new PassthroughEventHandler($oService, $sMethod, $aChannels, $aEvents, $iPriority);

            $this->oEventManager->addHandler($oHandler);
        }
    }

    public function initializeEventManager()
    {
        return $this->oEventManager;
    }    
}