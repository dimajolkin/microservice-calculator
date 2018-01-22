<?php

namespace app\calculator;


use app\calculator\operation\AbstractOperation;
use app\calculator\operation\Minus;
use app\calculator\operation\Plus;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use phpdk\org\json\JSON;

class OperationServiceManager
{
    protected $hosts = [];

    protected static $configuration = [
        Plus::class => '+',
    ];

    /**
     * OperationServiceManager constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->hosts = $config;
    }

    public function has(string $classNameOrAlias): bool
    {
        $aliases = array_flip(self::$configuration);
        return isset($aliases[$classNameOrAlias]);
    }

    public function getHost(string $alias): string
    {
        return $this->hosts[$alias];
    }

    public function request($a, $operation, $b)
    {

        $host = $this->getHost($operation);
        try {
            $client = new Client([
                'base_uri' => $this->getHost($operation),
            ]);
            $request = $client->get('/', ['form_params' => [
                'a' => $a,
                'b' => $b,
            ]]);

            $json = JSON::decode($request->getBody()->getContents(), true);
            return $json['result'];
        } catch (RequestException $exception) {
            throw new OperationException("operation $operation not allowed $host");
        }


    }

    public function execute($a, $operation, $b)
    {
        return $this->request($a, $operation, $b);
    }
}