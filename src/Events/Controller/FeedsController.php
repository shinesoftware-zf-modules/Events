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
use Events\Service\EventServiceInterface;
use Base\Service\SettingsServiceInterface;

class FeedsController extends AbstractActionController
{
    protected $eventService;
    protected $eventSettings;
    protected $translator;

    /**
     * preDispatch event of the event
     *
     * (non-PHPdoc)
     * @see Zend\Mvc\Controller.AbstractActionController::onDispatch()
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e){
        $this->translator = $e->getApplication()->getServiceManager()->get('translator');

        return parent::onDispatch( $e );
    }

    public function __construct(EventServiceInterface $eventService, SettingsServiceInterface $settings)
    {
        $this->eventService = $eventService;
        $this->eventSettings = $settings;
    }


    /**
     * Show the rss event
     */
    public function feedsAction ()
    {
        $appName = $this->eventSettings->getValueByParameter('Base', 'name');

        $result = new ViewModel();
        $result->setTerminal(true);
        $response = $this->getResponse();

        $url = "http://" . $_SERVER['HTTP_HOST'];
        $feed = new \Zend\Feed\Writer\Feed;
        $feed->setTitle('Latest events');
        $feed->setDescription($appName . " event feed");
        $feed->setLink($url."/");
        $feed->setFeedLink($url . '/feeds', 'atom');

        $feed->addAuthor(array(
            'name'  => 'iTango.it events',
            'uri'   => $url,
        ));

        $feed->setDateModified(time());
        $feed->addHub('http://pubsubhubbub.appspot.com/');

        /**
         * Add one or more entries. Note that entries must
         * be manually added once created.
         */

        $records = $this->eventService->getActiveEvents();
        foreach ($records as $record){
            $createdAt = new \DateTime($record->getCreatedAt());
            $updatedAt = new \DateTime($record->getUpdatedAt());
            $entry = $feed->createEntry();
            $entry->setTitle(utf8_encode(htmlentities($record->getTitle())));
            $entry->setLink($url.'/events/' . $record->getSlug() . '.html');
            $entry->setId($url . '/events/' . $record->getSlug() . '.html');

            $entry->addAuthor(array(
                'name'  => $appName,
                'uri'   => $url,
            ));
            $entry->setDateCreated($createdAt->getTimestamp());
            $entry->setDateModified($updatedAt->getTimestamp());
            $entry->setDescription(nl2br($record->getContent()));
            $entry->setContent("<![CDATA[" . $record->getContent() . "]]>");
            $feed->addEntry($entry);
        }
        /**
         * Render the resulting feed to Atom 1.0 and assign to $out.
         * You can substitute "atom" with "rss" to generate an RSS 2.0 feed.
         */
        header('Content-type: application/atom+xml');
        $response->setContent($feed->export('atom'));

        return $this->getResponse();

    }

}