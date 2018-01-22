<?php

namespace app\calculator;


use app\calculator\operation\Degree;
use app\calculator\operation\Division;
use app\calculator\operation\Minus;
use app\calculator\operation\Multiply;
use app\calculator\operation\Plus;
use NXP\Classes\Calculator;
use NXP\Classes\Lexer;
use NXP\Classes\TokenFactory;
use NXP\MathExecutor;

class Math
{
    /**
     * Available variables
     *
     * @var array
     */
    private $variables = array();

    /**
     * @var TokenFactory
     */
    private $tokenFactory;

    /**
     * @var array
     */
    private $cache = array();
    /**
     * @var OperationServiceManager
     */
    private $serviceManager;

    /**
     * Base math operators
     * @param OperationServiceManager $serviceManager
     */
    public function __construct(OperationServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        $this->addDefaults();
    }

    public function __clone()
    {
        $this->addDefaults();
    }

    /**
     * Set default operands and functions
     */
    protected function addDefaults()
    {
        $this->tokenFactory = new TokenFactory();
        $operations = [
            Plus::class,
            Minus::class,
            Multiply::class,
            Division::class,
            Degree::class,
        ];

        foreach ($operations as $className) {
            $className::service($this->serviceManager);
            $this->tokenFactory->addOperator($className);
        }

        $this->tokenFactory->addFunction('sin', 'sin');
        $this->tokenFactory->addFunction('cos', 'cos');
        $this->tokenFactory->addFunction('tn', 'tan');
        $this->tokenFactory->addFunction('asin', 'asin');
        $this->tokenFactory->addFunction('acos', 'acos');
        $this->tokenFactory->addFunction('atn', 'atan');
        $this->tokenFactory->addFunction('min', 'min', 2);
        $this->tokenFactory->addFunction('max', 'max', 2);
        $this->tokenFactory->addFunction('avg', function ($arg1, $arg2) {
            return ($arg1 + $arg2) / 2;
        }, 2);

        $this->setVars(array(
            'pi' => 3.14159265359,
            'e' => 2.71828182846
        ));
    }

    /**
     * Add variable to executor
     *
     * @param  string $variable
     * @param  integer|float $value
     * @throws \Exception
     * @return MathExecutor
     */
    public function setVar($variable, $value)
    {
        if (!is_numeric($value)) {
            throw new \Exception("Variable value must be a number");
        }

        $this->variables[$variable] = $value;

        return $this;
    }

    /**
     * Add variables to executor
     *
     * @param  array $variables
     * @param  bool $clear Clear previous variables
     * @return MathExecutor
     */
    public function setVars(array $variables, $clear = true)
    {
        if ($clear) {
            $this->removeVars();
        }

        foreach ($variables as $name => $value) {
            $this->setVar($name, $value);
        }

        return $this;
    }

    /**
     * Remove variable from executor
     *
     * @param  string $variable
     * @return MathExecutor
     */
    public function removeVar($variable)
    {
        unset ($this->variables[$variable]);

        return $this;
    }

    /**
     * Remove all variables
     */
    public function removeVars()
    {
        $this->variables = array();

        return $this;
    }

    /**
     * Add operator to executor
     *
     * @param  string $operatorClass Class of operator token
     * @return MathExecutor
     */
    public function addOperator($operatorClass)
    {
        $this->tokenFactory->addOperator($operatorClass);

        return $this;
    }

    /**
     * Add function to executor
     *
     * @param  string $name Name of function
     * @param  callable $function Function
     * @param  int $places Count of arguments
     * @return MathExecutor
     */
    public function addFunction($name, callable $function = null, $places = 1)
    {
        $this->tokenFactory->addFunction($name, $function, $places);

        return $this;
    }

    /**
     * Execute expression
     *
     * @param $expression
     * @return number
     */
    public function execute($expression)
    {
        if (!array_key_exists($expression, $this->cache)) {
            $lexer = new Lexer($this->tokenFactory);
            $tokensStream = $lexer->stringToTokensStream($expression);
            $tokens = $lexer->buildReversePolishNotation($tokensStream);
            $this->cache[$expression] = $tokens;
        } else {
            $tokens = $this->cache[$expression];
        }
        $calculator = new Calculator();
        $result = $calculator->calculate($tokens, $this->variables);

        return $result;
    }
}