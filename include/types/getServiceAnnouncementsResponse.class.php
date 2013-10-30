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

require_once('announcements.class.php');

class getServiceAnnouncementsResponse extends AbstractType {

    /**
     * @var (object)announcements
     */
    public $announcements;


    /******************** public functions ********************/

    /**
     * constructor for class getServiceAnnouncementsResponse
     */
    function __construct($_announcements = NULL) {
        if (is_a($_announcements, "announcements")) $this->setAnnouncements($_announcements);
    }


    /******************** class get set methods ********************/

    /**
     * getter for announcements
     */
    function getAnnouncements() {
        return $this->announcements;
    }

    /**
     * setter for announcements
     */
    function setAnnouncements($_announcements) {
        $this->announcements = $_announcements;
    }

    /**
     * resetter for announcements
     */
    function resetAnnouncements() {
        $this->announcements = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getServiceAnnouncementsResponse
     */
    function validate() {
        // announcements must occur exactly once
        if ($this->isInstanceOf($this->announcements, 'announcements') === false)
            return false;
        if ($this->announcements->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->announcements->getError();
            return false;
        }

        return true;
    }
}

?>
