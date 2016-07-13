<?php
namespace Slince\Command\Command;

use Slince\Application\Kernel;
use Slince\Command\Application;
use Slince\Di\Container;

use Symfony\Component\Console\Command\Command as BaseCommand;

class Command extends BaseCommand
{
    /**
     * @var Kernel
     */
    protected $kernel;

    function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * 获取服务容器
     * @return Container
     */
    function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @return Application
     */
    function getApplication()
    {
        return $this->getApplication();
    }
}