<?php
namespace EventsSettings\Form;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use \Base\Hydrator\Strategy\DateTimeStrategy;

class EventForm extends Form
{

    public function init ()
    {

        $this->setAttribute('method', 'post');

        
        $this->add(array (
                'name' => 'eventsperpage',
                'attributes' => array (
                        'class' => 'form-control',
                		'value' => 5
                ),
                'options' => array (
                        'label' => _('Events per page'),
                )
        ));
        $this->add(array (
                'name' => 'km_radius',
                'attributes' => array (
                        'class' => 'form-control',
                		'value' => 50
                ),
                'options' => array (
                        'label' => _('Event distance radius (default)'),
                )
        ));

        $this->add(array (
                'name' => 'usereventsperpage',
                'attributes' => array (
                        'class' => 'form-control',
                		'value' => 5
                ),
                'options' => array (
                        'label' => _('Events per page in the user profile'),
                )
        ));

        $this->add(array (
                'name' => 'far_events_per_page',
                'attributes' => array (
                        'class' => 'form-control',
                		'value' => 5
                ),
                'options' => array (
                        'label' => _('Far events per page in the public events page'),
                )
        ));

        $this->add(array (
                'name' => 'googleapikey',
                'attributes' => array (
                        'class' => 'form-control',
                        'placeholder' => 'Public API Key for server applications. Go to https://console.developers.google.com',
                ),
                'options' => array (
                        'label' => _('Google API Public Key'),
                )
        ));

        $this->add(array (
                'name' => 'recordsperpage',
                'attributes' => array (
                        'class' => 'form-control',
                		'value' => 5
                ),
                'options' => array (
                        'label' => _('Records per event for the admin grid'),
                )
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type' => 'submit',
                'class' => 'btn btn-success',
                        'value' => _('Save')
                )
        ));
     
    }
}