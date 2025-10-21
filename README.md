# PHP wrapper for the Etherscan API (supports v2).

PHP client for [Etherscan API](https://docs.etherscan.io) (and its families like BscScan), with nearly full API bindings 
(accounts, transactions, tokens, contracts, blocks, stats) and full [chains](https://docs.etherscan.io/supported-chains) support.


**Table of Contents**
- [Installation](#installation)
- [Quick Start](#quick-start)

## Installation

```bash
composer req seregazhuk/etherscan-api 
```

## Quick Start

Register Etherscan account and create [free API key](https://etherscan.io/myapikey).

```php
$etherscan = new seregazhuk\EtherscanApi\EtherscanApi('your-api-key');
$currentBlock = $etherscan->proxy->getBlockNumber();
$transactionInfo = $etherscan->proxy->getTransactionByHash('0x136f818dfe87b367eee9890c162ef343dbd65e409aef102219a6091ba7e696d7');
$isConfirmed = $currentBlock
        ->subtract($transactionInfo->blockNumber)
        ->compare(new BigInteger('12')) >= 0;

echo $isConfirmed ? 'Confirmed' : 'Not confirmed';
```

## Available bindings

### Accounts

https://docs.etherscan.io/api-endpoints/accounts#get-ether-balance-for-a-single-address
Get Ether balance for a single address: 

```php
$balance = $this->accounts->getBalance('0xde0b295669a9fd93d5f28d9ec85e40f4cb697bae');
```

https://docs.etherscan.io/api-endpoints/accounts#get-ether-balance-for-multiple-addresses-in-a-single-call
Get Ether balance for multiple addresses in a single call:

```php
$balances = $this->accounts->getBalances(['0xddbd2b932c763ba5b1b7ae3b362eac3e8d40121a', '0x63a9975ba31b0b9626b34300f7f627147df1f526']);
```

https://docs.etherscan.io/api-endpoints/accounts#get-a-list-of-normal-transactions-by-address
Get a list of 'Normal' transactions by address:
```php
$transactions = $this->accounts->getTransactions('0xc5102fE9359FD9a28f877a67E36B0F050d81a3CC');
```
