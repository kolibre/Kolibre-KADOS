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

require_once('meta.class.php');

class metadata extends AbstractType {

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $publisher;

    /**
     * @var string
     */
    public $format;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $source;

    /**
     * @var array[0, unbounded] of string
     */
    public $type;

    /**
     * @var array[0, unbounded] of string
     */
    public $subject;

    /**
     * @var array[0, unbounded] of string
     */
    public $rights;

    /**
     * @var array[0, unbounded] of string
     */
    public $relation;

    /**
     * @var array[0, unbounded] of string
     */
    public $language;

    /**
     * @var array[0, unbounded] of string
     */
    public $description;

    /**
     * @var array[0, unbounded] of string
     */
    public $creator;

    /**
     * @var array[0, unbounded] of string
     */
    public $coverage;

    /**
     * @var array[0, unbounded] of string
     */
    public $contributor;

    /**
     * @var array[0, unbounded] of string
     */
    public $narrator;

    /**
     * @var long
     */
    public $size;

    /**
     * @var array[0, unbounded] of (object)meta
     */
    public $meta;

    // You may set only one from the following set
    // ---------------Start Choice----------------

    /**
     * @var anonymous1
     */
    public $anonymous1;
    // ----------------End Choice---------------


    /******************** public functions ********************/

    /**
     * constructor for class metadata
     */
    function __construct($_title = NULL, $_identifier = NULL, $_publisher = NULL, $_format = NULL, $_date = NULL, $_source = NULL, $_type = NULL, $_subject = NULL, $_rights = NULL, $_relation = NULL, $_language = NULL, $_description = NULL, $_creator = NULL, $_coverage = NULL, $_contributor = NULL, $_narrator = NULL, $_size = NULL, $_meta = NULL) {
        if (is_string($_title)) $this->setTitle($_title);
        if (is_string($_identifier)) $this->setIdentifier($_identifier);
        if (is_string($_publisher)) $this->setPublisher($_publisher);
        if (is_string($_format)) $this->setFormat($_format);
        if (is_string($_date)) $this->setDate($_date);
        if (is_string($_source)) $this->setSource($_source);
        if (is_array($_type)) $this->setType($_type);
        if (is_array($_subject)) $this->setSubject($_subject);
        if (is_array($_rights)) $this->setRights($_rights);
        if (is_array($_relation)) $this->setRelation($_relation);
        if (is_array($_language)) $this->setLanguage($_language);
        if (is_array($_description)) $this->setDescription($_description);
        if (is_array($_creator)) $this->setCreator($_creator);
        if (is_array($_coverage)) $this->setCoverage($_coverage);
        if (is_array($_contributor)) $this->setContributor($_contributor);
        if (is_array($_narrator)) $this->setNarrator($_narrator);
        if (is_int($_size)) $this->setSize($_size);
        if (is_array($_meta)) $this->setMeta($_meta);
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
     * getter for identifier
     */
    function getIdentifier() {
        return $this->identifier;
    }

    /**
     * setter for identifier
     */
    function setIdentifier($_identifier) {
        $this->identifier = $_identifier;
    }

    /**
     * resetter for identifier
     */
    function resetIdentifier() {
        $this->identifier = NULL;
    }

    /**
     * getter for publisher
     */
    function getPublisher() {
        return $this->publisher;
    }

    /**
     * setter for publisher
     */
    function setPublisher($_publisher) {
        $this->publisher = $_publisher;
    }

    /**
     * resetter for publisher
     */
    function resetPublisher() {
        $this->publisher = NULL;
    }

    /**
     * getter for format
     */
    function getFormat() {
        return $this->format;
    }

    /**
     * setter for format
     */
    function setFormat($_format) {
        $this->format = $_format;
    }

    /**
     * resetter for format
     */
    function resetFormat() {
        $this->format = NULL;
    }

    /**
     * getter for date
     */
    function getDate() {
        return $this->date;
    }

    /**
     * setter for date
     */
    function setDate($_date) {
        $this->date = $_date;
    }

    /**
     * resetter for date
     */
    function resetDate() {
        $this->date = NULL;
    }

    /**
     * getter for source
     */
    function getSource() {
        return $this->source;
    }

    /**
     * setter for source
     */
    function setSource($_source) {
        $this->source = $_source;
    }

    /**
     * resetter for source
     */
    function resetSource() {
        $this->source = NULL;
    }

    /**
     * getter for type
     */
    function getType() {
        return $this->type;
    }

    /**
     * setter for type
     */
    function setType($_type) {
        $this->type = $_type;
    }

    /**
     * resetter for type
     */
    function resetType() {
        $this->type = NULL;
    }

    /**
     * getter for subject
     */
    function getSubject() {
        return $this->subject;
    }

    /**
     * setter for subject
     */
    function setSubject($_subject) {
        $this->subject = $_subject;
    }

    /**
     * resetter for subject
     */
    function resetSubject() {
        $this->subject = NULL;
    }

    /**
     * getter for rights
     */
    function getRights() {
        return $this->rights;
    }

    /**
     * setter for rights
     */
    function setRights($_rights) {
        $this->rights = $_rights;
    }

    /**
     * resetter for rights
     */
    function resetRights() {
        $this->rights = NULL;
    }

    /**
     * getter for relation
     */
    function getRelation() {
        return $this->relation;
    }

    /**
     * setter for relation
     */
    function setRelation($_relation) {
        $this->relation = $_relation;
    }

    /**
     * resetter for relation
     */
    function resetRelation() {
        $this->relation = NULL;
    }

    /**
     * getter for language
     */
    function getLanguage() {
        return $this->language;
    }

    /**
     * setter for language
     */
    function setLanguage($_language) {
        $this->language = $_language;
    }

    /**
     * resetter for language
     */
    function resetLanguage() {
        $this->language = NULL;
    }

    /**
     * getter for description
     */
    function getDescription() {
        return $this->description;
    }

    /**
     * setter for description
     */
    function setDescription($_description) {
        $this->description = $_description;
    }

    /**
     * resetter for description
     */
    function resetDescription() {
        $this->description = NULL;
    }

    /**
     * getter for creator
     */
    function getCreator() {
        return $this->creator;
    }

    /**
     * setter for creator
     */
    function setCreator($_creator) {
        $this->creator = $_creator;
    }

    /**
     * resetter for creator
     */
    function resetCreator() {
        $this->creator = NULL;
    }

    /**
     * getter for coverage
     */
    function getCoverage() {
        return $this->coverage;
    }

    /**
     * setter for coverage
     */
    function setCoverage($_coverage) {
        $this->coverage = $_coverage;
    }

    /**
     * resetter for coverage
     */
    function resetCoverage() {
        $this->coverage = NULL;
    }

    /**
     * getter for contributor
     */
    function getContributor() {
        return $this->contributor;
    }

    /**
     * setter for contributor
     */
    function setContributor($_contributor) {
        $this->contributor = $_contributor;
    }

    /**
     * resetter for contributor
     */
    function resetContributor() {
        $this->contributor = NULL;
    }

    /**
     * getter for narrator
     */
    function getNarrator() {
        return $this->narrator;
    }

    /**
     * setter for narrator
     */
    function setNarrator($_narrator) {
        $this->narrator = $_narrator;
    }

    /**
     * resetter for narrator
     */
    function resetNarrator() {
        $this->narrator = NULL;
    }

    /**
     * getter for size
     */
    function getSize() {
        return $this->size;
    }

    /**
     * setter for size
     */
    function setSize($_size) {
        $this->size = $_size;
    }

    /**
     * resetter for size
     */
    function resetSize() {
        $this->size = NULL;
    }

    /**
     * getter for meta
     */
    function getMeta() {
        return $this->meta;
    }

    /**
     * setter for meta
     */
    function setMeta($_meta) {
        $this->meta = $_meta;
    }

    /**
     * resetter for meta
     */
    function resetMeta() {
        $this->meta = NULL;
    }


    /****************************** get set methods for arrays **********************************/

    /**
     * get the ith element of type
     */
    function getTypeAt($i) {
        if ($this->sizeofType() > $i)
            return $this->type[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of type
     */
    function setTypeAt($i, $_type) {
        $this->type[$i] = $_type;
    }

    /**
     * add to type
     */
    function addType($_type) {
        if (is_array($this->type))
            array_push($this->type, $_type);
        else {
            $this->type = array();
            $this->addType($_type);
        }
    }

    /**
     * get the size of the type array
     */
    function sizeofType() {
        return sizeof($this->type);
    }

    /**
     * remove the ith element of type
     */
    function removeTypeAt($i) {
        if ($this->sizeofType() > $i)
            unset($this->type[$i]);
    }

    /**
     * get the ith element of subject
     */
    function getSubjectAt($i) {
        if ($this->sizeofSubject() > $i)
            return $this->subject[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of subject
     */
    function setSubjectAt($i, $_subject) {
        $this->subject[$i] = $_subject;
    }

    /**
     * add to subject
     */
    function addSubject($_subject) {
        if (is_array($this->subject))
            array_push($this->subject, $_subject);
        else {
            $this->subject = array();
            $this->addSubject($_subject);
        }
    }

    /**
     * get the size of the subject array
     */
    function sizeofSubject() {
        return sizeof($this->subject);
    }

    /**
     * remove the ith element of subject
     */
    function removeSubjectAt($i) {
        if ($this->sizeofSubject() > $i)
            unset($this->subject[$i]);
    }

    /**
     * get the ith element of rights
     */
    function getRightsAt($i) {
        if ($this->sizeofRights() > $i)
            return $this->rights[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of rights
     */
    function setRightsAt($i, $_rights) {
        $this->rights[$i] = $_rights;
    }

    /**
     * add to rights
     */
    function addRights($_rights) {
        if (is_array($this->rights))
            array_push($this->rights, $_rights);
        else {
            $this->rights = array();
            $this->addRights($_rights);
        }
    }

    /**
     * get the size of the rights array
     */
    function sizeofRights() {
        return sizeof($this->rights);
    }

    /**
     * remove the ith element of rights
     */
    function removeRightsAt($i) {
        if ($this->sizeofRights() > $i)
            unset($this->rights[$i]);
    }

    /**
     * get the ith element of relation
     */
    function getRelationAt($i) {
        if ($this->sizeofRelation() > $i)
            return $this->relation[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of relation
     */
    function setRelationAt($i, $_relation) {
        $this->relation[$i] = $_relation;
    }

    /**
     * add to relation
     */
    function addRelation($_relation) {
        if (is_array($this->relation))
            array_push($this->relation, $_relation);
        else {
            $this->relation = array();
            $this->addRelation($_relation);
        }
    }

    /**
     * get the size of the relation array
     */
    function sizeofRelation() {
        return sizeof($this->relation);
    }

    /**
     * remove the ith element of relation
     */
    function removeRelationAt($i) {
        if ($this->sizeofRelation() > $i)
            unset($this->relation[$i]);
    }

    /**
     * get the ith element of language
     */
    function getLanguageAt($i) {
        if ($this->sizeofLanguage() > $i)
            return $this->language[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of language
     */
    function setLanguageAt($i, $_language) {
        $this->language[$i] = $_language;
    }

    /**
     * add to language
     */
    function addLanguage($_language) {
        if (is_array($this->language))
            array_push($this->language, $_language);
        else {
            $this->language = array();
            $this->addLanguage($_language);
        }
    }

    /**
     * get the size of the language array
     */
    function sizeofLanguage() {
        return sizeof($this->language);
    }

    /**
     * remove the ith element of language
     */
    function removeLanguageAt($i) {
        if ($this->sizeofLanguage() > $i)
            unset($this->language[$i]);
    }

    /**
     * get the ith element of description
     */
    function getDescriptionAt($i) {
        if ($this->sizeofDescription() > $i)
            return $this->description[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of description
     */
    function setDescriptionAt($i, $_description) {
        $this->description[$i] = $_description;
    }

    /**
     * add to description
     */
    function addDescription($_description) {
        if (is_array($this->description))
            array_push($this->description, $_description);
        else {
            $this->description = array();
            $this->addDescription($_description);
        }
    }

    /**
     * get the size of the description array
     */
    function sizeofDescription() {
        return sizeof($this->description);
    }

    /**
     * remove the ith element of description
     */
    function removeDescriptionAt($i) {
        if ($this->sizeofDescription() > $i)
            unset($this->description[$i]);
    }

    /**
     * get the ith element of creator
     */
    function getCreatorAt($i) {
        if ($this->sizeofCreator() > $i)
            return $this->creator[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of creator
     */
    function setCreatorAt($i, $_creator) {
        $this->creator[$i] = $_creator;
    }

    /**
     * add to creator
     */
    function addCreator($_creator) {
        if (is_array($this->creator))
            array_push($this->creator, $_creator);
        else {
            $this->creator = array();
            $this->addCreator($_creator);
        }
    }

    /**
     * get the size of the creator array
     */
    function sizeofCreator() {
        return sizeof($this->creator);
    }

    /**
     * remove the ith element of creator
     */
    function removeCreatorAt($i) {
        if ($this->sizeofCreator() > $i)
            unset($this->creator[$i]);
    }

    /**
     * get the ith element of coverage
     */
    function getCoverageAt($i) {
        if ($this->sizeofCoverage() > $i)
            return $this->coverage[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of coverage
     */
    function setCoverageAt($i, $_coverage) {
        $this->coverage[$i] = $_coverage;
    }

    /**
     * add to coverage
     */
    function addCoverage($_coverage) {
        if (is_array($this->coverage))
            array_push($this->coverage, $_coverage);
        else {
            $this->coverage = array();
            $this->addCoverage($_coverage);
        }
    }

    /**
     * get the size of the coverage array
     */
    function sizeofCoverage() {
        return sizeof($this->coverage);
    }

    /**
     * remove the ith element of coverage
     */
    function removeCoverageAt($i) {
        if ($this->sizeofCoverage() > $i)
            unset($this->coverage[$i]);
    }

    /**
     * get the ith element of contributor
     */
    function getContributorAt($i) {
        if ($this->sizeofContributor() > $i)
            return $this->contributor[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of contributor
     */
    function setContributorAt($i, $_contributor) {
        $this->contributor[$i] = $_contributor;
    }

    /**
     * add to contributor
     */
    function addContributor($_contributor) {
        if (is_array($this->contributor))
            array_push($this->contributor, $_contributor);
        else {
            $this->contributor = array();
            $this->addContributor($_contributor);
        }
    }

    /**
     * get the size of the contributor array
     */
    function sizeofContributor() {
        return sizeof($this->contributor);
    }

    /**
     * remove the ith element of contributor
     */
    function removeContributorAt($i) {
        if ($this->sizeofContributor() > $i)
            unset($this->contributor[$i]);
    }

    /**
     * get the ith element of narrator
     */
    function getNarratorAt($i) {
        if ($this->sizeofNarrator() > $i)
            return $this->narrator[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of narrator
     */
    function setNarratorAt($i, $_narrator) {
        $this->narrator[$i] = $_narrator;
    }

    /**
     * add to narrator
     */
    function addNarrator($_narrator) {
        if (is_array($this->narrator))
            array_push($this->narrator, $_narrator);
        else {
            $this->narrator = array();
            $this->addNarrator($_narrator);
        }
    }

    /**
     * get the size of the narrator array
     */
    function sizeofNarrator() {
        return sizeof($this->narrator);
    }

    /**
     * remove the ith element of narrator
     */
    function removeNarratorAt($i) {
        if ($this->sizeofNarrator() > $i)
            unset($this->narrator[$i]);
    }

    /**
     * get the ith element of meta
     */
    function getMetaAt($i) {
        if ($this->sizeofMeta() > $i)
            return $this->meta[$i];
        else return NULL;
    }

    /**
     * set the ith elemenent of meta
     */
    function setMetaAt($i, $_meta) {
        $this->meta[$i] = $_meta;
    }

    /**
     * add to meta
     */
    function addMeta($_meta) {
        if (is_array($this->meta))
            array_push($this->meta, $_meta);
        else {
            $this->meta = array();
            $this->addMeta($_meta);
        }
    }

    /**
     * get the size of the meta array
     */
    function sizeofMeta() {
        return sizeof($this->meta);
    }

    /**
     * remove the ith element of meta
     */
    function removeMetaAt($i) {
        if ($this->sizeofMeta() > $i)
            unset($this->meta[$i]);
    }


    /******************** validator methods ********************/

    /**
     * validator for class metadata
     */
    function validate() {
        // title must occur exactly once
        if ($this->isNoneEmptyString($this->title, 'title') === false)
            return false;

        // identifier must occur exactly once
        if ($this->isNoneEmptyString($this->identifier, 'identifier') === false)
            return false;

        // publisher must occur zero or one times
        if (!is_null($this->publisher)) {
            if ($this->isNoneEmptyString($this->publisher, 'publisher') === false)
                return false;
        }

        // format must occur exactly once
        if ($this->isNoneEmptyString($this->format, 'format') === false)
            return false;

        // date must occur zero or one times
        if (!is_null($this->date)) {
            if ($this->isNoneEmptyString($this->date, 'date') === false)
                return false;
        }

        // source must occur zero or one times
        if (!is_null($this->source)) {
            if ($this->isNoneEmptyString($this->source, 'source') === false)
                return false;
        }

        // type must occur zero or more times
        if (!is_null($this->type)) {
            if ($this->isArrayOfNoneEmptyString($this->type, 'type') === false)
                return false;
        }

        // subject must occur zero or more times
        if (!is_null($this->subject)) {
            if ($this->isArrayOfNoneEmptyString($this->subject, 'subject') === false)
                return false;
        }

        // rights must occur zero or more times
        if (!is_null($this->rights)) {
            if ($this->isArrayOfNoneEmptyString($this->rights, 'rights') === false)
                return false;
        }

        // relation must occcur zero or more times
        if (!is_null($this->relation)) {
            if ($this->isArrayOfNoneEmptyString($this->relation, 'relation') === false)
                return false;
        }

        // language must occur zero or more times
        if (!is_null($this->language)) {
            if ($this->isArrayOfNoneEmptyString($this->language, 'language') === false)
                return false;
        }

        // description must occur zero or more times
        if (!is_null($this->description)) {
            if ($this->isArrayOfNoneEmptyString($this->description, 'description') === false)
                return false;
        }

        // creator must occur zero or more times
        if (!is_null($this->creator)) {
            if ($this->isArrayOfNoneEmptyString($this->creator, 'creator') === false)
                return false;
        }

        // coverage must occur zero or more times
        if (!is_null($this->coverage)) {
            if ($this->isArrayOfNoneEmptyString($this->coverage, 'coverage') === false)
                return false;
        }

        // contributor must occur zero or more times
        if (!is_null($this->contributor)) {
            if ($this->isArrayOfNoneEmptyString($this->contributor, 'contributor') === false)
                return false;
        }

        // narrator must occur zero or more times
        if (!is_null($this->narrator)) {
            if ($this->isArrayOfNoneEmptyString($this->narrator, 'narrator') === false)
                return false;
        }

        // size must occur exactly once
        if ($this->isPositiveInteger($this->size, 'size') === false)
            return false;

        // meta must occur zero or more times
        if (!is_null($this->meta)) {
            if ($this->isArrayOfInstanceOf($this->meta, 'meta') === false)
                return false;
            foreach ($this->meta as $index => $meta) {
                if ($meta->validate() === false) {
                    $this->error = __CLASS__ . '.' . $meta->getError();
                    $this->error = str_replace('meta', "meta[$index]");
                    return false;
                }
            }
        }

        return true;
    }
}

?>
