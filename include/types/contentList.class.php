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

require_once('label.class.php');
require_once('contentItem.class.php');

class contentList extends AbstractType {

    /**
     * @var (object)label
     */
    public $label;

    /**
     * @var array[0, unbounded] of (object)contentItem
     */
    public $contentItem;

    /**
     * @var int
     */
    public $totalItems;

    /**
     * @var int
     */
    public $firstItem;

    /**
     * @var int
     */
    public $lastItem;

    /**
     * @var NMTOKEN
     */
    public $id;


    /******************** public functions ********************/

    /**
     * constructor for class contentList
     */
    function __construct($_label = NULL, $_contentItem = NULL, $_totalItems = NULL, $_firstItem = NULL, $_lastItem = NULL, $_id = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_array($_contentItem)) $this->setContentItem($_contentItem);
        if (is_int($_totalItems)) $this->setTotalItems($_totalItems);
        if (is_int($_firstItem)) $this->setFirstItem($_firstItem);
        if (is_int($_lastItem)) $this->setLastItem($_lastItem);
        if (is_string($_id)) $this->setId($_id);
    }


    /******************** class get set methods ********************/

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

    /**
     * getter for contentItem
     */
    function getContentItem() {
        return $this->contentItem;
    }

    /**
     * setter for contentItem
     */
    function setContentItem($_contentItem) {
        $this->contentItem = $_contentItem;
    }

    /**
     * resetter for contentItem
     */
    function resetContentItem() {
        $this->contentItem = NULL;
    }

    /**
     * getter for totalItems
     */
    function getTotalItems() {
        return $this->totalItems;
    }

    /**
     * setter for totalItems
     */
    function setTotalItems($_totalItems) {
        $this->totalItems = $_totalItems;
    }

    /**
     * resetter for totalItems
     */
    function resetTotalItems() {
        $this->totalItems = NULL;
    }

    /**
     * getter for firstItem
     */
    function getFirstItem() {
        return $this->firstItem;
    }

    /**
     * setter for firstItem
     */
    function setFirstItem($_firstItem) {
        $this->firstItem = $_firstItem;
    }

    /**
     * resetter for firstItem
     */
    function resetFirstItem() {
        $this->firstItem = NULL;
    }

    /**
     * getter for lastItem
     */
    function getLastItem() {
        return $this->lastItem;
    }

    /**
     * setter for lastItem
     */
    function setLastItem($_lastItem) {
        $this->lastItem = $_lastItem;
    }

    /**
     * resetter for lastItem
     */
    function resetLastItem() {
        $this->lastItem = NULL;
    }

    /**
     * getter for id
     */
    function getId() {
        return $this->id;
    }

    /**
     * setter for id
     */
    function setId($_id) {
        $this->id = $_id;
    }

    /**
     * resetter for id
     */
    function resetId() {
        $this->id = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of contentItem
     */
    function getContentItemAt($i) {
        if ($this->sizeofContentItem() > $i)
            return $this->contentItem[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of contentItem
     */
    function setContentItemAt($i, $_contentItem) {
        $this->contentItem[$i] = $_contentItem;
    }

    /**
     * add to contentItem
     */
    function addContentItem($_contentItem) {
        if (is_array($this->contentItem))
            array_push($this->contentItem, $_contentItem);
        else {
            $this->contentItem = array();
            $this->addContentItem($_contentItem);
        }
    }

    /**
     * get the size of the contentItem array
     */
    function sizeofContentItem() {
        return sizeof($this->contentItem);
    }

    /**
     * remove the ith element of contentItem
     */
    function removeContentItemAt($i) {
        if ($this->sizeofContentItem() > $i)
            unset($this->contentItem[$i]);
    }

    /******************** validator methods ********************/

    /**
     * validator for class contentList
     */
    function validate() {
        // label must occur zero or one times
        if (!is_null($this->label)) {
            if ($this->isInstanceOf($this->label, 'label') === false)
                return false;
            if ($this->label->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->label->getError();
                return false;
            }
        }

        // contentItem must occur zero or more times
        if (!is_null($this->contentItem)) {
            if ($this->isArrayOfInstanceOf($this->contentItem, 'contentItem') === false)
                return false;
            foreach ($this->contentItem as $index => $contentItem) {
                if ($contentItem->validate() === false) {
                    $this->error = __CLASS__ . '.' . $contentItem->getError();
                    $this->error = str_replace('contentItem', "contentItem[$index]", $this->error);
                    return false;
                }
            }
        }

        // attribute totalItems is required
        if ($this->isPositiveInteger($this->totalItems, 'totalItems') === false)
            return false;

        // attribute firstItem is optional
        if (!is_null($this->firstItem)) {
            if ($this->isPositiveInteger($this->firstItem, 'firstItem') === false)
                return false;
        }

        // attribute lastItem is optional
        if (!is_null($this->lastItem)) {
            if ($this->isPositiveInteger($this->lastItem, 'lastItem') === false)
                return false;
        }

        // attribute id is required
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;

        // check precense of lastItem if firstItem is set
        if (!is_null($this->firstItem)) {
            if (is_null($this->lastItem)) {
                $this->error = __CLASS__ . ".lastItem must not be null when " . __CLASS__ . ".firstItem is set";
                return false;
            }
        }

        // check precense of firstItem if lastItem is set
        if (!is_null($this->lastItem)) {
            if (is_null($this->firstItem)) {
                $this->error = __CLASS__ . ".firstItem must not be null when " . __CLASS__ . ".lastItem is set";
                return false;
            }
        }

        // check value of firstItem and lastItem if they are present
        if (!is_null($this->firstItem) && !is_null($this->lastItem)) {
            // lastItem must be greater than or equal to firstItem
            if ($this->lastItem < $this->firstItem) {
                $this->error = __CLASS__ . ".lastItem must be greater than or equal to " . __CLASS__ . ".firstItem";
                return false;
            }
        }

        return true;
    }
}

?>
