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

/** System Dependencies **/
use NxSys\Core\ExtensibleSystemClasses as CoreEsc;
use Throwable;

/**
 * Configures and Executes Aether component
 *
 * We'll use this to allocate resources, pull config, 
 * and initialize & launch the module.
 *
 * @throws NxSys\Toolkits\Aether\SDK\Core\Boot\BootExceptionType
 * @author Chris R. Feamster <cfeamster@f2developments.com>
 */
class Configurator
{
	const CONTAINER_FILE='c3.xml';

	static $oInstance;
	public $oDIContainer;

	static function boot()
	{
		if (isset(self::$oInstance))
		{
			throw new InitializationException('can only boot once');
		}
		self::$oInstance=new BootLoader;

		//exec self check tests
	}

	private function __construct()
	{
		$this->initContainers();
	}

	public function initContainers()
	{
		$container = new SfDI\ContainerBuilder();

		$search_paths=[getcwd().DIRECTORY_SEPARATOR.'config',
					   getcwd().DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'services'];

		//first ini files
		$ini_loader = new SfDI\Loader\IniFileLoader($container, new sfConfig\FileLocator($search_paths));
		$ini_loader->load('config.sample.ini');

		//now xml files
		$loader = new SfDI\Loader\XmlFileLoader($container, new sfConfig\FileLocator($search_paths));
		$loader->load(self::CONTAINER_FILE);
		$this->oDIContainer=$container;
	}

	/**
	 * function getDependency
	 * @param object
	 */
	static function getDependency($sSvcName)
	{
		return self::$oInstance->oDIContainer->get($sSvcName);
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