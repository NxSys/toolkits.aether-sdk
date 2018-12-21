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


const EVENT_PRIORITY_REALTIME       = 8;
const EVENT_PRIORITY_HIGH           = 16;
const EVENT_PRIORITY_ABOVENORMAL    = 32;
const EVENT_PRIORITY_NORMAL         = 64;
const EVENT_PRIORITY_BELOWNORMAL    = 128;
const EVENT_PRIORITY_IDLE           = 256;

/**
 * 
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Event
{
	const EVENT_PRIORITY_REALTIME   = EVENT_PRIORITY_REALTIME;
	const EVENT_PRIORITY_HIGH       = EVENT_PRIORITY_HIGH;
	const EVENT_PRIORITY_ABOVENORMAL= EVENT_PRIORITY_ABOVENORMAL;
	const EVENT_PRIORITY_NORMAL     = EVENT_PRIORITY_NORMAL;
	const EVENT_PRIORITY_BELOWNORMAL= EVENT_PRIORITY_BELOWNORMAL;
	const EVENT_PRIORITY_IDLE		= EVENT_PRIORITY_IDLE;

    public $sChannel;
    public $sEvent;
    protected $aData;

    public function __construct(string $sChannel, string $sEvent, array $aData = [])
    {
        $this->sChannel = $sChannel;
        $this->sEvent = $sEvent;
        $this->aData = $aData;
    }

    public function getChannel(): string
    {
        return $this->sChannel;
    }

    public function getEvent(): string
    {
        return $this->sEvent;
    }

    public function __get($sProp)
    {
        if (array_key_exists($sProp, $this->aData))
        {
            return $this->aData[$sProp];
        }
        return null;
    }

    public function __set($sProp, $mVal)
    {
        $this->aData[$sProp] = $mVal;
    }
}