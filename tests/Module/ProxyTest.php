<?php

declare(strict_types=1);

namespace tests\Module;

use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use seregazhuk\EtherscanApi\ChainId;
use seregazhuk\EtherscanApi\Module\EtherscanClient;
use seregazhuk\EtherscanApi\Module\Proxy\Proxy;

class ProxyTest extends TestCase
{
    private ClientInterface&MockObject $httpClientMock;

    private Proxy $proxy;

    protected function setUp(): void
    {
        $this->httpClientMock = self::createMock(ClientInterface::class);
        $client = new EtherscanClient(
            $this->httpClientMock, 'apiKey',
            Psr17FactoryDiscovery::findRequestFactory(),
            ChainId::ETHEREUM_MAINNET
        );
        $this->proxy = new Proxy($client);
        parent::setUp();
    }

    #[Test]
    public function it_retrieves_transaction_by_hash(): void
    {
        $json = <<<'JSON'
            {
               "jsonrpc":"2.0",
               "id":1,
               "result":{
                  "blockHash":"0xf850331061196b8f2b67e1f43aaa9e69504c059d3d3fb9547b04f9ed4d141ab7",
                  "blockNumber":"0xcf2420",
                  "from":"0x00192fb10df37c9fb26829eb2cc623cd1bf599e8",
                  "gas":"0x5208",
                  "gasPrice":"0x19f017ef49",
                  "maxFeePerGas":"0x1f6ea08600",
                  "maxPriorityFeePerGas":"0x3b9aca00",
                  "hash":"0x136f818dfe87b367eee9890c162ef343dbd65e409aef102219a6091ba7e696d7",
                  "input":"0x",
                  "nonce":"0x33b79d",
                  "to":"0xc67f4e626ee4d3f272c2fb31bad60761ab55ed9f",
                  "transactionIndex":"0x5b",
                  "value":"0x19755d4ce12c00",
                  "type":"0x2",
                  "accessList":[
                     
                  ],
                  "chainId":"0x1",
                  "v":"0x0",
                  "r":"0xa681faea68ff81d191169010888bbbe90ec3eb903e31b0572cd34f13dae281b9",
                  "s":"0x3f59b0fa5ce6cf38aff2cfeb68e7a503ceda2a72b4442c7e2844d63544383e3"
               }
            }
        JSON;

        $this->httpClientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                $this->assertSame('chainid=1&module=proxy&action=eth_getTransactionByHash&apikey=apiKey&txhash=0x136f818dfe87b367eee9890c162ef343dbd65e409aef102219a6091ba7e696d7', $request->getUri()->getQuery());

                return true;
            }))
            ->willReturn(new Response(200, [], $json));
        $transaction = $this->proxy->getTransactionByHash('0x136f818dfe87b367eee9890c162ef343dbd65e409aef102219a6091ba7e696d7');
        $this->assertSame('0xf850331061196b8f2b67e1f43aaa9e69504c059d3d3fb9547b04f9ed4d141ab7', $transaction->blockHash);
        $this->assertSame('0xcf2420', $transaction->blockNumber);
        $this->assertSame('0x00192fb10df37c9fb26829eb2cc623cd1bf599e8', $transaction->from);
        $this->assertSame('0x5208', $transaction->gas);
        $this->assertSame('0x19f017ef49', $transaction->gasPrice);
        $this->assertSame('0x1f6ea08600', $transaction->maxFeePerGas);
        $this->assertSame('0x3b9aca00', $transaction->maxPriorityFeePerGas);
        $this->assertSame('0x', $transaction->input);
        $this->assertSame('0x5b', $transaction->transactionIndex);
        $this->assertSame('0x136f818dfe87b367eee9890c162ef343dbd65e409aef102219a6091ba7e696d7', $transaction->hash);
        $this->assertSame('0xc67f4e626ee4d3f272c2fb31bad60761ab55ed9f', $transaction->to);
        $this->assertSame('0x19755d4ce12c00', $transaction->value);
        $this->assertSame('0x2', $transaction->type);
        $this->assertSame('0x33b79d', $transaction->nonce);

    }
}
