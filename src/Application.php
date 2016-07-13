<?php
namespace Slince\Command;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    /**
     * 获取资源路径
     * @return string
     */
    function getAssetPath()
    {
        return dirname(__DIR__) . '/assets';
    }
}