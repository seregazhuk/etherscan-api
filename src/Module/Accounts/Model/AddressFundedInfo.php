<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts\Model;

final class AddressFundedInfo
{
    public function __construct(
        public readonly int $block,
        public readonly string $timestamp,
        public readonly string $fundingAddress,
        public readonly string $fundingTxn,
        public readonly string $value,
    ) {}
}
