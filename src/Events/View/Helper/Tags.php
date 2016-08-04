<?php 
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;

class Tags extends AbstractHelper
{
    public function __invoke($value)
    {
    	if(!empty($value)){
	    	$tags = explode(",", $value);
	    	
	    	return $this->view->render('events/partial/tags', array('tags' => $tags));
    	}
    }
}