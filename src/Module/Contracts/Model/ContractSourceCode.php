<?php

declare(strict_types=1);

namespace seregazhuk\EtherscanApi\Module\Contracts\Model;

final class ContractSourceCode
{
    public function __construct(
        public readonly string $sourceCode,
        public readonly string $abi,
        public readonly string $contractName,
        public readonly string $compilerVersion,
        public readonly string $optimizationUsed,
        public readonly string $constructorArguments,
        public readonly string $EVMVersion,
        public readonly string $proxy,
        public readonly string $licenseType,
    ) {}
}
