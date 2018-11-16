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

/**
 * 
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Event
{
    public $sChannel;
    public $sEvent;
    protected $aData;

    public function __construct(string $sChannel, string $sEvent, array $aData = [])
    {
        $this->sChannel = $sChannel;
        $this->sEvent = $sEvent;
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
        return $this->aData[$sProp];
    }

    public function __set($sProp, $mVal)
    {
        $this->aData[$sProp] = $mVal;
    }
}