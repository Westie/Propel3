<?php

namespace Propel\Generator\Builder\PhpModel;

use gossi\codegen\model\PhpTrait;

class TraitDefinition extends PhpTrait
{
    protected $constructorBodyExtras;
    
    public function addConstructorBody($code)
    {
        $this->constructorBodyExtras .= "\n$code";
    }
    
    /**
     * @return mixed
     */
    public function getConstructorBodyExtras()
    {
        return $this->constructorBodyExtras;
    }
}