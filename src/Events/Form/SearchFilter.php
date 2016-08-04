<?php
namespace Events\Form;
use Zend\InputFilter\InputFilter;

class SearchFilter extends InputFilter
{

    public function __construct ()
    {
    	$this->add(array (
    			'name' => 'start',
    			'required' => false
    	));
    	$this->add(array (
    			'name' => 'category_id',
    			'required' => false
    	));

		$this->add(array (
			'name' => 'country_id',
			'required' => false
		));

		$this->add(array (
			'name' => 'content',
			'required' => false
		));

    }
}