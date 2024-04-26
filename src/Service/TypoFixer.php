<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

readonly class TypoFixer
{
    public function __construct(
        private LoggerInterface $logger,
        private string $someConfig,
    ) {
    }

    public function doSomething(): void
    {
        $this->logger->info('something', ['config' => $this->someConfig]);
    }
}
