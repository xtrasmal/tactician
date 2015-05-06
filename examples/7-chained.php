<?php
require __DIR__ . '/../vendor/autoload.php';

use League\Tactician\Handler\Locator\InMemoryLocator;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Execution\HandlerExecution;
use League\Tactician\Execution\ChainExecutor;
use League\Tactician\Plugins\SelfExecutingCommands\SelfExecution;
use League\Tactician\Plugins\SelfExecutingCommands\SelfExecutingCommand;

// Our example Command and Handler. ///////////////////////////////////////////
class RegisterUserCommand
{
    public $emailAddress;
    public $password;
}

class RegisterUserHandler
{
    public function handleRegisterUserCommand(RegisterUserCommand $command)
    {
        // Do your core application logic here. Don't actually echo stuff. :)
        echo "User {$command->emailAddress} was registered!\n";
    }
}

class TurnOffLightCommand implements SelfExecutingCommand
{
    public function execute()
    {
        echo "Light turned off!\n";
    }
}

// Setup the bus, normally in your DI container ///////////////////////////////
$locator = new InMemoryLocator();
$locator->addHandler(new RegisterUserHandler(), RegisterUserCommand::class);

// Middleware is Tactician's plugin system. Even finding the handler and
// executing it is a plugin that we're configuring here.
$handlerMiddleware = new CommandHandlerMiddleware(
    new ChainExecutor(
        [
            new SelfExecution(),
            new HandlerExecution(
                new ClassNameExtractor(),
                $locator,
                new HandleClassNameInflector()
            )
        ]
    )
);

$commandBus = new \League\Tactician\CommandBus([$handlerMiddleware]);

// Controller Code ////////////////////////////////////////////////////////////
$command = new RegisterUserCommand();
$command->emailAddress = 'alice@example.com';
$command->password = 'secret';

$commandBus->handle($command);
$commandBus->handle(new TurnOffLightCommand());
