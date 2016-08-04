<?php
/**
* Copyright (c) 2014 Shine Software.
* All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions
* are met:
*
* * Redistributions of source code must retain the above copyright
* notice, this list of conditions and the following disclaimer.
*
* * Redistributions in binary form must reproduce the above copyright
* notice, this list of conditions and the following disclaimer in
* the documentation and/or other materials provided with the
* distribution.
*
* * Neither the names of the copyright holders nor the names of the
* contributors may be used to endorse or promote products derived
* from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
* "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
* LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
* FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
* COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
* INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
* CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
* LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
* ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*
* @package Events
* @subpackage Service
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace Events\Service;

use Zend\EventManager\EventManager;

use Events\Entity\Event;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use GoogleMaps;
use Base\Model\UrlRewrites as UrlRewrites;

class EventService implements EventServiceInterface, EventManagerAwareInterface
{
	protected $dbAdapter;
	protected $tableGateway;
	protected $translator;
	protected $eventManager;
	protected $country;
	protected $settings;

	public function __construct(\Zend\Db\Adapter\Adapter $dbAdapter, TableGateway $tableGateway, \Base\Service\CountryService $country, \Zend\Mvc\I18n\Translator $translator, \Base\Service\SettingsServiceInterface $settings ){
		$this->dbAdapter = $dbAdapter;
		$this->tableGateway = $tableGateway;
		$this->translator = $translator;
		$this->settings = $settings;
		$this->country = $country;
	}
	
    /**
     * @inheritDoc
     */
    public function findAll()
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) {
        	$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
        });
        
        return $records;
    }
	
    /**
     * @inheritDoc
     */
    public function findAllbyUserId($userId)
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($userId) {
        	$select->where(array('user_id' => $userId));
        });
        
        return $records;
    }

	/**
	 * @inheritDoc
	 */
	public function findbySku($sku)
	{
		$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($sku) {
			$select->where(array('sku' => $sku));
		});

		return $records;
	}

	/**
	 * @inheritDoc
	 */
	public function countByCountry($country_id)
	{
		$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($country_id) {
			$select->where(array('country_id' => $country_id));
			$select->where(array('showonlist' => true));
			$select->where(array('events.visible' => true));
			$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 0));
			$select->order(array('events.start'));
		});

		return $records->count();
	}

	/**
	 * @inheritDoc
	 */
	public function countByCategory($category_id)
	{
		$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($category_id) {
			$select->where(array('category_id' => $category_id));
			$select->where(array('showonlist' => true));
			$select->where(array('events.visible' => true));
			$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 0));
			$select->order(array('events.start'));
		});

		return $records->count();
	}

	/**
	 * Create the base of join
	 */
	private function defaultJoin(\Zend\Db\Sql\Select $select){
		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
		return $select;
	}

	/**
	 * Create the base where conditions
	 */
	private function defaultWhere(\Zend\Db\Sql\Select $select){
		$select->where(array('showonlist' => true));
		$select->where(array('events.visible' => true));
		$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 0));
		$select->order(array('events.start'));
		return $select;
	}


    /**
     * @inheritDoc
     */
    public function getActiveEvents()
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) {
			$select->join('base_country', 'country_id = base_country.id', array ('country' => 'name', 'countrycode' => 'code'), 'left');
			$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
        	$select->where(array('showonlist' => true));
        	$select->where(array('events.visible' => true));
        	$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 0));
        	$select->order(array('events.start'));
			#\Zend\Debug\debug::dump($select->getSqlString());
        });
        
        return $records;
    }


    /**
     * @inheritDoc
     */
    public function getEventsbyUser($userId)
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($userId){
    		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
        	$select->where(array('events.user_id' => $userId));
        	$select->order(array('events.start'));
			#\Zend\Debug\debug::dump($select->getSqlString());
        });

        return $records;
    }

	
    /**
     * @inheritDoc
     */
    public function getActiveEventsByUserId($userId)
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($userId){
    		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
        	$select->where(array('showonlist' => true));
        	$select->where(array('events.visible' => true));
        	$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 1));
        	$select->where(array('events.user_id' => $userId));
        	$select->order(array('events.start'));
