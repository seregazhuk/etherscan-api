<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts;

final class Balance
{
    public function __construct(
        public readonly string $account,
        public readonly string $balance,
    ) {}
}
