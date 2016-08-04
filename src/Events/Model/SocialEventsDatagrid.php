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
 * @subpackage Model
 * @author Michelangelo Turillo <mturillo@shinesoftware.com>
 * @copyright 2014 Michelangelo Turillo.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @link http://shinesoftware.com
 * @version @@PACKAGE_VERSION@@
 */

namespace Events\Model;

use \Base\Service\SettingsServiceInterface;
use ZfcDatagrid;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Formatter;
use ZfcDatagrid\Filter;
use Zend\Db\Sql\Select;

class SocialEventsDatagrid {
	
	/**
	 *
	 * @var \ZfcUser\Authentication
	 */
	protected $zfcAuthService;
	
	/**
	 *
	 * @var \ZfcDatagrid\Datagrid
	 */
	protected $grid;
	
	/**
	 *
	 * @var \Zend\Db\Adapter\Adapter
	 */
	protected $adapter;
	
	/**
	 *
	 * @var SettingsService
	 */
	protected $settings;
	
	/**
	 * Datagrid Constructor
	 * 
	 * @param \Zend\Db\Adapter\Adapter $dbAdapter
	 * @param \ZfcDatagrid\Datagrid $datagrid
	 */
	public function __construct(\Zend\Authentication\AuthenticationService $zfcAuthService,
	            \Zend\Db\Adapter\Adapter $dbAdapter, 
	            \ZfcDatagrid\Datagrid $datagrid, 
	            SettingsServiceInterface $settings )
	{
		$this->zfcAuthService = $zfcAuthService;
		$this->adapter = $dbAdapter;
		$this->grid = $datagrid;
		$this->settings = $settings;
	}
	
	/**
	 *
	 * @return \ZfcDatagrid\Datagrid
	 */
	public function getGrid()
	{
		return $this->grid;
	}
	
	/**
	 * Consumers list
	 *
	 * @return \ZfcDatagrid\Datagrid
	 */
	public function getDatagrid()
	{
		$grid = $this->getGrid();
		$grid->setId('socialeventGrid');
		
		$userId = $this->zfcAuthService->getIdentity()->getId();
		
		$dbAdapter = $this->adapter;
		$select = new Select();
    	$select->from(array ('es' => 'events_socialnetwork'));
        $select->where(array('user_id' => $userId));
        
    	$RecordsPerEvent = $this->settings->getValueByParameter('Events', 'usereventsperpage');
    	
    	// set default values
    	$RecordsPerEvent = !empty($RecordsPerEvent) ? $RecordsPerEvent : 5;
    	 
    	$grid->setDefaultItemsPerPage($RecordsPerEvent);
    	$grid->setDataSource($select, $dbAdapter);
    
    	$colId = new Column\Select('id', 'es');
    	$colId->setLabel('Id');
    	$colId->setIdentity();
    	$grid->addColumn($colId);
    	
    	$col = new Column\Select('summary', 'es');
    	$col->setLabel(_('Title'));
    	$col->setWidth(15);
    	$grid->addColumn($col);
    	
    	$col = new Column\Select('socialnetwork', 'es');
    	$col->setType(new \ZfcDatagrid\Column\Type\PhpString());
    	$col->setLabel(_('Social Network'));
    	$col->setTranslationEnabled(true);
    	$col->setFilterSelectOptions(array (
    	        '' => '-',
    	        'google' => 'Google Calendar',
    	        'facebook' => 'Facebook Events'
    	));
    	$col->setReplaceValues(array (
    	        '' => '-',
    	        'google' => '<i class="fa fa-google"></i> Google Calendar',
    	        'facebook' => '<i class="fa fa-facebook"></i> Facebook Events' 
    	));
    	$grid->addColumn($col);
    	
    	$col = new Column\Select('status', 'es');
    	$col->setType(new \ZfcDatagrid\Column\Type\PhpString());
    	$col->setLabel(_('Status'));
    	$col->setTranslationEnabled(true);
    	$col->setFilterSelectOptions(array (
    	        '' => '-',
    	        'cancelled' => _('Cancelled'),
    	        'confirmed' => _('Confirmed'),
    	        'not confirmed' => _('Not Confirmed')
    	));
    	$col->setReplaceValues(array (
    	        '' => '-',
    	        'cancelled' => '<i class="fa fa-eye-slash"></i> ' . _('Cancelled'),
    	        'confirmed' => '<i class="fa fa-check"></i> ' . _('Confirmed'),
    	        'not confirmed' => '<i class="fa fa-close"></i> ' . _('Not Confirmed')
    	));
    	$grid->addColumn($col);
    	
    	$col = new Column\Select('note', 'es');
    	$col->setType(new \ZfcDatagrid\Column\Type\PhpString());
    	$col->setLabel(_('Note'));
    	$col->setTranslationEnabled(true);
    	$col->setFilterSelectOptions(array (
    	        '' => '-',
    	        'cancelled' => _('Cancelled'),
    	        'published' => _('Published'),
    	        'error-empty-title' => _('Title is mandatory'),
    	        'error-empty-description' => _('Description is mandatory'),
    	        'error-empty-address' => _('Address is mandatory'),
    	));
    	$col->setReplaceValues(array (
    	        '' => '-',
    	        'cancelled' => '<i class="fa fa-eye-slash"></i> ' ._('Cancelled'),
    	        'published' => '<i class="fa fa-check"></i> ' ._('Published'),
    	        'error-empty-title' => '<i class="fa fa-close"></i> ' . _('Title is mandatory'),
    	        'error-empty-description' => '<i class="fa fa-close"></i> ' ._('Description is mandatory'),
    	        'error-empty-address' => '<i class="fa fa-close"></i> ' ._('Address is mandatory'),
    	));
    	$grid->addColumn($col);
    	
    	$colType = new Type\DateTime(\DateTime::ISO8601, \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT);
    	$colType->setSourceTimezone('Europe/Rome');
    	$colType->setOutputTimezone('UTC');
    	$colType->setLocale('it_IT');

    	$grid->setToolbarTemplate('');
    
		return $grid;
	}

}

?>