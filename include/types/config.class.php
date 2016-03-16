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

require_once('supportedContentFormats.class.php');
require_once('supportedContentProtectionFormats.class.php');
require_once('keyRing.class.php');
require_once('supportedMimeTypes.class.php');
require_once('supportedInputTypes.class.php');
require_once('additionalTransferProtocols.class.php');

class config extends AbstractType {

    /**
     * @var string
     */
    public $accessConfig;

    /**
     * @var boolean
     */
    public $supportsMultipleSelections;

    /**
     * @var boolean
     */
    public $supportsAdvancedDynamicMenus;

    /**
     * @var language
     */
    public $preferredUILanguage;

    /**
     * @var int
     */
    public $bandwidth;

    /**
     * @var (object)supportedContentFormats
     */
    public $supportedContentFormats;

    /**
     * @var (object)supportedContentProtectionFormats
     */
    public $supportedContentProtectionFormats;

    /**
     * @var (object)keyRing
     */
    public $keyRing;

    /**
     * @var (object)supportedMimeTypes
     */
    public $supportedMimeTypes;

    /**
     * @var (object)supportedInputTypes
     */
    public $supportedInputTypes;

    /**
     * @var boolean
     */
    public $requiresAudioLabels;

    /**
     * @var (object)additionalTransferProtocols
     */
    public $additionalTransferProtocols;


    /******************** public functions ********************/

    /**
     * constructor for class config
     */
    function __construct($_accessConfig = NULL, $_supportsMultipleSelections = NULL, $_supportsAdvancedDynamicMenus, $_preferredUILanguage = NULL, $_bandwidth = NULL, $_supportedContentFormats = NULL, $_supportedContentProtectionFormats = NULL, $_keyRing = NULL, $_supportedMimeTypes = NULL, $_supportedInputTypes = NULL, $_requiresAudioLabels = NULL, $_additionalTransferProtocols = NULL) {
        if (is_string($_accessConfig)) $this->setAccessConfig($_accessConfig);
        if (is_bool($_supportsMultipleSelections)) $this->setSupportsMultipleSelections($_supportsMultipleSelections);                
        if (is_bool($_supportsAdvancedDynamicMenus)) $this->setSupportsAdvancedDynamicMenus($_supportsAdvancedDynamicMenus);
        if (is_string($_preferredUILanguage)) $this->setPreferredUILanguage($_preferredUILanguage);
        if (is_int($_bandwidth)) $this->setBandwidth($_bandwidth);
        if (is_a($_supportedContentFormats, "supportedContentFormats")) $this->setSupportedContentFormats($_supportedContentFormats);
        if (is_a($_supportedContentProtectionFormats, "supportedContentProtectionFormats")) $this->setSupportedContentProtectionFormats($_supportedContentProtectionFormats);
        if (is_a($_keyRing, "keyRing")) $this->setKeyRing($_keyRing);
        if (is_a($_supportedMimeTypes, "supportedMimeTypes")) $this->setSupportedMimeTypes($_supportedMimeTypes);
        if (is_a($_supportedInputTypes, "supportedInputTypes")) $this->setSupportedInputTypes($_supportedInputTypes);
        if (is_bool($_requiresAudioLabels)) $this->setRequiresAudioLabels($_requiresAudioLabels);
        if (is_a($_additionalTransferProtocols, "additionalTransferProtocols")) $this->setAdditionalTransferProtocols($_additionalTransferProtocols);
    }


    /******************** class get set methods ********************/


    /**
     * getter for accessConfig
     */
    function getAccessConfig() {
        return $this->accessConfig;
    }

    /**
     * setter for accessConfig
     */
    function setAccessConfig($_accessConfig) {
        $this->accessConfig = $_accessConfig;
    }

    /**
     * resetter for accessConfig
     */
    function resetAccessConfig() {
        $this->accessConfig = NULL;
    }

    /**
     * getter for supportsMultipleSelections
     */
    function getSupportsMultipleSelections() {
        return $this->supportsMultipleSelections;
    }

    /**
     * setter for supportsMultipleSelections
     */
    function setSupportsMultipleSelections($_supportsMultipleSelections) {
        $this->supportsMultipleSelections = $_supportsMultipleSelections;
    }

