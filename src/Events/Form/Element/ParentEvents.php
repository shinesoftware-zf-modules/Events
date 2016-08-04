<?php
namespace Events\Form\Element;

use Events\Service\EventService;
use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\I18n\Translator\Translator;

class ParentEvents extends Select implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $translator;
    protected $eventService;

    public function __construct(EventService $eventService, \Zend\Mvc\I18n\Translator $translator){
        parent::__construct();
        $this->eventService = $eventService;
        $this->translator = $translator;
    }
    
    public function init()
    {
        $data = array();
        $auth = $this->getServiceLocator()->getServiceLocator()->get('zfcuser_auth_service');

        // if the request comes from the restful service the identity is not available so we have to get all the events
        if($auth->getIdentity()){
            $userId = $auth->getIdentity()->getId();
            $events = $this->eventService->findAllbyUserId($userId);
        }else{
            $events = $this->eventService->findAll();
        }

        $locale = $this->translator->getTranslator()->getLocale();

        $dateType = \IntlDateFormatter::SHORT;//type of date formatting
        $timeType = \IntlDateFormatter::NONE;

        $formatter = new \IntlDateFormatter($locale, $dateType, $timeType, null, null, 'dd/MM/YY');

        foreach ($events as $event){
            $date = new \DateTime($event->getStart());
            $title = $formatter->format($date) . " - " . $event->getTitle();

            $data[$event->getId()] = $title;
        }
        asort($data);

        $this->setEmptyOption($this->translator->translate('Please select ...'));
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
