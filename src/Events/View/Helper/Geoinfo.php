<?php
namespace Events\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Geoinfo extends AbstractHelper implements ServiceLocatorAwareInterface
{

    protected $serviceLocator;

    /**
     * Set the service locator.
     *
     * @param $serviceLocator ServiceLocatorInterface
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function __invoke($address)
    {
        $address = urlencode($address);
        $serviceLocator = $this->getServiceLocator()->getServiceLocator();
        $settingsService = $serviceLocator->get('SettingsService');
        $googleapikey = $settingsService->getValueByParameter('Events', 'googleapikey');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?key=$googleapikey&address=$address&sensor=true";
        #echo $url;
        // get the distance from the HTML5 Geolocation javascript
        $json = file_get_contents($url); // this WILL do an http request for you
        $gdata = json_decode($json);
        $city = null;
        $state = null;
        $country = null;
        $longitude = null;
        $latitude = null;

        if (count($gdata->results) > 0) {
            //break up the components
            $arrComponents = $gdata->results[0]->address_components;

            $latitude = $gdata->results[0]->geometry->location->lat;
            $longitude = $gdata->results[0]->geometry->location->lng;

            foreach ($arrComponents as $index => $component) {
                $type = $component->types[0];

                if (empty($city) && ($type == "administrative_area_level_3" || $type == "locality")) {
                    $city = trim($component->short_name);
                }
                if (empty($state) && $type == "administrative_area_level_1") {
                    $state = trim($component->short_name);
                }
                if (empty($country) && $type == "country") {
                    $country = trim($component->short_name);
                }
                if ($city != "" && $state != "" && $country != "") {
                    //we're done
                    break;
                }
            }
        }
        $arrReturn = array("city" => $city, "state" => $state, "country" => $country, "latitude" => $latitude, "longitude" => $longitude);
        return $this->view->render('events/partial/geoinfo', array('data' => $arrReturn));
    }
}