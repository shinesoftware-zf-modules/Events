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
use ZendTest\XmlRpc\Server\Exception;

class EventsController extends AbstractActionController
{
	protected $eventService;
	protected $eventSettings;
	protected $form;
	protected $datagrid;
	protected $filter;
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
	public function __construct(EventServiceInterface $eventService,
	                            \ZfcDatagrid\Datagrid $datagrid,
	                            \Events\Form\EventForm $form, 
								\Events\Form\EventFilter $formfilter,
	                            SettingsServiceInterface $settings)
	{
	    $this->eventService = $eventService;
	    $this->datagrid = $datagrid;
	    $this->form = $form;
	    $this->filter = $formfilter;
	    $this->eventSettings = $settings;
	}
	
	public function addAction(){
	    
    	$form = $this->form;
    
    	$viewModel = new ViewModel(array (
    			'form' => $form,
    	));
    
    	$viewModel->setTemplate('events/myevents/form');
    	return $viewModel;
	}
	
	/**
	 * List of all records
	 */
	public function listAction ()
	{
	    // prepare the datagrid
	    $this->datagrid->render();

		// get the datagrid ready to be shown in the template view
	    $response = $this->datagrid->getResponse();
	
	    if ($this->datagrid->isHtmlInitReponse()) {
	        $view = new ViewModel();
	        $view->setTemplate('events/myevents/list');
	        $view->addChild($response, 'grid');
	        return $view;
	    } else {
	        return $response;
	    }
	}
	
	
	/**
	 * Edit the main event information
	 */
	public function editAction ()
	{
	    $id = $this->params()->fromRoute('id');
	     
	    $form = $this->form;
	
	    // Get the record by its id
	    $rsevent = $this->eventService->find($id);

		if(empty($rsevent)){
	        $this->flashMessenger()->setNamespace('danger')->addMessage('The record has been not found!');
	        return $this->redirect()->toRoute('events');
	    }

		// Bind the data in the form
	    if (! empty($rsevent)) {
	        $form->bind($rsevent);
	    }
	     
	    $viewModel = new ViewModel(array (
	            'form' => $form,
	            'event' => $rsevent,
	    ));
	
	    $viewModel->setTemplate('events/myevents/form');
	    return $viewModel;
	}
	
	/**
	 * Prepare the data and then save them
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function saveAction ()
	{
	    
	    $urlRewrite = new UrlRewrites();
	    $request = $this->getRequest();
	     
	    // create the events upload directories
	    @mkdir(PUBLIC_PATH . '/documents/');
	    @mkdir(PUBLIC_PATH . '/documents/events');
	
	     
	    if (! $this->request->isPost()) {
	        return $this->redirect()->toRoute('events');
	    }
	
	    $post = $this->request->getPost();
	    $inputFilter = $this->filter;
	    $dateStrategy = new DateTimeStrategy();
	    
	    $form = $this->form;
	     
	    $post = array_merge_recursive(
	            $request->getPost()->toArray(),
	            $request->getFiles()->toArray()
	    );
	     
	    $strslug = $urlRewrite->format($post['title']);
	    
	    // customize the path
	    if(!empty($strslug)){
	        @mkdir(PUBLIC_PATH . '/documents/events/' . $strslug);
	        $path = PUBLIC_PATH . '/documents/events/' . $strslug . '/';
	        $fileFilter = $inputFilter->get('file')->getFilterChain()->getFilters()->top()->setTarget($path);
	    }

	    $form->setData($post);

	    $form->setInputFilter($inputFilter);
	     
	    if (!$form->isValid()) {
	        $viewModel = new ViewModel(array ('error' => true, 'form' => $form));
	        $viewModel->setTemplate('events/myevents/form');
	        return $viewModel;
	    }
	
	    // Get the posted vars
	    $data = $form->getData();
	    $slug = $data->getSlug();

		$strslug = !empty($slug) ? $slug : $urlRewrite->format($data->getTitle());
	    
	    $slugExists = $this->eventService->slugExists($strslug);
	    if(1 < $slugExists->count()){
	        $strslug .= "-" . rand(0, 1000);
	    }
	    
	    $data->setSlug($strslug);

		if($post['parent_id'] == 0){
			$data->setParentId(null);
		}

	    $data->setVisible(true);
	    $data->setShowonlist(true);

	    // set the input filter
	    $form->setInputFilter($inputFilter);
	     
	    $filename = $data->getFile();
	    if(!empty($filename['name'])) {
	        $data->setFile('/documents/events/' . $strslug . "/" . $filename['name']);
	    }else{
	        if(!empty($post['id'])){
    	        $attachment = $this->eventService->getAttachment($post['id']);
    	        if($attachment){
    	            $data->setFile($attachment->getFile());
    	        }else{
    	            $data->setFile(null);
    	        }
	        }else{
	            $data->setFile(null);
	        }
	    }
	     
	    $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
	    $data->setUserId($userId);

		// Save the data in the database
	    $record = $this->eventService->save($data);
	     
	    $this->flashMessenger()->setNamespace('success')->addMessage($this->translator->translate('The information have been saved.'));
	
	    return $this->redirect()->toRoute(null, array (
	            'controller' => 'events',
				'action' => 'Edit',
	            'id' => $record->getId()
	    ));
	}

	/**
	 *  Clone the event
	 */
	public function cloneAction(){
		$sku = $this->params()->fromRoute('sku');
		try{
			$records = $this->eventService->findbySku($sku);
			if($records->count()){
				$record = $records->current();
				$record->setId(null); // delete the id reference
				$record->setSku(null); // delete the sku reference
				$record->setTitle(sprintf($this->translator->translate('Copy of %s'), $record->getTitle())); // delete the sku reference
				$record->setStart(new \Datetime($record->getStart()));
				$record->setEnd(new \Datetime($record->getEnd()));
				$record->setVisible(false);

				$newrecord = $this->eventService->save($record);

				$this->flashMessenger()->setNamespace('success')->addMessage($this->translator->translate('The event has been cloned!'));

				$this->redirect()->toRoute('myevents/default', array (
					'controller' => 'events',
						'action' => 'Edit',
					'id' => $newrecord->getId()
				));
			}
		}catch(Exception $e){
			$this->flashMessenger()->setNamespace('danger')->addMessage($this->translator->translate('The event has been NOT cloned!'));
		}

	}
	
