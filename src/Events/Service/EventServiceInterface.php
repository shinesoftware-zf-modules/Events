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

interface EventServiceInterface
{
    /**
     * Should return all the records 
     *
     * @return array|\Traversable
     */
    public function findAll();
    
    /**
     * Should return all the active events and visible on the cms list page
     *
     * @return array|\Traversable
     */
    public function getActiveEvents();

    /**
     * Should return all the active events of a specific user
     *
     * @return array|\Traversable
     */
    public function getEventsbyUser($userId);

    /**
     * Should return a single record
     *
     * @param  int $id Identifier of the Record that should be returned
     * @return \Events\Entity\Event
     */
    public function find($id);
    
    /**
     * Should return a single record
     *
     * @param  int $slug Identifier of the Record that should be returned
     * @param  string $locale Identifier of the locale
     * @return \Events\Entity\Event
     */
    public function findByUri($slug, $locale);

    /**
     * Should delete a single record
     *
     * @param  int $id Identifier of the Record that should be deleted
     * @return \Events\Entity\Event
     */
    public function delete($id);
    
    /**
     * Should save a single record
     *
     * @param  \Events\Model\Event $record object that should be saved
     * @return \Events\Entity\Event
     */
    public function save(\Events\Entity\Event $record);
}