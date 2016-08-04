<?php 
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

class Distance extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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

    public function __invoke($latitudeTo, $longitudeTo, $earthRadius = "6371")
    {
        $session = new Container('coords');
        if(!empty($session->coords)){

            // get the distance from the HTML5 Geolocation javascript
            $latitudeFrom = $session->coords['latitude']; // User latitude
            $longitudeFrom = $session->coords['longitude']; // User latitude
            
            // Get the event service to calculate the Haversine radius
            if(!empty($latitudeTo) && !empty($longitudeTo)){
                $serviceLocator = $this->getServiceLocator()->getServiceLocator();
                $eventService = $serviceLocator->get('EventService');
                
                $distance = $eventService->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius);
                if(is_numeric($distance)){
                    $distance = round($distance, 3);
                    $arrDistance = explode(".", $distance);
                    
                    if(($arrDistance[0] > 0)){
                        return sprintf('%s km', $arrDistance[0]);
                    }elseif($arrDistance[0] <= 0 && !empty($arrDistance[1])){
                        return sprintf('%d m', $arrDistance[1]);
                    }
                    
                }
            }
        }
        return false;
    }
}