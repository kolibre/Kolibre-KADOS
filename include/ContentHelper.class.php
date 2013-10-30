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

require_once('log4php/Logger.php');
$contentLogger = Logger::getLogger('kolibre.daisyonline.contenthelper');
$loanDuration = 2592000; // 30 days in seconds

class ContentHelper
{

    /**
     * Helper function
     * Parse id
     * @return id as integer
     */
    public static function parseContentId($contentId)
    {
        global $contentLogger;
        if (strlen($contentId) < 5)
        {
            $msg = "length of content_id string ($contentId) smaller than 5, returning 0";
            $contentLogger->warn($msg);
            return 0;
        }
        return (int)(substr($contentId, 4));
    }

    /**
     * Helper function
     * Get user content
     * @return array of content or FALSE on failure
     */
    public static function getUserContent($pdo, $userId, $listId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT rowid, * FROM usercontent WHERE user_id = :userId AND contentlist_id = :listId ORDER BY updated_at DESC';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':userId' => $userId, ':listId' => $listId));
            $content = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        return $content;
    }

    /**
     * Helper function
     * Get an array with names of supported content lists
     * @return array with names or FALSE on failure
     */
    public static function getSupportedContentLists($pdo)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT name FROM contentlist';
            $sth = $pdo->prepare($query);
            $sth->execute();
            $lists = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $scl = array();
        foreach ($lists as $list)
            array_push($scl, $list['name']);

