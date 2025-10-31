<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts;

use InvalidArgumentException;
use phpseclib3\Math\BigInteger;
use seregazhuk\EtherscanApi\EtherscanClient;
use seregazhuk\EtherscanApi\Module\Accounts\Model\AccountBalanceTag;
use seregazhuk\EtherscanApi\Module\Accounts\Model\AddressFundedInfo;
use seregazhuk\EtherscanApi\Module\Accounts\Model\Balance;
use seregazhuk\EtherscanApi\Module\Accounts\Model\Erc1155Event;
use seregazhuk\EtherscanApi\Module\Accounts\Model\Erc20Event;
use seregazhuk\EtherscanApi\Module\Accounts\Model\Erc721Event;
use seregazhuk\EtherscanApi\Module\Accounts\Model\InternalTransaction;
use seregazhuk\EtherscanApi\Module\Accounts\Model\NormalTransaction;

final class Accounts
{
    private const MODULE_NAME = 'account';

    public function __construct(private readonly EtherscanClient $client) {}

    /**
     * Get Native Balance for an Address
     * @see https://docs.etherscan.io/api-reference/endpoint/balance
     */
    public function getBalance(string $address, AccountBalanceTag $tag = AccountBalanceTag::LATEST): BigInteger
    {
        $params = [
            'tag' => $tag->value,
            'address' => $address,
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'balance', $params);

        /** @var array{result: string} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return new BigInteger($json['result'], 16);
    }

    /**
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

        return array_map(fn(array $balance): Balance => new Balance($balance['account'], new BigInteger($balance['balance'], 16)), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-reference/endpoint/txlist
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
            new BigInteger($tx['blockNumber'], 16),
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

    /**
     * @see https://docs.etherscan.io/api-reference/endpoint/txlistinternal-txhash
     * @return InternalTransaction[]
     */
    public function getInternalTransactionsByHash(string $hash, int $page = 1, int $offset = 10): array
    {
        $params = [
            'txhash' => $hash,
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'txlistinternal', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     from: string,
         *     to: string,
         *     value: string,
         *     contractAddress: string,
         *     input: string,
         *     type: string,
         *     gas: string,
         *     gasUsed: string,
         *     isError: string,
         *     errCode: string
         * }>} $json */

        $json = json_decode($response->getBody()->getContents(), true);
        return array_map(fn(array $tx): InternalTransaction => new InternalTransaction(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['from'],
            $tx['to'],
            $tx['value'],
            $tx['contractAddress'],
            $tx['input'],
            $tx['type'],
            $tx['gas'],
            $tx['gasUsed'],
            $tx['isError'],
            $tx['errCode'],
        ), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-internal-transactions-by-block-range
     * @return InternalTransaction[]
     */
    public function getInternalTransactionsByBlockRange(int $startBlock, int $endBlock, int $page = 1, int $offset = 10): array
    {
        $params = [
            'startblock' => $startBlock,
            'endblock' => $endBlock,
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        $response = $this->client->sendRequest(self::MODULE_NAME, 'txlistinternal', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     from: string,
         *     to: string,
         *     value: string,
         *     contractAddress: string,
         *     input: string,
         *     type: string,
         *     gas: string,
         *     gasUsed: string,
         *     isError: string,
         *     errCode: string
         * }>} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $tx): InternalTransaction => new InternalTransaction(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['from'],
            $tx['to'],
            $tx['value'],
            $tx['contractAddress'],
            $tx['input'],
            $tx['type'],
            $tx['gas'],
            $tx['gasUsed'],
            $tx['isError'],
            $tx['errCode'],
        ), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-a-list-of-erc20-token-transfer-events-by-address
     * @return Erc20Event[]
     */
    public function getErc20TokenTransferEvents(?string $address = null, ?string $contractAddress = null, int $page = 1, int $offset = 100): array
    {
        if (null === $address && null === $contractAddress) {
            throw new InvalidArgumentException('Either address or contract address must be provided');
        }
        $params = [
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        if (null !== $address) {
            $params['address'] = $address;
        }
        if (null !== $contractAddress) {
            $params['contractaddress'] = $contractAddress;
        }

        $response = $this->client->sendRequest(self::MODULE_NAME, 'tokentx', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     hash: string,
         *     nonce: string,
         *     blockHash: string,
         *     from: string,
         *     contractAddress: string,
         *     to: string,
         *     value: string,
         *     tokenName: string,
         *     tokenSymbol: string,
         *     tokenDecimal: string,
         *     transactionIndex: string,
         *     gas: string,
         *     gasPrice: string,
         *     gasUsed: string,
         *     cumulativeGasUsed: string,
         *     input: string,
         *     confirmations: string
         * }>} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $tx): Erc20Event => new Erc20Event(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['hash'],
            $tx['nonce'],
            $tx['blockHash'],
            $tx['from'],
            $tx['contractAddress'],
            $tx['to'],
            $tx['value'],
            $tx['tokenName'],
            $tx['tokenSymbol'],
            $tx['tokenDecimal'],
            $tx['transactionIndex'],
            $tx['gas'],
            $tx['gasPrice'],
            $tx['gasUsed'],
            $tx['cumulativeGasUsed'],
            $tx['input'],
            $tx['confirmations'],
        ), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-a-list-of-erc721-token-transfer-events-by-address
     * @return Erc721Event[]
     */
    public function getErc721TokenTransferEvents(?string $address = null, ?string $contractAddress = null, int $page = 1, int $offset = 100): array
    {
        if (null === $address && null === $contractAddress) {
            throw new InvalidArgumentException('Either address or contract address must be provided');
        }
        $params = [
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        if (null !== $address) {
            $params['address'] = $address;
        }
        if (null !== $contractAddress) {
            $params['contractaddress'] = $contractAddress;
        }

        $response = $this->client->sendRequest(self::MODULE_NAME, 'tokennfttx', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     hash: string,
         *     nonce: string,
         *     blockHash: string,
         *     from: string,
         *     contractAddress: string,
         *     to: string,
         *     tokenID: string,
         *     tokenName: string,
         *     tokenSymbol: string,
         *     tokenDecimal: string,
         *     transactionIndex: string,
         *     gas: string,
         *     gasPrice: string,
         *     gasUsed: string,
         *     cumulativeGasUsed: string,
         *     input: string,
         *     confirmations: string
         * }>} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $tx): Erc721Event => new Erc721Event(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['hash'],
            $tx['nonce'],
            $tx['blockHash'],
            $tx['from'],
            $tx['contractAddress'],
            $tx['to'],
            $tx['tokenID'],
            $tx['tokenName'],
            $tx['tokenSymbol'],
            $tx['tokenDecimal'],
            $tx['transactionIndex'],
            $tx['gas'],
            $tx['gasPrice'],
            $tx['gasUsed'],
            $tx['cumulativeGasUsed'],
            $tx['input'],
            $tx['confirmations'],
        ), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-a-list-of-erc1155-token-transfer-events-by-address
     * @return Erc1155Event[]
     */
    public function getErc1155TokenTransferEvents(?string $address = null, ?string $contractAddress = null, int $page = 1, int $offset = 100): array
    {
        if (null === $address && null === $contractAddress) {
            throw new InvalidArgumentException('Either address or contract address must be provided');
        }
        $params = [
            'page' => $page,
            'offset' => $offset,
            'sort' => 'asc',
        ];
        if (null !== $address) {
            $params['address'] = $address;
        }
        if (null !== $contractAddress) {
            $params['contractaddress'] = $contractAddress;
        }

        $response = $this->client->sendRequest(self::MODULE_NAME, 'token1155tx', $params);

        /** @var array{result: array<int, array{
         *     blockNumber: string,
         *     timeStamp: string,
         *     hash: string,
         *     nonce: string,
         *     blockHash: string,
         *     from: string,
         *     contractAddress: string,
         *     to: string,
         *     tokenID: string,
         *     tokenValue: string,
         *     tokenName: string,
         *     tokenSymbol: string,
         *     transactionIndex: string,
         *     gas: string,
         *     gasPrice: string,
         *     gasUsed: string,
         *     cumulativeGasUsed: string,
         *     input: string,
         *     confirmations: string
         * }>} $json */
        $json = json_decode($response->getBody()->getContents(), true);

        return array_map(fn(array $tx): Erc1155Event => new Erc1155Event(
            $tx['blockNumber'],
            $tx['timeStamp'],
            $tx['hash'],
            $tx['nonce'],
            $tx['blockHash'],
            $tx['transactionIndex'],
            $tx['gas'],
            $tx['gasPrice'],
            $tx['gasUsed'],
            $tx['cumulativeGasUsed'],
            $tx['input'],
            $tx['contractAddress'],
            $tx['from'],
            $tx['to'],
            $tx['tokenID'],
            $tx['tokenValue'],
            $tx['tokenName'],
            $tx['tokenSymbol'],
            $tx['confirmations'],
        ), $json['result']);
    }

    /**
     * @see https://docs.etherscan.io/api-endpoints/accounts#get-address-funded-by
     */
    public function getFundedBy(string $address): AddressFundedInfo
    {
        $response = $this->client->sendRequest(self::MODULE_NAME, 'fundedby', ['address' => $address]);

        /** @var array{result: array{
         *     block: int,
         *     timeStamp: string,
         *     fundingAddress: string,
         *     fundingTxn: string,
         *     value: string,
         * }} $json */
        $json = json_decode($response->getBody()->getContents(), true);
        return new AddressFundedInfo(
            $json['result']['block'],
            $json['result']['timeStamp'],
            $json['result']['fundingAddress'],
            $json['result']['fundingTxn'],
            $json['result']['value'],
        );
    }
}
