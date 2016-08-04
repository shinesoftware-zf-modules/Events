<?php 
namespace Events\View\Helper;
use Zend\View\Helper\AbstractHelper;

class Richsnippets extends AbstractHelper
{
    public function __invoke($events)
    {
        $data = array();
        $j = 0;
		$url = "http://" . $_SERVER['HTTP_HOST'];

		foreach ($events as $event) {

			$fulladdress = $event->getAddress() . " " . $event->getCity() . " " . $event->country;

			$datetime2 = new \Datetime($event->getStart());

			if(empty($event->getRecurrence())){
				$strStartData = $this->view->datetime($event->getStart(), "Y-MM-dd kk:mm");
			}else{
				$strStartData = $this->view->whenoccurrence($datetime2, $event->getRecurrence(), 1); // if the event has a recurrence we will get the next event
			}

			$data[$j]['@context'] = "http://schema.org";
			$data[$j]['@type'] = "DanceEvent";
			$data[$j]['name'] = $event->getTitle();
			$data[$j]['startDate'] = $strStartData;
			$data[$j]['location']['@type'] = "Place";
			$data[$j]['location']['name'] = !empty($event->getPlace()) ? $event->getPlace() : $event->profile_name;
			$data[$j]['location']['address'] = $fulladdress;

            if(!empty($event->getUrl())) {
                $data[$j]['location']['sameAs'] = $event->getUrl();
            }

			$data[$j]['location']['geo']['@type'] = "GeoCoordinates";
			$data[$j]['location']['geo']['latitude'] = $event->getLatitude();
			$data[$j]['location']['geo']['longitude'] = $event->getLongitude();
			$data[$j]['image'] = !empty($event->getFile()) ? $url . $event->getFile() : null;

			$tickets = $this->view->tickets($event->getContent());

            if(empty($tickets)) {
                $data[$j]['offers'][0]['@type'] = "Offer";
                $data[$j]['offers'][0]['price'] = 0;
                $data[$j]['offers'][0]['url'] = $url . "/events/" . $event->getSlug() . ".html";
            }

			for($i=0; $i < count($tickets); $i++){
				$data[$j]['offers'][$i]['@type'] = "Offer";
				$data[$j]['offers'][$i]['availability'] = "InStock";
				$data[$j]['offers'][$i]['category'] = "primary";
				$data[$j]['offers'][$i]['url'] = $url . "/events/" . $event->getSlug() . ".html";
				$data[$j]['offers'][$i]['name'] = $tickets[$i]['description'];
				$data[$j]['offers'][$i]['price'] = $tickets[$i]['price'];
				$data[$j]['offers'][$i]['priceCurrency'] = $tickets[$i]['currency'];
			}

            $j++;

    	}

        $html = '<script type="application/ld+json">';
        $html .= json_encode($data);
        $html .= '</script>';

        return $html;
    }
}