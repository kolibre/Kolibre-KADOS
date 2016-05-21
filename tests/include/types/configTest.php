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

$includePath = dirname(realpath(__FILE__)) . '/../../../include/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('config.class.php');

class configTest extends PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $accessConfig = "STREAM_ONLY";
        $supportsMultipleSelections = false;
        $supportsAdvancedDynamicMenus = false;
        $preferredUILanguage = 'preferredUILanguage';
        $bandwidth = null;
        $supportedContentFormats = new supportedContentFormats();
        $supportedContentProtectionFormats = new supportedContentProtectionFormats();
        $keyRing = null;
        $supportedMimeTypes = new supportedMimeTypes();
        $supportedInputTypes = new supportedInputTypes();
        $requiresAudioLabels = false;
        $additionalTransferProtocols = null;
        $this->config = new config(
            $accessConfig,
            $supportsMultipleSelections,
            $supportsAdvancedDynamicMenus,
            $preferredUILanguage,
            $bandwidth,
            $supportedContentFormats,
            $supportedContentProtectionFormats,
            $keyRing,
            $supportedMimeTypes,
            $supportedInputTypes,
            $requiresAudioLabels,
            $additionalTransferProtocols);
    }

/**
     * @group config
     * @group validate
     */
    public function testAccessConfig()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->accessConfig = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.accessConfig', $instance->getError());
        $instance->accessConfig = 'configAccess';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.accessConfig', $instance->getError());
        $instance->accessConfig = "DOWNLOAD_ONLY";
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportsMultipleSelections()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportsMultipleSelections = 'supportsMultipleSelections';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportsMultipleSelections', $instance->getError());
        $instance->supportsMultipleSelections = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportsAdvancedDynamicMenus()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportsAdvancedDynamicMenus = 'supportsAdvancedDynamicMenus';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportsAdvancedDynamicMenus', $instance->getError());
        $instance->supportsAdvancedDynamicMenus = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testPreferredUILanguage()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->preferredUILanguage = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('config.preferredUILanguage', $instance->getError());
        $instance->preferredUILanguage = 'preferredUILanguage';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testBandwidth()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->bandwidth = 'bandwidth';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.bandwidth', $instance->getError());
        $instance->bandwidth = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('config.bandwidth', $instance->getError());
        $instance->bandwidth = 1;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportedContentFormats()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportedContentFormats = 'supportedContentFormats';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportedContentFormats', $instance->getError());
        $instance->supportedContentFormats = new supportedContentFormats();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportedContentProtectionFormats()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportedContentProtectionFormats = 'supportedContentProtectionFormats';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportedContentProtectionFormats', $instance->getError());
        $instance->supportedContentProtectionFormats = new supportedContentProtectionFormats();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testKeyRing()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->keyRing = 'keyRing';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.keyRing', $instance->getError());
        $instance->keyRing = new keyRing();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportedMimeTypes()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportedMimeTypes = 'supportedMimeTypes';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportedMimeTypes', $instance->getError());
        $instance->supportedMimeTypes = new supportedMimeTypes();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testSupportedInputTypes()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->supportedInputTypes = 'supportedInputTypes';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.supportedInputTypes', $instance->getError());
        $instance->supportedInputTypes = new supportedInputTypes();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testRequiresAudioLabels()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->requiresAudioLabels = 'requiresAudioLabels';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.requiresAudioLabels', $instance->getError());
        $instance->requiresAudioLabels = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group config
     * @group validate
     */
    public function testAdditionalTransferProtocols()
    {
        $instance = $this->config;
        $this->assertTrue($instance->validate());
        $instance->additionalTransferProtocols = 'additionalTransferProtocols';
        $this->assertFalse($instance->validate());
        $this->assertContains('config.additionalTransferProtocols', $instance->getError());
        $instance->additionalTransferProtocols = new additionalTransferProtocols(array('protocol'));
        $this->assertTrue($instance->validate());
    }
}

?>
