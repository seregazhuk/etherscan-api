<?php

declare(strict_types=1);

namespace Module;

use GuzzleHttp\Psr7\Response;
use Http\Discovery\Psr17FactoryDiscovery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use seregazhuk\EtherscanApi\ChainId;
use seregazhuk\EtherscanApi\EtherscanClient;
use seregazhuk\EtherscanApi\Module\Tokens\Tokens;

final class TokensTest extends TestCase
{
    private ClientInterface&MockObject $httpClientMock;

    private Tokens $tokens;

    protected function setUp(): void
    {
        $this->httpClientMock = self::createMock(ClientInterface::class);
        $client = new EtherscanClient(
            $this->httpClientMock,
            'apiKey',
            Psr17FactoryDiscovery::findRequestFactory(),
            ChainId::ETHEREUM_MAINNET,
        );
        $this->tokens = new Tokens($client);
        parent::setUp();
    }

    #[Test]
    public function it_retrieves_address_balance(): void
    {
        $json = <<<'JSON'
            {
               "status":"1",
               "message":"OK",
               "result":"135499"
            }
        JSON;

        $this->httpClientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request): bool {
                $this->assertSame('chainid=1&module=account&action=tokenbalance&apikey=apiKey&address=0xe04f27eb70e025b78871a2ad7eabe85e61212761&contractaddress=0x57d90b64a1a57749b0f932f1a3395792e12e7055', $request->getUri()->getQuery());

                return true;
            }))
            ->willReturn(new Response(200, [], $json));
        $result = $this->tokens->getTokenBalance('0x57d90b64a1a57749b0f932f1a3395792e12e7055', '0xe04f27eb70e025b78871a2ad7eabe85e61212761');
        $this->assertSame('135499', $result);
    }

    #[Test]
    public function it_retrieves_token_total_supply(): void
    {
        $json = <<<'JSON'
            {
               "status":"1",
               "message":"OK",
               "result":"21265524714464"
            }
        JSON;

        $this->httpClientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request): bool {
                $this->assertSame('chainid=1&module=stats&action=tokensupply&apikey=apiKey&contractaddress=0x57d90b64a1a57749b0f932f1a3395792e12e7055', $request->getUri()->getQuery());

                return true;
            }))
            ->willReturn(new Response(200, [], $json));
        $result = $this->tokens->getTotalSupply('0x57d90b64a1a57749b0f932f1a3395792e12e7055');
        $this->assertSame('21265524714464', $result);
    }
}
