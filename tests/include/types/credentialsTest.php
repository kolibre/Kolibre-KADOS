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

require_once('credentials.class.php');

class credentialsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group credentials
     * @group validate
     */
    public function testUsername()
    {
        $credentials = new credentials(null, 'password', 'RSAES-OAEP');
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.username', $credentials->getError());

        $credentials->username = 1;
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.username', $credentials->getError());

        $credentials->username = '';
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.username', $credentials->getError());

        $credentials->username = 'username';
        $this->assertTrue($credentials->validate());
    }

    /**
     * @group credentials
     * @group validate
     */
    public function testPassword()
    {
        $credentials = new credentials('username', null, 'RSAES-OAEP');
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.password', $credentials->getError());

        $credentials->password = 1;
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.password', $credentials->getError());

        $credentials->password = '';
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.password', $credentials->getError());

        $credentials->password = 'password';
        $this->assertTrue($credentials->validate());
    }

    /**
     * @group credentials
     * @group validate
     */

    public function testEncryptionScheme()
    {
        $credentials = new credentials('username','password');
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.encryptionScheme', $credentials->getError());

        $credentials->encryptionScheme = 1;
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.encryptionScheme', $credentials->getError());

        $credentials->encryptionScheme = '';
        $this->assertFalse($credentials->validate());
        $this->assertContains('credentials.encryptionScheme', $credentials->getError());

        $credentials->encryptionScheme = 'RSAES-OAEP';
        $this->assertTrue($credentials->validate());
    }
}

?>
