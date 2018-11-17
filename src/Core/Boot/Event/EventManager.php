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
use NxSys\Core\ExtensibleSystemClasses\standard\__PHP_Incomplete_Class;

class EventManager
{
    public function __construct()
    {
        $this->oEventQueue = new EventQueue();
        $this->oEventQueue->run();
        $this->aChannels = [];
        $this->aEvents = [];
    }

    public function addListener(EventListenerInterface $oListener)
    {
        $aChannels = $oListener->getChannels();
        
        if (count($aChannels) == 0)
        {
            //Wildcard channel
            $this->aChannels[-1][] =& $oListener;
        }
        else
        {
            foreach ($aChannels as $sChannel)
            {
                $this->aChannels[$sChannel][] =& $oListener;
            }
        }

        $aEvents = $oListener->getEvents();

        if (count($aEvents) == 0)
        {
            //Wildcard event
            $this->aEvents[-1][] =& $oListener;
        }
        else
        {
            foreach ($aEvents as $sEvent)
            {
                $this->aEvents[$sEvent][] =& $oListener;
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

        $aListenerQueues = [0 => [], //Event + Channel specified
                            1 => [], //Event specified, Channel wildcard
                            2 => [], //Event wildcard, Channel specified
                            3 => []];//Event + Channel wildcard

        $sChannel = $oEvent->getChannel();
        $sEvent = $oEvent->getChannel();

        $aChannels = $this->aChannels;
        $aEvents = $this->aEvents;
        
        //Get listeners for the given channel.
        if (array_key_exists($sChannel, $aChannels))
        {
            $aChannelListeners = $aChannels[$sChannel];
        }
        else
        {
            $aChannelListeners = [];
        }

        //Get listeners for the given event type.
        if (array_key_exists($sEvent, $aEvents))
        {
            $aEventListeners = $aEvents[$sEvent];
        }
        else
        {
            $aEventListeners = [];
        }

        //Categorize listeners by specificity
        foreach ($aEventListeners as $oListener)
        {
            if (array_search($sChannel, $oListener->getChannels())) //Specific Event+Channel
            {
                $aListenerQueues[0][] =& $oListener;
            }
            elseif (count($oListener->getChannels()) == 0) //Specfic Event, Wildcard Channel
            {
                $aListenerQueues[1][] =& $oListener;
            }
        }

        foreach ($aChannelListeners as $oListener)
        {
            //Specific channel + specific event already handled above, so just check specific channel + wildcard event
            if (count($oListener->getEvents()) == 0)
            {
                $aListenerQueues[2][] =& $oListener;
            }
        }

        foreach($aChannels[-1] as $oListener)
        {
            if (count($oListener->getEvents()) == 0) //Global listeners
            {
                $aListenerQueues[3][] =& $oListener;
            }
        }

        //Loop through listeners and notify.
        foreach ($aListenerQueues as $aListenerQueue)
        {
            //Sort by priority
            usort($aListenerQueue, function ($a, $b) {return $a->getPriority() <=> $b->getPriority();});

            foreach ($aListenerQueue as $oListener)
            {
                //Notify listener.
                $oListener->handleEvent($oEvent);
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