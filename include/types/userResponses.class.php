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

require_once('userResponse.class.php');

class userResponses extends AbstractType {

    /**
     * @var array[1, unbounded] of (object)userResponse
     */
    public $userResponse;


    /******************** public functions ********************/

    /**
     * constructor for class userResponses
     */
    function __construct($_userResponse = NULL) {
        if (is_array($_userResponse)) $this->setUserResponse($_userResponse);
    }


    /******************** class get set methods ********************/

    /**
     * getter for userResponse
     */
    function getUserResponse() {
        return $this->userResponse;
    }

    /**
     * setter for userResponse
     */
    function setUserResponse($_userResponse) {
        $this->userResponse = $_userResponse;
    }

    /**
     * resetter for userResponse
     */
    function resetUserResponse() {
        $this->userResponse = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of userResponse
     */
    function getUserResponseAt($i) {
        if ($this->sizeofUserResponse() > $i)
            return $this->userResponse[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of userResponse
     */
    function setUserResponseAt($i, $_userResponse) {
        $this->userResponse[$i] = $_userResponse;
    }

    /**
     * add to userResponse
     */
    function addUserResponse($_userResponse) {
        if (is_array($this->userResponse))
            array_push($this->userResponse, $_userResponse);
        else {
            $this->userResponse = array();
            $this->addUserResponse($_userResponse);
        }
    }

    /**
     * get the size of the userResponse array
     */
    function sizeofUserResponse() {
        return sizeof($this->userResponse);
    }

    /**
     * remove the ith element of userResponse
     */
    function removeUserResponseAt($i) {
        if ($this->sizeofUserResponse() > $i)
            unset($this->userResponse[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class userResponses
     */
    function validate() {
        // userResponse must occur one or more times
        if ($this->isNoneEmptyArray($this->userResponse, 'userResponse') === false)
            return false;
        if ($this->isArrayOfInstanceOf($this->userResponse, 'userResponse') === false)
            return false;
        foreach ($this->userResponse as $index => $userResponse) {
            if ($userResponse->validate() === false) {
                $this->error = __CLASS__ . '.' . $userResponse->getError();
                $this->error = str_replace('userResponse', "userResponse[$index]");
                return false;
            }
        }

        // check that questionID is not equal to 'default' or 'search' or 'back' when
        // size of userResponse is greater than 1
        if ($this->sizeofUserResponse() > 1) {
            foreach ($this->userResponse as $index => $userResponse) {
                $reservedValues = array('default', 'search', 'back');
                if (in_array($userResponse->getQuestionID(), $reservedValues)) {
                    $this->error = __CLASS__ . ".userResponse[$index].questionID can not be a reserved value when the request contains two or more userResponses";
                    return false;
                }
            }
        }

        return true;
    }
}

?>
