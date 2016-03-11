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
require_once('resourceRef.class.php');

class package extends AbstractType {

    /**
     * @var (object) resourceRef
     */
    public $resourceRef;

    /**
     * @var string
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
     * @var string
     */
    public $lastModifiedDate;


    /******************** public functions ********************/

    /**
     * constructor for class package
     */
    function __construct($_resourceRef = NULL, $_uri = NULL, $_mimeType = NULL, $_size = NULL, $_lastModifiedDate = NULL) {
        if (is_a($_resourceRef, "resourceRef")) $this->setResourceRef($_resourceRef);
        if (is_string($_uri)) $this->setUri($_uri);
        if (is_string($_mimeType)) $this->setMimeType($_mimeType);
        if (is_int($_size)) $this->setSize($_size);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);
    }


    /******************** class get set methods ********************/

    /**
     * getter for resourceRef
     */
    function getResourceRef() {
        return $this->resourceRef;
    }

    /**
     * setter for resourceRef
     */
    function setResourceRef($_resourceRef) {
        $this->resourceRef = $_resourceRef;
    }

    /**
     * resetter for resourceRef
     */
    function resetResourceRef() {
        $this->resourceRef = NULL;
    }

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
        echo "in setter";
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
        $this->lastDateModified = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class package
     */
    function validate() {

        // resourceRef can not be null
        if (is_null($this->resourceRef))
            return false;
        // resourceRef must be of type resourceRef and correct
        if (!is_null($this->resourceRef)) {
            if ($this->isInstanceOf($this->resourceRef, 'resourceRef') === false){
                return false;
            }
            if ($this->resourceRef->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->resourceRef->getError();
                return false;
            }
        }

        // uri must occur exactly once
        if ($this->isNoneEmptyString($this->uri, 'uri') === false){
            return false;
        }
        // mimeType must occur exactly once
        if ($this->isNoneEmptyString($this->mimeType, 'mimeType') === false){
            return false;
        }
        // size can not be NULL
        if(is_null($this->size))
            return false;

        // size must be specified and positive integer
        if (!is_null($this->size)) {
            if ($this->isPositiveInteger($this->size, 'size') === false){
                return false;
            }
        }
        //lastModifiedDate must occur exactly once
        if (!is_null($this->lastModifiedDate)){
            if($this->isString($this->lastModifiedDate, 'lastModifiedDate') === false){
                return false;
            }
        }
        // lastModifiedDate must include timezone if set
        if (preg_match('/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}(Z|\+\d{2}:\d{2})/', $this->lastModifiedDate) != 1) {
            echo " last one ";
            return false;    
        }
        
        return true;
    }
}

?>