        return $scl;
    }

    /**
     * Helper function
     * Get id for a content list
     * @return id of content list or FALSE on failure
     */
    public static function getContentListId($pdo, $name)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT rowid FROM contentlist WHERE name = :name';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':name' => $name));
            $list = $sth->fetch(PDO::FETCH_ASSOC);
            if ($list === false)
            {
                $msg = "failed to find content list with name '$name'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $listId = $list['rowid'];

        return $listId;
    }

    /**
     * Helper function
     * Get description for a content list
     * @return string or FALSE on failure
     */
    public static function getContentListDescription($pdo, $name)
    {
        switch ($name)
        {
            case 'new':
                return 'This list contains new content';
            case 'issued':
                return 'This list contains issued content';
            case 'expired':
               return 'This list contains expired content';
            case 'returned':
                return 'This list contains returned content';
            default:
                return false;
        }
    }

    /**
     * Helper function
     * Get array of content format supported by service
     * @return array of names or FALSE on failure
     */
    public static function getServiceSupportedContentFormats($pdo)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT rowid, * FROM daisyformat';
            $sth = $pdo->prepare($query);
            $sth->execute();
            $daisyformats = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentFormats = array();
        foreach ($daisyformats as $daisyformat)
            $contentFormats[$daisyformat['format']] = $daisyformat['rowid'];

        return $contentFormats;
    }

    /** Helper function
     * Get daisy format id based on content id
     * @return daisy format id of content or FALSE on failure
     */
    public static function getDaisyFormatId($pdo, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT daisyformat_id FROM content WHERE rowid = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $content = $sth->fetch(PDO::FETCH_ASSOC);
            if ($content === false)
            {
                $msg = "failed to find content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $daisyFormatId = $content['daisyformat_id'];

        return $daisyFormatId;
    }

    /**
     * Helper function
     * Get content label audio size
     * @return size as integer of FALSE on failure
     */
    public static function getContentLabelAudioSize($pdo, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT size FROM contentaudio WHERE rowid = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $audio = $sth->fetch(PDO::FETCH_ASSOC);
            if ($audio === false)
            {
                $msg = "failed to find audio for content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $size = (int)$audio['size'];

        return $size;
    }

    /**
     * Helper function
     * Get metadata for content
     * @return array with metadata (key and values) or FALSE on failure
     */
    public static function getContentMetadata($pdo, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT key, value FROM contentmetadata WHERE content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $metadata = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentMetadata = array();
        foreach ($metadata as $data)
            $contentMetadata[$data['key']] = $data['value'];

        return $contentMetadata;
    }

    /**
     * Helper function
     * Get content category type
     */
    public static function getContentCategory($pdo, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT category.name FROM content
                JOIN category ON content.category_id = category.rowid
                WHERE content.rowid = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $category = $sth->fetch(PDO::FETCH_ASSOC);
            if ($category === false)
            {
                $msg = "failed to find category for content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentCategory = $category['name'];

        return $contentCategory;
    }

    /**
     * Helper function
     * Check if date is valid
     * @return boolean
     */
    public static function isValidDate($date)
    {
        if (is_null($date)) return false;
        $pattern = '/\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        if (preg_match($pattern, $date) == 0) return false;
        if ($date == '0000-00-00 00:00:00') return false;
        return true;
    }

    /**
     * Helper function
     * Query whether content must be return or not
     * @return date or FALSE if content does not require return
     */
    public static function contentRequiresReturn($pdo, $userId, $contentId)
    {
        global $contentLogger;
        global $loanDuration;
        try
        {
            $query = 'SELECT requires_return, return_by FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':userId' => $userId, ':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if ($row === false)
            {
                $msg = "failed to determine if user with '$userId' must return content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        if ($row['requires_return'] == 0) return false;

        $returnDate = $row['return_by'];
        if (!ContentHelper::isValidDate($returnDate))
        {
            $timestamp = time() + $loanDuration;
            $returnDate = date('Y-m-d H:i:s', $timestamp);
        }

        // mark content as not returned if content has been issued
        $issued = ContentHelper::contentInList($pdo, $userId, $contentId, 'issued');
        if ($issued === true)
        {
            try
            {
                $query = 'UPDATE usercontent SET return_by = :datetime, is_returned = 0 WHERE user_id = :userId AND content_id = :contentId';
                $sth = $pdo->prepare($query);
                $values[':datetime'] = $returnDate;
                $values[':userId'] = $userId;
                $values[':contentId'] = $contentId;
                $sth = $pdo->prepare($query);
                if ($sth->execute($values) === false)
                {
                    $msg = "failed to update return date and status";
                    $contentLogger->error($msg);
                    return false;
                }
            }
            catch (PDOException $e)
            {
                $contentLogger->fatal($e->getMessage());
                return false;
            }
        }
        return $returnDate;
    }

    /**
     * Helper function
     * Return content
     * @return true or FALSE on failure
     */
    public static function returnContent($pdo, $userId, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT is_returned FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':userId' => $userId, ':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        if ($row['is_returned'] == 1) return true;

        // mark content as returned
        try
        {
            $query = 'UPDATE usercontent SET is_returned = 1, contentlist_id = :listId WHERE user_id = :userId AND content_id = :contentId';
            $values = array();
            $values[':listId'] = ContentHelper::getContentListId($pdo, 'returned');
            $values[':userId'] = $userId;
            $values[':contentId'] = $contentId;
            $sth = $pdo->prepare($query);
            if ($sth->execute($values) === false)
            {
                $msg = "failed to mark content as returned";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }
        return true;
    }

    /**
     * Helper function
     * Get content resources total sum
     * @return integer or FALSE on failure
     */
    public static function getContentResourcesSize($pdo, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT SUM(bytes) as sum FROM contentresource WHERE content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $sum = $sth->fetch(PDO::FETCH_ASSOC);
            if ($sum === false)
            {
                $msg = "failed to calculate size for content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentResourcesSize = (int)$sum['sum'];

        return $contentResourcesSize;
    }

    /**
     * Helper function
     * Query if content is issuable or not
     * @return string with current
     */
    public static function contentIssuable($pdo, $userId, $contentId)
    {
        $contentIssuable = false;
        if (ContentHelper::contentInList($pdo, $userId, $contentId, 'new'))
            $contentIssuable = true;

        return $contentIssuable;
    }

    /**
     * Helper function
     * Check if content exists in list
     * @return boolean
     */
    public static function contentInList($pdo, $userId, $contentId, $listName)
    {
        global $contentLogger;
        try
        {
            $query = 'SELECT contentlist_id FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':userId' => $userId, ':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if ($row === false)
            {
                $msg = "failed to find content with id '$contentId' for user with id '$userId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentInList = false;;
        $listId = ContentHelper::getContentListId($pdo, $listName);
        if ($listId == $row['contentlist_id'])
            $contentInList = true;

        return $contentInList;
    }

    /**
     * Helper function
     * Issue content
     * @return boolean
     */
    public static function issueContent($pdo, $userId, $contentId)
    {
        global $contentLogger;
        try
        {
            $query = 'UPDATE usercontent SET contentlist_id = :listId, updated_at = :timestamp
                WHERE user_id = :userId AND content_id = :contentId';
            $values = array();
            $values[':listId'] = ContentHelper::getContentListId($pdo, 'issued');
            $values[':timestamp'] = date('Y-m-d H:i:s');
            $values[':userId'] = $userId;
            $values[':contentId'] = $contentId;
            $sth = $pdo->prepare($query);
            if ($sth->execute($values) === false)
            {
                $msg = "failed to issue content with id '$contentId' for user with id '$userId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentIssued = true;

        return $contentIssued;
    }

    /**
     * Helper function
     * Get content resources
     * @return array with resources or FALSE on failure
     */
    public static function getContentResources($pdo, $userId, $contentId)
    {
        global $contentLogger;
        try
        {
            $issued = ContentHelper::contentInList($pdo, $userId, $contentId, 'issued');
            if ($issued === false)
            {
                $msg = "User not allowed to get content resources";
                $contentLogger->warn($msg);
                return array();
            }

            $query = 'SELECT filename, bytes, mimetype FROM contentresource WHERE content_id = :contentId';
            $sth = $pdo->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $resources = $sth->fetchAll(PDO::FETCH_ASSOC);
            if ($resources === false)
            {
                $msg = "failed to fetch resources for content with id '$contentId'";
                $contentLogger->error($msg);
                return false;
            }
        }
        catch (PDOException $e)
        {
            $contentLogger->fatal($e->getMessage());
            return false;
        }

        $contentResources = array();
        foreach ($resources as $resource)
        {
            $contentResource = array();
            $contentResource['filename'] = $resource['filename'];
            $contentResource['mimetype'] = $resource['mimetype'];
            $contentResource['size'] = $resource['bytes'];
            array_push($contentResources, $contentResource);
        }

        return $contentResources;
    }
}
?>