	/**
	 * Delete the records
	 *
	 * @return \Zend\Http\Response
	 */
	public function deleteAction ()
	{
	    $id = $this->params()->fromRoute('id');
	
	    if (is_numeric($id)) {
	        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
	        
	        $event = $this->eventService->find($id);
	        if($userId == $event->getUserId()){
    	        // Delete the record informaiton
    	        $this->eventService->delete($id);
    	
    	        // Go back showing a message
    	        $this->flashMessenger()->setNamespace('success')->addMessage($this->translator->translate('The record has been deleted!'));
    	        return $this->redirect()->toRoute('myevents');
	        }
	    }
	
	    $this->flashMessenger()->setNamespace('danger')->addMessage('The record has been not deleted!');
	    return $this->redirect()->toRoute('myevents');
	}
	
	
	/**
	 * Delete the records
	 *
	 * @return \Zend\Http\Response
	 */
	public function cleanupAction ()
	{
        $userId = $this->zfcUserAuthentication()->getIdentity()->getId();
        
//         $events = $this->eventService->findAllbyUserId($userId);
        
//         foreach ($events as $event){
            // TODO: delete the files and photo for each event
//         }
        
        // Delete the record informaiton
        $this->eventService->deleteByUserId($userId);

        // Go back showing a message
        $this->flashMessenger()->setNamespace('success')->addMessage($this->translator->translate('The record has been deleted!'));
        return $this->redirect()->toRoute('myevents');
	
	    $this->flashMessenger()->setNamespace('danger')->addMessage('The record has been not deleted!');
	    return $this->redirect()->toRoute('myevents');
	}
	
	/**
	 * Delete the file
	 *
	 * @return \Zend\View\Model\ViewModel
	 */
	public function delfileAction ()
	{
	    $id = $this->params()->fromRoute('id');
	
	    // Get the record by its id
	    $event = $this->eventService->find($id);
	
	    $file = $event->getFile();
	    if(file_exists(PUBLIC_PATH . $file)){
	        unlink(PUBLIC_PATH . $file);
	        $this->flashMessenger()->setNamespace('success')->addMessage('The file has been deleted!');
	    }else{
	        $this->flashMessenger()->setNamespace('danger')->addMessage('The file has been not found!');
	    }
	
	    $theProfile = $this->eventService->find($event->getId());
	    $event->setFile(null);
	    $this->eventService->save($event);
	
	    return $this->redirect()->toRoute(null, array (
	            'controller' => 'events',
				'action' => 'Edit',
	            'id' => $event->getId()
	    ));
	}
}

