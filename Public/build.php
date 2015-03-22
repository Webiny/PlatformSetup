<?php
use Webiny\Component\ClassLoader\ClassLoader;
use Webiny\Component\Config\Config;
use Webiny\Component\ServiceManager\ServiceManager;
use Webiny\Component\StdLib\StdObject\ArrayObject\ArrayObject;
use Webiny\Component\Storage\Storage;
use Webiny\Platform\Bootstrap\AppLoader;
use Webiny\Platform\Builders\Backend\DevelopmentBuilder;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once realpath(__DIR__ . '/../vendor/autoload.php');
$classLoaderMap = [
    'Webiny\Platform' => realpath(__DIR__.'/../').'/Vendors/Platform',
    'Apps' => realpath(__DIR__ . '/../').'/Apps'
];
ClassLoader::getInstance()->registerMap($classLoaderMap);

class Build
{
    private $_config;
    private $_storage;
    private $_developmentBuild = false;

    function __construct($argv)
    {
        /**
         * Parse command-line parameters
         */
        $args = new ArrayObject($argv);
        $this->_developmentBuild = $args->inArray('--dev');
        $this->_buildApp = $args->key(1);

        /**
         * Setup autoloader and load build config.
         * Register Services and Storage from config.
         */
        $this->_config = Config::getInstance()->yaml(__DIR__ . '/../Configs/Build.yaml');
        foreach ($this->_config->get('Services') as $sName => $sConfig) {
            ServiceManager::getInstance()->registerService($sName, $sConfig);
        }
        Storage::setConfig($this->_config->get('Storage', []));

        $this->_storage = ServiceManager::getInstance()->getService('Storage.Apps');

        $this->_build();
    }

    private function _build()
    {
        /**
         * Load platform apps
         */
        $appLoader = new AppLoader();
        $apps = $appLoader->loadApps(true);

        $app = isset($apps[$this->_buildApp]) ? $apps[$this->_buildApp] : false;

        if (!$app) {
            die("App with name `" . $this->_buildApp . "` was not found!\n");
        }

        if ($this->_developmentBuild) {
            $builder = new DevelopmentBuilder($this->_config);
            $builder->setAppsStorage($this->_storage)->buildApp($app);
        } else {
            //$builder = new ProductionBuilder($this->_config);
            //$builder->setAppsStorage($this->_storage)->buildApp($app);
        }
    }

}

$build = new Build($argv);
