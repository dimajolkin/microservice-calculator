<?php

namespace app\calculator;

class CalculatorService
{
    private $operationService;

    /**
     * CalculatorService constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->operationService = new OperationServiceManager($config);
    }

    public function execute(string $string)
    {
        $calculator = new Math($this->operationService);
        return $calculator->execute($string);
    }
}