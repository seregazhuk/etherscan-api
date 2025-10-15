<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Proxy\Model;

final class TransactionReceipt
{
    public function __construct(
        public readonly string $blockHash,
        public readonly string $blockNumber,
        public readonly ?string $contractAddress,
        public readonly string $cumulativeGasUsed,
        public readonly string $from,
        public readonly string $gasUsed,
        /** @var TransactionReceiptLog[] $logs */
        public readonly array $logs,
        public readonly string $logsBloom,
        public readonly string $status,
        public readonly string $to,
        public readonly string $transactionHash,
        public readonly string $transactionIndex,
        public readonly string $type,
    ) {}
}
