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
require_once('config.class.php');

class DaisyOnlineServiceOperationNotSupportedTest extends PHPUnit_Framework_TestCase
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
     * @group operation
     */
    public function testGetServiceAnnouncements()
    {
        // operation not supported
        $input = new getServiceAnnouncements();
        $this->assertTrue($this->callOperation('getServiceAnnouncements', $input, 'operationNotSupportedFault'));
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testMarkAnnouncementsAsRead()
    {
        // operation not supported
        $input = new markAnnouncementsAsRead();
        $this->assertTrue($this->callOperation('markAnnouncementsAsRead', $input, 'operationNotSupportedFault'));
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetBookmarks()
    {
        // operation not supported
        $input = new getBookmarks();
        $this->assertTrue($this->callOperation('getBookmarks', $input, 'operationNotSupportedFault'));
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testSetBookmarks()
    {
        // operation not supported
        $input = new setBookmarks();
        $this->assertTrue($this->callOperation('setBookmarks', $input, 'operationNotSupportedFault'));
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetQuestions()
    {
        // operation not supported
        $input = new getQuestions();
        $this->assertTrue($this->callOperation('getQuestions', $input, 'operationNotSupportedFault'));
    }

    /**
     * @group daisyonlineservice
     * @group operation
     */
    public function testGetKeyExchangeObject()
    {
        // operation not supported
        $input = new getKeyExchangeObject();
        $this->assertTrue($this->callOperation('getKeyExchangeObject', $input, 'operationNotSupportedFault'));
    }
}

?>
