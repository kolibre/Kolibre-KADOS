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

$includePath = dirname(realpath(__FILE__)) . '/../../include/adapter';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('Adapter.class.php');

class TestAdapter extends Adapter
{
    protected $sessionStarted = false;

    public function startSession()
    {
        if ($this->sessionStarted === false)
        {
            $this->sessionStarted = true;
            return true;
        }

        return true;
    }

    public function label($id, $type, $language = null)
    {
        $audio = array();
        $audio['uri'] = 'uri';
        $audio['rangeBegin'] = 0;
        $audio['rangeEnd'] = 1;
        $audio['size'] = 2;

        $label = array();
        $label['text'] = 'text';
        $label['lang'] = 'en';
        $label['dir'] = 'ltr';
        $label['audio'] = $audio;

        switch ($type)
        {
        case Adapter::LABEL_SERVICE:
            if ($id == 'label-exception')
                throw new AdapterException('Error in adapter');
            if ($id == 'org-kolibre')
                return $label;
            break;
        case Adapter::LABEL_CONTENTLIST:
            if ($id == 'empty-list-label-exception')
                throw new AdapterException('Error in adapter');
            if ($id == 'empty-list-label')
                return $label;
            break;
        case Adapter::LABEL_CONTENTITEM:
            if ($id == 'valid-list-label-exception')
                throw new AdapterException('Error in adapter');
            return $label;
            break;
        case Adapter::LABEL_CATEGORY:
            return $label;
            break;
        case Adapter::LABEL_SUBCATEGORY:
            return $label;
            break;
        default:
            return false;
        }

        return false;
    }

    public function authenticate($username, $password)
    {
        if ($username == 'exception' && $password == 'exception')
            throw new AdapterException('Error in adapter');

        if ($username == 'invalid' && $password == 'invalid')
            return false;

        if ($username == 'valid' && $password == 'valid')
            return true;

        return false;
    }

    public function contentListExists($list)
    {
        if ($list == 'exception-list-exists')
            throw new AdapterException('Error in adapter');

        if ($list == 'invalid-list')
            return false;

        return true;
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
        if ($list == 'exception-list')
            throw new AdapterException('Error in adapter');

        if ($list == 'valid-list-label-exception')
            return array('valid-list-label-exception');

        if ($list == 'sublist-first-item-exceed-total-items' || $list == 'sublist-last-item-exceed-total-items')
            return array('valid-identifier-1', 'valid-identifier-2', 'valid-identifier-3');

        if ($list == 'sublist-single-item' || $list == 'sublist-multiple-items' || $list == 'full-list')
            return array('valid-identifier-1', 'valid-identifier-2', 'valid-identifier-3');

        return array();
    }

    public function contentLastModifiedDate($contentId)
    {
        if ($contentId == 'exception-content-lastmodifieddate')
            throw new AdapterException('Error in adapter');

        return '1970-01-01T00:00:00+00:00';
    }

    public function contentAccessDate($contentId)
    {
        return array('first' => '1970-01-01T00:00:00+00:00', 'last' => '1970-01-01T00:00:00+00:00');
    }

    public function contentAccessMethod($contentId)
    {
        // TODO: implement test cases
        return Adapter::ACCESS_STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED;
    }

    public function contentExists($contentId)
    {
        if ($contentId == 'exception-content-exists')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-exists')
            return false;

        return true;
    }

    public function contentAccessible($contentId)
    {
        if ($contentId == 'exception-content-accessible')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-accessible')
            return false;

        return true;
    }

    public function contentSample($contentId)
    {
        if ($contentId == 'exception-content-sample')
            throw new AdapterException('Error in adapter');

        return 'sample';
    }

    public function contentCategory($contentId)
    {
        if ($contentId == 'exception-content-category')
            throw new AdapterException('Error in adapter');

        return 'category';
    }

    public function contentSubCategory($contentId)
    {
        if ($contentId == 'exception-content-category')
            throw new AdapterException('Error in adapter');

        return 'subCategory';
    }

    public function contentReturnDate($contentId)
    {
        if ($contentId == 'exception-content-returndate')
            throw new AdapterException('Error in adapter');

        return '1970-01-01T00:00:00+00:00';
    }

    public function contentMetadata($contentId)
    {
        if ($contentId == 'exception-content-metadata')
            throw new AdapterException('Error in adapter');

        $validContentIds = array('valid-identifier-1', 'valid-identifier-2', 'valid-identifier-3', 'valid-content-metadata');
        if (in_array($contentId, $validContentIds) === true)
        {
            $metadata = array();
            $metadata['dc:title'] = 'title';
            $metadata['dc:identifier'] = 'identifier';
            $metadata['dc:publisher'] = 'publisher';
            $metadata['dc:format'] = 'format';
            $metadata['dc:date'] = 'date';
            $metadata['dc:source'] = 'source';
            $metadata['dc:type'] = 'type';
            $metadata['dc:subject'] = 'subject';
            $metadata['dc:rights'] = 'rights';
            $metadata['dc:relation'] = 'relation';
            $metadata['dc:language'] = 'language';
            $metadata['dc:description'] = 'description';
            $metadata['dc:creator'] = 'creator';
            $metadata['dc:coverage'] = 'coverage';
            $metadata['dc:contributor'] = 'contributor';
            $metadata['dc:narrator'] = 'narrator';
            $metadata['size'] = 1;
            $metadata['meta'] = 'meta';
            return $metadata;

        }

        return array();
    }

    public function contentIssuable($contentId)
    {
        if ($contentId == 'exception-content-issuable')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-issuable')
            return false;

        return true;
    }

    public function contentIssue($contentId)
    {
        if ($contentId == 'exception-content-issue')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-issue')
            return false;

        return true;
    }

    public function contentResources($contentId, $accessMethod = null)
    {
        if ($contentId == 'exception-content-resources')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-resources')
        {
            $resources = array(array('uri' => 'uri'));
            return $resources;
        }

        if ($contentId == 'valid-content-resources')
        {
            $resources = array();
            $resource = array();
            $resource['uri'] = 'uri';
            $resource['mimeType'] = 'mimeType';
            $resource['size'] = 1;
            $resource['localURI'] = 'localURI';
            $resource['lastModifiedDate'] = '1970-01-01T00:00:00+00:00';
            $resource['serverSideHash'] = 'md5';
            array_push($resources, $resource);
            array_push($resources, $resource);
            array_push($resources, $resource);
            return $resources;
        }

        if ($contentId == 'valid-content-resources-with-package')
        {
            $resources = array();
            $resource = array();
            $resource['uri'] = 'uri';
            $resource['mimeType'] = 'mimeType';
            $resource['size'] = 1;
            $resource['localURI'] = 'localURI';
            $resource['lastModifiedDate'] = '1970-01-01T00:00:00+00:00';
            $resource['serverSideHash'] = 'md5';
            array_push($resources, $resource);
            array_push($resources, $resource);
            array_push($resources, $resource);
            $package = array();
            $package['uri'] = 'uri';
            $package['mimeType'] = 'mimeType';
            $package['size'] = 1;
            $package['lastModifiedDate'] = '1970-01-01T00:00:00+00:00';
            $package['resourceRef'] = array('localURI');
            array_push($resources, $package);
            return $resources;
        }

        return array();
    }

    public function contentReturnable($contentId)
    {
        if ($contentId == 'exception-content-returnable')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-returnable')
            return false;

        return true;
    }

    public function contentReturn($contentId)
    {
        if ($contentId == 'exception-content-return')
            throw new AdapterException('Error in adapter');

        if ($contentId == 'invalid-content-return')
            return false;

        return true;
    }
}

?>
