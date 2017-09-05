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

    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of bookmark
     */
    function getBookmarkAt($i) {
        if (array_key_exists($i, $this->bookmark))
            return $this->bookmark[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of bookmark
     */
    function setBookmarkAt($i, $_bookmark) {
        $this->bookmark[$i] = $_bookmark;
    }

    /**
     * add to bookmark
     */
    function addBookmark($_bookmark) {
        if (is_array($this->bookmark))
            $this->setBookmarkAt($this->nextChoiceIndex(), $_bookmark);
        else {
            $this->bookmark = array();
            $this->addBookmark($_bookmark);
        }
    }

    /**
     * remove the ith element of bookmark
     */
    function removeBookmarkAt($i) {
        if (array_key_exists($i, $this->bookmark))
            unset($this->bookmark[$i]);
    }

    /**
     * add to bookmark unless object it exist
     */
    function addBookmarkUnlessExist($_bookmark) {
        if (is_array($this->bookmark)) {
            $objectExists = false;
            foreach ($this->bookmark as $bookmark) {
                if ($bookmark == $_bookmark) {
                    $objectExists = true;
                    break;
                }
            }
            if (!$objectExists) {
                $this->setBookmarkAt($this->nextChoiceIndex(), $_bookmark);
                return true;
            }
        }
        else {
            $this->bookmark = array();
            $this->addBookmark($_bookmark);
            return true;
        }

        return false;
    }

    /**
     * remove from bookmark if object exists
     */
    function removeBookmarkIfExist($_bookmark) {
        if (is_array($this->bookmark)) {
            $objectExists = false;
            $i = 0;
            foreach ($this->bookmark as $index => $bookmark) {
                if ($bookmark == $_bookmark) {
                    $objectExists = true;
                    $i = $index;
                    break;
                }
            }
            if ($objectExists) {
                $this->removeBookmarkAt($i);
                return true;
            }
        }

        return false;
    }

    /**
     * get the ith element of hilite
     */
    function getHiliteAt($i) {
        if (array_key_exists($i, $this->hilite))
            return $this->hilite[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of hilite
     */
    function setHiliteAt($i, $_hilite) {
        $this->hilite[$i] = $_hilite;
    }

    /**
     * add to hilite
     */
    function addHilite($_hilite) {
        if (is_array($this->hilite))
            $this->setHiliteAt($this->nextChoiceIndex(), $_hilite);
        else {
            $this->hilite = array();
            $this->addHilite($_hilite);
        }
    }

    /**
     * remove the ith element of hilite
     */
    function removeHiliteAt($i) {
        if (array_key_exists($i, $this->hilite))
            unset($this->hilite[$i]);
    }

    /**
     * add to hilite unless object it exist
     */
    function addHiliteUnlessExist($_hilite) {
        if (is_array($this->hilite)) {
            $objectExists = false;
            foreach ($this->hilite as $hilite) {
                if ($hilite == $_hilite) {
                    $objectExists = true;
                    break;
                }
            }
            if (!$objectExists) {
                $this->setHiliteAt($this->nextChoiceIndex(), $_hilite);
                return true;
            }
        }
        else {
            $this->hilite = array();
            $this->addHilite($_hilite);
            return true;
        }

        return false;
    }

    /**
     * remove from hilite if object exists
     */
    function removeHiliteIfExist($_hilite) {
        if (is_array($this->hilite)) {
            $objectExists = false;
            $i = 0;
            foreach ($this->hilite as $index => $hilite) {
                if ($hilite == $_hilite) {
                    $objectExists = true;
                    $i = $index;
                    break;
                }
            }
            if ($objectExists) {
                $this->removeHiliteAt($i);
                return true;
            }
        }

        return false;
    }

    /**
     * returns the next index to use for choices
     */
    private function nextChoiceIndex() {
        $index = 0;
        if (is_array($this->bookmark) && count($this->bookmark) > 0) {
            $maxIndex = max(array_keys($this->bookmark));
            if ($maxIndex > $index) $index = $maxIndex;
        }
        if (is_array($this->hilite) && count($this->hilite) > 0) {
            $maxIndex = max(array_keys($this->hilite));
            if ($maxIndex > $index) $index = $maxIndex;
        }

        return ++$index;
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
                    $this->error = str_replace('bookmark', "bookmark[$index]", $this->error);
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
                    $this->error = str_replace('hilite', "hilite[$index]", $this->error);
                    return false;
                }
            }
        }

        return true;
    }
}

?>
