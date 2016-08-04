<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonevents for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
        'asset_manager' => array(
                'resolver_configs' => array(
                        'collections' => array(
                                'js/application.js' => array(
                                        'js/js.cookie.js',
                                        'js/jquery.datetimepicker.js',
                                        'js/fileinput.min.js',
                                        'js/events.js',
                                ),
                                'css/application.css' => array(
                                        'css/jquery.datetimepicker.css',
                                        'css/fileinput.min.css',
                                        'css/event-list.css',
                                        'css/events.css',
                                ),
                        ),
                        'paths' => array(
                                __DIR__ . '/../public',
                        ),
                ),
        ),
		'bjyauthorize' => array(
				'guards' => array(
					'BjyAuthorize\Guard\Route' => array(
							
		                // Generic route guards
		                array('route' => 'events', 'roles' => array('guest')),
		                array('route' => 'events/default', 'roles' => array('guest')),
		                array('route' => 'events/export', 'roles' => array('guest')),
		                array('route' => 'events/batch', 'roles' => array('guest')),
		                array('route' => 'events/search', 'roles' => array('guest')),
		                array('route' => 'myevents', 'roles' => array('user')),
		                array('route' => 'myevents/default', 'roles' => array('user')),
		                array('route' => 'myevents/clone', 'roles' => array('user')),
		                array('route' => 'myevents/social', 'roles' => array('user')),
		                array('route' => 'feeds', 'roles' => array('guest')),
		                array('route' => 'sitemap', 'roles' => array('guest')),
		                
		                array('route' => 'events/event', 'roles' => array('guest')),
		                array('route' => 'events/paginator', 'roles' => array('guest')),
		                array('route' => 'events/search', 'roles' => array('guest')),
		                array('route' => 'events/atom', 'roles' => array('guest')),
							
						// Custom Module
						array('route' => 'zfcadmin/events', 'roles' => array('admin')),
						array('route' => 'zfcadmin/events/default', 'roles' => array('admin')),
						array('route' => 'zfcadmin/events/settings', 'roles' => array('admin')),

						array('route' => 'zfcadmin/eventscategory', 'roles' => array('admin')),
						array('route' => 'zfcadmin/eventscategory/default', 'roles' => array('admin')),
						
					),
			  ),
		),
		'navigation' => array(
				'default' => array(
						'events' => array(
								'label' => _('Events'),
								'route' => 'events',
						),
				),
				
				'admin' => array(
        				'settings' => array(
                				'label' => _('Settings'),
                				'route' => 'zfcadmin',
        				        'pages' => array (
    				                    array (
    				                        'label' => 'Events',
    				                        'route' => 'zfcadmin/events/settings',
    				                        'icon' => 'fa fa-list'
        				                ),
        				        ),
        				),				
						'events' => array(
								'label' => _('Events'),
								'resource' => 'menu',
								'route' => 'zfcadmin/events',
								'privilege' => 'list',
								'icon' => 'fa fa-list',
								'pages' => array (
										array (
												'label' => 'Events',
												'route' => 'zfcadmin/events',
												'icon' => 'fa fa-list'
										),
										array (
												'label' => 'Event Categories',
												'route' => 'zfcadmin/eventscategory',
												'icon' => 'fa fa-list'
										),
								),
						),
				),
		),
    'router' => array(
        'routes' => array(
        		'zfcadmin' => array(
        				'child_routes' => array(
        						'events' => array(
        								'type' => 'literal',
        								'options' => array(
        										'route' => '/events',
        										'defaults' => array(
        												'controller' => 'EventsAdmin\Controller\Event',
        												'action'     => 'index',
        										),
        								),
        								'may_terminate' => true,
        								'child_routes' => array (
        										'default' => array (
        												'type' => 'Segment',
        												'options' => array (
        														'route' => '/[:action[/:id]]',
        														'constraints' => array (
        																'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        																'id' => '[0-9]*'
        														),
        														'defaults' => array ()
        												)
        										),
        										'settings' => array (
        												'type' => 'Segment',
        												'options' => array (
        														'route' => '/settings/[:action[/:id]]',
        														'constraints' => array (
        																'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        																'id' => '[0-9]*'
        														),
        														'defaults' => array (
            														'controller' => 'EventsSettings\Controller\Event',
            														'action'     => 'index',
        														)
        												)
        										)
        								),
        						),
        						
        						'eventscategory' => array(
        								'type' => 'literal',
        								'options' => array(
        										'route' => '/eventscategory',
        										'defaults' => array(
        												'controller' => 'EventsAdmin\Controller\EventCategory',
        												'action'     => 'index',
        										),
        								),
        								'may_terminate' => true,
        								'child_routes' => array (
        										'default' => array (
        												'type' => 'Segment',
        												'options' => array (
        														'route' => '/[:action[/:id]]',
        														'constraints' => array (
        																'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        																'id' => '[0-9]*'
        														),
        														'defaults' => array ()
        												)
        										)
        								),
        						),
        						'eventsblocks' => array(
        								'type' => 'literal',
        								'options' => array(
        										'route' => '/eventsblocks',
        										'defaults' => array(
        												'controller' => 'EventsAdmin\Controller\Block',
        												'action'     => 'index',
        										),
        								),
        								'may_terminate' => true,
        								'child_routes' => array (
        										'default' => array (
        												'type' => 'Segment',
        												'options' => array (
        														'route' => '/[:action[/:id]]',
        														'constraints' => array (
        																'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
        																'id' => '[0-9]*'
        														),
        														'defaults' => array ()
        												)
        										)
        								),
        						),
        				),
        		),
//             'home' => array(
//                 'type'    => 'Literal',
//                 'options' => array(
//                     'route'    => '/',
//                     'defaults' => array(
//                         '__NAMESPACE__' => 'Events\Controller',
//                         'controller'    => 'Index',
//                         'action'        => 'index',
//                         'page'			=> 1
//                     ),
//                 ),
//              ),
            'events' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/events',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Events\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                        'page'			=> 1
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                    
                    'batch' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                    'route'    => '/batch[/:action]',
                                    'constraints' => array(
                                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                            'controller'    => 'Batch',
                                    ),
                            ),
                    ),
                    'export' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                    'route'    => '/export[[/:type]/:id]',
                                    'constraints' => array(
                                            'type'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            'id'     => '[0-9]*',
                                    ),
                                    'defaults' => array(
                                        'action'        => 'export',
                                    ),
                            ),
                    ),
                    'search' => array(
                            'type'    => 'Segment',
                            'options' => array(
                                    'route'    => '/search[/:query]',
                                    'constraints' => array(
                                            'query'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                    ),
                                    'defaults' => array(
                                            'controller'    => 'Search',
                                            'action'    => 'search',
                                    ),
                            ),
                    ),
                    'event' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '[/:slug].html',
                            'constraints' => array(
                            	'slug'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            		'action'        => 'event',
                            ),
                        ),
                    ),

                    'paginator' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/list/[page/:page]',
                            'constraints' => array(
                            	'event'     => '[0-9]*',
                            ),
                            'defaults' => array(
                            		'page'        => 1,
                            ),
                        ),
                    ),
                ),
            ),
            
            'sitemap' => array(
                    'type'    => 'Literal',
                    'options' => array(
                            'route'    => '/sitemap',
                            'defaults' => array(
                                    '__NAMESPACE__' => 'Events\Controller',
                                    'controller'    => 'Sitemap',
                                    'action'        => 'index',
                            ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                            'default' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                            'route'    => '/[:controller[/:action]]',
                                            'constraints' => array(
                                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            ),
                                            'defaults' => array(
                                            ),
                                    ),
                            ),
                    ),
              ),
            
            'feeds' => array(
                    'type'    => 'Literal',
                    'options' => array(
                            'route'    => '/rss.xml',
                            'defaults' => array(
                                    '__NAMESPACE__' => 'Events\Controller',
                                    'controller'    => 'Feeds',
                                    'action'        => 'feeds',
                            ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                            'default' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                            'route'    => '/[:controller[/:action]]',
                                            'constraints' => array(
                                                    'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                    'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                            ),
                                            'defaults' => array(
                                            ),
                                    ),
                            ),
                    ),
              ),
            'myevents' => array(
                    'type'    => 'Literal',
                    'options' => array(
                            'route'    => '/myevents',
                            'defaults' => array(
                                    '__NAMESPACE__' => 'Events\Controller',
                                    'controller'    => 'Events',
                                    'action'        => 'list',
                            ),
                    ),
                    'may_terminate' => true,
                    'child_routes' => array(
                            'default' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                        'route' => '/[:action[/:id]]',
                                        'controller'    => 'Events',
                                        'constraints' => array (
                                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                                'id' => '[0-9]*'
                                        ),

                                    ),
                            ),
                            'clone' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                            'route' => '/clone[/:sku]',
                                            'constraints' => array(
                                            ),
                                            'defaults' => array(
                                                'action'        => 'clone',
                                            ),
                                    ),
                            ),
                            'social' => array(
                                    'type'    => 'Segment',
                                    'options' => array(
                                            'route' => '/social[/:action[/:id]]',
                                            'constraints' => array(
                                                    'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
													'id' => '[0-9]*'
                                            ),
                                            'defaults' => array(
                                                'controller'    => 'SocialEvents',
                                                'action'        => 'index',
                                            ),
                                    ),
                            ),
                    ),
              ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),

		'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(
        		'Events\Controller\Index' => 'Events\Factory\IndexControllerFactory',
        		'Events\Controller\Batch' => 'Events\Factory\BatchControllerFactory',
        		'Events\Controller\Events' => 'Events\Factory\EventsControllerFactory',
        		'Events\Controller\SocialEvents' => 'Events\Factory\SocialEventsControllerFactory',
        		'Events\Controller\Feeds' => 'Events\Factory\FeedsControllerFactory',
        		'Events\Controller\Sitemap' => 'Events\Factory\SitemapControllerFactory',
                'Events\Controller\Search' => 'Events\Factory\SearchControllerFactory',
        		'EventsAdmin\Controller\Event' => 'EventsAdmin\Factory\EventControllerFactory',
        		'EventsAdmin\Controller\EventCategory' => 'EventsAdmin\Factory\EventCategoryControllerFactory',
        		'EventsSettings\Controller\Event' => 'EventsSettings\Factory\EventControllerFactory',

        )
    ),
    'view_helpers' => array (
    		'invokables' => array (
                'geoinfo' => 'Events\View\Helper\Geoinfo',
		    		'geolocalization' => 'Events\View\Helper\Geolocalization',
		    		'distance' => 'Events\View\Helper\Distance',
		    		'extract' => 'Events\View\Helper\Extract',
    				'events' => 'Events\View\Helper\Events',
    				'tags' => 'Events\View\Helper\Tags',
    				'createMap' => 'Events\View\Helper\MapHelper',
    				'whenoccurrence' => 'Events\View\Helper\WhenOccurrence',
    				'search' => 'Events\View\Helper\Search',
    				'icsexporter' => 'Events\View\Helper\IcsExporter',
    				'eventsrelated' => 'Events\View\Helper\EventsRelated',
    				'richsnippets' => 'Events\View\Helper\Richsnippets',
    		)
    ),
    'view_manager' => array(
        'template_map' => array(
            'zfc-user/user/index' => __DIR__ . '/../view/zfc-user/user/index.phtml',
            'zfc-user/user/register' => __DIR__ . '/../view/zfc-user/user/register.phtml',
            'zfc-user/user/login' => __DIR__ . '/../view/zfc-user/user/login.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
