<?php

namespace EdouardCourty\RetryableCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

abstract class RetryableCommand extends Command
{
    private const DEFAULT_RETRIES = 3;

    private int $currentRetry = 0;
    private ?int $maxRetry = null;

    /**
     * @return void
     */
    protected function configure(): void
    {
        parent::configure();

        $this->addOption('max-retry', 'r', InputOption::VALUE_OPTIONAL, 'Maximum number of retries', self::DEFAULT_RETRIES);
    }

    /**
     * @param int $retryIndex
     * @return int
     */
    abstract protected function retryTimeout(int $retryIndex): int;

    /**
     * @param int $retry
     * @return $this
     */
    protected function setMaxRetry(int $retry): self
    {
        $this->maxRetry = $retry;

        return $this;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    abstract protected function executeWithRetry(InputInterface $input, OutputInterface $output): int;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maxRetry = $this->maxRetry ?: $input->getOption('max-retry');

        while (true) {
            try {
                return $this->executeWithRetry($input, $output);
            } catch (Throwable $exception) {
                $this->currentRetry += 1;

                if ($this->currentRetry === $maxRetry) {
                    throw $exception;
                }

                sleep($this->retryTimeout($this->currentRetry));

                $output->writeln('Retry ' . $this->currentRetry . '...');
            }
        }
    }

    /**
     * @return int
     */
    protected function getCurrentRetry(): int
    {
        return $this->currentRetry;
    }
}
