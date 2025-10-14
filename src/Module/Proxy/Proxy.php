<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Proxy;

use seregazhuk\EtherscanApi\EtherscanClient;

final class Proxy
{
    private const MODULE_NAME = 'proxy';

    public function __construct(private readonly EtherscanClient $client) {}

    /**
     * @see https://docs.etherscan.io/api-endpoints/geth-parity-proxy#eth_gettransactionbyhash
     */
    public function getTransactionByHash(string $hash): TransactionByHashInfo
    {
        $params = [
            'action' => 'eth_getTransactionByHash',
            'txhash' => $hash,
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'eth_getTransactionByHash', $params);
        $json = json_decode($response->getBody()->getContents(), true);

        return new TransactionByHashInfo(
            $json['result']['blockHash'],
            $json['result']['blockNumber'],
            $json['result']['from'],
            $json['result']['gas'],
            $json['result']['gasPrice'],
            $json['result']['maxFeePerGas'],
            $json['result']['maxPriorityFeePerGas'],
            $json['result']['hash'],
            $json['result']['input'],
            $json['result']['nonce'],
            $json['result']['to'],
            $json['result']['transactionIndex'],
            $json['result']['value'],
            $json['result']['type'],
        );
    }
}
