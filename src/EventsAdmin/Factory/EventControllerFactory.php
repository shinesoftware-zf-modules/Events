<?php
namespace EventsAdmin\Factory;

use EventsAdmin\Controller\EventController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use EventsAdmin\Model\EventDatagrid;

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
        $service = $realServiceLocator->get('EventService');
        $settings = $realServiceLocator->get('SettingsService');
        $language = $realServiceLocator->get('LanguagesService');
        $dbAdapter = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
        $datagrid = $realServiceLocator->get('ZfcDatagrid\Datagrid');
        $form = $realServiceLocator->get('FormElementManager')->get('Events\Form\EventForm');
        $formfilter = $realServiceLocator->get('EventFilter');
        
        // prepare the datagrid to handle the custom columns and data
        $theDatagrid = new EventDatagrid($dbAdapter, $datagrid, $language, $settings);
        $grid = $theDatagrid->getDatagrid();
        
        return new EventController($service, $form, $formfilter, $grid, $settings);
    }
}