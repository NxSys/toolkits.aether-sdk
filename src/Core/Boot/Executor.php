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
namespace NxSys\Toolkits\Aether\SDK\Core\Boot;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Core;

/** Library Dependencies **/
use Symfony\Component\Console;

/** System Dependencies **/
use Throwable;

/**
 * Configures and Executes Aether component
 *
 * We'll use this to allocate resources, pull config, 
 * and initialize & launch the module.
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType Well, does it?
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
 class Executor extends Console\Command\Command
 {
    public function execute()
    {
        #start console logging
        //do allocation & resource checking
            //disk space? mem? dynamic modules/phars?

        //do config
            //load config
        #start configured logger

        //do system initialization
            //DI
            //@todo setup Distributed Exception & Recovery System DXCR

        //do instantiation
        try
        {
            $return=ModuleCode::run($oConfigInstance);
        }
        catch(Throwable $e)
        {

        }

        //finalization
        #log output

        //deallocation/completion
        return;
    }
 }
