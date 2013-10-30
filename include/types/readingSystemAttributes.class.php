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

require_once('config.class.php');

class readingSystemAttributes extends AbstractType {

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var anonymous0
     */
    public $anonymous0;
    // ----------------End Choice---------------


    /**
     * @var string
     */
    public $manufacturer;

    /**
     * @var string
     */
    public $model;

    /**
     * @var string
     */
    public $serialNumber;

    /**
     * @var string
     */
    public $version;

    /**
     * @var (object)config
     */
    public $config;


    /******************** public functions ********************/

    /**
     * constructor for class readingSystemAttributes
     */
    function __construct($_manufacturer = NULL, $_model = NULL, $_serialNumber = NULL, $_version = NULL, $_config = NULL) {
        if (is_string($_manufacturer)) $this->setManufacturer($_manufacturer);
        if (is_string($_model)) $this->setModel($_model);
        if (is_string($_serialNumber)) $this->setSerialNumber($_serialNumber);
        if (is_string($_version)) $this->setVersion($_version);
        if (is_a($_config, "config")) $this->setConfig($_config);
    }


    /******************** class get set methods ********************/

    /**
     * getter for manufacturer
     */
    function getManufacturer() {
        return $this->manufacturer;
    }

    /**
     * setter for manufacturer
     */
    function setManufacturer($_manufacturer) {
        $this->manufacturer = $_manufacturer;
    }

    /**
     * resetter for manufacturer
     */
    function resetManufacturer() {
        $this->manufacturer = NULL;
    }

    /**
     * getter for model
     */
    function getModel() {
        return $this->model;
    }

    /**
     * setter for model
     */
    function setModel($_model) {
        $this->model = $_model;
    }

    /**
     * resetter for model
     */
    function resetModel() {
        $this->model = NULL;
    }

    /**
     * getter for serialNumber
     */
    function getSerialNumber() {
        return $this->serialNumber;
    }

    /**
     * setter for serialNumber
     */
    function setSerialNumber($_serialNumber) {
        $this->serialNumber = $_serialNumber;
    }

    /**
     * resetter for serialNumber
     */
    function resetSerialNumber() {
        $this->serialNumber = NULL;
    }

    /**
     * getter for version
     */
    function getVersion() {
        return $this->version;
    }

    /**
     * setter for version
     */
    function setVersion($_version) {
        $this->version = $_version;
    }

    /**
     * resetter for version
     */
    function resetVersion() {
        $this->version = NULL;
    }

    /**
     * getter for config
     */
    function getConfig() {
        return $this->config;
    }

    /**
     * setter for config
     */
    function setConfig($_config) {
        $this->config = $_config;
    }

    /**
     * resetter for config
     */
    function resetConfig() {
        $this->config = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class readingSystemAttributes
     */
    function validate() {
        // manufacturer must occur exactly once
        if ($this->isNoneEmptyString($this->manufacturer, 'manufacturer') === false)
            return false;

        // model must occur exactly once
        if ($this->isNoneEmptyString($this->model, 'model') === false)
            return false;

        // serialNumber must occur zero or one times
        if (!is_null($this->serialNumber)) {
            if ($this->isNoneEmptyString($this->serialNumber, 'serialNumber') === false)
                return false;
        }

        // version must occur exactly once
        if ($this->isNoneEmptyString($this->version, 'version') === false)
            return false;

        // config must occur exactly once
        if ($this->isInstanceOf($this->config, 'config') === false)
            return false;
        if ($this->config->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->config->getError();
            return false;
        }

        return true;
    }
}

?>
