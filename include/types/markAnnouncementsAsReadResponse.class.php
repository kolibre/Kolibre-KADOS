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

class markAnnouncementsAsReadResponse extends AbstractType {

    /**
     * @var boolean
     */
    public $markAnnouncementsAsReadResult;


    /******************** public functions ********************/

    /**
     * constructor for class markAnnouncementsAsReadResponse
     */
    function __construct($_markAnnouncementsAsReadResult = NULL) {
        if (is_bool($_markAnnouncementsAsReadResult)) $this->setMarkAnnouncementsAsReadResult($_markAnnouncementsAsReadResult);
    }


    /******************** class get set methods ********************/

    /**
     * getter for markAnnouncementsAsReadResult
     */
    function getMarkAnnouncementsAsReadResult() {
        return $this->markAnnouncementsAsReadResult;
    }

    /**
     * setter for markAnnouncementsAsReadResult
     */
    function setMarkAnnouncementsAsReadResult($_markAnnouncementsAsReadResult) {
        $this->markAnnouncementsAsReadResult = $_markAnnouncementsAsReadResult;
    }

    /**
     * resetter for markAnnouncementsAsReadResult
     */
    function resetMarkAnnouncementsAsReadResult() {
        $this->markAnnouncementsAsReadResult = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class markAnnouncementsAsReadResponse
     */
    function validate() {
        // markAnnouncementsAsReadResult must occur exactly once
        if ($this->isBoolean($this->markAnnouncementsAsReadResult, 'markAnnouncementsAsReadResult') === false)
            return false;

        return true;
    }
}

?>
