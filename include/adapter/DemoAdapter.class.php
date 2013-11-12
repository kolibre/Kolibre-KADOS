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
require_once('Adapter.class.php');

class DemoAdapter extends Adapter
{
    // placeholders for storing user information
    private $userLoggingEnabled = false;

    // duration for a loan
    private $loanDuration = 2592000; // 30 days in seconds

    // logger instance
    private $logger = null;

    // database connection handler
    private $dbh = null;

    public function __construct($database = null)
    {
        // stup logger
        $this->setupLogger();

        // setup database connection
        $this->setupDatabase($database);
    }

    /**
     * Invoked when restoring object from session
     */
    public function __wakeup()
    {
        // stup logger
        $this->setupLogger();

        // setup database connection
        $this->setupDatabase();
    }

    /**
     * Invoked when storing object to session
     */
    public function __sleep()
    {
        $instance_variables_to_serialize = array();
        array_push($instance_variables_to_serialize, 'user');
        array_push($instance_variables_to_serialize, 'userLoggingEnabled');
        return $instance_variables_to_serialize;
    }

    private function setupLogger()
    {
        $this->logger = Logger::getLogger('kolibre.daisyonline.demoadapter');
    }

    private function setupDatabase($database = null)
    {
        if (is_null($database))
        {
            $database = realpath(dirname(__FILE__)) . '/../../data/db/demo.db';
        }

        if (file_exists($database) === false)
        {
            $this->logger->error("File '$database' does not exist");
            return;
        }

        try
        {
            $this->dbh = new PDO("sqlite:$database");
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
        }
    }