    /**
     * resetter for supportsMultipleSelections
     */
    function resetSupportsMultipleSelections() {
        $this->supportsMultipleSelections = NULL;
    }

    /**
     * getter for supportsAdvancedDynamicMenus
     */
    function getSupportsAdvancedDynamicMenus() {
        return $this->supportsAdvancedDynamicMenus;
    }

    /**
     * setter for supportsAdvancedDynamicMenus
     */
    function setSupportsAdvancedDynamicMenus($_supportsAdvancedDynamicMenus) {
        $this->supportsAdvancedDynamicMenus = $_supportsAdvancedDynamicMenus;
    }

    /**
     * resetter for supportAdvancedDynamicMenus
     */
    function resetSupportsAdvancedDynamicMenus() {
        $this->supportsAdvancedDynamicMenus = NULL;
    }


    /**
     * getter for preferredUILanguage
     */
    function getPreferredUILanguage() {
        return $this->preferredUILanguage;
    }

    /**
     * setter for preferredUILanguage
     */
    function setPreferredUILanguage($_preferredUILanguage) {
        $this->preferredUILanguage = $_preferredUILanguage;
    }

    /**
     * resetter for preferredUILanguage
     */
    function resetPreferredUILanguage() {
        $this->preferredUILanguage = NULL;
    }

    /**
     * getter for bandwidth
     */
    function getBandwidth() {
        return $this->bandwidth;
    }

    /**
     * setter for bandwidth
     */
    function setBandwidth($_bandwidth) {
        $this->bandwidth = $_bandwidth;
    }

    /**
     * resetter for bandwidth
     */
    function resetBandwidth() {
        $this->bandwidth = NULL;
    }

    /**
     * getter for supportedContentFormats
     */
    function getSupportedContentFormats() {
        return $this->supportedContentFormats;
    }

    /**
     * setter for supportedContentFormats
     */
    function setSupportedContentFormats($_supportedContentFormats) {
        $this->supportedContentFormats = $_supportedContentFormats;
    }

    /**
     * resetter for supportedContentFormats
     */
    function resetSupportedContentFormats() {
        $this->supportedContentFormats = NULL;
    }

    /**
     * getter for supportedContentProtectionFormats
     */
    function getSupportedContentProtectionFormats() {
        return $this->supportedContentProtectionFormats;
    }

    /**
     * setter for supportedContentProtectionFormats
     */
    function setSupportedContentProtectionFormats($_supportedContentProtectionFormats) {
        $this->supportedContentProtectionFormats = $_supportedContentProtectionFormats;
    }

    /**
     * resetter for supportedContentProtectionFormats
     */
    function resetSupportedContentProtectionFormats() {
        $this->supportedContentProtectionFormats = NULL;
    }

    /**
     * getter for keyRing
     */
    function getKeyRing() {
        return $this->keyRing;
    }

    /**
     * setter for keyRing
     */
    function setKeyRing($_keyRing) {
        $this->keyRing = $_keyRing;
    }

    /**
     * resetter for keyRing
     */
    function resetKeyRing() {
        $this->keyRing = NULL;
    }

    /**
     * getter for supportedMimeTypes
     */
    function getSupportedMimeTypes() {
        return $this->supportedMimeTypes;
    }

    /**
     * setter for supportedMimeTypes
     */
    function setSupportedMimeTypes($_supportedMimeTypes) {
        $this->supportedMimeTypes = $_supportedMimeTypes;
    }

    /**
     * resetter for supportedMimeTypes
     */
    function resetSupportedMimeTypes() {
        $this->supportedMimeTypes = NULL;
    }

    /**
     * getter for supportedInputTypes
     */
    function getSupportedInputTypes() {
        return $this->supportedInputTypes;
    }

    /**
     * setter for supportedInputTypes
     */
    function setSupportedInputTypes($_supportedInputTypes) {
        $this->supportedInputTypes = $_supportedInputTypes;
    }

    /**
     * resetter for supportedInputTypes
     */
    function resetSupportedInputTypes() {
        $this->supportedInputTypes = NULL;
    }

