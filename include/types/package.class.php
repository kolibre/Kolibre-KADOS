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
     * @var array[1, unbounded] of (object) resourceRef
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
        if (is_array($_resourceRef)) $this->setResourceRef($_resourceRef);
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

    /****************************** get set methods for resourceRef arrays **********************************/

    /**
     * get the ith element of resourceRef
     */
    function getResourceRefAt($i) {
        if ($this->sizeOfResourceRef() > $i)
            return $this->resourceRef[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of resourceRef
     */
    function setResourceRefAt($i, $_resourceRef) {
        $this->resourceRef[$i] = $_resourceRef;
    }

    /**
     * add to resourceRef
     */
    function addResourceRef($_resourceRef) {
        if (is_array($this->resourceRef))
            array_push($this->resourceRef, $_resourceRef);
        else {
            $this->resourceRef = array();
            $this->addResourceRef($_resourceRef);
        }
    }

    /**
     * get the size of the resourceRef array
     */
    function sizeofResourceRef() {
        return sizeof($this->resourceRef);
    }

    /**
     * remove the ith element of resourceRef
     */
    function removeResourceRefAt($i) {
        if ($this->sizeofResourceRef() > $i)
            unset($this->resourceRef[$i]);
    }

    /******************** validator methods ********************/

    /**
     * validator for class package
     */
    function validate() {
        // resourceRef must occur one or more times
        if ($this->isNoneEmptyArray($this->resourceRef, 'resourceRef') === false)
            return false;
        if ($this->isArrayOfInstanceOf($this->resourceRef, 'resourceRef') === false)
            return false;
        foreach ($this->resourceRef as $index => $resourceRef) {
            if ($resourceRef->validate() === false) {
                $this->error = __CLASS__ . '.' . $resourceRef->getError();
                $this->error = str_replace('resourceRef.', "resourceRef[$index].", $this->error);
                return false;
            }
        }

        // uri must occur exactly once
        if ($this->isNoneEmptyString($this->uri, 'uri') === false)
            return false;
        
        // mimeType must occur exactly once
        if ($this->isNoneEmptyString($this->mimeType, 'mimeType') === false)
            return false;
        
        // size can not be NULL
        if(is_null($this->size))
            return false;

        // size must be specified and positive integer
        if ($this->isPositiveInteger($this->size, 'size') === false)
            return false;
            
           
        // lastModifiedDate must include timezone if set
        if ($this->isDateTimeString($this->lastModifiedDate, 'lastModifiedDate') === false) {
            return false;
        }    
        
        
        return true;
    }
}

?>