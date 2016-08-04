<?php
namespace Events\Factory;

use Events\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
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
        $form = $realServiceLocator->get('FormElementManager')->get('Events\Form\SearchForm');
        $formfilter = $realServiceLocator->get('SearchFilter');
        return new IndexController($eventService, $form, $formfilter, $eventSettings);
    }
}