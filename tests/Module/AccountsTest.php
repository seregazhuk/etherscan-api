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
use seregazhuk\EtherscanApi\Module\Accounts\Accounts;

class AccountsTest extends TestCase
{
    private ClientInterface&MockObject $httpClientMock;

    private Accounts $accounts;

    protected function setUp(): void
    {
        $this->httpClientMock = self::createMock(ClientInterface::class);
        $client = new EtherscanClient(
            $this->httpClientMock, 'apiKey',
            Psr17FactoryDiscovery::findRequestFactory(),
            ChainId::ETHEREUM_MAINNET
        );
        $this->accounts = new Accounts($client);
        parent::setUp();
    }

    #[Test]
    public function it_retrieves_account_balance(): void
    {
        $json = <<<'JSON'
            {
               "status":"1",
               "message":"OK",
               "result":"40891626854930000000000" 
            }
        JSON;

        $this->httpClientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                $this->assertSame('chainid=1&module=account&action=balance&apikey=apiKey&tag=latest&address=0xde0b295669a9fd93d5f28d9ec85e40f4cb697bae', $request->getUri()->getQuery());

                return true;
            }))
            ->willReturn(new Response(200, [], $json));

        $balance = $this->accounts->getBalance('0xde0b295669a9fd93d5f28d9ec85e40f4cb697bae');
        $this->assertSame('40891626854930000000000', $balance);
    }

    #[Test]
    public function it_retrieves_accounts_balances(): void
    {
        $json = <<<'JSON'
            {
                "status": "1",
                "message": "OK",
                "result": [
                    {
                        "account": "0xddbd2b932c763ba5b1b7ae3b362eac3e8d40121a",
                        "balance": "27000616846559600000999"
                    },
                    {
                        "account": "0x63a9975ba31b0b9626b34300f7f627147df1f526",
                        "balance": "2039670355000"
                    }
                ]
            }           
        JSON;

        $this->httpClientMock
            ->expects($this->once())
            ->method('sendRequest')
            ->with($this->callback(function (RequestInterface $request) {
                $this->assertSame('chainid=1&module=account&action=balancemulti&apikey=apiKey&address=0xddbd2b932c763ba5b1b7ae3b362eac3e8d40121a%2C0x63a9975ba31b0b9626b34300f7f627147df1f526&tag=latest', $request->getUri()->getQuery());

                return true;
            }))
            ->willReturn(new Response(200, [], $json));

        $balances = $this->accounts->getBalances(['0xddbd2b932c763ba5b1b7ae3b362eac3e8d40121a', '0x63a9975ba31b0b9626b34300f7f627147df1f526']);
    }
}
