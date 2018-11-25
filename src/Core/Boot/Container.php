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
use Symfony\Component\DependencyInjection as SfDI;
use Symfony\Component\Config as sfConfig;

use Symfonette\NeonIntegration\DependencyInjection\NeonFileLoader;

/** System Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;
use Throwable;
use NxSys\Core\ExtensibleSystemClasses\standard\__PHP_Incomplete_Class;


const DEFAULT_CONTAINER_FILE='aether.xml';

/**
 * Configures and Executes Aether component
 *
 * We'll use this to allocate resources, pull config, 
 * and initialize & launch the module.
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Container
{
	const DEFAULT_CONTAINER_FILE=DEFAULT_CONTAINER_FILE;

	public $sBaseContainerFile;

	/**
	 * @var self
	 */
	static $oInstance;
	public $oDIContainer;

	static function boot(string $sBaseContainerFile=DEFAULT_CONTAINER_FILE)
	{
		if (isset(self::$oInstance))
		{
			throw new InitializationException('can only boot once');
		}
		self::$oInstance=new self;
		self::$oInstance->sBaseContainerFile=$sBaseContainerFile;
		self::$oInstance->initContainers();
		//exec self check tests
	}

	private function __construct()
	{
		
	}
	
	public function initContainers()
	{
		$container = new SfDI\ContainerBuilder();

		$search_paths=[getcwd().DIRECTORY_SEPARATOR.'config',
					   getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'services'];

		//first ini files
		$ini_loader = new SfDI\Loader\IniFileLoader($container, new sfConfig\FileLocator($search_paths));
		##@todo
		$ini_loader->load('../config.sample.ini');

		$neon_loader = new NeonFileLoader($container, new sfConfig\FileLocator($search_paths));
		// $neon_loader->load('../config.cfg', 'neon'); @todo Something

		//now xml files
		$loader = new SfDI\Loader\XmlFileLoader($container, new sfConfig\FileLocator($search_paths));
		$loader->load($this->sBaseContainerFile);
		$this->oDIContainer=$container;
	}

	/**
	 * Get dependency
	 * @param object
	 */
	static function getDependency(string $sSvcName)
	{
		return self::$oInstance->oDIContainer->get($sSvcName);
	}

	/**
	 * Sets Dependency/Service
	 *
	 * @param string $sSvcName Name of service
	 * @param object $oBj Service object
	 * @return void
	 */
	static function setDependency(string $sSvcName, object $oBj): void
	{
		self::$oInstance->oDIContainer->set($sSvcName, $oBj);
		return;
	}

	/**
	 *
	 *  @throws \InvalidArgumentException
	 */
	static function getConfigParamIfExists($sParam)
	{
		if(self::$oInstance->oDIContainer->hasParameter($sParam))
		{
			return self::getConfigParam($sParam);
		}
		throw new InvalidArgumentException($sParam.' is not defined');
	}

	static function getConfigParam($sParam)
	{
		if(self::$oInstance->oDIContainer->hasParameter($sParam))
		{
			return self::$oInstance->oDIContainer->getParameter($sParam);
		}
		return false;
	}
}