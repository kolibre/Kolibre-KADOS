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

require_once('contentList.class.php');

class getContentListResponse extends AbstractType {

    /**
     * @var (object)contentList
     */
    public $contentList;


    /******************** public functions ********************/

    /**
     * constructor for class getContentListResponse
     */
    function __construct($_contentList = NULL) {
        if (is_a($_contentList, "contentList")) $this->setContentList($_contentList);
    }

    /******************** class get set methods ********************/

    /**
     * getter for contentList
     */
    function getContentList() {
        return $this->contentList;
    }

    /**
     * setter for contentList
     */
    function setContentList($_contentList) {
        $this->contentList = $_contentList;
    }

    /**
     * resetter for contentList
     */
    function resetContentList() {
        $this->contentList = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class getContentListResponse
     */
    function validate() {
        // contentList must occur exactly once
        if ($this->isInstanceOf($this->contentList, 'contentList') === false)
            return false;
        if ($this->contentList->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->contentList->getError();
            return false;
        }

        return true;
    }
}

?>
