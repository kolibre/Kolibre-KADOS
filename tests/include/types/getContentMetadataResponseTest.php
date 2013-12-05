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

require_once('getContentMetadataResponse.class.php');

class getContentMetadataResponseTest extends PHPUnit_Framework_TestCase
{
    protected $metadata;
    protected $contentMetadata;

    public function setUp()
    {
        $title = 'title';
        $identifier = 'identifier';
        $publisher = null;
        $format = 'format';
        $date = null;
        $source = null;
        $type = null;
        $subject = null;
        $rights = null;
        $relation = null;
        $language = null;
        $description = null;
        $creator = null;
        $coverage = null;
        $contributor = null;
        $narrator = null;
        $size = 1;
        $meta = null;
        $this->metadata = new metadata(
            $title,
            $identifier,
            $publisher,
            $format,
            $date,
            $source,
            $type,
            $subject,
            $rights,
            $relation,
            $language,
            $description,
            $creator,
            $coverage,
            $contributor,
            $narrator,
            $size,
            $meta);

        $sample = null;
        $metadata = $this->metadata;
        $category = null;
        $requiresReturn = false;
        $this->contentMetadata = new contentMetadata(
            $sample,
            $metadata,
            $category,
            $requiresReturn);
    }

    /**
     * @group getContentMetadataResponse
     * @group validate
     */
    public function testContentMetadata()
    {
        $instance = new getContentMetadataResponse();
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentMetadataResponse.contentMetadata', $instance->getError());
        $instance->contentMetadata = 'contentMetadata';
        $this->assertFalse($instance->validate());
        $this->assertContains('getContentMetadataResponse.contentMetadata', $instance->getError());
        $instance->contentMetadata = $this->contentMetadata;
        $this->assertTrue($instance->validate());
    }
}

?>
