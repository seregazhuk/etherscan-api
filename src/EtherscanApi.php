<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi;

use Http\Client\Common\Plugin\BaseUriPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use seregazhuk\EtherscanApi\Module\Accounts\Accounts;
use seregazhuk\EtherscanApi\Module\Contracts\Contracts;
use seregazhuk\EtherscanApi\Module\Proxy\Proxy;
use seregazhuk\EtherscanApi\Module\Tokens\Tokens;

final class EtherscanApi
{
    private const API_URL = 'https://api.etherscan.io/v2/api';

    public readonly Accounts $accounts;

    public readonly Proxy $proxy;

    public readonly Contracts $contracts;

    public readonly Tokens $tokens;

    public function __construct(string $apiKey, ChainId $chainId = ChainId::ETHEREUM_MAINNET)
    {
        $pluginClient = new PluginClient(
            Psr18ClientDiscovery::find(),
            [new BaseUriPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri(self::API_URL))],
        );

        $etherscanClient = new EtherscanClient(
            $pluginClient,
            $apiKey,
            Psr17FactoryDiscovery::findRequestFactory(),
            $chainId,
        );

        $this->accounts = new Accounts($etherscanClient);
        $this->proxy = new Proxy($etherscanClient);
        $this->contracts = new Contracts($etherscanClient);
        $this->tokens = new Tokens($etherscanClient);
    }
}
