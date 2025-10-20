<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Proxy\Model;

final class TransactionByHashInfo
{
    public function __construct(
        public readonly string $blockHash,
        public readonly string $blockNumber,
        public readonly string $from,
        public readonly string $gas,
        public readonly string $gasPrice,
        public readonly ?string $maxFeePerGas,
        public readonly ?string $maxPriorityFeePerGas,
        public readonly string $hash,
        public readonly string $input,
        public readonly string $nonce,
        public readonly string $to,
        public readonly string $transactionIndex,
        public readonly string $value,
        public readonly string $type,
    ) {}
}
