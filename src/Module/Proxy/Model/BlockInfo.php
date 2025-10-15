<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Proxy\Model;

final class BlockInfo
{
    public function __construct(
        public readonly string $baseFeePerGas,
        public readonly string $difficulty,
        public readonly string $extraData,
        public readonly string $gasLimit,
        public readonly string $gasUsed,
        public readonly string $hash,
        public readonly string $logsBloom,
        public readonly string $miner,
        public readonly string $mixHash,
        public readonly string $nonce,
        public readonly string $number,
        public readonly string $parentHash,
        public readonly string $receiptsRoot,
        public readonly string $sha3Uncles,
        public readonly string $size,
        public readonly string $stateRoot,
        public readonly string $timestamp,
        public readonly ?string $totalDifficulty,
        /** @var array<string> $transactions */
        public readonly array $transactions,
        public readonly string $transactionsRoot,
        /** @var array<string> $uncles */
        public readonly array $uncles,
    ) {
    }
}
