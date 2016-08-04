<?php
namespace Events\Form;
use Zend\InputFilter\InputFilter;

class EventCategoryFilter extends InputFilter
{

    public function __construct ()
    {
    	$this->add(array (
    			'name' => 'category',
    			'required' => true
    	));
    }
}