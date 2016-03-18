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

require_once('resource.class.php');

class resources extends AbstractType {

    /**
     * @var array[1, unbounded] of (object)resource
     */
    public $resource;

    /**
     * @var array[0, unbounded] of (object)package
     */
    public $package;

    /**
     * @var dateTime
     */
    public $lastModifiedDate;


    /******************** public functions ********************/

    /**
     * constructor for class resources
     */
    function __construct($_resource = NULL, $_package = NULL, $_lastModifiedDate = NULL) {
        if (is_array($_resource)) $this->setResource($_resource);
        if (is_array($_package)) $this->setPackage($_package);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);
    }


    /******************** class get set methods ********************/

    /**
     * getter for resource
     */
    function getResource() {
        return $this->resource;
    }

    /**
     * setter for resource
     */
    function setResource($_resource) {
        $this->resource = $_resource;
    }

    /**
     * resetter for resource
     */
    function resetResource() {
        $this->resource = NULL;
    }

    /**
     * getter for returnBy
     */
    function getPackage() {
        return $this->package;
    }

    /**
     * setter for package
     */
    function setPackage($_package) {
        $this->package = $_package;
    }

    /**
     * resetter for package
     */
    function resetPackage() {
        $this->package = NULL;
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


    /****************************** get set methods for resource arrays **********************************/

    /**
     * get the ith element of resource
     */
    function getResourceAt($i) {
        if ($this->sizeofResource() > $i)
            return $this->resource[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of resource
     */
    function setResourceAt($i, $_resource) {
        $this->resource[$i] = $_resource;
    }

    /**
     * add to resource
     */
    function addResource($_resource) {
        if (is_array($this->resource))
            array_push($this->resource, $_resource);
        else {
            $this->resource = array();
            $this->addResource($_resource);
        }
    }

    /**
     * get the size of the resource array
     */
    function sizeofResource() {
        return sizeof($this->resource);
    }

    /**
     * remove the ith element of resource
     */
    function removeResourceAt($i) {
        if ($this->sizeofResource() > $i)
            unset($this->resource[$i]);
    }

/****************************** get set methods for package arrays **********************************/
      /**
     * get the ith element of package
     */
    function getPackageAt($i) {
        if ($this->sizeOfPackage() > $i)
            return $this->package[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of package
     */
    function setPackageAt($i, $_package) {
        $this->package[$i] = $_package;
    }

    /**
     * add to package
     */
    function addPackage($_package) {
        if (is_array($this->package))
            array_push($this->package, $_package);
        else {
            $this->package = array();
            $this->addPackage($_package);
        }
    }

    /**
     * get the size of the package array
     */
    function sizeofPackage() {
        return sizeof($this->package);
    }

    /**
     * remove the ith element of package
     */
    function removePackageAt($i) {
        if ($this->sizeofPackage() > $i)
            unset($this->package[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class resources
     */
    function validate() {
        // resource must occur one or more times
        if ($this->isNoneEmptyArray($this->resource, 'resource') === false)
            return false;
        if ($this->isArrayOfInstanceOf($this->resource, 'resource') === false)
            return false;
        foreach ($this->resource as $index => $resource) {
            if ($resource->validate() === false) {
                $this->error = __CLASS__ . '.' . $resource->getError();
                $this->error = str_replace('resource.', "resource[$index].", $this->error);
                return false;
            }
        }

        // package must occur zero or more times
        if (!is_null($this->package)){
            if ($this->isArrayOfInstanceOf($this->package, 'package') === false)
                return false;
            if ($this->isNoneEmptyArray($this->package, 'package') === false)
                return false;
            foreach ($this->package as $index => $package) {
                if ($package->validate() === false) {
                    $this->error = __CLASS__ . '.' . $package->getError();
                    $this->error = str_replace('package.', "resource[$index].", $this->error);
                    return false;
                }
            }
        }
        // attribute lastModifiedDate must occur exactly once
        if ($this->isDateTimeString($this->lastModifiedDate, 'lastModifiedDate') === false) {
            return false;
        } 

        return true;
    }
}

?>
