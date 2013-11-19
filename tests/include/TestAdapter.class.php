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
    public function label($id, $type, $language = null)
    {
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
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
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
