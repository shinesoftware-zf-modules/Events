<?php
namespace Events\Form\Element;

use Base\Service\CountryService;
use Events\Service\EventService;
use Zend\Form\Element\Select;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Country extends Select implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;
    protected $translator;
    protected $countryService;
    protected $events;

    public function __construct(CountryService $countryService, EventService $events, \Zend\Mvc\I18n\Translator $translator)
    {
        parent::__construct();
        $this->countryService = $countryService;
        $this->translator = $translator;
        $this->events = $events;
    }

    public function init()
    {
        $data = array();

        $records = $this->countryService->findVisible();

        foreach ($records as $record) {
            $recordsByCountry = $this->events->countByCountry($record->getId());
            if ($recordsByCountry) {
                $data[$record->getId()] = $record->getName() . " ($recordsByCountry)";
            }
        }
        asort($data);
        $this->setEmptyOption($this->translator->translate('All countries ...'));
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
