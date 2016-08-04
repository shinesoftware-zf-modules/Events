<?php
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Events  extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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
	
	public function __invoke($userId) {
	    $serviceLocator = $this->getServiceLocator()->getServiceLocator();
	    $eventService = $serviceLocator->get('EventService');
	    $eventSettings = $serviceLocator->get('SettingsService');
        $ItemCountPerEvent = $eventSettings->getValueByParameter('Events', 'usereventsperpage');
        $events = array();
	    
	    $records = $eventService->getActiveEventsByUserId($userId);
	    if($records->count()){
	        $data = array();
	        	
	        foreach ($records as $record){
	            $data[] = $record;
	        }
	         
	        $events = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($data));
            $events->setItemCountPerPage($ItemCountPerEvent);
	    }
	    
	    return $this->view->render('events/partial/listnew', array('events' => $events));
	}
}