    /**
     * getter for requiresAudioLabels
     */
    function getRequiresAudioLabels() {
        return $this->requiresAudioLabels;
    }

    /**
     * setter for requiresAudioLabels
     */
    function setRequiresAudioLabels($_requiresAudioLabels) {
        $this->requiresAudioLabels = $_requiresAudioLabels;
    }

    /**
     * resetter for requiresAudioLabels
     */
    function resetRequiresAudioLabels() {
        $this->requiresAudioLabels = NULL;
    }

    /**
     * getter for additionalTransferProtocols
     */
    function getAdditionalTransferProtocols() {
        return $this->additionalTransferProtocols;
    }

    /**
     * setter for additionalTransferProtocols
     */
    function setAdditionalTransferProtocols($_additionalTransferProtocols) {
        $this->additionalTransferProtocols = $_additionalTransferProtocols;
    }

    /**
     * resetter for additionalTransferProtocols
     */
    function resetAdditionalTransferProtocols() {
        $this->additionalTransferProtocols = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class config
     */
    function validate() {
        // accessConfig is required
        if ($this->isNoneEmptyString($this->accessConfig, 'accessConfig') === false){
            $allowedValues =  array("STREAM_ONLY", "DOWNLOAD_ONLY", "STREAM_AND_DOWNLOAD", "STREAM_AND_RESTRICTED_DOWNLOAD", "RESTRICTED_DOWNLOAD_ONLY");
            if (in_array($this->accessConfig, $allowedValues) === false){
                return false;
            }
        } 

        // supportsMultipleSelections must occur exactly once
        if ($this->isBoolean($this->supportsMultipleSelections, 'supportsMultipleSelections') === false)
            return false;

        // supportsMultipleSelections must occur exactly once
        if ($this->isBoolean($this->supportsAdvancedDynamicMenus, 'supportsAdvancedDynamicMenus') === false)
            return false;

        // preferredUILanguage must occur exactly once
        if ($this->isNoneEmptyString($this->preferredUILanguage, 'preferredUILanguage') === false)
            return false;

        // bandwidth must occur zero or one times
        if (!is_null($this->bandwidth)) {
            if ($this->isPositiveInteger($this->bandwidth, 'bandwidth') === false)
                return false;
        }

        // supportedContentFormats must occur exactly once
        if ($this->isInstanceOf($this->supportedContentFormats, 'supportedContentFormats') === false)
            return false;
        if ($this->supportedContentFormats->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedContentFormats->getError();
            return false;
        }

        // supportedContentProtectionFormats must occur exactly once
        if ($this->isInstanceOf($this->supportedContentProtectionFormats, 'supportedContentProtectionFormats') === false)
            return false;
        if ($this->supportedContentProtectionFormats->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedContentProtectionFormats->getError();
            return false;
        }

        // keyRing must occur zero or one times
        if (!is_null($this->keyRing)) {
            if ($this->isInstanceOf($this->keyRing, 'keyRing') === false)
                return false;
            if ($this->keyRing->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->keyRing->getError();
                return false;
            }
        }

        // supportedMimeTypes must occur exactly once
        if ($this->isInstanceOf($this->supportedMimeTypes, 'supportedMimeTypes') === false)
            return false;
        if ($this->supportedMimeTypes->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedMimeTypes->getError();
            return false;
        }

        // supportedInputTypes must occur exactly once
        if ($this->isInstanceOf($this->supportedInputTypes, 'supportedInputTypes') === false)
            return false;
        if ($this->supportedInputTypes->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedInputTypes->getError();
            return false;
        }

        // requiresAudioLabels must occur exactly once
        if ($this->isBoolean($this->requiresAudioLabels, 'requiresAudioLabels') === false)
            return false;

        // additionalTransferProtocols must occur zero or one times
        if (!is_null($this->additionalTransferProtocols)) {
            if ($this->isInstanceOf($this->additionalTransferProtocols, 'additionalTransferProtocols') === false)
                return false;
            if ($this->additionalTransferProtocols->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->additionalTransferProtocols->getError();
                return false;
            }
        }

        return true;
    }
}

?>
