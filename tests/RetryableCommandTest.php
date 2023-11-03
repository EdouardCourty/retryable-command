<?php

namespace Tests;

use RetryableCommand\Command\RetryableCommand;
use Exception;
use PHPUnit\Framework\MockObject\Exception as PHPUnitException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tests\Command\DummyRetryableCommand;
use Throwable;

class RetryableCommandTest extends TestCase
{
    private RetryableCommand $command;

    private InputInterface $input;
    private OutputInterface $output;

    /**
     * @return void
     * @throws PHPUnitException
     */
    protected function setUp(): void
    {
        $this->command = new DummyRetryableCommand();

        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testItRetries(): void
    {
        $this->input->method('getOption')->willReturnMap([
            ['max-retry', 10]
        ]);

        $this->expectException(Exception::class);

        $this->assertEquals(0, $this->command->getCurrentRetry());
        $this->command->execute($this->input, $this->output);
        $this->assertEquals(10, $this->command->getCurrentRetry());
    }
}
