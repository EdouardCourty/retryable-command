<?php

namespace RetryableCommand\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

/**
 * @author Edouard Courty <edouard.courty2@gmail.com>
 */
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
     *
     * @return int
     */
    abstract protected function retryTimeout(int $retryIndex): int;

    /**
     * @param int $retry
     *
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
     *
     * @return int
     */
    abstract protected function executeWithRetry(InputInterface $input, OutputInterface $output): int;

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $maxRetry = (int) $this->maxRetry ?: (int) $input->getOption('max-retry');

        while (true) {
            try {
                return $this->executeWithRetry($input, $output);
            } catch (Throwable $exception) {
                $timeout = $this->retryTimeout($this->currentRetry);

                if ($this->currentRetry === $maxRetry) {
                    $io->error(sprintf('Command failed %s times, exiting.', $this->currentRetry));
                    throw $exception;
                }

                $io->warning(sprintf('Command failed at retry %s, retrying in %s seconds.', $this->currentRetry, $timeout));

                $this->currentRetry += 1;

                sleep($timeout);
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
