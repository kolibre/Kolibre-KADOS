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

require_once('metadata.class.php');

class metadataTest extends PHPUnit_Framework_TestCase
{
    protected $metadata;

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
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testTitle()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->title = null;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.title', $instance->getError());
        $instance->title = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.title', $instance->getError());
        $instance->title = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.title', $instance->getError());
        $instance->title = 'title';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testIdentifier()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->identifier = null;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.identifier', $instance->getError());
        $instance->identifier = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.identifier', $instance->getError());
        $instance->identifier = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.identifier', $instance->getError());
        $instance->identifier = 'identifier';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testPublisher()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->publisher = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.publisher', $instance->getError());
        $instance->publisher = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.publisher', $instance->getError());
        $instance->publisher = 'publisher';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testFormat()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->format = null;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.format', $instance->getError());
        $instance->format = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.format', $instance->getError());
        $instance->format = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.format', $instance->getError());
        $instance->format = 'format';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testDate()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->date = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.date', $instance->getError());
        $instance->date = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.date', $instance->getError());
        $instance->date = 'date';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testSource()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->source = 1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.source', $instance->getError());
        $instance->source = '';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.source', $instance->getError());
        $instance->source = 'source';
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testType()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->type = 'type';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.type', $instance->getError());
        $instance->type = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.type', $instance->getError());
        $instance->type = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.type', $instance->getError());
        $instance->type = array();
        $this->assertTrue($instance->validate());
        $instance->type = array('type');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testSubject()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->subject = 'subject';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.subject', $instance->getError());
        $instance->subject = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.subject', $instance->getError());
        $instance->subject = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.subject', $instance->getError());
        $instance->subject = array();
        $this->assertTrue($instance->validate());
        $instance->subject = array('subject');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testRights()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->rights = 'rights';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.rights', $instance->getError());
        $instance->rights = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.rights', $instance->getError());
        $instance->rights = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.rights', $instance->getError());
        $instance->rights = array();
        $this->assertTrue($instance->validate());
        $instance->rights = array('rights');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testRelation()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->relation = 'relation';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.relation', $instance->getError());
        $instance->relation = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.relation', $instance->getError());
        $instance->relation = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.relation', $instance->getError());
        $instance->relation = array();
        $this->assertTrue($instance->validate());
        $instance->relation = array('relation');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testLanguage()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->language = 'language';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.language', $instance->getError());
        $instance->language = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.language', $instance->getError());
        $instance->language = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.language', $instance->getError());
        $instance->language = array();
        $this->assertTrue($instance->validate());
        $instance->language = array('language');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testDescription()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->description = 'description';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.description', $instance->getError());
        $instance->description = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.description', $instance->getError());
        $instance->description = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.description', $instance->getError());
        $instance->description = array();
        $this->assertTrue($instance->validate());
        $instance->description = array('description');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testCreator()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->creator = 'creator';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.creator', $instance->getError());
        $instance->creator = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.creator', $instance->getError());
        $instance->creator = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.creator', $instance->getError());
        $instance->creator = array();
        $this->assertTrue($instance->validate());
        $instance->creator = array('creator');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testCoverage()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->coverage = 'coverage';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.coverage', $instance->getError());
        $instance->coverage = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.coverage', $instance->getError());
        $instance->coverage = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.coverage', $instance->getError());
        $instance->coverage = array();
        $this->assertTrue($instance->validate());
        $instance->coverage = array('coverage');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testContributor()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->contributor = 'contributor';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.contributor', $instance->getError());
        $instance->contributor = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.contributor', $instance->getError());
        $instance->contributor = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.contributor', $instance->getError());
        $instance->contributor = array();
        $this->assertTrue($instance->validate());
        $instance->contributor = array('contributor');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testNarrator()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->narrator = 'narrator';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.narrator', $instance->getError());
        $instance->narrator = array(1);
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.narrator', $instance->getError());
        $instance->narrator = array('');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.narrator', $instance->getError());
        $instance->narrator = array();
        $this->assertTrue($instance->validate());
        $instance->narrator = array('narrator');
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testSize()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->size = null;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.size', $instance->getError());
        $instance->size = 'size';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.size', $instance->getError());
        $instance->size = -1;
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.size', $instance->getError());
        $instance->size = 0;
        $this->assertTrue($instance->validate());
    }

    /**
     * @group metadata
     * @group validate
     */
    public function testMeta()
    {
        $instance = $this->metadata;
        $this->assertTrue($instance->validate());
        $instance->meta = 'meta';
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.meta', $instance->getError());
        $instance->meta = array('meta');
        $this->assertFalse($instance->validate());
        $this->assertContains('metadata.meta', $instance->getError());
        $instance->meta = array();
        $this->assertTrue($instance->validate());
        $instance->meta = array(new meta('name', 'content'));
        $this->assertTrue($instance->validate());
    }
}

?>