    public function logSoapRequestAndResponse($request, $response, $timestamp, $ip)
    {
        if ($this->userLoggingEnabled === false) return;

        try
        {
            $query = 'INSERT INTO userlog VALUES(:user_id, :datetime, :request, :response, :ip)';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':user_id'] = $this->user;
            $values[':datetime'] = date('Y-m-d H:i:s', $timestamp);
            $values[':request'] = $request;
            $values[':response'] = $response;
            $values[':ip'] = $ip;
            if ($sth->execute($values) === false)
                $this->logger->error("Insert row to userlog failed");
        }
        catch (PDOException $e)
        {
            $msg = $e->getMessage();
            $this->logger->fatal($msg);
        }
    }

    public function serviceBaseUri($allowencrypted = false)
    {
        $protocol = 'http';
        if ($allowencrypted === true)
        {
            if (isset($_SERVER['HTTPS'])) $protocol = 'https';
        }

        $host = 'localhost';
        if (isset($_SERVER['SERVER_NAME'])) $host = $_SERVER['SERVER_NAME'];

        $port = '80';
        if (isset($_SERVER['SERVER_PORT']))
        {
            switch ($protocol)
            {
                case 'http':
                    if (!($_SERVER['SERVER_PORT'] == 80 || $_SERVER['SERVER_PORT'] == 443))
                        $port = $_SERVER['SERVER_PORT'];
                    break;
                case 'https':
                    if ($_SERVER['SERVER_PORT'] != 443)
                        $port = $_SERVER['SERVER_PORT'];
                    break;
            }
        }

        $path = '';
        if (isset($_SERVER['SCRIPT_NAME'])) $path = dirname($_SERVER['SCRIPT_NAME']);
        if (strlen($path) > 0 && substr($path, -1) != '/') $path .= '/';

        return "$protocol://$host:$port$path";
    }

    public function extractId($identifier)
    {
        if (strlen($identifier) < 5)
        {
            $this->logger->warn("Length of string '$identifier' smaller then 5");
            return -1;
        }
        return (int)(substr($identifier, 4));
    }

    public function contentAudioUri($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        $filename = "content_$contentID.ogg";
        return $this->serviceBaseUri()."media/$filename";
    }

    public function contentAudioSize($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT size FROM contentaudio WHERE rowid = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content audio size failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No content audio found with content id '$contentID'");
            return -1;
        }

        return (int)$row['size'];
    }

    public function contentTitle($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT title FROM content WHERE rowid = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content title failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No content found with id '$contentID'");
            return 'unknown';
        }

        return $row['title'];
    }

    public function contentTitleLang($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT value FROM contentmetadata WHERE content_id = :contentId AND key = :key';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID, ':key' => 'dc:language'));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content title language failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No dc:language metadata found for content id '$contentID'");
            return 'i-unknown';
        }

        return $row['value'];
    }

    public function label($id, $type, $language = null)
    {
        switch ($type)
        {
        case Adapter::LABEL_CONTENTITEM:
            $label = array();
            $label['text'] = $this->contentTitle($id);
            $label['lang'] = $this->contentTitleLang($id);
            $audio = array();
            $audio['uri'] = $this->contentAudioUri($id);
            $size = $this->contentAudioSize($id);
            if ($size > 0) $audio['size'] = $size;
            $label['audio'] = $audio;
            return $label;
        default:
            return false;
        }
    }

    public function authenticate($username, $password)
    {
        try
        {
            $query = 'SELECT rowid, * FROM user WHERE username = :username AND password = :password';
            $sth = $this->dbh->prepare($query);
            $values = array(':username' => $username, ':password' => $password);
            $sth->execute($values);
            $users = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Authentication failed');
        }

        if (sizeof($users) == 0)
        {
            $msg = "No user found with username = '$username' and password = '********'";
            $this->logger->warn($msg);
            return false;
        }
        else if (sizeof($users) > 1)
        {
            $count = sizeof($users);
            $msg = "$count users found with username = '$username' and password = '********'";
            $this->logger->error($msg);
            return false;
        }

        $this->user = $users[0]['rowid'];
        if ($users[0]['log'] == 1)
            $this->userLoggingEnabled = true;

        return true;
    }

    public function contentListExists($list)
    {
        try
        {
            $query = 'SELECT name FROM contentlist WHERE name = :name';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':name' => $list));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Checking if list exists failed');
        }

        if ($row === false) return false;

        return true;
    }

    public function contentListId($list)
    {
        try
        {
            $query = 'SELECT rowid FROM contentlist WHERE name = :name';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':name' => $list));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving list id failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No list found with name '$list'");
            return -1;
        }

        return $row['rowid'];
    }

    public function supportedContentFormats()
    {
        try
        {
            $query = 'SELECT rowid, * FROM daisyformat';
            $sth = $this->dbh->prepare($query);
            $sth->execute();
            $formats = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving supported content formats failed');
        }

        $contentFormats = array();
        foreach ($formats as $format)
            $contentFormats[$format['format']] = $format['rowid'];

        return $contentFormats;
    }

    public function contentFormatId($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT daisyformat_id FROM content WHERE rowid = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content format id failed');
        }

        if ($row === false)
        {
            $this->logger->warn->error("No content found with id '$contentID'");
            return -1;
        }

        return $row['daisyformat_id'];
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
        $listId = $this->contentListId($list);

        try
        {
            $query = 'SELECT rowid, * FROM usercontent WHERE user_id = :userId AND contentlist_id = :listId ORDER BY updated_at DESC';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':listId' => $listId));
            $content = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving user content failed');
        }

        // check if filter must be applied
        $filterContent = false;
        if (is_null($contentFormats) === false || is_null($protectionFormats) === false || is_null($mimeTypes) === false)
            $filterContent = true;

        if ($filterContent)
            $this->logger->debug("Content items before filtering: " . sizeof($content));

        $contentList = array();
        foreach ($content as $item)
        {
            $contentID = "con_" . $item['rowid'];

            // filter content based on DAISY format
            if (is_null($contentFormats) === false && is_array($contentFormats))
            {
                $scf = $this->supportedContentFormats();
                $formatFilter = array_intersect_ukey($scf, array_flip($contentFormats), 'strcasecmp');
                $contentFormatId = $this->contentFormatId($contentID);
                if (in_array($contentFormatId, $formatFilter) === false)
                    continue;
                array_push($contentList, $contentID);
            }
            else
                array_push($contentList, $contentID);
        }

        if ($filterContent)
            $this->logger->debug("Content items after filtering: " . sizeof($contentList));

        return $contentList;
    }

    public function contentExists($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT rowid FROM content WHERE rowid = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Checking if content exists failed');
        }

        if ($row === false) return false;
        return true;
    }

    public function contentAccessible($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT rowid FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Checking if content is accessible failed');
        }

        if ($row === false) return false;
        return true;
    }

    public function contentCategory($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT category.name FROM content
                JOIN category ON content.category_id = category.rowid
                WHERE content.rowid = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content category failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No category found for content with id '$contentID'");
            return false;
        }

        return $row['name'];
    }

    public function isValidDate($date)
    {
        if (is_null($date)) return false;
        $pattern = '/\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        if (preg_match($pattern, $date) == 0) return false;
        if ($date == '0000-00-00 00:00:00') return false;
        return true;
    }

    public function contentReturnDate($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT requires_return, return_by FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content return date failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No usercontent found for user id '$this->user' and content id '$contentID'");
            return false;
        }

        if ($row['requires_return'] == 0) return false;

        $returnDate = $row['return_by'];
        if ($this->isValidDate($returnDate) === false)
        {
            $timestamp = time() + $this->loanDuration;
            $returnDate = date('Y-m-d H:i:s', $timestamp);
        }

        return $returnDate;
    }

    public function contentMetadata($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT key, value FROM contentmetadata WHERE content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $metadata = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content metadata failed');
        }

        $contentMetadata = array();
        foreach ($metadata as $data)
            $contentMetadata[$data['key']] = $data['value'];

        try
        {
            $query = 'SELECT SUM(bytes) as size FROM contentresource WHERE content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentID));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content total size failed');
        }

        if ($row === false || (int)$row['size'] == 0)
            $this->logger->warn("Calculating total size for content with id '$contentID' failed");
        else
            $contentMetadata['size'] = (int)$row['size'];

        return $contentMetadata;
    }

    public function contentInList($contentID, $list)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        try
        {
            $query = 'SELECT contentlist.name FROM usercontent
                JOIN contentlist ON usercontent.contentlist_id = contentlist.rowid
                WHERE user_id = :userId AND content_id = :contentId AND contentlist.name = :list';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentID, ':list' => $list));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving list which content resides in failed');
        }

        if ($row === false) return false;
        return true;
    }

    public function contentIssuable($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        if ($this->contentInList($contentID, 'new') || $this->contentInList($contentID, 'issued'))
            return true;

        return false;
    }

    public function contentIssue($contentID)
    {
        if (is_string($contentID))
            $contentID = $this->extractId($contentID);

        if ($this->contentInList($contentID, 'expired') || $this->contentInList($contentID, 'returned'))
            return false;

        if ($this->contentInList($contentID, 'issued'))
            return true;

        if ($this->contentInList($contentID, 'new'))
        {
            try
            {
                $query = 'UPDATE usercontent SET contentlist_id = :listId, updated_at = :timestamp
                    WHERE user_id = :userId AND content_id = :contentId';
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':listId'] = $this->contentListId('issued');
                $values[':timestamp'] = date('Y-m-d H:i:s');
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentID;
                if ($sth->execute($values) === false)
                {
                    $this->logger->error("Issuing content with id '$contentID' for user with id '$this->user' failed");
                    return false;
                }
                return true;
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Issuing content failed');
            }
        }

        return false;
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
