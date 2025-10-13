<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use seregazhuk\EtherscanApi\ChainId;

class EtherscanClient
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly string $apiKey,
        private readonly RequestFactoryInterface $requestFactory,
        private readonly ChainId $chainId,
    ) {}

    /**
     * @param array<string, string> $params
     */
    public function sendRequest(string $module, string $action, array $params): ResponseInterface
    {
        $params = array_merge([
            'chainid' => $this->chainId->value,
            'module' => $module,
            'action' => $action,
            'apikey' => $this->apiKey,
        ], $params);
        $request = $this->requestFactory->createRequest('GET', '/v2/api?'.http_build_query($params));

        return $this->client->sendRequest($request);
    }
}
