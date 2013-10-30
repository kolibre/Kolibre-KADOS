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

class userResponse extends AbstractType {

    // You need to set only one from the following two vars

    /**
     * @var Plain Binary
     */
    public $data;

    /**
     * @var base64Binary
     */
    public $data_encoded;


    /**
     * @var NMTOKEN
     */
    public $questionID;

    /**
     * @var string
     */
    public $value;


    /******************** public functions ********************/

    /**
     * constructor for class userResponse
     */
    function __construct($_questionID = NULL, $_value = NULL) {
        if (is_string($_questionID)) $this->setQuestionID($_questionID);
        if (is_string($_value)) $this->setValue($_value);
    }


    /******************** class get set methods ********************/

    /**
     * getter for questionID
     */
    function getQuestionID() {
        return $this->questionID;
    }

    /**
     * setter for questionID
     */
    function setQuestionID($_questionID) {
        $this->questionID = $_questionID;
    }

    /**
     * resetter for questionID
     */
    function resetQuestionID() {
        $this->questionID = NULL;
    }

    /**
     * getter for value
     */
    function getValue() {
        return $this->value;
    }

    /**
     * setter for value
     */
    function setValue($_value) {
        $this->value = $_value;
    }

    /**
     * resetter for value
     */
    function resetValue() {
        $this->value = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class userResponse
     */
    function validate() {
        // attribute questionID is required
        if ($this->isNoneEmptyString($this->questionID, 'questionID') === false)
            return false;

        // attribute value is optional
        if (!is_null($this->value)) {
            if ($this->isString($this->value, 'value') === false)
                return false;
        }

        // attribute value and data are optional and only one must be set when
        // the value of questionID is other then 'default' or 'search' or 'back'
        $reservedValues = array('default', 'search', 'back');
        if (in_array($this->questionID, $reservedValues)) {
            if (!is_null($this->value)) {
                $this->error = __CLASS__ . ".value must no be set when questionID is '$this->questionID'";
                return false;
            }
            if (!is_null($this->data)) {
                $this->error = __CLASS__ . ".data must no be set when questionID is '$this->questionID'";
                return false;
            }
            if (!is_null($this->data_encoded)) {
                $this->error = __CLASS__ . ".data_encoded must no be set when questionID is '$this->questionID'";
                return false;
            }
        }
        else {
            if (is_null($this->value) && is_null($this->data) && is_null($this->data_encoded)) {
                $this->error = __CLASS__ . " must contain either a value attribute or a data element";
                return false;
            }
            if (!is_null($this->value) && (!is_null($this->data) || !is_null($this->data_encoded))) {
                $this->error = __CLASS__ . " can not contain a data element when the value attribute is set";
                return false;
            }
            if (is_null($this->value) && (!is_null($this->data) && !is_null($this->data_encoded))) {
                $this->error = __CLASS__ . " both data and data_encoded can not be set when value is not set";
                return false;
            }
        }

        return true;
    }
}

?>
