<?php

namespace Tests\Command;

use RetryableCommand\Command\RetryableCommand;
use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DummyRetryableCommand extends RetryableCommand
{
    protected function retryTimeout(int $retryIndex): int
    {
        return 0;
    }

    /**
     * @throws Exception
     */
    public function executeWithRetry(InputInterface $input, OutputInterface $output): int
    {
        throw new Exception('Error');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        return parent::execute($input, $output);
    }

    public function getCurrentRetry(): int
    {
        return parent::getCurrentRetry();
    }
}
