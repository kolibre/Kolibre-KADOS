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

require_once('title.class.php');
require_once('lastmark.class.php');
require_once('bookmark.class.php');
require_once('hilite.class.php');

class bookmarkSet extends AbstractType {

    /**
     * @var (object)title
     */
    public $title;

    /**
     * @var string
     */
    public $uid;

    /**
     * @var (object)lastmark
     */
    public $lastmark;

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var (object)bookmark
     */
    public $bookmark;

    /**
     * @var (object)hilite
     */
    public $hilite;
    // ----------------End Choice---------------


    /******************** public functions ********************/

    /**
     * constructor for class bookmarkSet
     */
    function __construct($_title = NULL, $_uid = NULL, $_lastmark = NULL) {
        if (is_a($_title, "title")) $this->setTitle($_title);
        if (is_string($_uid)) $this->setUid($_uid);
        if (is_a($_lastmark, "lastmark")) $this->setLastmark($_lastmark);
    }


    /******************** class get set methods ********************/

    /**
     * getter for title
     */
    function getTitle() {
        return $this->title;
    }

    /**
     * setter for title
     */
    function setTitle($_title) {
        $this->title = $_title;
    }

    /**
     * resetter for title
     */
    function resetTitle() {
        $this->title = NULL;
    }

    /**
     * getter for uid
     */
    function getUid() {
        return $this->uid;
    }

    /**
     * setter for uid
     */
    function setUid($_uid) {
        $this->uid = $_uid;
    }

    /**
     * resetter for uid
     */
    function resetUid() {
        $this->uid = NULL;
    }

    /**
     * getter for lastmark
     */
    function getLastmark() {
        return $this->lastmark;
    }

    /**
     * setter for lastmark
     */
    function setLastmark($_lastmark) {
        $this->lastmark = $_lastmark;
    }

    /**
     * resetter for lastmark
     */
    function resetLastmark() {
        $this->lastmark = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class bookmarkSet
     */
    function validate() {
        // title must occur exactly once
        if ($this->isInstanceOf($this->title, 'title') === false)
            return false;
        if ($this->title->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->title->getError();
            return false;
        }

        // uid must occur exactly once
        if ($this->isNoneEmptyString($this->uid, 'uid') === false)
            return false;

        // lastmark must occur zero or one times
        if (!is_null($this->lastmark)) {
            if ($this->isInstanceOf($this->lastmark, 'lastmark') === false)
                return false;
            if ($this->lastmark->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->lastmark->getError();
                return false;
            }
        }

        // bookmark must occur zero or more times
        if (!is_null($this->bookmark)) {
            if ($this->isArrayOfInstanceOf($this->bookmark, 'bookmark') === false)
                return false;
            foreach ($this->bookmark as $index => $bookmark) {
                if ($bookmark->validate() === false) {
                    $this->error = __CLASS__ . '.' . $bookmark->getError();
                    $this->error = str_replace('bookmark', "bookmark[$index]");
                    return false;
                }
            }
        }

        // hilite must occur zero or more times
        if (!is_null($this->hilite)) {
            if ($this->isArrayOfInstanceOf($this->hilite, 'hilite') === false)
                return false;
            foreach ($this->hilite as $index => $hilite) {
                if ($hilite->validate() === false) {
                    $this->error = __CLASS__ . '.' . $hilite->getError();
                    $this->error = str_replace('hilite', "hilite[$index]");
                    return false;
                }
            }
        }

        return true;
    }
}

?>
