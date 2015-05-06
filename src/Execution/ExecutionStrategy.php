<?php
namespace League\Tactician\Execution;

/**
 *
 */
interface ExecutionStrategy
{
    /**
     * @param $command
     * @throws CanNotInvokeHandlerException
     * @return callable|null
     */
    public function makeCallable($command);
}
