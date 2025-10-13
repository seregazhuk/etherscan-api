<?php

namespace seregazhuk\EtherscanApi\Module\Accounts;

enum AccountBalanceTag: string
{
    case LATEST = 'latest';
    case EARLIEST = 'earliest';
    case PENDING = 'pending';
}
