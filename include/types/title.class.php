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

require_once('bookmarkAudio.class.php');

class title extends AbstractType {

    /**
     * @var string
     */
    public $text;

    /**
     * @var (object)bookmarkAudio
     */
    public $audio;


    /******************** public functions ********************/

    /**
     * constructor for class title
     */
    function __construct($_text = NULL, $_audio = NULL) {
        if (is_string($_text)) $this->setText($_text);
        if (is_a($_audio, "bookmarkAudio")) $this->setAudio($_audio);
    }


    /******************** class get set methods ********************/

    /**
     * getter for text
     */
    function getText() {
        return $this->text;
    }

    /**
     * setter for text
     */
    function setText($_text) {
        $this->text = $_text;
    }

    /**
     * resetter for text
     */
    function resetText() {
        $this->text = NULL;
    }

    /**
     * getter for audio
     */
    function getAudio() {
        return $this->audio;
    }

    /**
     * setter for audio
     */
    function setAudio($_audio) {
        $this->audio = $_audio;
    }

    /**
     * resetter for audio
     */
    function resetAudio() {
        $this->audio = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class title
     */
    function validate() {
        // text must occur exactly once
        if ($this->isNoneEmptyString($this->text, 'text') === false)
            return false;

        // audio must occur zero or one times
        if (!is_null($this->audio)) {
            if ($this->isInstanceOf($this->audio, 'audio', 'bookmarkAudio') === false)
                return false;
            if ($this->audio->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->audio->getError();
                return false;
            }
        }

        return true;;
    }
}

?>
