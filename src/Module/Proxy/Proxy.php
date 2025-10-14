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
        /** @var array{result: array{
         *     blockHash: string,
         *     blockNumber: string,
         *     from: string,
         *     gas: string,
         *     gasPrice: string,
         *     maxFeePerGas: string,
         *     maxPriorityFeePerGas: string,
         *     hash: string,
         *     input: string,
         *     nonce: string,
         *     to: string,
         *     transactionIndex: string,
         *     value: string,
         *     type: string
         * }} $json */
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

    /**
     * @see https://docs.etherscan.io/api-endpoints/geth-parity-proxy#eth_blocknumber
     */
    public function getBlockNumber(): string
    {
        $response = $this->client->sendRequest(self::MODULE_NAME, 'eth_blockNumber');
        /** @var array{result: string} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return $json['result'];
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/geth-parity-proxy#eth_sendrawtransaction
     */
    public function sendRawTransaction(string $hex): string
    {
        $response = $this->client->sendRequest(self::MODULE_NAME, 'eth_sendRawTransaction', ['hex' => $hex]);
        /** @var array{result: string} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return $json['result'];
    }
}
