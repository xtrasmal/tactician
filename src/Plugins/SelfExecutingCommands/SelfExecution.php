<?php
namespace League\Tactician\Plugins\SelfExecutingCommands;

use League\Tactician\Execution\CanNotInvokeHandlerException;
use League\Tactician\Execution\ExecutionStrategy;

class SelfExecution implements ExecutionStrategy
{
    /**
     * @param $command
     * @throws CanNotInvokeHandlerException
     * @return callable|null
     */
    public function makeCallable($command)
    {
        if (!$command instanceof SelfExecutingCommand) {
            return;
        }

        return function () use ($command) {
            return $command->execute();
        };
    }
}
