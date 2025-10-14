<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Tokens;

use seregazhuk\EtherscanApi\EtherscanClient;

final class Tokens
{
    private const MODULE_NAME_ACCOUNT = 'account';

    private const MODULE_NAME_STATS = 'stats';

    public function __construct(private readonly EtherscanClient $client) {}

    /**
     * @see https://docs.etherscan.io/api-endpoints/tokens#get-erc20-token-account-balance-for-tokencontractaddress
     */
    public function getTokenBalance(string $contractAddress, string $address): string
    {
        $params = ['address' => $address, 'contractaddress' => $contractAddress];
        $response = $this->client->sendRequest(self::MODULE_NAME_ACCOUNT, 'tokenbalance', $params);
        $json = json_decode($response->getBody()->getContents(), true);

        return $json['result'];
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/tokens#get-erc20-token-totalsupply-by-contractaddress
     */
    public function getTotalSupply(string $contractAddress): string
    {
        $params = ['contractaddress' => $contractAddress];
        $response = $this->client->sendRequest(self::MODULE_NAME_STATS, 'tokensupply', $params);
        $json = json_decode($response->getBody()->getContents(), true);

        return $json['result'];
    }
}
