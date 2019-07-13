<?php
/**
 * $BaseName$
 * $Id$
 *
 * DESCRIPTION
 *  A Core file for Aether.sh
 *
 * @link http://nxsys.org/spaces/aether
 * @link https://onx.zulipchat.com
 *
 * @package Aether
 * @subpackage SDK\Core
 * @license http://nxsys.org/spaces/aether/wiki/license
 * Please see the license.txt file or the url above for full copyright and license information.
 * @copyright Copyright 2018 Nexus Systems, inc.
 *
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 * @author $LastChangedBy$
 *
 * @version $Revision$
 */

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Core\Boot\Event;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Core;
use NxSys\Toolkits\Parallax\Channel;

/** Library Dependencies **/


/** System Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;
use Throwable;
use NxSys\Toolkits\Aether\SDK\Core\Boot\Container;

const EVENT_CHANNEL_ID = "Aether_Event_Channel";

class EventManager
{
    const EVENT_CHANNEL_ID = EVENT_CHANNEL_ID;

    public function __construct()
    {
        //$this->oEventQueue = new EventQueue();
        $this->oEventChannel = Channel::make(static::EVENT_CHANNEL_ID, 1024);
        $this->aChannels = [-1 => []];
		$this->aEvents = [-1 => []];
		$this->oLogger=Container::getDependency('sys.log');
		$this->oLogger->info('EventManager: Event manager instantiated', []);
    }

    public function addHandler(EventHandlerInterface $oHandler)
    {
        $aChannels = $oHandler->getChannels();

        if (count($aChannels) == 0)
        {
            //Wildcard channel
            $this->aChannels[-1][] =& $oHandler;
        }
        else
        {
            foreach ($aChannels as $sChannel)
            {
                $this->aChannels[$sChannel][] =& $oHandler;
            }
        }

        $aEvents = $oHandler->getEvents();

        if (count($aEvents) == 0)
        {
            //Wildcard event
            $this->aEvents[-1][] =& $oHandler;
        }
        else
        {
            foreach ($aEvents as $sEvent)
            {
                $this->aEvents[$sEvent][] =& $oHandler;
            }
        }

    }

    public function processEvent()
    {

        // printf(">>>CHECKPOINT %s::%s:%s<<<\n", __CLASS__, __FUNCTION__, __LINE__);
        //Get next event to process (should remove from queue)
        $oEvent = $this->oEventChannel->recv();

        $aHandlerQueues = [	0 => [], //Event + Channel specified
                            1 => [], //Event specified, Channel wildcard
                            2 => [], //Event wildcard, Channel specified
                            3 => []];//Event + Channel wildcard

        $sChannel = $oEvent->getChannel();
        $sEvent = $oEvent->getChannel();

        $aChannels = $this->aChannels;
        $aEvents = $this->aEvents;

        //Get handlers for the given channel.
        if (array_key_exists($sChannel, $aChannels))
        {
            $aChannelHandlers = $aChannels[$sChannel];
        }
        else
        {
            $aChannelHandlers = [];
        }

        //Get handlers for the given event type.
        if (array_key_exists($sEvent, $aEvents))
        {
            $aEventHandlers = $aEvents[$sEvent];
        }
        else
        {
            $aEventHandlers = [];
        }

        //Categorize handlers by specificity
        foreach ($aEventHandlers as $oHandler)
        {
            if (array_search($sChannel, $oHandler->getChannels())) //Specific Event+Channel
            {
                $aHandlerQueues[0][] = $oHandler;
            }
            elseif (count($oHandler->getChannels()) == 0) //Specfic Event, Wildcard Channel
            {
                $aHandlerQueues[1][] = $oHandler;
            }
        }

        foreach ($aChannelHandlers as $oHandler)
        {
            //Specific channel + specific event already handled above, so just check specific channel + wildcard event
            if (count($oHandler->getEvents()) == 0)
            {
                $aHandlerQueues[2][] = $oHandler;
            }
        }

        foreach($aChannels[-1] as $oHandler)
        {
            if (count($oHandler->getEvents()) == 0) //Global handlers
            {
                $aHandlerQueues[3][] = $oHandler;
            }
        }

        //Loop through handlers and notify.
        foreach ($aHandlerQueues as $aHandlerQueue)
        {
            //Sort by priority
            usort($aHandlerQueue, function ($a, $b) {return $a->getPriority() <=> $b->getPriority();});
            foreach ($aHandlerQueue as $oHandler)
            {
                //Notify listener.
                $iExecutionTime = -microtime(true);
                $oHandler->handleEvent($oEvent);
                $iExecutionTime += microtime(true);

                if ($iExecutionTime > 0.1) //100ms
                {
                    $this->oLogger->warn('EventManager: Event process taking too long.', ["time" => $iExecutionTime, "event" => $oEvent, "handler" => get_class($oHandler)]);
                }
            }
        }
    }

    static function addEvent(Event $oEvent)
    {
        //Any thread handling code goes here.
        // var_dump("Add event", $oEvent);
        $oEventChannel = Channel::open(static::EVENT_CHANNEL_ID);

        $oEventChannel->send($oEvent);
    }
}