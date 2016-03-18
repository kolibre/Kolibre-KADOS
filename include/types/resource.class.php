<?php

/*
 * Copyright (C) 2013 Kolibre
 *
 * This file is part of Kolibre-KADOS.
 * Kolibre-KADOS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 2.1 of the License, or
 * at your option) any later version.
 *
 * Kolibre-KADOS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with Kolibre-KADOS. If not, see <http://www.gnu.org/licenses/>.
 */

require_once('AbstractType.class.php');

class resource extends AbstractType {

    /**
     * @var anyURI
     */
    public $uri;

    /**
     * @var string
     */
    public $mimeType;

    /**
     * @var long
     */
    public $size;

    /**
     * @var anyURI
     */
    public $localURI;

    /**
     * @var dateTime
     */
    public $lastModifiedDate;

    /**
     * @var string
     */
    public $serverSideHash;


    /******************** public functions ********************/

    /**
     * constructor for class resource
     */
    function __construct($_uri = NULL, $_mimeType = NULL, $_size = NULL, $_localURI = NULL, $_lastModifiedDate = NULL, $_serverSideHash = NULL) {
        if (is_string($_uri)) $this->setUri($_uri);
        if (is_string($_mimeType)) $this->setMimeType($_mimeType);
        if (is_int($_size)) $this->setSize($_size);
        if (is_string($_localURI)) $this->setLocalURI($_localURI);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);
        if (is_string($_serverSideHash)) $this->setServerSideHash($_serverSideHash);
    }


    /******************** class get set methods ********************/

    /**
     * getter for uri
     */
    function getUri() {
        return $this->uri;
    }

    /**
     * setter for uri
     */
    function setUri($_uri) {
        $this->uri = $_uri;
    }

    /**
     * resetter for uri
     */
    function resetUri() {
        $this->uri = NULL;
    }

    /**
     * getter for mimeType
     */
    function getMimeType() {
        return $this->mimeType;
    }

    /**
     * setter for mimeType
     */
    function setMimeType($_mimeType) {
        $this->mimeType = $_mimeType;
    }

    /**
     * resetter for mimeType
     */
    function resetMimeType() {
        $this->mimeType = NULL;
    }

    /**
     * getter for size
     */
    function getSize() {
        return $this->size;
    }

    /**
     * setter for size
     */
    function setSize($_size) {
        $this->size = $_size;
    }

    /**
     * resetter for size
     */
    function resetSize() {
        $this->size = NULL;
    }

    /**
     * getter for localURI
     */
    function getLocalURI() {
        return $this->localURI;
    }

    /**
     * setter for localURI
     */
    function setLocalURI($_localURI) {
        $this->localURI = $_localURI;
    }

    /**
     * resetter for localURI
     */
    function resetLocalURI() {
        $this->localURI = NULL;
    }

    /**
     * getter for lastModifiedDate
     */
    function getLastModifiedDate() {
        return $this->lastModifiedDate;
    }

    /**
     * setter for lastModifiedDate
     */
    function setLastModifiedDate($_lastModifiedDate) {
        $this->lastModifiedDate = $_lastModifiedDate;
    }

    /**
     * resetter for lastModifiedDate
     */
    function resetLastModifiedDate() {
        $this->lastModifiedDate = NULL;
    }

    /**
     * getter for serverSideHash
     */
    function getServerSideHash() {
        return $this->serverSideHash;
    }

    /**
     * setter for serverSideHash
     */
    function setServerSideHash($_serverSideHash) {
        $this->serverSideHash = $_serverSideHash;
    }

    /**
     * resetter for serverSideHash
     */
    function resetServerSideHash() {
        $this->serverSideHash = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class resource
     */
    function validate() {
        // attribute uri is required
        if ($this->isNoneEmptyString($this->uri, 'uri') === false)
            return false;

        // attribute mimeType is required
        if ($this->isNoneEmptyString($this->mimeType, 'mimeType') === false)
            return false;

        // attribute size is required
        if ($this->isPositiveInteger($this->size, 'size') === false)
            return false;

        // atttribute localURI is required
        if ($this->isNoneEmptyString($this->localURI, 'localURI') === false)
            return false;

        //lastModifiedDate must occur exactly once
        if ($this->isDateTimeString($this->lastModifiedDate, 'lastModifiedDate') === false) {
            return false;
        } 
  
        // attribute serverSideHash is optional
        if (!is_null($this->serverSideHash)) {
            if ($this->isNoneEmptyString($this->serverSideHash, 'serverSideHash') === false)
                return false;
        }

        return true;
    }
}

?>
