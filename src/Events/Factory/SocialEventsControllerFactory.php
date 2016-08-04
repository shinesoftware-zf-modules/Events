<?php
namespace Events\Factory;

use Events\Controller\SocialEventsController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Events\Model\SocialEventsDatagrid;

class SocialEventsControllerFactory implements FactoryInterface
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
        $baseSettings = $realServiceLocator->get('SettingsService');
        $socialEvents = $realServiceLocator->get('SocialEvents');
        $datagrid = $realServiceLocator->get('ZfcDatagrid\Datagrid');
        $dbAdapter = $realServiceLocator->get('Zend\Db\Adapter\Adapter');
        $zfcAuthService = $realServiceLocator->get('zfcuser_auth_service');
        
        // prepare the datagrid to handle the custom columns and data
        $theDatagrid = new SocialEventsDatagrid($zfcAuthService, $dbAdapter, $datagrid, $baseSettings);
        $grid = $theDatagrid->getDatagrid();
        

        return new SocialEventsController($grid, $socialEvents, $baseSettings);
    }
}