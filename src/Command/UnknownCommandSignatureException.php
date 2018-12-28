<?php

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Command;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Command\ICommandException;
use BadMethodCallException;

class UnknownCommandSignatureException extends BadMethodCallException implements ICommandException {}