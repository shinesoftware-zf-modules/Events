<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Base\Service\SettingsServiceInterface;

/**
 * Common class to search records in all the services
 * You have to create your own module and inject the service of your module
 * into the /Event/Controller/SearchController object
 * Then you have to create into your service the "search" method like this: https://gist.github.com/shinesoftware/d7a758395e93c087d2bf
 * 
 * @author shinesoftware
 *
 */
class SearchController extends AbstractActionController
{
	protected $allevents;
	protected $settings;
	protected $translator;
    protected $km;
	
	/**
	 * preDispatch event of the page
	 *
	 * (non-PHPdoc)
	 * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
	 */
	public function onDispatch(\Zend\Mvc\MvcEvent $e){
		$this->translator = $e->getApplication()->getServiceManager()->get('translator');
	
		return parent::onDispatch( $e );
	}
	
	public function __construct(\Events\Service\EventService $events, SettingsServiceInterface $settings)
	{
		$this->allevents = $events;
		$this->settings = $settings;
        $this->km = 50;
	}
    
    /**
     * Search the page by the name
     */
    public function searchAction ()
    {
        $request = $this->getRequest();
        $ItemCountPerEvent = $this->settings->getValueByParameter('Events', 'usereventsperpage');
        $this->km = $this->settings->getValueByParameter('Events', 'km_radius');

        $paginator = array();

        $query = $this->params()->fromPost('query');
        $query = json_decode($query, true);

        if ($request->isXmlHttpRequest()) {

            $this->getServiceLocator()->get('ViewHelperManager')->get('HeadTitle')->set($this->translator->translate('List of all events'));
            $records = $this->allevents->Search($query, $this->translator->getLocale());

            if ($records->count()) {
                $data = array();

                foreach ($records as $record) {
                    #\Zend\Debug\debug::dump($record);
                    $data[] = $record;
                }
                $paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\ArrayAdapter($data));
                $paginator->setItemCountPerPage($ItemCountPerEvent);
                $paginator->setCurrentPageNumber(1);
            }

            $viewModel = new ViewModel(array('events' => $paginator, 'km' => $this->km));

            $viewModel->setTemplate('events/partial/listnew');
            $viewModel->setTerminal($request->isXmlHttpRequest());
            return $viewModel;
        }

        die();
    }
}
