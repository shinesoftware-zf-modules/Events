<?php
namespace Events\Form\Element;

use Events\Service\EventCategoryService;
use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Events\Service\EventService;

class EventCategories extends Select implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $translator;
    protected $eventcategoryService;
    protected $events;

    public function __construct(EventCategoryService $eventcategoryService, EventService $events, \Zend\Mvc\I18n\Translator $translator)
    {
        parent::__construct();
        $this->eventcategoryService = $eventcategoryService;
        $this->translator = $translator;
        $this->events = $events;
    }
    
    public function init()
    {
        $data = array();
        
        $eventcategories = $this->eventcategoryService->findVisible();
        foreach ($eventcategories as $eventcategory){
            $recordsByCategory = $this->events->countByCategory($eventcategory->getId());
            if ($recordsByCategory) {
                $data[$eventcategory->getId()] = $this->translator->translate($eventcategory->getCategory()) . " ($recordsByCategory)";
            }
        }
        asort($data);

        $this->setEmptyOption($this->translator->translate('All Categories ...'));
        $this->setValueOptions($data);
    }
    
    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
    }
    
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
