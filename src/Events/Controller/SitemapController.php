<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Events\Controller;

use Cms\Service\PageServiceInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Events\Service\EventServiceInterface;

class SitemapController extends AbstractActionController
{
    protected $eventService;
    protected $pageService;
    protected $translator;

    /**
     * preDispatch event of the event
     *
     * (non-PHPdoc)
     * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $this->translator = $e->getApplication()->getServiceManager()->get('translator');
        return parent::onDispatch($e);
    }


    public function __construct(EventServiceInterface $eventService, PageServiceInterface $pageService)
    {
        $this->eventService = $eventService;
        $this->pageService = $pageService;
    }

    /**
     * Sitemap creation for all the active events
     *
     * @return \Zend\Http\Response
     */
    public function indexAction()
    {
        header("Content-Type:text/xml");

        $url = "http://$_SERVER[HTTP_HOST]/";
        @mkdir(PUBLIC_PATH . "/sitemaps");

        $sitemap = new \Base\Model\Sitemap($url);
        $sitemap->setPath(PUBLIC_PATH . "/sitemaps/");
        $sitemap->setFilename('main');

        $events = $this->eventService->getActiveEvents();
        foreach ($events as $event) {
            $sitemap->addItem('events/' . $event->getSlug() . ".html", '1.0', 'daily', $event->getCreatedAt());
        }

        $pages = $this->pageService->getActivePages();

        foreach ($pages as $page) {
            $sitemap->addItem('cms/' . $page->getSlug() . ".html", '1.0', 'weekly', $page->getCreatedAt(), array('en', 'it'));
        }

        $sitemap->createSitemapIndex($url . "sitemaps/", 'Today');

        $filename = PUBLIC_PATH . "/sitemaps/main-index.xml";

        if (file_exists($filename)) {
            $handle = fopen($filename, "r");
            echo fread($handle, filesize($filename));
            fclose($handle);
        }

        die();
    }
}

?>