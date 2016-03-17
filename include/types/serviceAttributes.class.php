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

require_once('serviceProvider.class.php');
require_once('service.class.php');
require_once('supportedUplinkAudioCodecs.class.php');
require_once('supportedOptionalOperations.class.php');

class serviceAttributes extends AbstractType {

    /**
     * @var optional (object)serviceProvider
     */
    public $serviceProvider;

    /**
     * @var optional (object)service
     */
    public $service;


    /**
     * @var boolean
     */
    public $supportsServerSideBack;

    /**
     * @var boolean
     */
    public $supportsSearch;

    /**
     * @var (object)supportedUplinkAudioCodecs
     */
    public $supportedUplinkAudioCodecs;

    /**
     * @var boolean
     */
    public $supportsAudioLabels;

    /**
     * @var (object)supportedOptionalOperations
     */
    public $supportedOptionalOperations;

    /**
     * @var string accessConfig
     */
    public $accessConfig;

    /**
     * @var int announcementsPullFrequency
     */
    public $announcementsPullFrequency;

    /**
     * @var bool progressStateOperationAllowed
     */
    public $progressStateOperationAllowed;

    /******************** public functions ********************/

    /**
     * constructor for class serviceAttributes
     */
    function __construct($_serviceProvider = NULL, $_service = NULL, $_supportsServerSideBack = NULL, $_supportsSearch = NULL, $_supportedUplinkAudioCodecs = NULL, $_supportsAudioLabels = NULL, $_supportedOptionalOperations = NULL, $_accessConfig = NULL, $_announcementsPullFrequency = NULL, $_progressStateOperationAllowed = NULL) {
        if (is_a($_serviceProvider, "serviceProvider")) $this->setServiceProvider($_serviceProvider);
        if (is_a($_service, "service")) $this->setService($_service);
        if (is_bool($_supportsServerSideBack)) $this->setSupportsServerSideBack($_supportsServerSideBack);
        if (is_bool($_supportsSearch)) $this->setSupportsSearch($_supportsSearch);
        if (is_a($_supportedUplinkAudioCodecs, "supportedUplinkAudioCodecs")) $this->setSupportedUplinkAudioCodecs($_supportedUplinkAudioCodecs);
        if (is_bool($_supportsAudioLabels)) $this->setSupportsAudioLabels($_supportsAudioLabels);
        if (is_a($_supportedOptionalOperations, "supportedOptionalOperations")) $this->setSupportedOptionalOperations($_supportedOptionalOperations);
        if (is_string($_accessConfig)) $this->setAccessConfig($_accessConfig);
        if (is_int($_announcementsPullFrequency)) $this->setAnnouncementsPullFrequency($_announcementsPullFrequency);
        if(is_bool($_progressStateOperationAllowed)) $this->setProgressStateOperationAllowed($_progressStateOperationAllowed);
    }


    /******************** class get set methods ********************/


    /**
     * getter for serviceProvider
     */
    function getServiceProvider() {
        return $this->serviceProvider;
    }

    /**
     * setter for serviceProvider
     */
    function setServiceProvider($_serviceProvider) {
        $this->serviceProvider = $_serviceProvider;
    }

    /**
     * resetter for serviceProvider
     */
    function resetServiceProvider() {
        $this->serviceProvider = NULL;
    }

    /**
     * getter for service
     */
    function getService() {
        return $this->service;
    }

    /**
     * setter for service
     */
    function setService($_service) {
        $this->service = $_service;
    }

    /**
     * resetter for service
     */
    function resetService() {
        $this->service = NULL;
    }

    /**
     * getter for supportsServerSideBack
     */
    function getSupportsServerSideBack() {
        return $this->supportsServerSideBack;
    }

    /**
     * setter for supportsServerSideBack
     */
    function setSupportsServerSideBack($_supportsServerSideBack) {
        $this->supportsServerSideBack = $_supportsServerSideBack;
    }

    /**
     * resetter for supportsServerSideBack
     */
    function resetSupportsServerSideBack() {
        $this->supportsServerSideBack = NULL;
    }

    /**
     * getter for supportsSearch
     */
    function getSupportsSearch() {
        return $this->supportsSearch;
    }

    /**
     * setter for supportsSearch
     */
    function setSupportsSearch($_supportsSearch) {
        $this->supportsSearch = $_supportsSearch;
    }

    /**
     * resetter for supportsSearch
     */
    function resetSupportsSearch() {
        $this->supportsSearch = NULL;
    }

    /**
     * getter for supportedUplinkAudioCodecs
     */
    function getSupportedUplinkAudioCodecs() {
        return $this->supportedUplinkAudioCodecs;
    }

    /**
     * setter for supportedUplinkAudioCodecs
     */
    function setSupportedUplinkAudioCodecs($_supportedUplinkAudioCodecs) {
        $this->supportedUplinkAudioCodecs = $_supportedUplinkAudioCodecs;
    }

