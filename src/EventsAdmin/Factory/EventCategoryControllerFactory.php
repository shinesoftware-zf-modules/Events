<?php
namespace EventsAdmin\Factory;

use EventsAdmin\Controller\EventCategoryController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use EventsAdmin\Model\EventCategoryDatagrid;

class EventCategoryControllerFactory implements FactoryInterface
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
        $service = $realServiceLocator->get('EventCategoryService');
        $dbAdapter = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
        $datagrid = $realServiceLocator->get('ZfcDatagrid\Datagrid');
        $form = $realServiceLocator->get('FormElementManager')->get('Events\Form\EventCategoryForm');
        $formfilter = $realServiceLocator->get('EventCategoryFilter');
        $settings = $realServiceLocator->get('SettingsService');
        
        // prepare the datagrid to handle the custom columns and data
        $theDatagrid = new EventCategoryDatagrid($dbAdapter, $datagrid, $settings);
        $grid = $theDatagrid->getDatagrid();
        
        return new EventCategoryController($service, $form, $formfilter, $grid, $settings);
    }
}