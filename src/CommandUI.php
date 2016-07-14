<?php
namespace Slince\Command;

use App\AppKernel;
use Slince\Command\Command\CacheFlushCommand;
use Slince\Command\Command\CodeMakeCommand;

class CommandUI
{
    /**
     * 默认的应用
     * @var string
     */
    const DEFAULT_COMMAND_NAME = 'welcome';

    /**
     * 创建command
     * @return array
     */
    static function createCommands()
    {
        return [
            new CacheFlushCommand(),
            new CodeMakeCommand()
        ];
    }

    /**
     * command应用主入口
     * @throws \Exception
     */
    static function main()
    {
        $kernel = new AppKernel();
        $application = new Application($kernel);
        $application->addCommands(self::createCommands());
        $application->setDefaultCommand(self::DEFAULT_COMMAND_NAME);
        $application->run();
    }
}