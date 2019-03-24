<?php
/**
 * 
 */

namespace NxSys\Toolkits\Aether\SDK;

use ReflectionClass,
	ReflectionMethod;

	
use NxSys\Toolkits\Aether\SDK\Command\UnknownCommandSignatureException;

/**
 * undocumented class
 */
class Command
{
    public function _getMetadata()
    {
        $oRefClass = new ReflectionClass($this);
        $sDocComment = $oRefClass->getDocComment();
    }

    public function _getInputSignatures()
    {
        $oRefClass = new ReflectionClass($this);
        $oPublicMethods = $oRefClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($oPublicMethods as $oMethod)
        {
            //Not internal method.
            if ($oMethod->getShortName()[0] != '_')
            {
                $sDocComment = $oMethod->getDocComment();
                $aParameters = $oMethod->getParameters();
                $oReturnType = $oMethod->getReturnType();
                $bIsVariadic = $oMethod->isVariadic();
            }
        }
    }
	
	public function _matchSignature($aArgs, object $oReturnType = null) : string
	{
		//Attempt to match given args array to one of the available input signatures.
		//If $oReturnType is supplied, restrict matching to only methods which specify that return type.
		//On match, return method name.
		//On no match, throw exception.
	}
	
	public function _execute($aArgs, object $oReturnType = null)
	{
		$sMethod = $this->_matchSignature($aArgs, $oReturnType); //Throws an exception on no match.
		return call_user_func_array([$this, $sMethod], $aArgs);
	}
	
	public function _canExecute($aArgs, object $oReturnType = null) : bool
	{
		try
		{
			$this->_matchSignature($aArgs, $oReturnType);
			return true;
		}
		catch (UnknownCommandSignatureException $e)
		{
			return false;
		}
	}
}
