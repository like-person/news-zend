<?php
namespace News;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '\..\config\module.config.php';
    }
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\NewsTable::class => function($container) {
                    $tableGateway = $container->get(Model\NewsTableGateway::class);
                    return new Model\NewsTable($tableGateway);
                },
                Model\NewsTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\News());
                    return new TableGateway('news', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\NewsController::class => function($container) {
                    return new Controller\NewsController(
                        $container->get(Model\NewsTable::class)
                    );
                },
            ],
        ];
    }
}
