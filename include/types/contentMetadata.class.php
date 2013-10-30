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

require_once('sample.class.php');
require_once('metadata.class.php');

class contentMetadata extends AbstractType {

    /**
     * @var (object)sample
     */
    public $sample;

    /**
     * @var (object)metadata
     */
    public $metadata;

    /**
     * @var string
     */
    public $category;

    /**
     * @var boolean
     */
    public $requiresReturn;



    /******************** public functions ********************/

    /**
     * constructor for class contentMetadata
     */
    function __construct($_sample = NULL, $_metadata = NULL, $_category = NULL, $_requiresReturn = NULL) {
        if (is_a($_sample, "sample")) $this->setSample($_sample);
        if (is_a($_metadata, "metadata")) $this->setMetadata($_metadata);
        if (is_string($_category)) $this->setCategory($_category);
        if (is_bool($_requiresReturn)) $this->setRequiresReturn($_requiresReturn);
    }

    /******************** class get set methods ********************/

    /**
     * getter for sample
     */
    function getSample() {
        return $this->sample;
    }

    /**
     * setter for sample
     */
    function setSample($_sample) {
        $this->sample = $_sample;
    }

    /**
     * resetter for sample
     */
    function resetSample() {
        $this->sample = NULL;
    }

    /**
     * getter for metadata
     */
    function getMetadata() {
        return $this->metadata;
    }

    /**
     * setter for metadata
     */
    function setMetadata($_metadata) {
        $this->metadata = $_metadata;
    }

    /**
     * resetter for metadata
     */
    function resetMetadata() {
        $this->metadata = NULL;
    }

    /**
     * getter for category
     */
    function getCategory() {
        return $this->category;
    }

    /**
     * setter for category
     */
    function setCategory($_category) {
        $this->category = $_category;
    }

    /**
     * resetter for category
     */
    function resetCategory() {
        $this->category = NULL;
    }

    /**
     * getter for requiresReturn
     */
    function getRequiresReturn() {
        return $this->requiresReturn;
    }

    /**
     * setter for requiresReturn
     */
    function setRequiresReturn($_requiresReturn) {
        $this->requiresReturn = $_requiresReturn;
    }

    /**
     * resetter for requiresReturn
     */
    function resetRequiresReturn() {
        $this->requiresReturn = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class contentMetadata
     */
    function validate() {
        // sample must occur zero or one times
        if (!is_null($this->sample)) {
            if ($this->isInstanceOf($this->sample, 'sample') === false)
                return false;
            if ($this->sample->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->sample->getError();
                return false;
            }
        }

        // metadata must occur exactly once
        if ($this->isInstanceOf($this->metadata, 'metadata') === false)
            return false;
        if ($this->metadata->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->metadata->getError();
            return false;
        }

        // attribute category is optional
        if (!is_null($this->category)) {
            if ($this->isNoneEmptyString($this->category, 'category') === false)
                return false;
        }

        // attribute requiresReturn is required
        if ($this->isBoolean($this->requiresReturn, 'requiresReturn') === false)
            return false;

        return true;
    }
}

?>
