<?php

namespace app\calculator\operation;


use app\calculator\OperationServiceManager;

interface OperationInterface
{
    public function getService(): OperationServiceManager;

}