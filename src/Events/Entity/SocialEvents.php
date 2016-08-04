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

class SocialEvents implements SocialEventsInterface {

    public $id;
    public $code;
    public $start;
    public $end;
    public $location;
    public $city;
    public $country_id;
    public $description;
    public $icaluid;
    public $status;
    public $category_id;
    public $summary;
    public $created;
    public $updated;
    public $user_id;
    public $latitude;
    public $longitude;
    public $etag;
    public $recurrence;
    public $note;
    public $photo;
    public $socialnetwork;
    public $referencelink;

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
     * @return the $code
     */
    public function getCode() {
        return $this->code;
    }

	/**
     * @param field_type $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

	/**
     * @return the $icaluid
     */
    public function getIcaluid() {
        return $this->icaluid;
    }

	/**
     * @param field_type $icaluid
     */
    public function setIcaluid($icaluid) {
        $this->icaluid = $icaluid;
    }

	/**
     * @return the $status
     */
    public function getStatus() {
        return $this->status;
    }

	/**
     * @param field_type $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

	/**
     * @return the $summary
     */
    public function getSummary() {
        return $this->summary;
    }

	/**
     * @param field_type $summary
     */
    public function setSummary($summary) {
        $this->summary = $summary;
    }

	/**
     * @return the $created
     */
    public function getCreated() {
        return $this->created;
    }

	/**
     * @param field_type $created
     */
    public function setCreated($created) {
        $this->created = $created;
    }

	/**
     * @return the $updated
     */
    public function getUpdated() {
        return $this->updated;
    }

	/**
     * @param field_type $updated
     */
    public function setUpdated($updated) {
        $this->updated = $updated;
    }
	/**
     * @return the $user_id
     */
    public function getUserId() {
        return $this->user_id;
    }

	/**
     * @param field_type $user_id
     */
    public function setUserId($user_id) {
        $this->user_id = $user_id;
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
    public function setStart($start) {
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
    public function setEnd($end) {
        $this->end = $end;
    }

	/**
     * @return the $location
     */
    public function getLocation() {
        return $this->location;
    }

	/**
     * @param field_type $location
     */
    public function setLocation($location) {
        $this->location = $location;
    }

	/**
     * @return the $description
     */
    public function getDescription() {
        return $this->description;
    }

	/**
     * @param field_type $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }
	/**
     * @return the $etag
     */
    public function getEtag() {
        return $this->etag;
    }

	/**
     * @param field_type $etag
     */
    public function setEtag($etag) {
        $this->etag = $etag;
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
	/**
     * @return the $note
     */
    public function getNote() {
        return $this->note;
    }

	/**
     * @param field_type $note
     */
    public function setNote($note) {
        $this->note = $note;
    }
	/**
     * @return the $photo
     */
    public function getPhoto() {
        return $this->photo;
    }

	/**
     * @param field_type $photo
     */
    public function setPhoto($photo) {
        $this->photo = $photo;
    }
	/**
     * @return the $socialnetwork
     */
    public function getSocialnetwork ()
    {
        return $this->socialnetwork;
    }

	/**
     * @param field_type $socialnetwork
     */
    public function setSocialnetwork ($socialnetwork)
    {
        $this->socialnetwork = $socialnetwork;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $type
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
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
    public function getReferencelink()
    {
        return $this->referencelink;
    }

    /**
     * @param mixed $referencelink
     */
    public function setReferencelink($referencelink)
    {
        $this->referencelink = $referencelink;
    }


}