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

require_once('multipleChoiceQuestion.class.php');
require_once('inputQuestion.class.php');
require_once('label.class.php');

class questions extends AbstractType {

    // You may set only one from the following set
    // ---------------Start Choice----------------

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var (object)multipleChoiceQuestion
     */
    public $multipleChoiceQuestion;

    /**
     * @var (object)inputQuestion
     */
    public $inputQuestion;
    // ----------------End Choice---------------


    /**
     * @var NMTOKEN
     */
    public $contentListRef;

    /**
     * @var (object)label
     */
    public $label;
    // ----------------End Choice---------------


    /******************** public functions ********************/

    /**
     * constructor for class questions
     */
    function __construct($_multipleChoiceQuestion = NULL, $_inputQuestion = NULL, $_contentListRef = NULL, $_label = NULL) {
        if (is_a($_multipleChoiceQuestion, "multipleChoiceQuestion")) $this->setMultipleChoiceQuestion($_multipleChoiceQuestion);
        if (is_a($_inputQuestion, "inputQuestion")) $this->setInputQuestion($_inputQuestion);
        if (is_string($_contentListRef)) $this->setContentListRef($_contentListRef);
        if (is_a($_label, "label")) $this->setLabel($_label);
    }


    /******************** class get set methods ********************/

    /**
     * getter for multipleChoiceQuestion
     */
    function getMultipleChoiceQuestion() {
        return $this->multipleChoiceQuestion;
    }

    /**
     * setter for multipleChoiceQuestion
     */
    function setMultipleChoiceQuestion($_multipleChoiceQuestion) {
        $this->multipleChoiceQuestion = $_multipleChoiceQuestion;
    }

    /**
     * resetter for multipleChoiceQuestion
     */
    function resetMultipleChoiceQuestion() {
        $this->multipleChoiceQuestion = NULL;
    }

    /**
     * getter for inputQuestion
     */
    function getInputQuestion() {
        return $this->inputQuestion;
    }

    /**
     * setter for inputQuestion
     */
    function setInputQuestion($_inputQuestion) {
        $this->inputQuestion = $_inputQuestion;
    }

    /**
     * resetter for inputQuestion
     */
    function resetInputQuestion() {
        $this->inputQuestion = NULL;
    }

    /**
     * getter for contentListRef
     */
    function getContentListRef() {
        return $this->contentListRef;
    }

    /**
     * setter for contentListRef
     */
    function setContentListRef($_contentListRef) {
        $this->contentListRef = $_contentListRef;
    }

    /**
     * resetter for contentListRef
     */
    function resetContentListRef() {
        $this->contentListRef = NULL;
    }

    /**
     * getter for label
     */
    function getLabel() {
        return $this->label;
    }

    /**
     * setter for label
     */
    function setLabel($_label) {
        $this->label = $_label;
    }

