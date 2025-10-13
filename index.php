<?php

declare(strict_types=1);

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\PluginClient;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use seregazhuk\EtherscanApi\EtherscanApi;

require __DIR__.'/vendor/autoload.php';

// HTTP client initialization
$pluginClient = new PluginClient(
    Psr18ClientDiscovery::find(),
    [new AddHostPlugin(Psr17FactoryDiscovery::findUriFactory()->createUri('https://api.etherscan.io/'))]
);
$requestFactory = Psr17FactoryDiscovery::findRequestFactory();
$apiKey = 'EP6TWQ1NQDXTK4JSQSC98JX6PY8CVWVFDP';
$etherscanApi = new EtherscanApi($apiKey);
print_r($etherscanApi->accounts->getBalances(['0xde0b295669a9fd93d5f28d9ec85e40f4cb697bae', '0xddbd2b932c763ba5b1b7ae3b362eac3e8d40121a']));
print_r($etherscanApi->proxy->getTransactionByHash('0xbc78ab8a9e9a0bca7d0321a27b2c03addeae08ba81ea98b03cd3dd237eabed44'));
