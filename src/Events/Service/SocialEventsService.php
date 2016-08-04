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

use Zend\Db\Sql\Delete;

use Zend\EventManager\EventManager;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class SocialEventsService implements SocialEventsServiceInterface, EventManagerAwareInterface
{
	protected $eventManager; 
	protected $tablegateway;
	protected $translator;
	
	public function __construct(TableGateway $tableGateway, \Zend\Mvc\I18n\Translator $translator ){
	    $this->tableGateway = $tableGateway;
	    $this->translator = $translator;
	}
	
	/**
	 * @inheritDoc
	 */
	public function findAll()
	{
	    $records = $this->tableGateway->select();
	    return $records;
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
	public function findByUserId($user_id)
	{
	    $records = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($user_id) {
	        $select->where(array('user_id' => $user_id));
	    });
	
	    return $records;
	}
	
	
	/**
	 * @inheritDoc
	 */
	public function findByCode($code, $socialnetwork = null)
	{
	    $record = $this->tableGateway->select(function (\Zend\Db\Sql\Select $select) use ($code, $socialnetwork){
	        $select->where(array('code' => $code));
			if($socialnetwork){
				$select->where(array('socialnetwork' => $socialnetwork));
			}
		});
	
	    return $record->current();
	}
	
	/**
	 * @inheritDoc
	 */
	public function deleteAllbyIcalUID($icalUid)
	{
	    return $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($icalUid){
	        $select->where(array('icaluid' => $icalUid));
	    });
	
	}
	
	/**
	 * @inheritDoc
	 */
	public function deleteAllbyUserId($userId)
	{
	    return $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($userId){
	        $select->where(array('user_id' => $userId));
	    });
	}
	
	/**
	 * @inheritDoc
	 */
	public function deleteAllbyUserIdAndSocialNetwork($userId, $socialnetwork)
	{
	    return $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($userId, $socialnetwork){
	        $select->where(array('user_id' => $userId));
	        $select->where(array('socialnetwork' => $socialnetwork));
	    });
	}

	/**
	 * @inheritDoc
	 */
	public function deleteAllbySocialNetwork($socialnetwork)
	{
		return $this->tableGateway->delete(function (\Zend\Db\Sql\Delete $select) use ($socialnetwork) {
			$select->where(array('socialnetwork' => $socialnetwork));
		});
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
	public function save(\Events\Entity\SocialEvents $record)
	{
	    $hydrator = new ClassMethods(true);
	
	    // extract the data from the object
	    $data = $hydrator->extract($record);
	    
	    $id = (int) $record->getId();
	    if ($id == 0) {
	        unset($data['id']);
	        $this->tableGateway->insert($data); // add the record
	        $id = $this->tableGateway->getLastInsertValue();
	    } else {
	        $rs = $this->find($id);
	        if (!empty($rs)) {
	            $this->tableGateway->update($data, array (
	                    'id' => $id
	            ));
	        } else {
	            throw new \Exception('Social Event Setting ID does not exist');
	        }
	    }
	    $record = $this->find($id);
	    return $record;
	}
	
	
	/* (non-PHPdoc)
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
     public function setEventManager (EventManagerInterface $eventManager){
         $eventManager->addIdentifiers(get_called_class());
         $this->eventManager = $eventManager;
     }

	/* (non-PHPdoc)
     * @see \Zend\EventManager\ProfileCapableInterface::getEventManager()
     */
     public function getEventManager (){
       if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }

        return $this->eventManager;
     }
}