<?php

namespace League\Tactician\Handler;

use League\Tactician\Exception\InvalidCommandException;
use League\Tactician\Middleware;
use League\Tactician\Execution\ExecutionStrategy;
use League\Tactician\Exception\CanNotInvokeHandlerException;
use League\Tactician\Handler\CommandNameExtractor\CommandNameExtractor;
use League\Tactician\Handler\Locator\HandlerLocator;
use League\Tactician\Handler\MethodNameInflector\MethodNameInflector;

/**
 * The "core" CommandBus. Locates the appropriate handler and executes command.
 */
class CommandHandlerMiddleware implements Middleware
{
    /**
     * @var ExecutionStrategy
     */
    private $execution;

    /**
     * @param ExecutionStrategy $execution
     */
    public function __construct(ExecutionStrategy $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Executes a command and optionally returns a value
     *
     * @param object   $command
     * @param callable $next
     *
     * @return mixed
     *
     * @throws CanNotInvokeHandlerException
     */
    public function execute($command, callable $next)
    {
        $executor = $this->execution->makeCallable($command);
        if ($executor === null) {
            throw new InvalidCommandException("Couldn't figure out how to execute command.");
        }

        return $executor();
    }
}
