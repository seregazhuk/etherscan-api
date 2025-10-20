<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Proxy\Model;

use phpseclib3\Math\BigInteger;

final class TransactionReceiptLog
{
    public function __construct(
        public readonly string $address,
        /** @var string[] $topics */
        public readonly array $topics,
        public readonly string $data,
        public readonly BigInteger $blockNumber,
        public readonly string $transactionHash,
        public readonly string $transactionIndex,
        public readonly string $logIndex,
        public readonly string $blockHash,
        public readonly bool $removed,
    ) {}
}
