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

class SystemTestAdapter extends Adapter
{
    protected $sessionActive = true;
    protected $contentLists = array('bookshelf' => array('id_1','id_2'));

    public function startSession()
    {
        return $this->sessionActive;
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
        case Adapter::LABEL_CONTENTITEM:
            return $label;
        }

        return false;
    }

    public function authenticate($username, $password)
    {
        if ($username == 'invalid' && $password == 'invalid')
            return false;

        if ($username == 'valid' && $password == 'valid')
            return true;

        return false;
    }

    public function contentListExists($list)
    {
        return true;
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
        $contentList = array();
        if ($list == 'stop-backend-session') $this->sessionActive = false;
        if (array_key_exists($list, $this->contentLists))
        {
            foreach ($this->contentLists[$list] as $item)
                array_push($contentList, $item);
        }
        return $contentList;
    }

    public function contentLastModifiedDate($contentId)
    {
        // TODO: implement test cases
        //return false;
        return '2016-03-11T14:23:23+00:00';
    }

    public function contentAccessMethod($contentId)
    {
        // TODO: implement test cases
        return Adapter::ACCESS_STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED;
    }

    public function contentExists($contentId)
    {
        return true;
    }

    public function contentAccessible($contentId)
    {
        return true;
    }

    public function contentReturnDate($contentId)
    {
        return '2016-03-11T14:23:23+00:00';
    }

    public function contentMetadata($contentId)
    {
        $metadata = array();
        $metadata['size'] = 1;
        $metadata['dc:title'] = 'dc:title';
        $metadata['dc:identifier'] = 'dc:identifier';
        $metadata['dc:publisher'] = 'dc:publisher';
        $metadata['dc:format'] = 'dc:format';
        $metadata['dc:date'] = 'dc:date';
        $metadata['dc:source'] = 'dc:source';
        $metadata['dc:type'] = 'dc:type';
        $metadata['dc:subject'] = 'dc:subject';
        $metadata['dc:rights'] = 'dc:rights';
        $metadata['dc:relation'] = 'dc:relation';
        $metadata['dc:language'] = 'dc:language';
        $metadata['dc:description'] = 'dc:description';
        $metadata['dc:creator'] = 'dc:creator';
        $metadata['dc:coverage'] = 'dc:coverage';
        $metadata['dc:contributor'] = 'dc:contributor';
        $metadata['pdtb2:specVersion'] = 'PDTB2';
        return $metadata;
    }

    public function contentIssuable($contentId)
    {
        return true;
    }

    public function contentIssue($contentId)
    {
        if (in_array($contentId, $this->contentLists['new']))
        {
            $this->contentLists['new'] = array_diff($this->contentLists['new'], array($contentId));
            array_push($this->contentLists['issued'], $contentId);
        }
        return true;
    }

    public function contentResources($contentId, $accessMethod = null)
    {
        $resources = array();
        $resource = array();
        $resource['uri'] = 'uri';
        $resource['mimeType'] = 'mimeType';
        $resource['size'] = 1;
        $resource['localURI'] = 'localURI';
        $resource['lastModifiedDate'] = '2016-03-11T14:23:23+00:00';
        array_push($resources, $resource);
        return $resources;
    }

    public function contentReturnable($contentId)
    {
        return true;
    }
    public function contentReturn($contentId)
    {
        $key = array_search($contentId, $this->contentLists['bookshelf']);
        if ($key !== false)
        {
            unset($this->contentLists['bookshelf'][$key]);
            return true;
        }
        return false;
    }

    public function contentAccessState($contentId, $state)
    {
        return true;
    }

    public function termsOfService()
    {
        $label = array();
        $label['text'] = "No Terms";
        $label['lang'] = "en";
        return $label;
    }

    public function termsOfServiceAccept()
    {
        return true;
    }

    public function termsOfServiceAccepted()
    {
        return true;
    }
}

?>
