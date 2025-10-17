<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts\Model;

final class InternalTransaction
{
    public function __construct(
        public readonly string $blockNumber,
        public readonly string $timeStamp,
        public readonly string $from,
        public readonly string $to,
        public readonly string $value,
        public readonly string $contractAddress,
        public readonly string $input,
        public readonly string $type,
        public readonly string $gas,
        public readonly string $gasUsed,
        public readonly string $isError,
        public readonly string $errCode,
    ) {}
}
