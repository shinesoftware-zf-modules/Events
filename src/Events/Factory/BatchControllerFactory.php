<?php
namespace Events\Factory;

use Events\Controller\BatchController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BatchControllerFactory implements FactoryInterface
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
        $socialEventsService = $realServiceLocator->get('SocialEvents');

        return new BatchController($eventService, $socialEventsService, $eventSettings);
    }
}