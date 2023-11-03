# Retryable Commands

[![PHP CI - Full](https://github.com/EdouardCourty/retryable-command/actions/workflows/php-ci-full.yml/badge.svg)](https://github.com/EdouardCourty/retryable-command/actions/workflows/php-ci-full.yml)

This library adds a retry feature to Symfony Commands.

### Usage

To make a command retryable, make it extend `RetryableCommand\Command\RetryableCommand`.  
To configure how many retries the command will do, you can either:
- Use the `setMaxRetry` command inside the `configure` method
- Use the `max-retry` CLI option when calling the command

The sleep timeout is calculated using the `retryTimeout` that has to be implemented.  
If you don't want to wait between retries, simply return 0.

This method accepts the current retry as a parameter, making it possible to compute dynamic retry timeouts depending on how many retries have been done already.

### Contributing

If you wish to contribute to this project, feel free to submit a PR explaining your changes, and make sure to test your addition.  
The pipeline has to be passing.

**&copy; - Edouard Courty**
