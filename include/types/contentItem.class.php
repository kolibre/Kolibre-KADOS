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
require_once('sample.class.php');
require_once('metadata.class.php');
require_once('categoryLabel.class.php');
require_once('subCategoryLabel.class.php');
require_once('lastmark.class.php');
require_once('multipleChoiceQuestion.class.php');

class contentItem extends AbstractType {

    /**
     * @var (object)label
     */
    public $label;

    /**
     * @var optional (object) sample
     */
    public $sample;

    /**
     * @var (object) metadata
     */
    public $metadata;

    /**
     * @var optional (object) categoryLabel
     */
    public $categoryLabel;

    /**
     * @var optional (object) subCategoryLabel
     */
    public $subCategoryLabel;

    /**
     * @var string accessPermission
     */
    public $accessPermission;

    /**
     * @var optional (object) lastmark
     */
    public $lastmark;

    /**
     * @var optional (object) multiplChoiceQuestion
     */
    public $multipleChoiceQuestion;

    /**
     * @var string
     */
    public $id;

    /**
     * @var optional string
     */
    public $firstAccessedDate;

    /**
     * @var optional string
     */
    public $lastAccessedDate;

    /**
     * @var string
     */
    public $lastModifiedDate;

    /**
     * @var optional string
     */
    public $category;

        /**
     * @var optional string
     */
    public $subCategory;

    /**
     * @var optional string
     */
    public $returnBy;

    /**
     * @var bool
     */
    public $hasBookmarks;

    /******************** public functions ********************/