    /**
     * resetter for supportedUplinkAudioCodecs
     */
    function resetSupportedUplinkAudioCodecs() {
        $this->supportedUplinkAudioCodecs = NULL;
    }

    /**
     * getter for supportsAudioLabels
     */
    function getSupportsAudioLabels() {
        return $this->supportsAudioLabels;
    }

    /**
     * setter for supportsAudioLabels
     */
    function setSupportsAudioLabels($_supportsAudioLabels) {
        $this->supportsAudioLabels = $_supportsAudioLabels;
    }

    /**
     * resetter for supportsAudioLabels
     */
    function resetSupportsAudioLabels() {
        $this->supportsAudioLabels = NULL;
    }

    /**
     * getter for supportedOptionalOperations
     */
    function getSupportedOptionalOperations() {
        return $this->supportedOptionalOperations;
    }

    /**
     * setter for supportedOptionalOperations
     */
    function setSupportedOptionalOperations($_supportedOptionalOperations) {
        $this->supportedOptionalOperations = $_supportedOptionalOperations;
    }

    /**
     * resetter for supportedOptionalOperations
     */
    function resetSupportedOptionalOperations() {
        $this->supportedOptionalOperations = NULL;
    }

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
     * getter for announcementsPullFrequency
     */
    function getAnnouncementsPullFrequency() {
        return $this->announcementsPullFrequency;
    }

    /**
     * setter for announcementsPullFrequency
     */
    function setAnnouncementsPullFrequency($_announcementsPullFrequency) {
        $this->announcementsPullFrequency = $_announcementsPullFrequency;
    }

    /**
     * resetter for announcementsPullFrequency
     */
    function resetAnnouncementsPullFrequency() {
        $this->announcementsPullFrequency = NULL;
    }
    


    /**
     * getter for accessConfig
     */
    function getProgressStateOperationAllowed() {
        return $this->progressStateOperationAllowed;
    }

    /**
     * setter for progressStateOperationAllowed
     */
    function setProgressStateOperationAllowed($_progressStateOperationAllowed) {
        $this->progressStateOperationAllowed = $_progressStateOperationAllowed;
    }

    /**
     * resetter for progressStateOperationAllowed
     */
    function resetProgressStateOperationAllowed() {
        $this->progressStateOperationAllowed = NULL;
    }


    /******************** validator methods ********************/

    /**
     * validator for class serviceAttributes
     */
    function validate() {
        // serviceprovider must occur zero or one times
        if (!is_null($this->serviceProvider)) {
            if ($this->isInstanceOf($this->serviceProvider, 'serviceProvider') === false)
                return false;
            if ($this->serviceProvider->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->serviceProvider->getError();
                return false;
            }
        }

        // service must occur zero or one times
        if (!is_null($this->service)) {
            if ($this->isInstanceOf($this->service, 'service') === false)
                return false;
            if ($this->service->validate() === false) {
                $this->error = __CLASS__ . '.' . $this->service->getError();
                return false;
            }
        }

        // supportsServerSideBack must occur exactly once
        if ($this->isBoolean($this->supportsServerSideBack, 'supportsServerSideBack') === false)
            return false;

        // supportsSearch must occur exactly once
        if ($this->isBoolean($this->supportsSearch, 'supportsSearch') === false)
            return false;

        // supportedUplinkAudioCodecs must occur exactly once
        if ($this->isInstanceOf($this->supportedUplinkAudioCodecs, 'supportedUplinkAudioCodecs') === false)
            return false;
        if ($this->supportedUplinkAudioCodecs->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedUplinkAudioCodecs->getError();
            return false;
        }

        // supportsAudioLabels must occur exactly once
        if ($this->isBoolean($this->supportsAudioLabels, 'supportsAudioLabels') === false)
            return false;

        // supportedOptionalOperations must occur exactly once
        if ($this->isInstanceOf($this->supportedOptionalOperations, 'supportedOptionalOperations') === false)
            return false;
        if ($this->supportedOptionalOperations->validate() === false) {
            $this->error = __CLASS__ . '.' . $this->supportedOptionalOperations->getError();
            return false;
        }
        // accessConfig must occur exactly once
        if ($this->isNoneEmptyString($this->accessConfig, 'accessConfig') === false)
            return false;
        $allowedValues =  array("STREAM_ONLY", "DOWNLOAD_ONLY", "STREAM_AND_DOWNLOAD", "STREAM_AND_RESTRICTED_DOWNLOAD", "RESTRICTED_DOWNLOAD_ONLY");
        if ($this->isString($this->accessConfig, 'accessConfig', $allowedValues) === false)
            return false;
                    
        // announcementsPullFrequency must be positive integer
        if ($this->isPositiveInteger($this->announcementsPullFrequency, 'announcementsPullFrequency') === false)
                return false;

        // progressStateOperationAllowed must occur exactly once
        if ($this->isBoolean($this->progressStateOperationAllowed, 'progressStateOperationAllowed') === false)
            return false;

        return true;
    }
}

?>
