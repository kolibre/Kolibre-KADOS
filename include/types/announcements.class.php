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

require_once('announcement.class.php');

class announcements extends AbstractType {

    /**
     * @var array[0, unbounded] of (object)announcement
     */
    public $announcement;


    /******************** public functions ********************/

    /**
     * constructor for class announcements
     */
    function __construct($_announcement = NULL) {
        if (is_array($_announcement)) $this->setAnnouncement($_announcement);
    }


    /******************** class get set methods ********************/

    /**
     * getter for announcement
     */
    function getAnnouncement() {
        return $this->announcement;
    }

    /**
     * setter for announcement
     */
    function setAnnouncement($_announcement) {
        $this->announcement = $_announcement;
    }

    /**
     * resetter for announcement
     */
    function resetAnnouncement() {
        $this->announcement = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of announcement
     */
    function getAnnouncementAt($i) {
        if ($this->sizeofAnnouncement() > $i)
            return $this->announcement[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of announcement
     */
    function setAnnouncementAt($i, $_announcement) {
        $this->announcement[$i] = $_announcement;
    }

    /**
     * add to announcement
     */
    function addAnnouncement($_announcement) {
        if (is_array($this->announcement))
            array_push($this->announcement, $_announcement);
        else {
            $this->announcement = array();
            $this->addAnnouncement($_announcement);
        }
    }

    /**
     * get the size of the announcement array
     */
    function sizeofAnnouncement() {
        return sizeof($this->announcement);
    }

    /**
     * remove the ith element of announcement
     */
    function removeAnnouncementAt($i) {
        if ($this->sizeofAnnouncement() > $i)
            unset($this->announcement[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class announcements
     */
    function validate() {
        // announcement must occur zero or more times
        if (!is_null($this->announcement)) {
            if ($this->isArrayOfInstanceOf($this->announcement, 'announcement') === false)
                return false;
            foreach ($this->announcement as $index => $announcement) {
                if ($announcement->validate() === false) {
                    $this->error = __CLASS__ . '.' . $announcement->getError();
                    $this->error = str_replace('announcement', "announcement[$index]");
                    return false;
                }
            }
        }

        return true;
    }

}

?>
