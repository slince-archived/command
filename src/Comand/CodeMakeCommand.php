<?php
/**
 * slince console helper
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Command\Command;

use Cake\Utility\Inflector;
use Slince\Application\ApplicationInterface;
use Slince\Command\InvalidArgumentException;
use Slince\Command\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CodeMakeCommand extends Command
{
    /**
     * 代码类型，控制器
     * @var string
     */
    const TYPE_CONTROLLER = 'controller';

    /**
     * 代码类型，模型
     * @var string
     */
    const TYPE_MODEL = 'model';

    /**
     * 模板名
     * @var array
     */
    protected static $templates = [
        self::TYPE_CONTROLLER => 'ControllerTemplate',
        self::TYPE_MODEL => 'ModelTemplate'
    ];

    /**
     * 文件路径
     * @var array
     */
    protected static $filePaths = [
        self::TYPE_CONTROLLER => 'Controller',
        self::TYPE_MODEL => 'Model'
    ];

    function configure()
    {
        $this->addArgument('type', InputArgument::REQUIRED, __("The code type you want"));
        $this->addOption('app', 'a', InputOption::VALUE_OPTIONAL, __("The Application you want generate"));
    }

    function execute(InputInterface $input, OutputInterface $output)
    {
        $type = $input->getArgument('type');
        $applicationName = $input->getOption('app');
        $name = $input->getArgument('name');
        if (!in_array($type, static::$templates)) {
            throw new InvalidArgumentException(__("Type [{0}] is not supported", $type));
        }
        if (!empty($applicationName)) {
            $application = $this->kernel->getApplication($applicationName);
            if (is_null($applicationName)) {
                throw new InvalidArgumentException(__("Application [{0}] does not exists", $applicationName));
            }
        }
        $content = $this->fetchTemplate(static::$templates[$type], $this->buildArguments($application));
        $this->generateFile($this->getFilePath($name, $type, $application) ,$content);
        $output->writeln(__("Code generate ok"));
    }

    function getFilePath($name, $type, ApplicationInterface $application = null)
    {
        $basePath = (is_null($application) ? $this->kernel->getRootPath() : $application->getRootPath()) . '/src';
        $classifyName = Inflector::camelize($name);
        switch ($type) {
            case static::TYPE_CONTROLLER:
                $file = $basePath . $classifyName . 'Controller';
                break;
            case static::TYPE_MODEL:
                $file = $basePath . $classifyName . 'Model';
                break;
        }
        return $file;
    }


    /**
     * 构建所需要的参数
     * @param $application
     * @return array
     */
    function buildArguments(ApplicationInterface $application = null)
    {
        $namespace = is_null($application) ? $this->kernel->getNamespace() : $application->getNamespace();
        return [
            'namespace' => $namespace
        ];
    }

    /**
     * 提取模板并渲染
     * @param $name
     * @param array $parameters
     * @return mixed
     */
    protected function fetchTemplate($name, array $parameters = [])
    {
        $templatePath = $this->getApplication()->getAssetPath() . "/{$name}";
        $resource = @file_get_contents($templatePath);
        if (empty($resource)) {
            throw new InvalidArgumentException(__("Template {0} does not exists", $name));
        }
        return preg_replace_callback('/{(\w+)}/', function($matches) use ($parameters){
            if (isset($parameters[$matches[1]])) {
                return $parameters[$matches[1]];
            }
            throw new InvalidArgumentException(__("Argument [{0}] should be provided", $matches[1]));
        }, $resource);
    }

    /**
     * @param $file
     * @param $content
     * @return bool
     */
    function generateFile($file, $content)
    {
        if (@file_put_contents($file, $content) === false) {
            throw new RuntimeException(__("File [{0}] write failed", $file));
        }
        return true;
    }
}