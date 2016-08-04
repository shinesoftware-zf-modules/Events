<?php
namespace Events\Factory;

use Events\Controller\SearchController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SearchControllerFactory implements FactoryInterface
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
        $eventSettings = $realServiceLocator->get('SettingsService');
        $eventService = $realServiceLocator->get('EventService');

        return new SearchController($eventService, $eventSettings);
    }
}