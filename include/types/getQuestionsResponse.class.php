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

require_once('questions.class.php');

class getQuestionsResponse extends AbstractType {

    /**
     * @var (object)questions
     */
    public $questions;


    /******************** public functions ********************/

    /**
     * constructor for class getQuestionsResponse
     */
    function __construct($_questions = NULL) {
        if (is_a($_questions, "questions")) $this->setQuestions($_questions);
    }


    /******************** class get set methods ********************/

    /**
     * getter for questions
     */
    function getQuestions() {
        return $this->questions;
    }

    /**
     * setter for questions
     */
    function setQuestions($_questions) {
        $this->questions = $_questions;
    }

    /**
     * resetter for questions
     */
    function resetQuestions() {
        $this->questions = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class getQuestionsResponse
     */
    function validate() {
        // questions must occur exactly once
        if ($this->isInstanceOf($this->questions, 'questions') === false)
            return false;
        if ($this->questions->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->questions->getError();
            return false;
        }

        return true;
    }
}

?>
