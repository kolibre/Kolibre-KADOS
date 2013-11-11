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

require_once('Adapter.class.php');

class DemoAdapter extends Adapter
{
    public function label($id, $type, $language = null)
    {
    }

    public function authenticate($username, $password)
    {
    }

    public function contentListExists($list)
    {
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
    }

    public function contentExists($contentID)
    {
    }

    public function contentAccessible($contentID)
    {
    }

    public function contentReturnDate($contentID)
    {
    }

    public function contentMetadata($contentID)
    {
    }

    public function contentIssuable($contentID)
    {
    }

    public function contentIssue($contentID)
    {
    }

    public function contentResources($contentID)
    {
    }

    public function contentReturnable($contentID)
    {
    }

    public function contentReturn($contentID)
    {
    }
}

?>
