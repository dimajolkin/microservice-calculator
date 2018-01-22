<?php

namespace app\calculator\operation;


use app\calculator\OperationServiceManager;

trait TraitOperation
{
    protected static $service;

    public static function service(OperationServiceManager $serviceManager)
    {
        static::$service = $serviceManager;
    }

    /**
     * @return OperationServiceManager
     */
    public function getService(): OperationServiceManager
    {
        return self::$service;
    }

}