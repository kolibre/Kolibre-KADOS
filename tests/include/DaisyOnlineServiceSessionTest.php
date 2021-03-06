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

class DaisyOnlineServiceSession extends PHPUnit_Framework_TestCase
{
    protected static $rsa;
    protected static $inifile;
    protected static $instance;

    public static function setUpBeforeClass()
    {
        self::$inifile = realpath(dirname(__FILE__)) . '/service.ini';
        if (file_exists(self::$inifile)) unlink(self::$inifile);

        $settings = array();
        $settings['Service'] = array();
        $settings['Adapter'] = array();
        $settings['Adapter']['name'] = 'SystemTestAdapter';
        $settings['Adapter']['path'] = realpath(dirname(__FILE__));

        self::write_ini_file($settings, self::$inifile);

        self::$instance = new DaisyOnlineService(self::$inifile);
        self::$instance->disableCookieCheckInSessionHandle();

        // build readingSystemAttributes object
        $accessConfig = 'STREAM_ONLY';
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
        $config = new config(
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

        self::$rsa = $readingSystemAttributes;
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

    private function callOperation($operation, $input, $fault)
    {
        $soapFault = false;
        try
        {
            self::$instance->$operation($input);
        }
        catch (SoapFault $f)
        {
            $name = $operation .'_' . $fault;
            if ($f->_name == $name)
                $soapFault = true;
        }
        return $soapFault;
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testNoActiveSessionBeforeLogOn()
    {
        $input = new getContentList('id', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'noActiveSessionFault'));
        $input = new getContentResources('contentID', 'STREAM');
        $this->assertTrue($this->callOperation('getContentResources', $input, 'noActiveSessionFault'));
        $input = new returnContent('contentID');
        $this->assertTrue($this->callOperation('returnContent', $input, 'noActiveSessionFault'));
        $input = new getServiceAnnouncements();
        $this->assertTrue($this->callOperation('getServiceAnnouncements', $input, 'noActiveSessionFault'));
        $input = new markAnnouncementsAsRead(new read());
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'noActiveSessionFault'));
        $input = new updateBookmarks('contentID', 'ADD', new bookmarkObject(new bookmarkSet(new title('title'), 'uid')), '1970-01-01T00:00:00+00:00');
        $this->assertTrue($this->callOperation('updateBookmarks', $input, 'noActiveSessionFault'));
        $input = new getBookmarks('contentID', 'ALL');
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'noActiveSessionFault'));
        $input = new addContentToBookshelf('contentID');
        $this->assertTrue($this->callOperation('addContentToBookshelf', $input, 'noActiveSessionFault'));
        $input = new getQuestions(new userResponses(array(new userResponse('default'))));
        $this->assertTrue($this->callOperation('getQuestions', $input, 'noActiveSessionFault'));
        $input = new getUserCredentials();
        $this->assertTrue($this->callOperation('getUserCredentials', $input, 'operationNotSupportedFault')); // does not require a valid session
        $input = new getKeyExchangeObject('requestedKeyName');
        $this->assertTrue($this->callOperation('getKeyExchangeObject', $input, 'noActiveSessionFault'));
        $input = new getTermsOfService();
        $this->assertTrue($this->callOperation('getTermsOfService', $input, 'noActiveSessionFault'));
        $input = new acceptTermsOfService();
        $this->assertTrue($this->callOperation('acceptTermsOfService', $input, 'noActiveSessionFault'));
        $input = new setProgressState('contentID', 'START');
        $this->assertTrue($this->callOperation('setProgressState', $input, 'noActiveSessionFault'));
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testLogOnWithIncorrectUsernameAndPassword()
    {
        $input = new logOn('invalid', 'invalid', self::$rsa);
        $this->assertTrue($this->callOperation('logOn', $input, 'unauthorizedFault'));
        $input = new getContentList('empty', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'noActiveSessionFault'));
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testLogOnWithIncorrectReadingSystemAttributes()
    {
        $input = new logOn('invalid', 'invalid', null);
        $this->assertTrue($this->callOperation('logOn', $input, 'invalidParameterFault'));
        $input = new getContentList('empty', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'noActiveSessionFault'));
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testLogOnWithCorrectUsernameAndPassword()
    {
        $input = new logOn('valid', 'valid', self::$rsa);
        $output = self::$instance->logOn($input);
        $this->assertInstanceOf('serviceAttributes', $output->serviceAttributes);
        $input = new getContentList('empty', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertInstanceOf('getContentListResponse', $output);
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testSessionDestroyedByLogOff()
    {
        $input = new logOn('valid', 'valid', self::$rsa);
        $output = self::$instance->logOn($input);
        $this->assertInstanceOf('serviceAttributes', $output->serviceAttributes);
        $input = new getContentList('empty', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertInstanceOf('getContentListResponse', $output);
        $input = new logOff();
        $output = self::$instance->logOff($input);
        $this->assertTrue($output->logOffResult);
        $input = new getContentList('empty', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'noActiveSessionFault'));
    }

    /**
     * @group daisyonlineservice
     * @group session
     */
    public function testSessionDestroyedByAdapter()
    {
        $input = new logOn('valid', 'valid', self::$rsa);
        $output = self::$instance->logOn($input);
        $this->assertInstanceOf('serviceAttributes', $output->serviceAttributes);
        $input = new getContentList('stop-backend-session', 0, -1);
        $output = self::$instance->getContentList($input);
        $this->assertInstanceOf('getContentListResponse', $output);
        $input = new getContentList('empty', 0, -1);
        $this->assertTrue($this->callOperation('getContentList', $input, 'noActiveSessionFault'));
    }
}

?>
