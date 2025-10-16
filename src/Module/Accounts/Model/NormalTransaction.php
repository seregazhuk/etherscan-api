<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts\Model;

final class NormalTransaction
{
    public function __construct(
        public readonly string $blockNumber,
        public readonly string $timeStamp,
        public readonly string $hash,
        public readonly string $nonce,
        public readonly string $blockHash,
        public readonly string $transactionIndex,
        public readonly string $from,
        public readonly string $to,
        public readonly string $value,
        public readonly string $gas,
        public readonly string $gasPrice,
        public readonly string $isError,
        public readonly string $txReceiptStatus,
        public readonly string $input,
        public readonly string $contractAddress,
        public readonly string $cumulativeGasUsed,
        public readonly string $gasUsed,
        public readonly string $confirmations,
        public readonly string $methodId,
        public readonly string $functionName,
    ) {}
}
