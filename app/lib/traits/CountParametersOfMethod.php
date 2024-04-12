<?php
/*
* count required parameters for method
*/ 
namespace App\Lib\Traits;

trait CountParametersOfMethod 
{
    function count_parameters_of_method($class, $method)
    {
        $action_method_relfection = new \ReflectionMethod($class, $method);
        return $action_method_relfection->getNumberOfRequiredParameters();
    }
}
?>