<?php
/**
 * Created by PhpStorm.
 * User: ACER
 * Date: 2016/7/13
 * Time: 17:49
 */

namespace Slince\Command\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheFlushCommand extends Command
{
    function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('cache')->flush();
        $output->writeln(__("Clear OK"));
    }
}