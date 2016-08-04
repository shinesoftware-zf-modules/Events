<?php
namespace EventsSettings\Form;
use Zend\InputFilter\InputFilter;

class EventFilter extends InputFilter
{

    public function __construct ()
    {
        $this->add(array(
            'name' => 'eventsperpage',
            'required' => false
        ));

        $this->add(array(
            'name' => 'usereventsperpage',
            'required' => false
        ));

        $this->add(array(
            'name' => 'far_events_per_page',
            'required' => false
        ));

        $this->add(array(
            'name' => 'googleapikey',
            'required' => false
        ));

        $this->add(array(
            'name' => 'recordsperpage',
            'required' => false
        ));
    }
}