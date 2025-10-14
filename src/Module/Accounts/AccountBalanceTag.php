<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Accounts;

enum AccountBalanceTag: string
{
    case LATEST = 'latest';
    case EARLIEST = 'earliest';
    case PENDING = 'pending';
}