    /**
     * resetter for label
     */
    function resetLabel() {
        $this->label = NULL;
    }

    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of multipleChoiceQuestion
     */
     function getMultipleChoiceQuestionAt($i) {
        if (array_key_exists($i, $this->multipleChoiceQuestion))
            return $this->multipleChoiceQuestion[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of multipleChoiceQuestion
     */
    function setMultipleChoiceQuestionAt($i, $_multipleChoiceQuestion) {
        $this->multipleChoiceQuestion[$i] = $_multipleChoiceQuestion;
    }

    /**
     * add to multipleChoiceQuestion
     */
    function addMultipleChoiceQuestion($_multipleChoiceQuestion) {
        if (is_array($this->multipleChoiceQuestion))
            $this->setMultipleChoiceQuestionAt($this->nextChoiceIndex(), $_multipleChoiceQuestion);
        else {
            $this->multipleChoiceQuestion = array();
            $this->addMultipleChoiceQuestion($_multipleChoiceQuestion);
        }
    }

    /**
     * remove the ith element of multipleChoiceQuestion
     */
    function removeMultipleChoiceQuestionAt($i) {
        if (array_key_exists($i, $this->multipleChoiceQuestion))
            unset($this->multipleChoiceQuestion[$i]);
    }

    /**
     * get the ith element of inputQuestion
     */
     function getInputQuestionAt($i) {
        if (array_key_exists($i, $this->inputQuestion))
            return $this->inputQuestion[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of inputQuestion
     */
    function setInputQuestionAt($i, $_inputQuestion) {
        $this->inputQuestion[$i] = $_inputQuestion;
    }

    /**
     * add to inputQuestion
     */
    function addInputQuestion($_inputQuestion) {
        if (is_array($this->inputQuestion))
            $this->setInputQuestionAt($this->nextChoiceIndex(), $_inputQuestion);
        else {
            $this->inputQuestion = array();
            $this->addInputQuestion($_inputQuestion);
        }
    }

    /**
     * remove the ith element of inputQuestion
     */
     function removeInputQuestionAt($i) {
        if (array_key_exists($i, $this->inputQuestion))
            unset($this->inputQuestion[$i]);
    }

    /**
     * returns the next index to use for choices
     */
     private function nextChoiceIndex() {
        $index = 0;
        if (is_array($this->multipleChoiceQuestion) && count($this->multipleChoiceQuestion) > 0) {
            $maxIndex = max(array_keys($this->multipleChoiceQuestion));
            if ($maxIndex > $index) $index = $maxIndex;
        }
        if (is_array($this->inputQuestion) && count($this->inputQuestion) > 0) {
            $maxIndex = max(array_keys($this->inputQuestion));
            if ($maxIndex > $index) $index = $maxIndex;
        }

        return ++$index;
    }

    /******************** validator methods ********************/

    /**
     * validator for class questions
     */
    function validate() {
        // multipleChoiceQuestion must occur zero or more times
        if (!is_null($this->multipleChoiceQuestion)) {
            if ($this->isArrayOfInstanceOf($this->multipleChoiceQuestion, 'multipleChoiceQuestion') === false)
                return false;
            foreach ($this->multipleChoiceQuestion as $index => $multipleChoiceQuestion) {
                if ($multipleChoiceQuestion->validate() === false) {
                    $this->error = __CLASS__ . '.' . $multipleChoiceQuestion->getError();
                    $this->error = str_replace('multipleChoiceQuestion', "multipleChoiceQuestion[$index]", $this->error);
                    return false;
                }
            }
        }

        // inputQuestion must occur zero or more times
        if (!is_null($this->inputQuestion)) {
            if ($this->isArrayOfInstanceOf($this->inputQuestion, 'inputQuestion') === false)
                return false;
            foreach ($this->inputQuestion as $index => $inputQuestion) {
                if ($inputQuestion->validate() === false) {
                    $this->error = __CLASS__ . '.' . $inputQuestion->getError();
                    $this->error = str_replace('inputQuestion', "inputQuestion[$index]", $this->error);
                    return false;
                }
            }
        }

        // contentListRef must occur zero or one times
        if (!is_null($this->contentListRef)) {
            if ($this->isNoneEmptyString($this->contentListRef, 'contentListRef') === false)
                return false;
        }

        // label must occur zero or more times
        if (!is_null($this->label)) {
            if ($this->isInstanceOf($this->label, 'label') === false)
                return false;
            if ($this->label->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->label->getError();
                return false;
            }
        }

        // check that neither contentListRef nor label is set when multipleChoiceQuestion is set
        if (!is_null($this->multipleChoiceQuestion)) {
            if (!is_null($this->contentListRef)) {
                $this->error = __CLASS__ . ".contentListRef can not be set when multipleChoiceQuestion is set";
                return false;
            }
        }

        // check that neither contentListRef nor label is set when inputQuestion is set
        if (!is_null($this->inputQuestion)) {
            if (!is_null($this->contentListRef)) {
                $this->error = __CLASS__ . ".contentListRef can not be set when inputQuestion is set";
                return false;
            }
        }

        // check that either multipleChoiceQuestion, inputQuestion, contentListRef or label is set
        if (is_null($this->multipleChoiceQuestion) && is_null($this->inputQuestion) && is_null($this->contentListRef) && is_null($this->label)) {
            $this->error = __CLASS__ . " no required element set";
            return false;
        }

        return true;
    }
}

?>
