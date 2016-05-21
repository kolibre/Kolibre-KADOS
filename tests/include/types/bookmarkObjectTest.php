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

require_once('bookmarkObject.class.php');
require_once('bookmarkSet.class.php');
require_once('title.class.php');
require_once('lastmark.class.php');
require_once('bookmarkAudio.class.php');

class bookmarkObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group bookmarkObject
     * @group validate
     */

    public function testBookmarkSet()
    {

        $bookmarkObject = new bookmarkObject('test', '2016-03-11T14:23:23+00:00');
        $this->assertFalse($bookmarkObject->validate());

        $bookmarkAudio = new bookmarkAudio('src','clipBegin','ClipEnd');
        $title = new title('title',$bookmarkAudio);
        $lastmark = new lastMark('uri','uri','time', 1234);

        $bookmarkSet = new bookmarkSet($title, 'uid', $lastmark);
        // check to see thast the bookmarkset is a valide object
        $this->assertTrue($bookmarkSet->validate());


        $bookmarkObject = new bookmarkObject($bookmarkSet, '2016-03-11T14:23:23+00:00');
        $this->assertTrue($bookmarkObject->validate());

    }


    /**
     * @group bookmarkObject
     * @group validate
     */

    public function testLastDateModified()
    {
        $bookmarkAudio = new bookmarkAudio('src','clipBegin','ClipEnd');
        $title = new title('title',$bookmarkAudio);
        $lastmark = new lastMark('uri','uri','time', 1234);

        $bookmarkSet = new bookmarkSet($title, 'uid', $lastmark);

        $bookmarkObject = new bookmarkObject($bookmarkSet);
        $bookmarkObject->lastModifiedDate = 1;
        $this->assertFalse($bookmarkObject->validate());


        $bookmarkObject = new bookmarkObject($bookmarkSet, 'nfd');
        $this->assertFalse($bookmarkObject->validate());


        $bookmarkObject = new bookmarkObject($bookmarkSet, '2016-03-11T14:23:23Z');
        $this->assertTrue($bookmarkObject->validate());


        $bookmarkObject = new bookmarkObject($bookmarkSet, '2016-03-11T14:23:23+00:00');
        $this->assertTrue($bookmarkObject->validate());

    }

}

?>
