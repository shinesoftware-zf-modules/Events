<?php

/**
 * Get the next occurrence from today using the date sent to the function
 * https://github.com/tplaner/When
 * @author mturillo
 *
 */

namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;

class WhenOccurrence  extends AbstractHelper {
    
    /**
     * Get the next occurrence from today using the date sent to the function
     * 
     * @param Datetime $date
     * @param Rrule/String $recurrence
     * @param integer $nextevents
     * @param boolean $rawdata
     */
	public function __invoke($date, $recurrence, $nextevents=5, $rawdata=false) {
	    
	    $items = 0;
	    $today = new \DateTime("now");
	    $data = array();
        $recurrence = str_replace("RRULE:", "", $recurrence);


        if(!empty($recurrence)){
    	    $r = new \When\When();

            $r->startDate($date)
                ->rrule($recurrence)
                ->generateOccurrences();

            foreach($r->occurrences as $date){

                $interval = date_diff($date, $today);

                $diff = (int)$interval->format('%R%a');

                if($items == $nextevents){
    	            break;
    	        }
               
    	        if($diff >= -30 && $diff <= 0){
    	            $data[] = $date;
    	            $items++;
    	        }
    	    }
    	    
    	    if($rawdata){
    	         return $data;
    	    }
    	    	
	        return $this->view->render('events/partial/when', array('data' => $data));
        }
	}
}