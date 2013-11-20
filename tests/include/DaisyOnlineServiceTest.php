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

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('DaisyOnlineService.class.php');

class DaisyOnlineServiceTest extends PHPUnit_Framework_TestCase
{
    protected static $inifile;
    protected static $instance;

    public static function setUpBeforeClass()
    {
        self::$inifile = realpath(dirname(__FILE__)) . '/service.ini';
        if (file_exists(self::$inifile)) unlink(self::$inifile);

        $settings = array();
        $settings['Service'] = array();
        $settings['Adapter'] = array();
        $settings['Adapter']['name'] = 'TestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));

        self::write_ini_file($settings, self::$inifile);

        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableInternalSessionHandling();
    }

    public static function tearDownAfterClass()
    {
        if (file_exists(self::$inifile)) unlink(self::$inifile);
    }

    public static function write_ini_file($settings, $file)
    {
        $content = '';
        foreach ($settings as $group => $value)
        {
            $content .= "[$group]\n";
            foreach ($value as $key => $value)
            {
                if (is_array($value))
                {
                    foreach ($value as $key2 => $value)
                        $content .= "$key" . "[] = $value\n";
                }
                else
                    $content .= "$key = $value\n";
            }
        }

        if (!$handle = fopen($file, 'w'))
            return false;

        if (!fwrite($handle, $content))
            return false;

        fclose($handle);
        return true;
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testLogOn()
    {
        // request is not valid
        $input = new logOn();
        $output = self::$instance->logOn($input);
        $this->assertFalse($output->logOnResult);

        // adapter throws exception on authenticate
        $SoapFault = false;
        $input = new logOn('exception', 'exception');
        try {
            $output = self::$instance->logOn($input);
        } catch (SoapFault $f) {
            if ($f->_name == 'logOn_internalServerErrorFault')
                $SoapFault = true;
        }
        $this->assertTrue($SoapFault);

        // adapter returns false on authenticate
        $input = new logOn('invalid', 'invalid');
        $output = self::$instance->logOn($input);
        $this->assertFalse($output->logOnResult);

        // adapter returns true on authenticate
        $input = new logOn('valid', 'valid');
        $output = self::$instance->logOn($input);
        $this->assertTrue($output->logOnResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testLogOff()
    {
        $input = new logOff();
        $output = self::$instance->logOff($input);
        $this->assertTrue($output->logOffResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetServiceAttributes()
    {
        // minimal settings
        $input = new getServiceAttributes($input);
        $output = self::$instance->getServiceAttributes($input);
        $this->assertNull($output->serviceAttributes->serviceProvider);
        $this->assertNull($output->serviceAttributes->service);
        $this->assertCount(1, $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('OUT_OF_BAND', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertFalse($output->serviceAttributes->supportsServerSideBack);
        $this->assertFalse($output->serviceAttributes->supportsSearch);
        $this->assertNull($output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertFalse($output->serviceAttributes->supportsAudioLabels);
        $this->assertNull($output->serviceAttributes->supportedOptionalOperations->operation);

        // full settings
        $settings = array();
        $settings['Service'] = array();
        $settings['Service']['serviceProvider'] = 'org-kolibre';
        $settings['Service']['service'] = 'org-kolibre-daisyonline';
        $settings['Service']['supportedContentSelectionMethods'] = array('OUT_OF_BAND', 'BROWSE');
        $settings['Service']['supportsServerSideBack'] = 1;
        $settings['Service']['supportsSearch'] = 1;
        $settings['Service']['supportedUplinkAudioCodecs'] = array('codec 1', 'codec 2');
        $settings['Service']['supportsAudioLabels'] = 1;
        $settings['Service']['supportedOptionalOperations'] = array();
        $settings['Service']['supportedOptionalOperations'][] = 'SERVICE_ANNOUNCEMENTS';
        $settings['Service']['supportedOptionalOperations'][] = 'SET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'GET_BOOKMARKS';
        $settings['Service']['supportedOptionalOperations'][] = 'DYNAMIC_MENUS';
        $settings['Service']['supportedOptionalOperations'][] = 'PDTB2_KEY_PROVISION';
        $settings['Adapter']['name'] = 'TestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));
        self::write_ini_file($settings, self::$inifile);
        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableInternalSessionHandling();
        $input = new getServiceAttributes($input);
        $output = self::$instance->getServiceAttributes($input);
        $this->assertEquals($output->serviceAttributes->serviceProvider->id, 'org-kolibre');
        $this->assertEquals($output->serviceAttributes->service->id, 'org-kolibre-daisyonline');
        $this->assertCount(2, $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('OUT_OF_BAND', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertContains('BROWSE', $output->serviceAttributes->supportedContentSelectionMethods->method);
        $this->assertTrue($output->serviceAttributes->supportsServerSideBack);
        $this->assertTrue($output->serviceAttributes->supportsSearch);
        $this->assertCount(2, $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertContains('codec 1', $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertContains('codec 2', $output->serviceAttributes->supportedUplinkAudioCodecs->codec);
        $this->assertTrue($output->serviceAttributes->supportsAudioLabels);
        $this->assertCount(5, $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('SERVICE_ANNOUNCEMENTS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('SET_BOOKMARKS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('GET_BOOKMARKS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('DYNAMIC_MENUS', $output->serviceAttributes->supportedOptionalOperations->operation);
        $this->assertContains('PDTB2_KEY_PROVISION', $output->serviceAttributes->supportedOptionalOperations->operation);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testSetReadingSystemAttributes()
    {
        // request is not valid
        $SoapFault = false;
        $input = new setReadingSystemAttributes();
        try {
            $output = self::$instance->setReadingSystemAttributes($input);
        } catch (SoapFault $f) {
            if ($f->_name == 'setReadingSystemAttributes_invalidParameterFault')
                $SoapFault = true;
        }
        $this->assertTrue($SoapFault);

        // build readingSystemAttributes object
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
        $config = new config(
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
        $readingSystemAttributes = new readingSystemAttributes(
            $manufacturer,
            $model,
            $serialNumber,
            $version,
            $config);

        // adapter returns false on start_session
        $input = new setReadingSystemAttributes($readingSystemAttributes);
        $output = self::$instance->setReadingSystemAttributes($input);
        $this->assertFalse($output->setReadingSystemAttributesResult);

        // adapter returns true on start_session
        $output = self::$instance->setReadingSystemAttributes($input);
        $this->assertTrue($output->setReadingSystemAttributesResult);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentList()
    {
        $this->assertTrue(true);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentMetadata()
    {
        $this->assertTrue(true);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testIssueContent()
    {
        $this->assertTrue(true);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetContentResouces()
    {
        $this->assertTrue(true);
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testReturnContent()
    {
        $this->assertTrue(true);
    }
}

?>
