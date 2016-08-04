<?php 
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MapHelper extends AbstractHelper implements ServiceLocatorAwareInterface {
	
	protected $serviceLocator;
	 
	/**
	 * Set the service locator.
	 *
	 * @param $serviceLocator ServiceLocatorInterface       	
	 * @return CustomHelper
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}
	/**
	 * Get the service locator.
	 *
	 * @return \Zend\ServiceManager\ServiceLocatorInterface
	 */
	public function getServiceLocator() {
		return $this->serviceLocator;
	}

    public function __invoke($address, $zoom = 6)
    {
        $coords = array();
        $serviceLocator = $this->getServiceLocator()->getServiceLocator();
        $settingsService = $serviceLocator->get('SettingsService');
        $googleapikey = $settingsService->getValueByParameter('Events', 'googleapikey');
        
        if(!empty($address) && is_string($address)){
            
        }elseif(!empty($address) && is_array($address)){
            if(!empty($address['latitude']) && !empty($address['longitude'])){
                $coords[] = array('lat' => $address['latitude'], 'lng' => $address['longitude']);
            }
        }
        
        return $this->view->render('events/partial/map', array('coords' => $coords, 'key' => $googleapikey, 'zoom' => $zoom));
    }
}