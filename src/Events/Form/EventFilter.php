<?php
namespace Events\Form;
use Zend\InputFilter\InputFilter;

class EventFilter extends InputFilter
{

    public function __construct ()
    {
    	$this->add(array (
    			'name' => 'title',
    			'required' => true
    	));
    	$this->add(array (
    			'name' => 'content',
    			'required' => true
    	));
    	$this->add(array (
    			'name' => 'start',
    			'required' => true
    	));
		$this->add(array(
				'name' => 'end',
				'required' => true
		));

    	$this->add(array (
    			'name' => 'parent_id',
    			'required' => false
    	));
    	$this->add(array (
    			'name' => 'category_id',
    			'required' => false
    	));

		$this->add(array (
    			'name' => 'visible',
    			'required' => false
    	));
		$this->add(array (
    			'name' => 'showonlist',
    			'required' => false
    	));
		$this->add(array (
			'name' => 'language_id',
			'required' => false
		));
		$this->add(array (
			'name' => 'cssclass',
			'required' => false
		));

		$this->add(array (
			'name' => 'country_id',
			'required' => true
		));

		$this->add(array (
			'name' => 'contact',
			'required' => false
		));

		$this->add(
    	        array(
    	                'type' => 'Zend\InputFilter\FileInput',
    	                'name' => 'file',
    	                'required' => false,
    	                'validators' => array(
    	                        array(
    	                                'name' => 'File\UploadFile',
    	                                'filesize' => array('max' => 204800),
    	                                'filemimetype' => array('mimeType' => 'application/pdf'),
    	                        ),
    	                ),
    	                'filters' => array(
    	                        array(
    	                                'name' => 'File\RenameUpload',
    	                                'options' => array(
    	                                        'target' => PUBLIC_PATH . '/documents/events/',
    	                                        'overwrite' => true,
    	                                        'use_upload_name' => true,
    	                                ),
    	                        ),
    	                ),
    	        )
    	);
    }
}