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

require_once('serviceAttributes.class.php');

class serviceAttributesTest extends PHPUnit_Framework_TestCase
{
    protected $serviceAttributes;

    public function setUp()
    {
        $serviceProvider = null;
        $service = null;
        //$supportedContentSelectionMethods = new supportedContentSelectionMethods(array('OUT_OF_BAND'));
        $supportsServerSideBack = false;
        $supportsSearch = false;
        $supportedUplinkAudioCodecs = new supportedUplinkAudioCodecs();
        $supportsAudioLabels = false;
        $supportedOptionalOperations = new supportedOptionalOperations();
        $accessConfig = "STREAM_AND_DOWNLOAD";
        $announcementsPullFrequency = 1;
        $progressStateOperationAllowed = false;
        $this->serviceAttributes = new serviceAttributes(
            $serviceProvider,
            $service,
            $supportsServerSideBack,
            $supportsSearch,
            $supportedUplinkAudioCodecs,
            $supportsAudioLabels,
            $supportedOptionalOperations,
            $accessConfig,
            $announcementsPullFrequency,
            $progressStateOperationAllowed);
        $this->assertTrue($this->serviceAttributes->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testServiceProvider()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->serviceProvider = 'serviceProvider';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.serviceProvider', $instance->getError());
        $instance->serviceProvider = new serviceProvider(null, 'id');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testService()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->service = 'service';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.service', $instance->getError());
        $instance->service = new service(null, 'id');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testSupportedServiceSideBack()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->supportsServerSideBack = 'supportsServerSideBack';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.supportsServerSideBack', $instance->getError());
        $instance->supportsServerSideBack = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testSupportsSerach()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->supportsSearch = 'supportsSearch';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.supportsSearch', $instance->getError());
        $instance->supportsSearch = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testSupportedUplinkAudioCodecs()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->supportedUplinkAudioCodecs = 'supportedUplinkAudioCodecs';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.supportedUplinkAudioCodecs', $instance->getError());
        $instance->supportedUplinkAudioCodecs = new supportedUplinkAudioCodecs();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testSupportsAudioLabels()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->supportsAudioLabels = 'supportsAudioLabels';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.supportsAudioLabels', $instance->getError());
        $instance->supportsAudioLabels = false;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testServiceOptionalOperations()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->supportedOptionalOperations = 'supportedOptionalOperations';
        $this->assertFalse($instance->validate());
        $this->assertContains('serviceAttributes.supportedOptionalOperations', $instance->getError());
        $instance->supportedOptionalOperations = new supportedOptionalOperations();
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testAccessConfig()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->accessConfig = '';
        $this->assertFalse($instance->validate());
        $instance->accessConfig = 'djhdsjkhdsjhkdfjkhdfjkhdfjkh';
        $this->assertFalse($instance->validate());
        $instance->accessConfig = 'STREAM_AND_DOWNLOAD';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testAnnouncementsPullFrequency()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->announcementsPullFrequency = '';
        $this->assertFalse($instance->validate());
        $instance->announcementsPullFrequency = -3;
        $this->assertFalse($instance->validate());
        $instance->announcementsPullFrequency = 2;
        $this->assertTrue($instance->validate());
    }
    /**
     * @group serviceAttributes
     * @group validate
     */
    public function testProgressStateOperationAllowed()
    {
        $instance = $this->serviceAttributes;
        $this->assertTrue($instance->validate());
        $instance->progressStateOperationAllowed = '';
        $this->assertFalse($instance->validate());
        $instance->progressStateOperationAllowed = 2;
        $this->assertFalse($instance->validate());
        $instance->progressStateOperationAllowed = false;
        $this->assertTrue($instance->validate());
    }
}

?>
