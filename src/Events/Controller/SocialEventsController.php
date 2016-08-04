<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Events\Entity\Event;
use Base\Model\UrlRewrites as UrlRewrites;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Events\Service\AddressServiceInterface;
use Events\Service\ContactServiceInterface;
use Events\Service\EventServiceInterface;
use Base\Service\SettingsServiceInterface;
use Base\Hydrator\Strategy\DateTimeStrategy;

class SocialEventsController extends AbstractActionController
{
	protected $datagrid;
	protected $socialevents;
	protected $translator;
	
	/**
	 * preDispatch of the page
	 *
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
	 */
	public function onDispatch(\Zend\Mvc\MvcEvent $e){
	    $this->translator = $e->getApplication()->getServiceManager()->get('translator');
	    return parent::onDispatch( $e );
	}
	
	/**
	 * this is the constructor 
	 * 
	 * @param EventServiceInterface $eventService
	 * @param \Events\Form\EventForm $form
	 * @param \Events\Form\EventFilter $formfilter
	 * @param SettingsServiceInterface $settings
	 */
	public function __construct(\ZfcDatagrid\Datagrid $datagrid, \Events\Service\SocialEventsServiceInterface $socialevents, SettingsServiceInterface $settings)
	{
	    $this->datagrid = $datagrid;
	    $this->eventSettings = $settings;
	    $this->socialevents = $socialevents;
	}
	
	/**
	 * Delete the records
	 *
	 * @return \Zend\Http\Response
	 */
	public function deleteAction ()
	{
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
         
        $event = $this->socialevents->deleteByUserId($userId);

        // Go back showing a message
        $this->flashMessenger()->setNamespace('success')->addMessage($this->translator->translate('The pending events have been deleted!'));
        return $this->redirect()->toRoute('myevents/social');
	
	}
	
	/**
	 * List of all records
	 */
	public function indexAction ()
	{
	    // prepare the datagrid
	    $this->datagrid->render();
	
	    // get the datagrid ready to be shown in the template view
	    $response = $this->datagrid->getResponse();
	
	    if ($this->datagrid->isHtmlInitReponse()) {
	        $view = new ViewModel();
	        $view->setTemplate('events/myevents/socialeventlist');
	        $view->addChild($response, 'grid');
	        return $view;
	    } else {
	        return $response;
	    }
	}
	
}