    /**
     * constructor for class contentItem
     */
    function __construct($_label = NULL, $_sample = NULL, $_metadata = NULL, $_categoryLabel = NULL, $_subCategoryLabel = NULL, $_accessPermission = NULL, $_lastmark = NULL, $_multipleChoiceQuestion = NULL, $_id = NULL, $_firstAccessedDate = NULL, $_lastAccessedDate = NULL, $_lastModifiedDate = NULL, $_category = NULL, $_subCategory = NULL, $_returnBy = NULL, $_hasBookmarks = NULL) {
        if (is_a($_label, "label")) $this->setLabel($_label);
        if (is_a($_sample, "sample")) $this->setSample($_sample);
        if (is_a($_metadata, "metadata")) $this->setMetadata($_metadata);
        if (is_a($_categoryLabel, "categoryLabel")) $this->setCategoryLabel($_categoryLabel);
        if (is_a($_subCategoryLabel, "subCategoryLabel")) $this->setSubCategoryLabel($_subCategoryLabel);
        if (is_string($_accessPermission)) $this->setAccessPermission($_accessPermission);
        if (is_a($_lastmark, "lastmark")) $this->setLastmark($_lastmark);
        if (is_a($_multipleChoiceQuestion, "multipleChoiceQuestion")) $this->setMultipleChoiceQuestion($_multipleChoiceQuestion);
        if (is_string($_id)) $this->setId($_id);
        if (is_string($_firstAccessedDate)) $this->setFirstAccessedDate($_firstAccessedDate);
        if (is_string($_lastAccessedDate)) $this->setLastAccessedDate($_lastAccessedDate);
        if (is_string($_lastModifiedDate)) $this->setLastModifiedDate($_lastModifiedDate);
        if (is_string($_category)) $this->setCategory($_category);
        if (is_string($_subCategory)) $this->setSubCategory($_subCategory);
        if (is_string($_returnBy)) $this->setReturnBy($_returnBy);
        if (is_bool($_hasBookmarks)) $this->setHasBookmarks($_hasBookmarks);
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
     * getter for sample
     */
    function getSample() {
        return $this->sample;
    }

    /**
     * setter for sample
     */
    function setSample($_sample) {
        $this->sample = $_sample;
    }

    /**
     * resetter for sample
     */
    function resetSample() {
        $this->sample = NULL;
    }

    /**
     * getter for sample
     */
    function getMetadata() {
        return $this->metadata;
    }

    /**
     * setter for metadata
     */
    function setMetadata($_metadata) {
        $this->metadata = $_metadata;
    }

    /**
     * resetter for metadata
     */
    function resetMetadata() {
        $this->metadata = NULL;
    }


    /**
     * getter for category
     */
    function getCategoryLabel() {
        return $this->categoryLabel;
    }

    /**
     * setter for categoryLabel
     */
    function setCategoryLabel($_categoryLabel) {
        $this->categoryLabel = $_categoryLabel;
    }

    /**
     * resetter for categoryLabel
     */
    function resetCategoryLabel() {
        $this->categoryLabel = NULL;
    }

    /**
     * getter for subCategory
     */
    function getSubCategoryLabel() {
        return $this->subCategoryLabel;
    }

    /**
     * setter for subCategoryLabel
     */
    function setSubCategoryLabel($_subCategoryLabel) {
        $this->subCategoryLabel = $_subCategoryLabel;
    }

    /**
     * resetter for subCategoryLabel
     */
    function resetSubCategoryLabel() {
        $this->subCategoryLabel = NULL;
    }

    /**
     * getter for accessPermission
     */
    function getAccessPermission() {
        return $this->accessPermission;
    }

    /**
     * setter for accessPermission
     */
    function setAccessPermission($_accessPermission) {
        $this->accessPermission = $_accessPermission;
    }

    /**
     * resetter for accessPermission
     */
    function resetAccessPermission() {
        $this->accessPermission = NULL;
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

    /**
     * getter for MultipleChoiceQuestion
     */
    function getMultipleChoiceQuestion() {
        return $this->multipleChoiceQuestion;
    }

    /**
     * setter for multipleChoiceQuestion
     */
    function setMultipleChoiceQuestion($_multipleChoiceQuestion) {
        $this->multipleChoiceQuestion = $_multipleChoiceQuestion;
    }

    /**
     * resetter for multipleChoiceQuestion
     */
    function resetMultipleChoiceQuestion() {
        $this->multipleChoiceQuestion = NULL;
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

    /**
     * getter for firstAccessedDate
     */
    function getFirstAccessedDate() {
        return $this->firstAccessedDate;
    }

    /**
     * setter for firstAccessedDate
     */
    function setFirstAccessedDate($_firstAccessedDate) {
        $this->firstAccessedDate = $_firstAccessedDate;
    }

    /**
     * resetter for firstAccessedDate
     */
    function resetFirstAccessedDate() {
        $this->firstAccessedDate = NULL;
    }

    /**
     * getter for lastAccessedDate
     */
    function getLastAccessedDate() {
        return $this->lastAccessedDate;
    }

    /**
     * setter for lastAccessedDate
     */
    function setLastAccessedDate($_lastAccessedDate) {
        $this->lastAccessedDate = $_lastAccessedDate;
    }

    /**
     * resetter for lastAccessedDate
     */
    function resetLastAccessedDate() {
        $this->lastAccessedDate = NULL;
    }

    /**
     * getter for lastModifiedDate
     */
    function getLastModifiedDate() {
        return $this->lastModifiedDate;
    }

    /**
     * setter for lastModifiedDate
     */
    function setLastModifiedDate($_lastModifiedDate) {
        $this->lastModifiedDate = $_lastModifiedDate;
    }

    /**
     * resetter for lastModifiedDate
     */
    function resetLastModifiedDate() {
        $this->lastModifiedDate = NULL;
    }

    /**
     * getter for category
     */
    function getCategory() {
        return $this->category;
    }

    /**
     * setter for category
     */
    function setCategory($_category) {
        $this->category = $_category;
    }

    /**
     * resetter for category
     */
    function resetCategory() {
        $this->category = NULL;
    }

    /**
     * getter for subCategory
     */
    function getSubCategory() {
        return $this->subCategory;
    }

    /**
     * setter for subCategory
     */
    function setSubCategory($_subCategory) {
        $this->subCategory = $_subCategory;
    }

    /**
     * resetter for subCategory
     */
    function resetSubCategory() {
        $this->subCategory = NULL;
    }

    /**
     * getter for returnBy
     */
    function getReturnBy() {
        return $this->returnBy;
    }

    /**
     * setter for returnBy
     */
    function setReturnBy($_returnBy) {
        $this->returnBy = $_returnBy;
    }

    /**
     * resetter for returnBy
     */
    function resetReturnBy() {
        $this->returnBy = NULL;
    }

    /**
     * getter for hasBookmarks
     */
    function getHasBookmarks() {
        return $this->hasBookmarks;
    }

    /**
     * setter for hasBookmarks
     */
    function setHasBookmarks($_hasBookmarks) {
        $this->hasBookmarks = $_hasBookmarks;
    }

    /**
     * resetter for hasBookmarks
     */
    function resetHasBookmarks() {
        $this->hasBookmarks = NULL;
    }

    /******************** validator methods ********************/

    /**
     * validator for class contentItem
     */
    function validate() {
        // label must occur exactly once
        if ($this->isInstanceOf($this->label, 'label') === false)
            return false;
        if ($this->label->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->label->getError();
            return false;
        }

        // sample is optional
        if (!is_null($this->sample)) {
            if ($this->isInstanceOf($this->sample, 'sample') === false)
                return false;

            if ($this->sample->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->sample->getError();
                return false;
            }
        }

        // metadata is required
        if ($this->isInstanceOf($this->metadata, 'metadata') === false)
            return false;
        if ($this->metadata->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->metadata->getError();
            return false;
        }

        // categoryLabel is optional
        if (!is_null($this->categoryLabel)) {
            if ($this->isInstanceOf($this->categoryLabel, 'categoryLabel') === false)
                return false;

            if ($this->categoryLabel->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->categoryLabel->getError();
                return false;
            }
        }

        // subCategoryLabel in optional
        if (!is_null($this->subCategoryLabel)) {
            if ($this->isInstanceOf($this->subCategoryLabel, 'subCategoryLabel') === false)
                return false;

            if ($this->subCategoryLabel->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->subCategoryLabel->getError();
                return false;
            }
        }

        // accessPermissions is required
        if ($this->isNoneEmptyString($this->accessPermission, 'accessPermission') === false)
            return false;
        if ($this->isString($this->accessPermission, 'accessPermission', array("STREAM_ONLY","DOWNLOAD_ONLY","STREAM_AND_DOWNLOAD","STREAM_AND_RESTRICTED_DOWNLOAD",
            "RESTRICTED_DOWNLOAD_ONLY","DOWNLOAD_ONLY_AUTOMATIC_ALLOWED",
            "STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED","STREAM_AND_RESTRICTED_DOWNLOAD_AUTOMATIC_ALLOWED",
            "RESTRICTED_DOWNLOAD_ONLY_AUTOMATIC_ALLOWED")) === false)
            return false;


        // lastmark is optional
        if (!is_null($this->lastmark)) {
            if ($this->isInstanceOf($this->lastmark, 'lastmark') === false)
                return false;

            if ($this->lastmark->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->lastmark->getError();
                return false;
            }
        }

        // multipleChoiceQuestion is optional
        if (!is_null($this->multipleChoiceQuestion)) {
            if ($this->isInstanceOf($this->multipleChoiceQuestion, 'multipleChoiceQuestion') === false)
                return false;

            if ($this->multipleChoiceQuestion->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->multipleChoiceQuestion->getError();
                return false;
            }
        }
        // attribute id is required
        if ($this->isNoneEmptyString($this->id, 'id') === false)
            return false;

        // attribute firstAccessedDate is optional
        if (!is_null($this->firstAccessedDate))
            if ($this->isDateTimeString($this->firstAccessedDate, 'firstAccessedDate') === false)
                return false;

        // attribute lastAccessedDate is optional
        if (!is_null($this->lastAccessedDate))
            if ($this->isDateTimeString($this->lastAccessedDate, 'lastAccessedDate') === false)
                return false;

        // attribute lastModifiedDate is required
        if (!is_null($this->lastModifiedDate))
            if ($this->isDateTimeString($this->lastModifiedDate, 'lastModifiedDate') === false)
                return false;

        // attribute category is optional
        if (!is_null($this->category))
            if ($this->isNoneEmptyString($this->category, 'category') === false)
                return false;

        // attribute subCategory is optional
        if (!is_null($this->subCategory))
            if ($this->isNoneEmptyString($this->subCategory, 'subCategory') === false)
                return false;

        // attribute returnBy is optional
        if (!is_null($this->returnBy))
            if ($this->isDateTimeString($this->returnBy, 'returnBy') === false)
                return false;

        // attribute hasBookmarks is required
        if ($this->isBoolean($this->hasBookmarks, 'hasBookmarks') === false)
            return false;

        return true;
    }
}

?>
