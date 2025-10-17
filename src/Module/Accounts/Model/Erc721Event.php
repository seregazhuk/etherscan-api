<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts\Model;

final class Erc721Event
{
    public function __construct(
        public readonly string $blockNumber,
        public readonly string $timeStamp,
        public readonly string $hash,
        public readonly string $nonce,
        public readonly string $blockHash,
        public readonly string $from,
        public readonly string $contractAddress,
        public readonly string $to,
        public readonly string $tokenId,
        public readonly string $tokenName,
        public readonly string $tokenSymbol,
        public readonly string $tokenDecimal,
        public readonly string $transactionIndex,
        public readonly string $gas,
        public readonly string $gasPrice,
        public readonly string $gasUsed,
        public readonly string $cumulativeGasUsed,
        public readonly string $input,
        public readonly string $confirmations,
    ) {}
}
