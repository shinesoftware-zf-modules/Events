<?php
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EventsRelated  extends AbstractHelper implements ServiceLocatorAwareInterface {
	
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
	
	public function __invoke($eventid) {
	    $serviceLocator = $this->getServiceLocator()->getServiceLocator();
	    $eventService = $serviceLocator->get('EventService');
	    $paginator = array();
	    
	    $records = $eventService->getEventRelated($eventid);
	    if($records->count()){
	        $data = array();
	        	
	        foreach ($records as $record){
	            $data[] = $record;
	        }
	         
	        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($data));
	    }

	    return $this->view->render('events/partial/listnew', array('events' => $paginator));
	}
}