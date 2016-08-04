<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Events\Service\EventServiceInterface;
use Base\Service\SettingsServiceInterface;
use Base\Model\UrlRewrites as UrlRewrites;

class BatchController extends AbstractActionController
{
	protected $eventService;
	protected $eventSettings;
	protected $socialeventsService;
	protected $translator;
	
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
	
	/**
	 * Constructor
	 * 
	 * @param EventServiceInterface $eventService
	 * @param SettingsServiceInterface $settings
	 */
	public function __construct(EventServiceInterface $eventService,
	                            $socialeventsService,
	                            SettingsServiceInterface $settings)
	{
		$this->eventService = $eventService;
		$this->eventSettings = $settings;
		$this->socialeventsService = $socialeventsService;
	}
	
	
	/**
	 * Save the events
	 */
	public function indexAction ()
	{
	    $urlRewrite = new UrlRewrites();
	    
	    // get all the events from the google calendar temporary table
	    $socialevents = $this->socialeventsService->findAll();
	    
	    foreach ($socialevents as $socialevent){

	        $newEvent = $this->eventService->findByExtid($socialevent->getCode());
	        if(empty($newEvent)){
	            $newEvent = new \Events\Entity\Event();
	        }

	        // The field summary is mandatory
	        if(!$socialevent->getSummary()){
	            $socialevent->setNote('error-empty-title');
	            $this->socialeventsService->save($socialevent);
	            continue;
	        }

	        // The field description is mandatory
	        if(!$socialevent->getDescription()){
	            $socialevent->setNote('error-empty-description');
	            $this->socialeventsService->save($socialevent);
	            continue;
	        }

	        // The field address is mandatory
	        if(!$socialevent->getLocation()){
	            $socialevent->setNote('error-empty-title');
	            $this->socialeventsService->save($socialevent);
	            continue;
	        }
	        
	        // Starting the creation of the new event
	        $newEvent->setTitle($socialevent->getSummary());
	        
	        $strslug = $urlRewrite->format($socialevent->getSummary());
	         
	        $slugExists = $this->eventService->slugExists($strslug);
	        if(1 < $slugExists->count()){
	            $strslug .= "-" . $socialevent->getCode();
	        }
	        
	        $newEvent->setSocialnetworkId($socialevent->getId());
	        $newEvent->setUserId($socialevent->getUserId());

			$newEvent->setCountryId($socialevent->getCountryId());
	        $newEvent->setCity($socialevent->getCity());
	        $newEvent->setAddress($socialevent->getLocation());
	        $newEvent->setExtid($socialevent->getCode());
	        $newEvent->setSlug($strslug);
	        $newEvent->setUrl($socialevent->getReferencelink());

	        if("cancelled" == $socialevent->getStatus()){
	            $newEvent->setVisible(0);
	            $socialevent->setNote('cancelled');
	        }else{
	            $newEvent->setVisible(1);
	            $socialevent->setNote('published');
	        }
	        
	        $newEvent->setShowonlist(true);
	        $newEvent->setContent($socialevent->getDescription());
	        $newEvent->setStart(new \Datetime($socialevent->getStart()));

			if($socialevent->getEnd()){
				$newEvent->setEnd(new \Datetime($socialevent->getEnd()));
			}else{
				$newEvent->setEnd(null);
			}

	        $newEvent->setLanguageId(2); // Italiano
	        $newEvent->setCategoryId($socialevent->getCategoryId());
	        $newEvent->setLatitude($socialevent->getLatitude());
	        $newEvent->setLongitude($socialevent->getLongitude());
	        $newEvent->setRecurrence($socialevent->getRecurrence());
	        
	        try{
                // download the first image 
                if($socialevent->getPhoto()){
        	        $url = $socialevent->getPhoto();
        	        
        	        preg_match('/^([^\?]+)(?:\?.*)?/', $url, $path_noQS); // exclude the querystring like: ".jpg?myvar=1..."
        	        $ext = pathinfo($path_noQS[1], PATHINFO_EXTENSION);
        
        	        $relativepath = '/documents/events/' . $socialevent->getCode();
        	        $fullpath = PUBLIC_PATH . '/documents/events/' . $socialevent->getCode();
        	        
        	        @unlink($fullpath); // deleting the folder of the event
        	        @mkdir($fullpath); // creating a new event folder
        	        
        	        $img = $fullpath . '/' . $strslug . "." . $ext;
        	        
        	        $imageContent = @file_get_contents($url, true);
        	        
        	        if($imageContent){
        	            file_put_contents($img, $imageContent);
        	            $file = $relativepath . '/' . $strslug . "." . $ext;
        	            $newEvent->setFile($file);
        	        }else{
        	            $newEvent->setFile(null);
        	        }
        	        
        	        $newDescription = $socialevent->getDescription();
        	        
        	        $photos = array();
        	        preg_match_all('~https?://\\S+\\.(?:jpe?g|png|gif)~im' , $socialevent->getDescription() , $photos);
        	        if(!empty($photos[0])){
        	            foreach ($photos[0] as $photo){
        	                $newDescription = str_replace($photo, "", $newDescription);
        	            }
        	        }
        	        
        	        $newEvent->setContent($newDescription);
        	        
                }
	        }catch(\Exception $e){
	            $socialevent->setNote('error-photo-attachment');
	        }

            $this->eventService->save($newEvent);
	        $this->socialeventsService->save($socialevent);
	    }
	    die('end');
	}
}