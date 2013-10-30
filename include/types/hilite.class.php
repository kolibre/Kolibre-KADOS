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

require_once('hiliteStart.class.php');
require_once('hiliteEnd.class.php');
require_once('note.class.php');

class hilite extends AbstractType {

    /**
     * @var (object)hiliteStart
     */
    public $hiliteStart;

    /**
     * @var (object)hiliteEnd
     */
    public $hiliteEnd;

    /**
     * @var (object)note
     */
    public $note;

    /**
     * @var string
     */
    public $label;


    /******************** public functions ********************/

    /**
     * constructor for class hilite
     */
    function __construct($_hiliteStart = NULL, $_hiliteEnd = NULL, $_note = NULL, $_label = NULL) {
        if (is_a($_hiliteStart, "hiliteStart")) $this->setHiliteStart($_hiliteStart);
        if (is_a($_hiliteEnd, "hiliteEnd")) $this->setHiliteEnd($_hiliteEnd);
        if (is_a($_note, "note")) $this->setNote($_note);
        if (is_string($_label)) $this->setLabel($_label);
    }


    /******************** class get set methods ********************/

    /**
     * getter for hiliteStart
     */
    function getHiliteStart() {
        return $this->hiliteStart;
    }

    /**
     * setter for hiliteStart
     */
    function setHiliteStart($_hiliteStart) {
        $this->hiliteStart = $_hiliteStart;
    }

    /**
     * resetter for hiliteStart
     */
    function resetHiliteStart() {
        $this->hiliteStart = NULL;
    }

    /**
     * getter for hiliteEnd
     */
    function getHiliteEnd() {
        return $this->hiliteEnd;
    }

    /**
     * setter for hiliteEnd
     */
    function setHiliteEnd($_hiliteEnd) {
        $this->hiliteEnd = $_hiliteEnd;
    }

    /**
     * resetter for hiliteEnd
     */
    function resetHiliteEnd() {
        $this->hiliteEnd = NULL;
    }

    /**
     * getter for note
     */
    function getNote() {
        return $this->note;
    }

    /**
     * setter for note
     */
    function setNote($_note) {
        $this->note = $_note;
    }

    /**
     * resetter for note
     */
    function resetNote() {
        $this->note = NULL;
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


    /******************** validator methods ********************/

    /**
     * validator for class hilite
     */
    function validate() {
        // hiliteStart must occur exactly once
        if ($this->isInstanceOf($this->hiliteStart, 'hiliteStart') === false)
            return false;
        if ($this->hiliteStart->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->hiliteStart->getError();
            return false;
        }

        // hiliteEnd must occur exactly once
        if ($this->isInstanceOf($this->hiliteEnd, 'hiliteEnd') === false)
            return false;
        if ($this->hiliteEnd->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->hiliteEnd->getError();
            return false;
        }

        // note must occur exactly once
        if (!is_null($this->note)) {
            if ($this->isInstanceOf($this->note, 'note') === false)
                return false;
            if ($this->note->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->note->getError();
                return false;
            }
        }

        // label must occur exactly once
        if (!is_null($this->label)) {
            if ($this->isNoneEmptyString($this->label, 'label') === false)
                return false;
        }

        return true;
    }

}

?>
