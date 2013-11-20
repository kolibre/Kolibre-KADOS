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
            return false;
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

    public function contentExists($contentId)
    {
    }

    public function contentAccessible($contentId)
    {
    }

    public function contentReturnDate($contentId)
    {
    }

    public function contentMetadata($contentId)
    {
    }

    public function contentIssuable($contentId)
    {
    }

    public function contentIssue($contentId)
    {
    }

    public function contentResources($contentId)
    {
    }

    public function contentReturnable($contentId)
    {
    }

    public function contentReturn($contentId)
    {
    }
}

?>
