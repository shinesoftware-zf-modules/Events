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
* @subpackage Entity
* @author Michelangelo Turillo <mturillo@shinesoftware.com>
* @copyright 2014 Michelangelo Turillo.
* @license http://www.opensource.org/licenses/bsd-license.php BSD License
* @link http://shinesoftware.com
* @version @@PACKAGE_VERSION@@
*/

namespace Events\Entity;
use DateTime;

class Event implements EventInterface {

    public $id;
    public $sku;
    public $extid;
    public $user_id;
    public $socialnetwork_id;
    public $title;
    public $country_id;
	public $place;
    public $city;
    public $address;
    public $contact;
    public $start;
    public $end;
    public $slug;
    public $content;
    public $visible;
    public $showonlist;
    public $category_id;
    public $url;
    public $language_id;
    public $parent_id;
    public $tags;
    public $layout;
    public $file;
    public $latitude;
    public $longitude;
    public $createdat;
    public $recurrence;
    public $updatedat;
    
    /**
     * This method get the array posted and assign the values to the table
     * object
     *
     * @param array $data
     */
    public function exchangeArray ($data)
    {
    	foreach ($data as $field => $value) {
    		$this->$field = (isset($value)) ? $value : null;
    	}
    
    	return true;
    }
    
    /**
	 * @return the $id
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param field_type $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getSku()
	{
		return $this->sku;
	}

	/**
	 * @param mixed $sku
	 */
	public function setSku($sku)
	{
		$this->sku = $sku;
	}

	/**
     * @return the $extid
     */
    public function getExtid() {
        return $this->extid;
    }

	/**
     * @param field_type $extid
     */
    public function setExtid($extid) {
        $this->extid = $extid;
    }

	/**
     * @return the $user_id
     */
    public function getUserId ()
    {
        return $this->user_id;
    }

	/**
     * @param field_type $user_id
     */
    public function setUserId ($user_id)
    {
        $this->user_id = $user_id;
    }

	/**
     * @return the $socialnetwork_id
     */
    public function getSocialnetworkId() {
        return $this->socialnetwork_id;
    }

	/**
     * @param field_type $socialnetwork_id
     */
    public function setSocialnetworkId($socialnetwork_id) {
        $this->socialnetwork_id = $socialnetwork_id;
    }

	/**
	 * @return the $title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param field_type $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return mixed
	 */
	public function getCountryId()
	{
		return $this->country_id;
	}

	/**
	 * @param mixed $country_id
	 */
	public function setCountryId($country_id)
	{
		$this->country_id = $country_id;
	}


	/**
	 * @return mixed
	 */
	public function getPlace()
	{
		return $this->place;
	}

	/**
	 * @param mixed $place
	 */
	public function setPlace($place)
	{
		$this->place = $place;
	}

	/**
	 * @return mixed
	 */
	public function getCity()
	{
		return $this->city;
	}

	/**
	 * @param mixed $city
	 */
	public function setCity($city)
	{
		$this->city = $city;
	}

	/**
     * @return the $address
     */
    public function getAddress() {
        return $this->address;
    }

	/**
     * @param field_type $address
     */
    public function setAddress($address) {
        $this->address = $address;
    }

	/**
     * @return the $contact
     */
    public function getContact() {
        return $this->contact;
    }

	/**
     * @param field_type $contact
     */
    public function setContact($contact) {
        $this->contact = $contact;
    }

	/**
     * @return the $start
     */
    public function getStart() {
        return $this->start;
    }

	/**
     * @param field_type $start
     */
    public function setStart(DateTime $start = null) {
        $this->start = $start;
    }

	/**
     * @return the $end
     */
    public function getEnd() {
        return $this->end;
    }

	/**
     * @param field_type $end
     */
    public function setEnd(DateTime $end = null) {
        $this->end = $end;
    }

	/**
	 * @return the $slug
	 */
	public function getSlug() {
		return $this->slug;
	}

	/**
	 * @param field_type $slug
	 */
	public function setSlug($slug) {
		$this->slug = $slug;
	}

	/**
	 * @return the $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param field_type $content
	 */
	public function setContent($content) {
		$this->content = $content;
	}

	/**
	 * @return the $createdat
	 */
	public function getCreatedat() {
		return $this->createdat;
	}

	/**
	 * @param field_type $createdat
	 */
	public function setCreatedat(DateTime $createdat = NULL) {
		$this->createdat = $createdat;
	}

	/**
	 * @return the $updatedat
	 */
	public function getUpdatedat() {
		return $this->updatedat;
	}

	/**
	 * @param field_type $updatedat
	 */
	public function setUpdatedat(DateTime $updatedat = NULL) {
		$this->updatedat = $updatedat;
	}

	/**
	 * @return the $visible
	 */
	public function getVisible() {
		return $this->visible;
	}

	/**
	 * @param field_type $visible
	 */
	public function setVisible($visible) {
		$this->visible = $visible;
	}

	/**
     * @return the $showonlist
     */
    public function getShowonlist ()
    {
        return $this->showonlist;
    }

	/**
     * @param field_type $showonlist
     */
    public function setShowonlist ($showonlist)
    {
        $this->showonlist = $showonlist;
    }

	/**
	 * @return the $category_id
	 */
	public function getCategoryId() {
		return $this->category_id;
	}

	/**
	 * @return the $language_id
	 */
	public function getLanguageId() {
		return $this->language_id;
	}

	/**
	 * @param field_type $language_id
	 */
	public function setLanguageId($language_id) {
		$this->language_id = $language_id;
	}

	/**
	 * @param field_type $category_id
	 */
	public function setCategoryId($category_id) {
		$this->category_id = $category_id;
	}

	/**
	 * @return the $tags
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * @param field_type $tags
	 */
	public function setTags($tags) {
		$this->tags = $tags;
	}

	/**
	 * @return the $layout
	 */
	public function getLayout() {
		return $this->layout;
	}

	/**
	 * @param field_type $layout
	 */
	public function setLayout($layout) {
		$this->layout = $layout;
	}

	/**
	 * @return the $parent_id
	 */
	public function getParentId() {
		return $this->parent_id;
	}

	/**
	 * @param field_type $parent_id
	 */
	public function setParentId($parent_id) {
		$this->parent_id = $parent_id;
	}

	/**
     * @return the $url
     */
    public function getUrl() {
        return $this->url;
    }

	/**
     * @param field_type $url
     */
    public function setUrl($url) {
        $this->url = $url;
    }
	
	/**
     * @return the $file
     */
    public function getFile() {
        return $this->file;
    }

	/**
     * @param field_type $file
     */
    public function setFile($file) {
        $this->file = $file;
    }
	/**
     * @return the $latitude
     */
    public function getLatitude() {
        return $this->latitude;
    }

	/**
     * @param field_type $latitude
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

	/**
     * @return the $longitude
     */
    public function getLongitude() {
        return $this->longitude;
    }

	/**
     * @param field_type $longitude
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;
    }
	/**
     * @return the $recurrence
     */
    public function getRecurrence() {
        return $this->recurrence;
    }

	/**
     * @param field_type $recurrence
     */
    public function setRecurrence($recurrence) {
        $this->recurrence = $recurrence;
    }






}