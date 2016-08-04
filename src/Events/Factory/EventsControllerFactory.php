<?php
namespace Events\Factory;

use Events\Controller\EventsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Events\Model\EventDatagrid;
class EventsControllerFactory implements FactoryInterface
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
        $baseSettings = $realServiceLocator->get('SettingsService');
        $datagrid = $realServiceLocator->get('ZfcDatagrid\Datagrid');
        $dbAdapter = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
        $zfcAuthService = $realServiceLocator->get('zfcuser_auth_service');
        $form = $realServiceLocator->get('FormElementManager')->get('Events\Form\EventForm');
        $formfilter = $realServiceLocator->get('EventFilter');
        $translator = $realServiceLocator->get('translator');
        
        // prepare the datagrid to handle the custom columns and data
        $theDatagrid = new EventDatagrid($zfcAuthService, $dbAdapter, $datagrid, $baseSettings, $translator);
        $grid = $theDatagrid->getDatagrid();
        

        return new EventsController($eventService, $grid, $form, $formfilter, $baseSettings);
    }
}