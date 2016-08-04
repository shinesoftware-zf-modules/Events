<?php
namespace Events\Form;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use \Base\Hydrator\Strategy\DateTimeStrategy;

class EventForm extends Form
{

    public function init ()
    {
        $hydrator = new ClassMethods(true);
        $this->setName('event');
        $this->setAttribute('method', 'post');
        $this->setHydrator($hydrator)->setObject(new \Events\Entity\Event());
        
        $hydrator->addStrategy('start', new DateTimeStrategy());
        $hydrator->addStrategy('end', new DateTimeStrategy());
        
        $this->add(array ( 
                'name' => 'title', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control input-lg',
                		'placeholder' => _('Write here the title of the event'),
                ), 
                'options' => array ( 
                        'label' => _('Title'),
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));


        $this->add(array (
                'name' => 'city',
                'attributes' => array (
                        'type' => 'text',
                        'class' => 'form-control',
                		'placeholder' => _('Write here the city of the event'),
                ),
                'options' => array (
                        'label' => _('City'),
                ),
                'filters' => array (
                        array (
                                'name' => 'StringTrim'
                        )
                )
        ));


        $this->add(array (
            'type' => 'Base\Form\Element\Country',
            'name' => 'country_id',
            'attributes' => array (
                'class' => 'form-control',
            ),
            'options' => array (
                'label' => _('Country'),
            ),
        ));

        $this->add(array ( 
                'name' => 'address', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control',
                		'placeholder' => _('Write here full address of the event'),
                ), 
                'options' => array ( 
                        'label' => _('Address'),
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));

        $this->add(array (
                'name' => 'tags',
                'attributes' => array (
                        'type' => 'text',
                        'class' => 'form-control',
                		'placeholder' => _('Write here your event tags. Use commas to separate words'),
                ),
                'options' => array (
                        'label' => _('Tags'),
                ),
                'filters' => array (
                        array (
                                'name' => 'StringTrim'
                        )
                )
        ));

        $this->add(array ( 
                'name' => 'contact', 
                'attributes' => array ( 
                        'type' => 'text', 
                        'class' => 'form-control',
                		'placeholder' => _('Write here the phone number of the organizer like +39.123456789'),
                ), 
                'options' => array ( 
                        'label' => _('Contact'),
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));
        
        $this->add(array ( 
                'name' => 'start', 
                'options' => array(
                        'label' => _('Start Date/Time'),
                        'format' => 'd/m/Y H:i'
                ),
                'attributes' => array(
                        'type' => 'DateTime',
                        'class' => 'form-control datetime',
                        'min' => '2010-01-01',
                        'max' => '2020-01-01',
                        'step' => '1', // minutes; default step interval is 1 min
                        'placeholder' => _('Select when the event start. If empty is set now!'),
                )
        ));
        
        $this->add(array ( 
                'name' => 'end', 
                'options' => array(
                        'label' => _('End Date/Time'),
                        'format' => 'd/m/Y H:i'
                ),
                'attributes' => array(
                        'type' => 'DateTime',
                        'class' => 'form-control datetime',
                        'min' => '2010-01-01',
                        'max' => '2020-01-01',
                        'step' => '1', // minutes; default step interval is 1 min
                    'placeholder' => _('Select when the event end.'),
                )
        ));
        
        
        $this->add(array ( 
        		'type' => 'Base\Form\Element\Languages',
                'name' => 'language_id', 
                'attributes' => array ( 
                        'class' => 'form-control',
                ), 
                'options' => array ( 
                        'label' => _('Language'),
                ), 
        ));
        
        $this->add(array (
        		'type' => 'Events\Form\Element\EventCategories',
        		'name' => 'category_id',
        		'attributes' => array (
        				'class' => 'form-control'
        		),
        		'options' => array (
        				'label' => _('Category')
        		)
        ));
        
        
        $this->add(array (
        		'type' => 'Events\Form\Element\ParentEvents',
        		'name' => 'parent_id',
        		'attributes' => array (
        				'class' => 'form-control'
        		),
        		'options' => array (
        				'label' => _('Event connected')
        		)
        ));
        
        $this->add(array (
        		'name' => 'url',
        		'attributes' => array (
        				'class' => 'form-control',
        		        'placeholder' => _('Type here the website URL like http://www.mysite.com'),
        		),
        		'options' => array (
        				'label' => _('Website URL')
        		)
        ));

        $this->add(array (
            'name' => 'slug',
            'attributes' => array (
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => _('Write here the url key of the event'),
            ),
            'options' => array (
                'label' => _('URL Key'),
            ),
            'filters' => array (
                array (
                    'name' => 'StringTrim'
                )
            )
        ));


        $this->add(array (
            'name' => 'place',
            'attributes' => array (
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => _('Write here the name of the event place'),
            ),
            'options' => array (
                'label' => _('Name'),
            ),
            'filters' => array (
                array (
                    'name' => 'StringTrim'
                )
            )
        ));

        $this->add(array ( 
                'id' => 'content', 
                'name' => 'content', 
                'attributes' => array ( 
                        'type' => 'textarea', 
                        'class' => 'form-control wysiwyg',
                        'rows' => 20,
                ), 
                'options' => array ( 
                        'label' => _('Event description'),
                ), 
                'filters' => array ( 
                        array ( 
                                'name' => 'StringTrim'
                        )
                )
        ));
        
        
        $this->add(array (
        		'name' => 'tags',
        		'attributes' => array (
        				'type' => 'text',
        				'class' => 'form-control',
        				'data-role' => 'tagsinput',
        				'placeholder' => _('Add a tag and press enter'),
        		),
        		'options' => array (
        				'label' => _('Tags'),
        		),
        		'filters' => array (
        				array (
        						'name' => 'StringTrim'
        				)
        		)
        ));
        
        $this->add(array (
                'type' => 'Zend\Form\Element\Select',
                'name' => 'visible',
                'attributes' => array (
                        'class' => 'form-control'
                ),
                'options' => array (
                        'label' => _('Visible'),
                        'value_options' => array (
                        		'1' => _('Visible'),
                        		'0' => _('Not Visible'),
                        )
                )
        ));
        
        $this->add(array (
                'type' => 'Zend\Form\Element\Select',
                'name' => 'showonlist',
                'attributes' => array (
                        'class' => 'form-control'
                ),
                'options' => array (
                        'label' => _('Show on list'),
                        'value_options' => array (
                        		'1' => _('Yes'),
                        		'0' => _('No'),
                        )
                )
        ));
        
        $this->add ( array ('type' => 'Zend\Form\Element\File', 
                            'name' => 'file', 
                            'attributes' => array ('id' => 'file' ), 
                            'options' => array (
                                'label' => _ ( 'Upload File' ) ), 
                                'filters' => array (array ('required' => false )  
        ) ) );
        
        
        $this->add(array ( 
                'name' => 'submit', 
                'attributes' => array ( 
                        'type' => 'submit', 
                        'class' => 'btn btn-success',
                    'value' => _('Save the event')
                )
        ));
        $this->add(array (
                'name' => 'id',
                'attributes' => array (
                        'type' => 'hidden'
                )
        ));

        $this->add(array (
                'name' => 'sku',
                'attributes' => array (
                        'type' => 'hidden'
                )
        ));
    }
}