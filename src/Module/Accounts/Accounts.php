<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts;

use seregazhuk\EtherscanApi\EtherscanClient;
use seregazhuk\EtherscanApi\Module\Accounts\Model\AccountBalanceTag;
use seregazhuk\EtherscanApi\Module\Accounts\Model\Balance;
use seregazhuk\EtherscanApi\Module\Accounts\Model\NormalTransaction;

final class Accounts
{
    private const MODULE_NAME = 'account';

    public function __construct(private readonly EtherscanClient $client) {}

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-ether-balance-for-a-single-address
     */
    public function getBalance(string $address, AccountBalanceTag $tag = AccountBalanceTag::LATEST): string
    {
        $params = [
            'tag' => $tag->value,
            'address' => $address,
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'balance', $params);

        /** @var array{result: string} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return $json['result'];
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/contracts#get-contract-abi-for-verified-contract-source-codes
     *
     * @param string[]  $addresses
     * @return Balance[]
     */
    public function getBalances(array $addresses, AccountBalanceTag $tag = AccountBalanceTag::LATEST): array
    {
        $params = [
            'address' => implode(',', $addresses),
            'tag' => $tag->value,
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'balancemulti', $params);
        /** @var array{result: array<int, array{
         *     account: string,
         *     balance: string,
         * }>} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $balance): Balance => new Balance($balance['account'], $balance['balance']), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-a-list-of-normal-transactions-by-address
     * @return NormalTransaction[]
     */
    public function getTransactions(string $address, int $page = 1, int $offset = 10): array
    {
        $params = [
            'address' => $address,
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'txlist', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     hash: string,
         *     nonce: string,
         *     blockHash: string,
         *     transactionIndex: string,
         *     from: string,
         *     to: string,
         *     value: string,
         *     gas: string,
         *     gasPrice: string,
         *     isError: string,
         *     txreceipt_status: string,
         *     input: string,
         *     contractAddress: string,
         *     cumulativeGasUsed: string,
         *     gasUsed: string,
         *     confirmations: string,
         *     methodId: string,
         *     functionName: string
         * }>} $json */

        $json = json_decode($response->getBody()->getContents(), true);
        return array_map(fn(array $tx): NormalTransaction => new NormalTransaction(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['hash'],
            $tx['nonce'],
            $tx['blockHash'],
            $tx['transactionIndex'],
            $tx['from'],
            $tx['to'],
            $tx['value'],
            $tx['gas'],
            $tx['gasPrice'],
            $tx['isError'],
            $tx['txreceipt_status'],
            $tx['input'],
            $tx['contractAddress'],
            $tx['cumulativeGasUsed'],
            $tx['gasUsed'],
            $tx['confirmations'],
            $tx['methodId'],
            $tx['functionName'],
        ), $json['result']);
    }
}