//         	var_dump($select->getSqlString());
        });
        
        return $records;
    }

    /**
     * @inheritDoc
     */
    public function getEventRelated($eventId)
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($eventId){
    		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
        	$select->where(array('showonlist' => true));
        	$select->where(array('events.visible' => true));
        	$select->where(new \Zend\Db\Sql\Predicate\Expression('TO_DAYS(events.end) - TO_DAYS(NOW()) >= ?', 1));
        	$select->where(array('events.parent_id' => $eventId));
        	$select->order(array('events.start'));
         	#var_dump($select->getSqlString());
        });

        return $records;
    }

    /**
     * @inheritDoc
     */
    public function getAttachment($id)
    {
    	$record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($id) {
        	$select->where('file IS NOT NULL');
        	$select->where(array('id' => $id));
        });
        return $record->current();
    }
	
    /**
     * @inheritDoc
     */
    public function slugExists($slug)
    {
    	$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($slug){
        	$select->where(array('slug' => $slug));
        });
        return $records;
    }
    
    /**
     * @inheritDoc
     */
    public function findByExtid($extid)
    {
        $record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($extid) {
            $select->where(array('extid' => $extid));
        });
    
        return $record->current();
    }
    
    /**
     * @inheritDoc
     */
    public function deleteByUserId($userId)
    {
        $record = $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($userId) {
            $select->where(array('user_id' => $userId));
        });
    
        return $record;
    }

    /**
     * @inheritDoc
     */
    public function deleteByExtId($code)
    {
        $record = $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($code) {
            $select->where(new \Zend\Db\Sql\Predicate\Like('extid', '%' . $code . '%'));
        });

        return $record;
    }
	
    /**
     * @inheritDoc
     */
    public function getById($id)
    {
    	$record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($id){
    		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('profile_name' => 'name'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
        	$select->where(array('showonlist' => true));
        	$select->where(array('events.visible' => true));
        	$select->where(array('events.id' => $id));
        	$select->order(array('events.start'));
        });
        
        return $record->current();
    }

    /**
     * @inheritDoc
     */
    public function find($id)
    {
    	if(!is_numeric($id)){
    		return false;
    	}
    	$rowset = $this->tableGateway->select(array('id' => $id));
    	$row = $rowset->current();
    	return $row;
    }

    /**
     * @inheritDoc
     */
    public function findByUri($slug, $locale="en_US")
    {
    	$record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($slug, $locale){
    		$select->join('base_country', 'country_id = base_country.id', array ('country' => 'name', 'countrycode' => 'code'), 'left');
    		$select->join('events_category', 'category_id = events_category.id', array ('category', 'cssclass'), 'left');
    		$select->join('base_languages', 'language_id = base_languages.id', array ('locale', 'language'), 'left');
    		$select->join('profile', 'events.user_id = profile.user_id', array ('paypal', 'profile_name' => 'name', 'profile_slug' => 'slug', 'facebook', 'instagram', 'googleplus', 'twitter', 'website' => 'url'), 'left');
    		$select->join('events_socialnetwork', 'events.socialnetwork_id = events_socialnetwork.id', array ('socialnetwork'), 'left');
    		$select->where(array('events.slug' => $slug));
    	});

    	if ($record->count()){
    		if($record->current()->locale != $locale){
    			$myRecord = $record->current();
    			$myContent = $myRecord->getContent();
    			$message = sprintf($this->translator->translate('The content has not been found into the selected language. Original %s version is shown.'), $myRecord->language);
    			$message = "\n<div class='text-muted'>$message</div>";
    			$myRecord->setContent($myContent . $message);
    			return $myRecord;
    		}
    	}
    	 
    	return $record->current();
    }

    /**
     * @inheritDoc
     */
    public function Search(array $queries, $locale="en_US")
    {

		$records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($queries, $locale){
			$select = $this->defaultJoin($select);
			$select = $this->defaultWhere($select);

			foreach($queries as $query){
				if(!empty($query['value'])){
					if(is_numeric($query['value'])) {
						$select->where(array("events." . $query['name'] => $query['value']));
					}else{
						$value = htmlspecialchars($query['value']);
						$select->where(new \Zend\Db\Sql\Predicate\Like('events.'. $query['name'], '%'.$value.'%'), 'AND');
					}
				}
			}

			#\Zend\Debug\debug::dump($select->getSqlString());
		});

		#\Zend\Debug\debug::dump($records->current());


		return $records;
    }
    

    /**
     * @inheritDoc
     */
    public function delete($id)
    {
    	$this->tableGateway->delete(array(
    			'id' => $id
    	));
    }

    /**
     * Save the event in the database
     * 
     * @inheritDoc
     */
    public function save(\Events\Entity\Event $record)
    {
    	$hydrator = new ClassMethods(true);
    	$urlRewrite = new UrlRewrites();
		$apiKey = $this->settings->getValueByParameter("Events", "googleapikey");
		$logger = new \Zend\Log\Logger();
		$writer = new \Zend\Log\Writer\Stream(PUBLIC_PATH . "/../data/log/".date('Y-m-d')."-events-batch.log");
		$logger->addWriter($writer);

		$country = $this->country->find($record->getCountryId());
		$countryName = !empty($country) ? $country->getName() : null;

		// Get the latitude and the longitude of the event if there is not already get
		if($record->getAddress() && !$record->getLatitude()){

			#\Zend\Debug\debug::dump("Try to get the latitude and longitude for " . $record->getAddress());

			try{
				$geocoder = new \Geocoder\ProviderAggregator();
				$adapter = new \Ivory\HttpAdapter\CurlHttpAdapter();

				$chain = new \Geocoder\Provider\Chain([
					new \Geocoder\Provider\GoogleMaps($adapter, 'it_IT', 'Italy', true, $apiKey),
					new \Geocoder\Provider\ArcGISOnline($adapter),
					new \Geocoder\Provider\Geonames($adapter, "itango"),
					new \Geocoder\Provider\Nominatim($adapter, "http://nominatim.openstreetmap.org/"),
					// ...
				]);

				$geocoder->registerProvider($chain);

				$request = $geocoder->geocode($record->getAddress() . " " . $countryName);

			}catch (\Exception $e){
				$logger->debug($e->getMessage());

			}

			if(isset($request)){
    	        $record->setLatitude($request->first()->getLatitude());
    	        $record->setLongitude($request->first()->getLongitude());
    	    }
    	}


		// extract the data from the object
    	$data = $hydrator->extract($record);
    	$id = (int) $record->getId();

		// prepare the dates
    	if(!empty($data['start'])){
    	    $data['start'] = $data['start']->format('Y-m-d H:i:s');
    	}
    	
    	if(!empty($data['end'])){
    	    $data['end'] = $data['end']->format('Y-m-d H:i:s');
    	}

    	$this->getEventManager()->trigger(__FUNCTION__ . '.pre', null, array('data' => $data));  // Trigger an event

    	if ($id == 0) {
    		unset($data['id']);

			if (empty($data['sku'])) {
				$data['sku'] = (md5($data['title'] . date('Ymdhis')));
			}

			$validator = new \Zend\Validator\Db\RecordExists(
					array(
							'table' => 'events',
							'field' => 'slug',
							'adapter' => $this->dbAdapter
					)
			);

			// Validation is then performed as usual
			if ($validator->isValid($data['slug'])) {
				$data['slug'] .= "-" . rand(1, 1000);
			}

    		$data['createdat'] = date('Y-m-d H:i:s');
    		$data['updatedat'] = date('Y-m-d H:i:s');

            $this->tableGateway->insert($data); // add the record
    		$id = $this->tableGateway->getLastInsertValue();
    	} else {
			$validator = new \Zend\Validator\Db\RecordExists(
					array(
							'table' => 'events',
							'field' => 'slug',
							'adapter' => $this->dbAdapter,
							'exclude' => array(
									'field' => 'id',
									'value' => $id
							),
					)
			);

			// Validation is then performed as usual
			if ($validator->isValid($data['slug'])) {
				$data['slug'] .= "-" . rand(1, 1000);
			}

			$rs = $this->find($id);
    		if (!empty($rs)) {
    			$data['updatedat'] = date('Y-m-d H:i:s');
    			unset( $data['createdat']);
       			$this->tableGateway->update($data, array (
    					'id' => $id
    			));
    		} else {
    			throw new \Exception('Record ID does not exist');
    		}
    	}
    	
    	$record = $this->find($id);
    	$this->getEventManager()->trigger(__FUNCTION__ . '.post', null, array('id' => $id, 'data' => $data, 'record' => $record));  // Trigger an event
    	return $record;
    }

	/**
	 * Export an Event in a ICS file
	 *
	 * @param $eventId
	 *
	 */
	public function icsExporter($eventId, $url, $locale){
		$strIcs = null;

		$record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($eventId, $locale){
			$select = $this->defaultJoin($select);
			$select = $this->defaultWhere($select);
			$select->where(array('events.id' => $eventId));
		})->current();

		if($record) {
			$uniqId = uniqid();
			$today = new \DateTime();
			$dateStart = new \DateTime($record->getStart());
			$dateEnd = new \DateTime($record->getEnd());
			$uri = "http://" . $url . "/events/" . $record->getSlug() . ".html";
			$recurrence = $record->getRecurrence();
			$summary = $record->getTitle();
			$description = preg_replace("/\r|\n/", "", strip_tags($record->getContent()));
			$address = $record->getAddress() . " " . $record->getCity();

			$strIcs = "BEGIN:VCALENDAR\n";
			$strIcs .= "VERSION:2.0\n";
			$strIcs .= "PRODID:-//Calendar Labs//Calendar 1.0//EN\n";
			$strIcs .= "CALSCALE:GREGORIAN\n";
			$strIcs .= "METHOD:PUBLISH\n";
			$strIcs .= "X-WR-TIMEZONE:Europe/Rome\n";
			$strIcs .= "BEGIN:VEVENT\n";
			$strIcs .= "RRULE:FREQ=$recurrence\n";
			$strIcs .= "DTSTART:" . $dateStart->format('Ymd\THis\Z') . "\n";
			$strIcs .= "DTEND:" . $dateEnd->format('Ymd\THis\Z') . "\n";
			$strIcs .= "UID:$uniqId\n";
			$strIcs .= "DTSTAMP:" . $today->format('Ymd\THis\Z') . "\n";
			$strIcs .= "CREATED:" . $today->format('Ymd\THis\Z') . "\n";
			$strIcs .= "LOCATION:$address\n";
			$strIcs .= "DESCRIPTION:$description\n";
			$strIcs .= "URL;VALUE=URI:$uri\n";
			$strIcs .= "SUMMARY:$summary\n";
			$strIcs .= "STATUS:CONFIRMED\n";
			$strIcs .= "TRANSP:TRANSPARENT\n";
			$strIcs .= "END:VEVENT\n";
			$strIcs .= "END:VCALENDAR\n";
		}
		return $strIcs;
	}
    
    /**
     * Calculates the great-circle distance between two points, with
     * the Haversine formula.
     * @param float $latitudeFrom Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo Latitude of target point in [deg decimal]
     * @param float $longitudeTo Longitude of target point in [deg decimal]
     * @param float $earthRadius Mean earth radius in [m]
     * @return float Distance between points in [m] (same as earthRadius)
     */
    public function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
    
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
    
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
    
	/* (non-PHPdoc)
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
     public function setEventManager (EventManagerInterface $eventManager){
         $eventManager->addIdentifiers(get_called_class());
         $this->eventManager = $eventManager;
     }

	/* (non-PHPdoc)
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
     public function getEventManager (){
       if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
     }

}