<?php
namespace League\Tactician\Execution;

class ChainExecutor implements ExecutionStrategy
{
    /**
     * @var ExecutionStrategy[]
     */
    private $executionStrategy;

    public function __construct($executors)
    {
        $this->executionStrategy = $executors;
    }

    /**
     * @param $command
     * @throws CanNotInvokeHandlerException
     * @return callable|null
     */
    public function makeCallable($command)
    {
        foreach ($this->executionStrategy as $executionStrategy) {
            $callable = $executionStrategy->makeCallable($command);
            if ($callable !== null) {
                return $callable;
            }
        }
    }
}
