<?php

/** @namespace Native Namespace */
namespace NxSys\Toolkits\Aether\SDK\Core;

/** Local Project Dependencies **/
use NxSys\Toolkits\Aether\SDK\Command;
use NxSys\Toolkits\Aether\SDK\UnknownCommandSignatureException;

class ExecutionFrame
{
	protected $oCommand = null;
	protected $aArgs = [];
	protected $aStack = [];
	
	public function __construct(Command $oCommand, ...$aArgs)
	{
		$this->oCommand = $oCommand;
		$this->aArgs = $aArgs;
	}
	
	public function buildStack() : array
	{
		$this->aStack = [];
		
		foreach ($aArgs as $mArg)
		{
			if (is_subclass_of($mArg, 'ExecutionFrame'))
			{
				//Build substack, prepend to my stack.
				array_unshift($this->aStack, ...$mArg->buildStack());	
			}
		}
		
		$this->aStack[] = $this;
		
		return $this->aStack;
	}
	
	public function validateStack() : bool
	{
		//Validate stack before attempting execution.
	}
	
	public function execute($oReturnType = null)
	{
		if ($this->aStack == [])
		{
			$this->buildStack();
		}
		
		if (!$this->validateStack())
		{
			//Throw exception
		}
		
		$aFinalArgs = [];
		
		foreach ($aArgs as $mArg)
		{
			if (is_subclass_of($mArg, 'ExecutionFrame'))
			{
				//Recurse, so deepest commands run first.
				//@TODO: Determine expected return type and request it/
				$aFinalArgs[] = $mArg->execute();
			}
			else
			{
				$aFinalArgs[] = $mArg;
			}
		}
		
		return $this->oCommand->_execute($aFinalArgs, $oReturnType);
	}
}
