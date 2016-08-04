<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Events\Entity\Event;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Events\Service\AddressServiceInterface;
use Events\Service\ContactServiceInterface;
use Events\Service\EventServiceInterface;
use Base\Service\SettingsServiceInterface;
use Zend\Session\Container;

class IndexController extends AbstractActionController
{
	protected $eventService;
	protected $form;
	protected $filter;
	protected $eventSettings;
	protected $translator;
	protected $km;

	
	/**
	 * preDispatch event of the event
	 * 
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
	 */
	public function onDispatch(\Zend\Mvc\MvcEvent $e){
		$this->translator = $e->getApplication()->getServiceManager()->get('translator');
		return parent::onDispatch( $e );
	}
	

	public function __construct(EventServiceInterface $eventService,
								\Events\Form\SearchForm $form,
								\Events\Form\SearchFilter $formfilter,
								SettingsServiceInterface $settings){
		$this->km = 50;
		$this->eventService = $eventService;
		$this->form = $form;
		$this->filter = $formfilter;
		$this->eventSettings = $settings;
	}

	/**
	 * Get the list of the active and visible events 
	 * 
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
    public function indexAction ()
    {
        $session = new Container('coords');

        $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter(array()));
    	$page = $this->params()->fromRoute('page');
    	$ItemCountPerEvent = $this->eventSettings->getValueByParameter('Events', 'far_events_per_page');

		// set default values
    	$ItemCountPerEvent = !empty($ItemCountPerEvent) ? $ItemCountPerEvent : 5;
    	$page = !empty($page) ? $page : 1;
    	
    	$records = $this->eventService->getActiveEvents();
    	
    	// get the distance from the HTML5 Geolocation javascript
    	$latitudeFrom = $session->coords['latitude']; // User latitude
    	$longitudeFrom = $session->coords['longitude']; // User latitude

        if($records->count()){
	        $data = array();
	            	    
        	foreach ($records as $record){
        	    // get the list of the events FAR from me (about 10 KM!)
        	    if (!empty($latitudeFrom) && !empty($longitudeFrom)){
        		    $latitude = $record->getLatitude();
        		    $longitude = $record->getLongitude();
        		    
        		    $distance = $this->eventService->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitude, $longitude, "6371");
        		    if(!empty($distance)){
            		    $distance = round($distance, 3);
            		    $arrDistance = explode(".", $distance);
            		    if( ((int)$arrDistance[0] > $this->km)){
            		        $data[] = $record;
            		    }
        		    }
        	    }else{
        	        $data[] = $record;
        	    }
        	}
        	
        	$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($data));
        	$paginator->setItemCountPerPage($ItemCountPerEvent);
        	$paginator->setCurrentPageNumber($page);
    	} 
    	
    	$viewModel = new ViewModel(array(
									 'form' => $this->form,
									 'events' => $paginator,
									 'coords' => $session->coords,
									 'km' => $this->km
								));
       	$viewModel->setTemplate('events/index/list');
    	
    	return $viewModel;

    }
	
	/**
	 * Get the list of the close and visible events 
	 * 
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
    public function geteventsAction (){
        $session = new Container('coords');
        $request = $this->getRequest();
    	$event = $this->params()->fromRoute('events');
    	$ItemCountPerEvent = $this->eventSettings->getValueByParameter('Events', 'usereventsperpage');
    	$paginator = array();

    	if ($request->isXmlHttpRequest()){
    	
        	$this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($this->translator->translate('List of all events'));
        	$records = $this->eventService->getActiveEvents();

			// get the distance from the HTML5 Geolocation javascript
        	$latitudeFrom = $session->coords['latitude']; // User latitude
        	$longitudeFrom = $session->coords['longitude']; // User latitude
        	
        	if($records->count()){
        	    $data = array();
        	    
            	foreach ($records as $record){
            	    if (!empty($latitudeFrom) && !empty($longitudeFrom)){
            		    $latitude = $record->getLatitude();
            		    $longitude = $record->getLongitude();
            		    
            		    $distance = $this->eventService->haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitude, $longitude, "6371");
            		    if(!empty($distance)){
                		    $distance = round($distance, 3);

                		    $arrDistance = explode(".", $distance);
                		    if( ((int)$arrDistance[0] < $this->km)){
                		        $data[] = $record;
                		    }

            		    }
            	    }
            	}
            	$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($data));
            	$paginator->setItemCountPerPage($ItemCountPerEvent);
            	$paginator->setCurrentPageNumber($event);
        	}
        	
        	$viewModel = new ViewModel(array('events' => $paginator, 'km' => $this->km));
        	
            $viewModel->setTemplate('events/partial/listnew');
            $viewModel->setTerminal($request->isXmlHttpRequest());
            return $viewModel;
    	}
    	
    	die();

    }
    
    /**
     * Show the event selected by its slug code 
     */
    public function eventAction (){

    	$slug = $this->params()->fromRoute('slug');
    	
    	if(empty($slug)){
    		return $this->redirect()->toRoute('home');
    	}

    	// get the event by its slug code
    	$event = $this->eventService->findByUri($slug, $this->translator->getLocale());
    	
    	if($event){

    	    $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($event->getTitle());
    	    
    		// get the parent event
    		$parent = $this->eventService->getById($event->getParentId());
    		
    		// set the view for the event
    		$viewModel  = new ViewModel(array('event' => $event, 'parent' => $parent));
	    	$viewModel->setTemplate('events/index/details.phtml');
	    	
    	}else{
    		$this->flashMessenger()->setNamespace('danger')->addMessage(sprintf($this->translator->translate('The event with the url "%s" has been not found!'), $slug));

    		$viewModel  = new ViewModel();
    		$viewModel->setTemplate('events/index/notfound');
    	}
    	

    	return $viewModel;

    }

	/**
	 * Export an event into a Ics Calendar file
	 */
	public function exportAction(){
		$id = $this->params()->fromRoute('id');
		$type = $this->params()->fromRoute('type');

		if(empty($id)){
			return $this->redirect()->toRoute('events');
		}

		if($type == "ics"){
			header('Content-type: text/calendar; charset=utf-8');
			header('Content-Disposition: attachment; filename=cal.ics');
			$url = $this->getRequest()->getServer('HTTP_HOST');

			die($this->eventService->icsExporter($id, $url, $this->translator->getLocale()));
		}

		return $this->redirect()->toRoute('events');

	}
    
    /**
     * Save the localization of the user by the HTML5 Geolocation
     * https://github.com/estebanav/javascript-mobile-desktop-geolocation
     * Ajax post is executed 
     */
    public function savecoordsAction(){
        $request = $this->getRequest();
        $session = new Container('coords');
        
        if ($request->isXmlHttpRequest()){ // If it's ajax call
            $latitude = $request->getPost('lat');
            $longitude = $request->getPost('lng');

            if(!empty($latitude) && !empty($longitude)){
                $session->coords = array('latitude' => $latitude, 'longitude' => $longitude);
                die(true);
            }
        }
        die(false);
    }
}
