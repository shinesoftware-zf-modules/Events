<?php
namespace Events\Form;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\ClassMethods;
use \Base\Hydrator\Strategy\DateTimeStrategy;

class SearchForm extends Form
{

    public function init ()
    {
        $hydrator = new ClassMethods(true);

        $this->setAttribute('method', 'post');
        $this->setAttribute('id', 'search');
        $this->setHydrator($hydrator)->setObject(new \Events\Entity\Event());

        $this->add(array (
                'name' => 'city',
                'attributes' => array (
                        'type' => 'text',
                        'class' => 'form-control',
                		'placeholder' => _('Write here the city of the event'),
                        'onchange'   => 'onSearch( $(this).closest(\'form\') );'
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
                'name' => 'content',
                'attributes' => array (
                        'type' => 'text',
                        'class' => 'form-control',
                		'placeholder' => _('Free search'),
                        'onchange'   => 'onSearch( $(this).closest(\'form\') );'
                ),
                'options' => array (
                        'label' => _('Free Search'),
                ),
                'filters' => array (
                        array (
                                'name' => 'StringTrim'
                        )
                )
        ));


        $this->add(array (
            'type' => 'Events\Form\Element\Country',
            'name' => 'country_id',
            'attributes' => array (
                'class' => 'form-control',
                'onchange'   => 'onSearch( $(this).closest(\'form\') );'
            ),
            'options' => array (
                'label' => _('Country'),
            ),
        ));

        $this->add(array (
        		'type' => 'Events\Form\Element\EventCategories',
        		'name' => 'category_id',
        		'attributes' => array (
        				'class' => 'form-control',
                        'onchange'   => 'onSearch( $(this).closest(\'form\') );'
        		),
        		'options' => array (
        				'label' => _('Category')
        		)
        ));


    }
}