<?php
namespace Slince\Command;

use Slince\Application\Kernel;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * @var Kernel
     */
    protected $kernel;

    function __construct(Kernel $kernel)
    {
        parent::__construct();
        $this->kernel = $kernel;
    }

    /**
     * 获取资源路径
     * @return string
     */
    function getAssetPath()
    {
        return dirname(__DIR__) . '/assets';
    }

    /**
     * @param Kernel $kernel
     */
    public function setKernel($kernel)
    {
        $this->kernel = $kernel;
    }
    
    /**
     * @return Kernel
     */
    public function getKernel()
    {
        return $this->kernel;
    }
}