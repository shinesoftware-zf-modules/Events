<?php
namespace Events\Factory;

use Events\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventControllerFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $eventService = $realServiceLocator->get('EventService');
        $eventSettings = $realServiceLocator->get('SettingsService');

        return new IndexController($eventService, $eventSettings);
    }
}