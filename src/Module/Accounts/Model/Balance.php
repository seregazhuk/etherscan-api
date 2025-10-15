<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts\Model;

final class Balance
{
    public function __construct(
        public readonly string $account,
        public readonly string $balance,
    ) {}
}
