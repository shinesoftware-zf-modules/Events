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
* @package Event
* @subpackage Entity
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace Events\Entity;

use DateTime;

interface EventInterface
{
    public function getId();
    public function getUserId();
    public function setUserId($user_id);
    public function getSocialnetworkId();
    public function setSocialnetworkId($socialnetwork_id);
    public function getSku();
    public function setSku($sku);
    public function getExtid();
    public function setExtid($extid);
    public function getTitle();
    public function setTitle($title);
    public function getCountryId();
    public function setCountryId($country_id);
    public function getCity();
    public function setCity($city);
    public function getAddress();
    public function setAddress($address);
    public function getContact();
    public function setContact($contact);
    public function getStart();
    public function setStart(DateTime $start = null);
    public function getEnd();
    public function setEnd(DateTime $end = null);
    public function getSlug();
    public function setSlug($slug);
    public function getContent();
    public function setContent($content);
    public function getVisible();
    public function setVisible($visible);
    public function getShowonlist();
    public function setShowonlist($showonlist);
    public function getCategoryId();
    public function setCategoryId($category_id);
    public function getUrl();
    public function setUrl($url);
    public function getLanguageId();
    public function setLanguageId($language_id);
    public function getParentId();
    public function setParentId($parent_id);
    public function getTags();
    public function setTags($tags);
    public function getLayout();
    public function setLayout($layout);
    public function getCreatedat();
    public function setCreatedat(DateTime $createdat = null);
    public function getUpdatedat();
    public function setUpdatedat(DateTime $updatedat = null);
    public function getLatitude();
    public function setLatitude($latitude);
    public function getLongitude();
    public function setLongitude($longitude);
    public function getRecurrence();
    public function setRecurrence($recurrence);
}