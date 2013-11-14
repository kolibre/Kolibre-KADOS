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

require_once('setReadingSystemAttributes.class.php');

class setReadingSystemAttributesTest extends PHPUnit_Framework_TestCase
{
    protected $config;
    protected $readingSystemAttributes;

    public function setUp()
    {
        $supportsMultipleSelections = false;
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
            $supportsMultipleSelections,
            $preferredUILanguage,
            $bandwidth,
            $supportedContentFormats,
            $supportedContentProtectionFormats,
            $keyRing,
            $supportedMimeTypes,
            $supportedInputTypes,
            $requiresAudioLabels,
            $additionalTransferProtocols);

        $manufacturer = 'manufacturer';
        $model = 'model';
        $serialNumber = null;
        $version = 'version';
        $config = $this->config;
        $this->readingSystemAttributes = new readingSystemAttributes(
            $manufacturer,
            $model,
            $serialNumber,
            $version,
            $config);
    }

    /**
     * @group setReadingSystemAttributes
     * @group validate
     */
    public function testReadingSystemAttributes()
    {
        $instance = new setReadingSystemAttributes();
        $this->assertFalse($instance->validate());
        $this->assertContains('setReadingSystemAttributes.readingSystemAttributes', $instance->getError());
        $instance->readingSystemAttributes = 'readingSystemAttributes';
        $this->assertFalse($instance->validate());
        $this->assertContains('setReadingSystemAttributes.readingSystemAttributes', $instance->getError());
        $instance->readingSystemAttributes = $this->readingSystemAttributes;
        $this->assertTrue($instance->validate());
    }
}

?>
