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

/** Library Dependencies **/


/** System Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;
use Throwable;

class EventManager
{
    public function __construct()
    {
        $this->oEventQueue = new EventQueue();
        $this->oEventQueue->run();
        $this->aChannels = [-1 => []];
        $this->aEvents = [-1 => []];
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
        if (!$this->oEventQueue->hasEvents())
        {
            return;
        }
        //Get next event to process (should remove from queue)
        $oEvent = $this->oEventQueue->next();

        $aHandlerQueues = [0 => [], //Event + Channel specified
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
                $aHandlerQueues[0][] =& $oHandler;
            }
            elseif (count($oHandler->getChannels()) == 0) //Specfic Event, Wildcard Channel
            {
                $aHandlerQueues[1][] =& $oHandler;
            }
        }

        foreach ($aChannelHandlers as $oHandler)
        {
            //Specific channel + specific event already handled above, so just check specific channel + wildcard event
            if (count($oHandler->getEvents()) == 0)
            {
                $aHandlerQueues[2][] =& $oHandler;
            }
        }

        foreach($aChannels[-1] as $oHandler)
        {
            if (count($oHandler->getEvents()) == 0) //Global handlers
            {
                $aHandlerQueues[3][] =& $oHandler;
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
                $oHandler->handleEvent($oEvent);
            }
        }
    }

    public function addEvent(Event $oEvent)
    {
        //Any thread handling code goes here.
        // var_dump("Add event", $oEvent);
        $this->oEventQueue->add($oEvent);
    }

    public function getQueue() : EventQueue
    {
        return $this->oEventQueue;
    }
}