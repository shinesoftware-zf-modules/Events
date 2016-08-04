<?php 
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Geolocalization extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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

    public function __invoke($latitude, $longitude)
    {
        $serviceLocator = $this->getServiceLocator()->getServiceLocator();
        $settingsService = $serviceLocator->get('SettingsService');
        $googleapikey = $settingsService->getValueByParameter('Events', 'googleapikey');
        $km_radius = $settingsService->getValueByParameter('Events', 'km_radius');

        try{
            // get the distance from the HTML5 Geolocation javascript
            $json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key=$googleapikey&latlng=$latitude,$longitude&sensor=true"); // this WILL do an http request for you
            $gdata = json_decode($json);
            if(!empty($gdata->results[0])){
                return $this->view->render('events/partial/geolocalization', array('data' => $gdata->results[0]->address_components, 'km' => $km_radius));
            }
        }catch (\Exception $e){
            return null;
        }
        
        return null;
    }
}