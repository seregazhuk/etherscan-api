<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts;

use seregazhuk\EtherscanApi\EtherscanClient;

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
     * @param string[] $addresses
     * @return Balance[]
     */
    public function getBalances(array $addresses, AccountBalanceTag $tag = AccountBalanceTag::LATEST): array
    {
        $params = [
            'address' => implode(',', $addresses),
            'tag' => $tag->value,
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'balancemulti', $params);
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $balance): Balance => new Balance($balance['account'], $balance['balance']), $json['result']);
    }
}
