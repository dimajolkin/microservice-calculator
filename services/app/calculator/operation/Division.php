<?php

namespace app\calculator\operation;


use NXP\Classes\Token\InterfaceToken;
use NXP\Classes\Token\TokenDivision;

class Division extends TokenDivision implements OperationInterface
{
    use TraitOperation;

    /**
     * @param InterfaceToken[] $stack
     * @return $this
     */
    public function execute(&$stack)
    {
        $op2 = array_pop($stack);
        $op1 = array_pop($stack);

        $result = $this->getService()->execute($op1->getValue(), '/', $op2->getValue());
        return new TokenNumber($result);
    }
}