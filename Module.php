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
* @subpackage Entity
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/


namespace Events;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\ResultSet\ResultSet;
use Events\Service\EventService;
use Events\Entity\Event;
use Events\Entity\Contact;
use Events\Entity\ContactType;
use Events\Entity\Address;
use Events\Entity\Block;
use Events\Entity\EventCategory;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;

class Module implements DependencyIndicatorInterface{
	
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    
    /**
     * Check the dependency of the module
     * (non-PHPdoc)
     * @see Zend\ModuleManager\Feature.DependencyIndicatorInterface::getModuleDependencies()
     */
    public function getModuleDependencies()
    {
    	return array('Base', 'Profile');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    
    /**
     * Set the Services Manager items
     */
    public function getServiceConfig ()
    { 
    	return array(
    			'factories' => array(
    			        
    					'EventService' => function  ($sm)
    					{
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$translator = $sm->get('translator');
    						$settings = $sm->get('SettingsService');
    						$country = $sm->get('CountryService');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Event());
    						$tableGateway = new TableGateway('events', $dbAdapter, null, $resultSetPrototype);
    						$service = new \Events\Service\EventService($dbAdapter, $tableGateway, $country, $translator, $settings);
    						return $service;
    					},
    					'EventCategoryService' => function  ($sm)
    					{
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new EventCategory());
    						$tableGateway = new TableGateway('events_category', $dbAdapter, null, $resultSetPrototype);
    						$service = new \Events\Service\EventCategoryService($tableGateway);
    						return $service;
    					},
    					
    					'SocialEvents' => function  ($sm)
    					{
    					    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    					    $translator = $sm->get('translator');
    					    $resultSetPrototype = new ResultSet();
    					    $resultSetPrototype->setArrayObjectPrototype(new \Events\Entity\SocialEvents());
    					    $tableGateway = new TableGateway('events_socialnetwork', $dbAdapter, null, $resultSetPrototype);
    					    $service = new \Events\Service\SocialEventsService($tableGateway, $translator);
    					    return $service;
    					},
    					
    					'EventForm' => function  ($sm)
    					{
    						$form = new \Events\Form\EventForm();
    						$form->setInputFilter($sm->get('EventFilter'));
    						return $form;
    					},
    					'EventFilter' => function  ($sm)
    					{
    						return new \Events\Form\EventFilter();
    					},

    					'SearchForm' => function  ($sm)
    					{
    						$form = new \Events\Form\SearchForm();
    						$form->setInputFilter($sm->get('SearchFilter'));
    						return $form;
    					},
    					'SearchFilter' => function  ($sm)
    					{
    						return new \Events\Form\SearchFilter();
    					},

    					'EventSettingsForm' => function  ($sm) 
    					{
    						$form = new \EventsSettings\Form\EventForm();
    						$form->setInputFilter($sm->get('EventSettingsFilter'));
    						return $form;
    					},
    					'EventSettingsFilter' => function  ($sm)
    					{
    						return new \EventsSettings\Form\EventFilter();
    					},
    					
    					'EventCategoryForm' => function  ($sm)
    					{
    						$form = new \Events\Form\EventCategoryForm();
    						$form->setInputFilter($sm->get('EventCategoryFilter'));
    						return $form;
    					},
    					'EventCategoryFilter' => function  ($sm)
    					{
    						return new \Events\Form\EventCategoryFilter();
    					}, 
    					
    				),
    			);
    }
    
    
    /**
     * Get the form elements
     */
    public function getFormElementConfig ()
    {
    	return array (
    			'factories' => array (
						'Events\Form\Element\Country' => function ($sm) {
							$serviceLocator = $sm->getServiceLocator();
							$translator = $sm->getServiceLocator()->get('translator');
							$service = $serviceLocator->get('CountryService');
							$eventService = $serviceLocator->get('EventService');
							$element = new \Events\Form\Element\Country($service, $eventService, $translator);
							return $element;
						},
    					'Events\Form\Element\EventCategories' => function  ($sm)
		    					{
		    						$serviceLocator = $sm->getServiceLocator();
		    						$translator = $sm->getServiceLocator()->get('translator');
		    						$service = $serviceLocator->get('EventCategoryService');
									$eventService = $serviceLocator->get('EventService');
									$element = new \Events\Form\Element\EventCategories($service, $eventService, $translator);
		    						return $element;
		    					},
    					'Events\Form\Element\ParentEvents' => function  ($sm)
		    					{
		    						$serviceLocator = $sm->getServiceLocator();
		    						$translator = $sm->getServiceLocator()->get('translator');
		    						$service = $serviceLocator->get('EventService');
		    						$element = new \Events\Form\Element\ParentEvents($service, $translator);
		    						return $element;
		    					},
    						),
    					);
    }
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                    __NAMESPACE__ . "Admin" => __DIR__ . '/src/' . __NAMESPACE__ . "Admin",
                    __NAMESPACE__ . "Settings" => __DIR__ . '/src/' . __NAMESPACE__ . "Settings",
                ),
            ),
        );
    }
}
