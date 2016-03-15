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

require_once('contentItem.class.php');

class contentItemTest extends PHPUnit_Framework_TestCase
{   

    protected $contentItem;
    /**
     * @before
     */
     public function setUp()
     {  
        $this->audio = new audio('localuri',1,15,1234);
        
        $this->valid_label = new label('text',$this->audio,'lang','ltr');
        $this->assertTrue($this->valid_label->validate());
        
        $this->invalid_label = new label(NULL, $this->audio, 'lang','ltr');
                $this->assertFalse($this->invalid_label->validate());

        $this->valid_sample = new sample('id');
        $this->assertTrue($this->valid_sample->validate());
        $this->invalid_sample = new sample(2);
        $this->assertFalse($this->invalid_sample->validate());

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
        $this->valid_metadata = new metadata($title,$identifier,$publisher, $format,$date,$source,$type,$subject,$rights,$relation,$language,
            $description,$creator,$coverage,$contributor,$narrator,$size,
            $meta);
        $this->assertTrue($this->valid_metadata->validate());
        
        $this->invalid_metadata = new metadata(NULL,$identifier,$publisher, $format,$date,$source,$type,$subject,$rights,$relation,$language,
            $description,$creator,$coverage,$contributor,$narrator,$size,
            $meta);
        $this->assertFalse($this->invalid_metadata->validate());

        $this->valid_categoryLabel = new categoryLabel($this->valid_label);
        $this->assertTrue($this->valid_categoryLabel->validate());
        
        $this->invalid_categoryLabel = new categoryLabel(NULL);
        $this->assertFalse($this->invalid_categoryLabel->validate());

        $this->valid_subCategoryLabel = new subCategoryLabel($this->valid_label);
        $this->assertTrue($this->valid_subCategoryLabel->validate());
        $this->invalid_subCategoryLabel = new subCategoryLabel(NULL);
        $this->assertFalse($this->invalid_subCategoryLabel->validate());

        $this->valid_accessPermission = "STREAM_ONLY";
        $this->invalid_accessPermission = '';

        $this->valid_lastmark = new lastmark('uri','uri','timeoffset',15);
        $this->assertTrue($this->valid_lastmark->validate());
        $this->invalid_lastmark = new lastmark('','uri','timeoffset',15);
        $this->assertFalse($this->invalid_lastmark->validate());

        $this->choice = new choice($this->valid_label,'id');
        $this->assertTrue($this->choice->validate());
        $this->assertTrue($this->valid_label->validate());
        $array = array($this->choice);
        $this->choices = new choices($array);
        $this->valid_multipleChoiceQuestion = new multipleChoiceQuestion($this->valid_label,$this->choices,'id',true);
        $this->assertTrue($this->valid_multipleChoiceQuestion->validate());

        $this->invalid_multipleChoiceQuestion = new multipleChoiceQuestion($this->valid_label,$this->choices,'id',NULL);
        $this->assertTrue($this->invalid_multipleChoiceQuestion->validate());

        $this->valid_Id = 'id';
        $this->invalid_Id = NULL;

        $this->valid_FirstAccessDate = '2016-03-11T14:23:23+00:00';
        $this->invalid_FirstAccessDate = '';

        $this->valid_LastAccessDate = '2016-03-11T14:23:23+00:00';
        $this->invalid_LastAccessDate = '';

        $this->valid_LastModifiedDate = '2016-03-11T14:23:23+00:00';
        $this->invalid_LastModifiedDate = '';

        $this->valid_Category = 'category';
        $this->invalid_Category = '';

        $this->valid_SubCategory = 'subCategory';
        $this->invalid_SubCategory = '';

        $this->valid_ReturnBy = '2016-03-11T14:23:23+00:00';
        $this->invalid_ReturnBy = '';

        $this->valid_HasBookmarks = True;
        $this->invalid_HasBookmarks = NULL;

        $this->contentItem = new contentItem(
            $this->valid_label,
            $this->valid_sample,
            $this->valid_metadata, 
            $this->valid_categoryLabel, 
            $this->valid_subCategoryLabel, 
            $this->valid_accessPermission, 
            $this->valid_lastmark, 
            $this->valid_multipleChoiceQuestion,
            $this->valid_Id,
            $this->valid_FirstAccessDate,
            $this->valid_LastAccessDate, 
            $this->valid_LastAccessDate, 
            $this->valid_Category, 
            $this->valid_SubCategory, 
            $this->valid_ReturnBy, 
            $this->valid_HasBookmarks);
     }

    /**
     * @group contentItem
     * @group validate
     */
    public function testLabel()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->label = $this->invalid_label;
        $this->assertFalse($instance->validate());
    }

    public function testSample()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->sample = $this->invalid_sample;
        $this->assertFalse($instance->validate());
    }

    public function testMetadata()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->metadata = $this->invalid_metadata;
        $this->assertFalse($instance->validate());
    }

    public function testCategotyLabel()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->categoryLabel = $this->invalid_categoryLabel;
        $this->assertFalse($instance->validate());
    }

    public function testSubCategotyLabel()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->subCategoryLabel = $this->invalid_subCategoryLabel;
        $this->assertFalse($instance->validate());
    }

    public function testAccessPermission()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->accessPermission = $this->invalid_accessPermission;
        $this->assertFalse($instance->validate());
    }

    public function testLastmark()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->lastmark = $this->invalid_lastmark;
        $this->assertFalse($instance->validate());
    }

    public function testMultipleChoiceQuestion()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->multipleChoiceQuestion = $this->invalid_multipleChoiceQuestion;
        $this->assertTrue($instance->validate());
    }

    public function testId()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->id = $this->invalid_Id;
        $this->assertFalse($instance->validate());
    }

    public function testFirstAccessedDate()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->firstAccessedDate = $this->invalid_FirstAccessDate;
        $this->assertFalse($instance->validate());
    }

    public function testLastAccessedDate()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->lastAccessedDate = $this->invalid_LastAccessDate;
        $this->assertFalse($instance->validate());
    }

    public function testLastModifiedDate()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->lastModifiedDate = $this->invalid_LastModifiedDate;
        $this->assertFalse($instance->validate());
    }

    public function testCategory()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->category = $this->invalid_Category;
        $this->assertFalse($instance->validate());
    }

    public function testSubCategory()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->subCategory = $this->invalid_SubCategory;
        $this->assertFalse($instance->validate());
    }

    public function testReturnBy()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->returnBy = $this->invalid_ReturnBy;
        $this->assertFalse($instance->validate());
    }

    public function testHasBookmark()
    {
        $instance = $this->contentItem; 
        $this->assertTrue($instance->validate());

        $instance->returnBy = $this->invalid_HasBookmarks;
        $this->assertTrue($instance->validate());
    }
    
}

?>
