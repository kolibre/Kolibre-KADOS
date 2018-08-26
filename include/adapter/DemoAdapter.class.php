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

$filePath = dirname(realpath(__FILE__));
set_include_path(get_include_path() . PATH_SEPARATOR . $filePath . '/../../');

require_once('vendor/autoload.php');
require_once('Adapter.class.php');
require_once('include/bookmarkSet_serialize.php');
require_once('include/types/bookmarkSet.class.php');

class DemoAdapter extends Adapter
{
    const MENU_DEFAULT = 0;
    const MENU_SEARCH = 2;
    const MENU_BROWSE = 3;
    const MENU_FEEDBACK = 4;
    const MENU_SEARCH_BY_AUTHOR = 25;
    const MENU_SEARCH_BY_TITLE = 26;
    const MENU_BROWSE_BY_TITLE = 34;
    const MENU_BROWSE_BY_DAISY2 = 35;
    const MENU_BROWSE_BY_DAISY3 = 36;

    // placeholders for storing user information
    private $userLoggingEnabled = false;

    // duration for a loan
    private $loanDuration = 2592000; // 30 days in seconds

    // logger instance
    private $logger = null;

    // database connection handler
    private $dbh = null;

    // preferredLang is the users preferred language
    private $preferredLang = null;

    public function __construct($database = null)
    {
        // setup logger
        $this->setupLogger();

        // setup database connection
        $this->setupDatabase($database);
    }

    /**
     * Invoked when restoring object from session
     */
    public function __wakeup()
    {
        // setup logger
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
            $query = "INSERT INTO userlog ('user_id', 'datetime', 'request', 'response', 'ip')
                VALUES(:user_id, :datetime, :request, :response, :ip)";
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

        $port = '';
        if (isset($_SERVER['SERVER_PORT']))
        {
            switch ($protocol)
            {
                case 'http':
                    if ($_SERVER['SERVER_PORT'] != 80)
                        $port = ':' . $_SERVER['SERVER_PORT'];
                    break;
                case 'https':
                    if ($_SERVER['SERVER_PORT'] != 443)
                        $port = ':' . $_SERVER['SERVER_PORT'];
                    break;
            }
        }

        $path = '';
        if (isset($_SERVER['SCRIPT_NAME'])) $path = dirname($_SERVER['SCRIPT_NAME']);
        if (strlen($path) > 0 && substr($path, -1) != '/') $path .= '/';

        return "$protocol://$host$port$path";
    }

    public function extractId($identifier)
    {
        if (is_int($identifier)) return $identifier;

        if (is_string($identifier) && strlen($identifier) < 5)
        {
            $this->logger->warn("Length of string '$identifier' smaller then 5");
            return -1;
        }
        return (int)(substr($identifier, 4));
    }

    public function contentAudioUri($contentId)
    {
        $contentId = $this->extractId($contentId);

        $filename = "content_$contentId.ogg";
        return $this->serviceBaseUri()."media/$filename";
    }

    public function contentAudioSize($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT size FROM contentaudio WHERE id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content audio size failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No content audio found with content id '$contentId'");
            return -1;
        }

        return (int)$row['size'];
    }

    public function contentTitle($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT title FROM content WHERE id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content title failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No content found with id '$contentId'");
            return 'unknown';
        }

        return $row['title'];
    }

    public function contentTitleLang($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT value FROM contentmetadata WHERE content_id = :contentId AND key = :key';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId, ':key' => 'dc:language'));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content title language failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No dc:language metadata found for content id '$contentId'");
            return 'i-unknown';
        }

        return $row['value'];
    }

    public function announcementAudioUri($announcementId)
    {
        $filename = "announcement_$announcementId.ogg";
        return $this->serviceBaseUri()."media/$filename";
    }

    public function announcementLabel($announcementId, $language = 'en')
    {
        $announcementId = $this->extractId($announcementId);

        try
        {
            $query = 'SELECT
                        announcementtext.text as text,
                        language.lang as lang,
                        announcementaudio.size as size,
                        announcementaudio.id as id
                    FROM announcement
                    JOIN announcementtext ON announcement.id = announcementtext.announcement_id
                    JOIN language ON announcementtext.language_id = language.id
                    JOIN announcementaudio ON announcementtext.id = announcementaudio.announcementtext_id
                    WHERE announcement.id = :announcementId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':announcementId' => $announcementId));
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving announcement label failed');
        }

        $label = null;
        foreach ($rows as $row)
        {
            $tmpLabel = array();
            $tmpLabel['text'] = $row['text'];
            $tmpLabel['lang'] = $row['lang'];
            $audio = array();
            $audio['uri'] = $this->announcementAudioUri($row['id']);
            $size = $row['size'];
            if ($size > 0) $audio['size'] = $size;
            $tmpLabel['audio'] = $audio;
            if (is_null($label)) $label = $tmpLabel;
            if ($row['lang'] == $language)
            {
                $label = $tmpLabel;
                break;
            }
        }

        return $label;
    }

    public function questionAudioUri($questionId)
    {
        $filename = "question_$questionId.ogg";
        return $this->serviceBaseUri()."media/$filename";
    }

    public function questionLabel($questionId, $language = 'en')
    {
        $questionId = $this->extractId($questionId);

        try
        {
            $query = 'SELECT
                        questiontext.text as text,
                        language.lang as lang,
                        questionaudio.size as size,
                        questionaudio.id as id
                    FROM question_questiontext
                    JOIN questiontext ON question_questiontext.questiontext_id = questiontext.id
                    JOIN language ON questiontext.language_id = language.id
                    JOIN questionaudio ON questiontext.id = questionaudio.questiontext_id
                    WHERE question_questiontext.question_id = :questionId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':questionId' => $questionId));
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving question label failed');
        }

        $label = null;
        foreach ($rows as $row)
        {
            $tmpLabel = array();
            $tmpLabel['text'] = $row['text'];
            $tmpLabel['lang'] = $row['lang'];
            $audio = array();
            $audio['uri'] = $this->questionAudioUri($row['id']);
            $size = $row['size'];
            if ($size > 0) $audio['size'] = $size;
            $tmpLabel['audio'] = $audio;
            if (is_null($label)) $label = $tmpLabel;
            if ($row['lang'] == $language)
            {
                $label = $tmpLabel;
                break;
            }
        }

        return $label;
    }

    public function label($id, $type, $language = null)
    {
        // save preferred lang for later use
        if (is_null($this->preferredLang) && !is_null($language)) $this->preferredLang = $language;

        switch ($type)
        {
        case Adapter::LABEL_SERVICE:
            if ($id != 'org-kolibre-kados') return false;
            $label = array();
            $label['text'] = 'Kados demo service';
            $label['lang'] = 'en';
            $audio = array();
            $audio['uri'] = $this->serviceBaseUri() . 'media/service.ogg';
            $audiofile = realpath(dirname(__FILE__)) . '/../../data/media/service.ogg';
            $audio['size'] = filesize($audiofile);
            $label['audio'] = $audio;
            return $label;
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
        case Adapter::LABEL_ANNOUNCEMENT:
            $label = $this->announcementLabel($id, $language);
            return $label;
        case Adapter::LABEL_INPUTQUESTION:
        case Adapter::LABEL_CHOICEQUESTION:
        case Adapter::LABEL_CHOICE:
            $label = $this->questionLabel($id, $language);
            return $label;
        default:
            return false;
        }
    }

    public function authenticate($username, $password)
    {
        try
        {
            $query = 'SELECT * FROM user WHERE username = :username AND password = :password';
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

        $this->user = $users[0]['id'];
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
            $query = 'SELECT id FROM contentlist WHERE name = :name';
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

        return $row['id'];
    }

    public function supportedContentFormats()
    {
        try
        {
            $query = 'SELECT * FROM daisyformat';
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
            $contentFormats[$format['format']] = $format['id'];

        return $contentFormats;
    }

    public function contentFormatId($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT daisyformat_id FROM content WHERE id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content format id failed');
        }

        if ($row === false)
        {
            $this->logger->warn->error("No content found with id '$contentId'");
            return -1;
        }

        return $row['daisyformat_id'];
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
        $listId = $this->contentListId($list);

        try
        {
            $query = 'SELECT * FROM usercontent WHERE user_id = :userId AND contentlist_id = :listId ORDER BY updated_at DESC';
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
            $contentId = "con_" . $item['content_id'];

            // filter content based on DAISY format
            if (is_null($contentFormats) === false && is_array($contentFormats))
            {
                $scf = $this->supportedContentFormats();
                $formatFilter = array_intersect_ukey($scf, array_flip($contentFormats), 'strcasecmp');
                $contentFormatId = $this->contentFormatId($contentId);
                if (in_array($contentFormatId, $formatFilter) === false)
                    continue;
                array_push($contentList, $contentId);
            }
            else
                array_push($contentList, $contentId);
        }

        if ($filterContent)
            $this->logger->debug("Content items after filtering: " . sizeof($contentList));

        return $contentList;
    }

    public function contentExists($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT id FROM content WHERE id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
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

    public function contentAccessible($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT id FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentId));
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

    public function contentCategory($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT category.name FROM content
                JOIN category ON content.category_id = category.id
                WHERE content.id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content category failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No category found for content with id '$contentId'");
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

    public function contentReturnDate($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT return, return_at FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content return date failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No usercontent found for user id '$this->user' and content id '$contentId'");
            return false;
        }

        if ($row['return'] == 0) return false;

        $returnDate = $row['return_at'];
        if ($this->isValidDate($returnDate) === false)
        {
            $timestamp = time() + $this->loanDuration;
            $returnDate = date('Y-m-d H:i:s', $timestamp);

            // mark content as not returned and set return date if content has been issued
            if ($this->contentInList($contentId, 'issued') === true)
            {
                try
                {
                    $query = 'UPDATE usercontent SET return_at = :datetime, returned = 0
                        WHERE user_id = :userId AND content_id = :contentId';
                    $sth = $this->dbh->prepare($query);
                    $values = array();
                    $values[':datetime'] = $returnDate;
                    $values[':userId'] = $this->user;
                    $values[':contentId'] = $contentId;
                    if ($sth->execute($values) === false)
                    {
                        $this->logger->error("Updating return date for content with id '$contentId' for user with id '$this->user' failed");
                        return false;
                    }
                }
                catch (PDOException $e)
                {
                    $this->logger->fatal($e->getMessage());
                    throw new AdapterException('Updating return data and status failed');
                }
            }
        }

        return $returnDate;
    }

    public function contentMetadata($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT key, value FROM contentmetadata WHERE content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
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
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content total size failed');
        }

        if ($row === false || (int)$row['size'] == 0)
            $this->logger->warn("Calculating total size for content with id '$contentId' failed");
        else
            $contentMetadata['size'] = (int)$row['size'];

        return $contentMetadata;
    }

    public function contentInList($contentId, $list)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT contentlist.name FROM usercontent
                JOIN contentlist ON usercontent.contentlist_id = contentlist.id
                WHERE user_id = :userId AND content_id = :contentId AND contentlist.name = :list';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentId, ':list' => $list));
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

    public function contentIssuable($contentId)
    {
        $contentId = $this->extractId($contentId);

        if ($this->contentInList($contentId, 'new') || $this->contentInList($contentId, 'issued') || $this->contentInList($contentId, 'search') || $this->contentInList($contentId, 'browse'))
            return true;

        return false;
    }

    public function contentIssue($contentId)
    {
        $contentId = $this->extractId($contentId);

        if ($this->contentInList($contentId, 'search') || $this->contentInList($contentId, 'browse'))
        {
            // do nothing, database is updated later on
        }
        else if ($this->contentInList($contentId, 'expired') || $this->contentInList($contentId, 'returned'))
            return false;

        if ($this->contentInList($contentId, 'issued'))
            return true;

        if ($this->contentInList($contentId, 'new') || $this->contentInList($contentId, 'search') || $this->contentInList($contentId, 'browse'))
        {
            try
            {
                // get first row (in list new, search or browse) containing the content 
                $contentListIds = array($this->contentListId('new'), $this->contentListId('search'), $this->contentListId('browse'));
                $contentListIdValues = implode(',', $contentListIds); // variable substitution in PDO prepared statements doesn't support arrays
                $query = "SELECT id FROM usercontent
                    WHERE user_id = :userId AND content_id = :contentId AND contentlist_id IN ($contentListIdValues) ORDER BY contentlist_id";
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
                $sth->execute($values);
                $row = $sth->fetch(PDO::FETCH_ASSOC);
                if ($row === false)
                {
                    $this->logger->error("Content with id '$contentId' for user with id '$this->user' not found in new, search or browse list");
                    return false;
                }
                // update contentlist_id for the returned row
                $query = 'UPDATE usercontent SET contentlist_id = :contentListId, updated_at = :timestamp WHERE id = :id';
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':contentListId'] = $this->contentListId('issued');
                $values[':timestamp'] = date('Y-m-d H:i:s');
                $values[':id'] = $row['id'];
                if ($sth->execute($values) === false)
                {
                    $this->logger->error("Issuing content with id '$contentId' for user with id '$this->user' failed");
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

    public function contentResources($contentId)
    {
        $contentId = $this->extractId($contentId);

        if ($this->contentInList($contentId, 'issued') === false)
        {
            $this->logger->warn("Resources requested for non-issued content");
            return array();
        }

        try
        {
            $query = 'SELECT filename, bytes, mimetype FROM contentresource WHERE content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $resources = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content resources failed');
        }

        if ($resources === false)
        {
            $this->logger->error("No resouces found for content with id '$contentId'");
            return array();
        }

        $contentResources = array();
        foreach ($resources as $resource)
        {
            $contentResource = array();
            $uri = $this->serviceBaseUri() . "content/$contentId/" . $resource['filename'];
            $contentResource['uri'] = $uri;
            $contentResource['mimeType'] = $resource['mimetype'];
            $contentResource['size'] = $resource['bytes'];
            $contentResource['localURI'] = $resource['filename'];
            array_push($contentResources, $contentResource);
        }

        return $contentResources;
    }

    public function contentReturnable($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT return FROM usercontent WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user, ':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Checking if content requires return failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No usercontent found for user id '$this->user' and content id '$contentId'");
            return false;
        }

        if ($row['return'] == 0) return false;
        return true;
    }

    public function contentReturn($contentId)
    {
        $contentId = $this->extractId($contentId);

        if ($this->contentInList($contentId, 'returned'))
            return true;

        if ($this->contentInList($contentId, 'issued'))
        {
            try
            {
                $query = 'UPDATE usercontent SET returned = 1, contentlist_id = :listId, return_at = :datetime
                    WHERE user_id = :userId AND content_id = :contentId';
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':listId'] = $this->contentListId('new');
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
                $values[':datetime'] = NULL;
                if ($sth->execute($values) === false)
                {
                    $this->logger->error("Returning content with id '$contentId' for user with id '$this->user' failed");
                    return false;
                }
                return true;
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Returning content failed');
            }
        }

        return false;
    }

    public function announcements()
    {
        try
        {
            $query = 'SELECT * FROM userannouncement WHERE user_id = :userId AND read_at IS NULL ORDER BY updated_at DESC';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user));
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving user announcements failed');
        }

        $announcements = array();
        foreach ($rows as $row)
        {
            $announcementId = "ann_" . $row['announcement_id'];
            array_push($announcements, $announcementId);
        }

        return $announcements;
    }

    public function announcementInfo($announcementId)
    {
        $announcementId = $this->extractId($announcementId);

        try
        {
            $query = 'SELECT type,priority FROM announcement WHERE id = :announcementId';
            $sth = $this->dbh->prepare($query);
            if ($sth->execute(array(':announcementId' => $announcementId)) === false)
            {
                $this->logger->error("Retriving info for announcement '$announcementId' for user with id '$this->user' failed");
                return array();
            }

            $row = $sth->fetch(PDO::FETCH_ASSOC);
            return $row;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving announcement info failed');
        }
        return false;
    }

    public function announcementExists($announcementId)
    {
        $announcementId = $this->extractId($announcementId);

        try
        {
            $query = 'SELECT * FROM userannouncement WHERE user_id = :userId AND announcement_id = :announcementId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':announcementId'] = $announcementId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving user announcements failed');
        }

        if ($row === false) return false;
        return true;
    }

    public function announcementRead($announcementId)
    {
        $announcementId = $this->extractId($announcementId);

        try
        {
            // check if announcement already marked as read
            $query = "SELECT read_at FROM userannouncement WHERE user_id = :userId AND announcement_id = :announcementId";
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':announcementId'] = $announcementId;
            if ($sth->execute($values) === false)
            {
                $this->logger->error("Checking read status for announcement with id '$announcementId' for user with id '$this->user' failed");
                return false;
            }

            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if (!is_null($row['read_at']))
            {
                return true;
            }

            // mark as read
            $query = "UPDATE userannouncement SET read_at = :timeNow WHERE user_id = :userId AND announcement_id = :announcementId";
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':timeNow'] = date('c');
            $values[':userId'] = $this->user;
            $values[':announcementId'] = $announcementId;
            if ($sth->execute($values) === false)
            {
                $this->logger->error("Marking announcement with id '$announcementId' as read for user with id '$this->user' failed");
                return false;
            }
            return true;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Marking announcement as read failed');
        }
        return false;
    }

    public function setBookmarks($contentId, $bookmark, $action = null, $lastModifiedDate = null)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            // check if bookmark exists
            $query = 'SELECT * FROM userbookmark WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $bookmarkExists = ($row === false ? false : true);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to check if user bookmark exists');
        }

        if ($bookmarkExists)
        {
            $storedBookmarkSet = bookmarkSet_from_json($row['bookmarkset']);
            $bookmarkSet = bookmarkSet_from_json($bookmark);
            switch ($action)
            {
                case Adapter::BMSET_ADD:
                    if (isset($bookmarkSet->lastmark))
                        $storedBookmarkSet->setLastmark($bookmarkSet->lastmark);
                    if (is_array($bookmarkSet->bookmark) && count($bookmarkSet->bookmark) > 0)
                    {
                        foreach ($bookmarkSet->bookmark as $bookmark)
                            $storedBookmarkSet->addBookmarkUnlessExist($bookmark);
                    }
                    if (is_array($bookmarkSet->hilite) && count($bookmarkSet->hilite) > 0)
                    {
                        foreach ($bookmarkSet->hilite as $hilite)
                            $storedBookmarkSet->addHiliteUnlessExist($hilite);
                    }
                    $bookmark = json_encode($storedBookmarkSet);
                    break;
                case Adapter::BMSET_REMOVE:
                    if (isset($bookmarkSet->lastmark))
                        $storedBookmarkSet->resetLastmark();
                    if (is_array($bookmarkSet->bookmark) && count($bookmarkSet->bookmark) > 0)
                    {
                        foreach ($bookmarkSet->bookmark as $bookmark)
                            $storedBookmarkSet->removeBookmarkIfExist($bookmark);
                    }
                    if (is_array($bookmarkSet->hilite) && count($bookmarkSet->hilite) > 0)
                    {
                        foreach ($bookmarkSet->hilite as $hilite)
                            $storedBookmarkSet->removeHiliteIfExist($hilite);
                    }
                    $bookmark = json_encode($storedBookmarkSet);
                    break;
            }
        }

        try
        {
            if ($bookmarkExists)
               $query = 'UPDATE userbookmark SET bookmarkset = :bookmarkset WHERE user_id = :userId AND content_id = :contentId';
            else
                $query = 'INSERT INTO userbookmark (user_id, content_id, bookmarkset) VALUES(:userId, :contentId, :bookmarkset)';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $values[':bookmarkset'] = $bookmark;
            if ($sth->execute($values) === false)
            {
                $this->logger->error("setting bookmark for content '$contentId' for user with id '$this->user' failed");
                return false;
            }
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to set user bookmark');
        }

        return true;
    }

    public function getBookmarks($contentId, $action = null)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT * FROM userbookmark WHERE user_id = :userId AND content_id = :contentId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving user bookmarks failed');
        }

        if ($row === false) return false;

        $bookmarks = array('lastModifiedDate' => $row['updated_at']);
        switch ($action)
        {
            case Adapter::BMGET_ALL:
                $bookmarks['bookmarkSet'] = $row['bookmarkset'];
                break;
            case Adapter::BMGET_LASTMARK:
                $bookmarkSet = bookmarkSet_from_json($row['bookmarkset']);
                $bookmarkSet->hilite = null;
                $bookmarkSet->bookmark = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            case Adapter::BMGET_HILITE:
                $bookmarkSet = bookmarkSet_from_json($row['bookmarkset']);
                $bookmarkSet->lastmark = null;
                $bookmarkSet->bookmark = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            case Adapter::BMGET_BOOKMARK:
                $bookmarkSet = bookmarkSet_from_json($row['bookmarkset']);
                $bookmarkSet->lastmark = null;
                $bookmarkSet->hilite = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            default:
                $bookmarks['bookmarkSet'] = $row['bookmarkset'];
                break;
        }

        return $bookmarks;
    }

    public function menuDefault()
    {
        return $this->generateMenu(self::MENU_DEFAULT);
    }

    public function menuSearch()
    {
        return $this->generateMenu(self::MENU_SEARCH);
    }

    public function menuNext($responses)
    {
        // NOTE: here we would also store the data from previous
        // questions but we are not interested in that
        foreach ($responses as $response)
        {
            $questionId = $this->extractId($response['questionID']);
            try
            {
                $query = 'SELECT questiontype.type
                        FROM question
                        JOIN questiontype ON question.questiontype_id = questiontype.id
                        WHERE question.id = :questionId
                        ORDER BY question.id';
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':questionId'] = $questionId;
                $sth->execute($values);
                $row = $sth->fetch(PDO::FETCH_ASSOC);
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Failed to query database for menu question type');
            }
            try
            {
                switch ($row['type'])
                {
                    case 'multipleChoiceQuestion';
                        return $this->generateMenu($this->extractId($response['value']));
                    case 'inputQuestion':
                        return $this->generateMenu($questionId, $response['value']);
                }
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Failed to determine next question');
            }

            break; // we're not interested in the other answers from the user
        }

        return $this->generateMenu(self::MENU_DEFAULT, $input); // default to main menu in case next question can't be determined
    }

    private function generateMenu($parentId, $input = null)
    {
        try
        {
            // get children for the given parent id
            $query = 'SELECT
                        question.id,
                        questiontype.type
                    FROM question
                    JOIN questiontype ON question.questiontype_id = questiontype.id
                    WHERE parent_id = :parentId
                    ORDER BY question.id ASC';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':parentId'] = $parentId;
            $sth->execute($values);
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to query database for menu');
        }

        if (sizeof($rows) == 0)
        {
            $msg = "Next menu for parent = '$parentId' not available";
            $this->logger->warn($msg);
            throw new AdapterException('No menu found');
        }

        $questions = array();
        foreach ($rows as $row)
        {
            switch ($row['type'])
            {
                case 'multipleChoiceQuestion':
                    array_push($questions, $this->generateMultipleChoiceQuestion($row['id']));
                    break;
                case 'inputQuestion':
                    array_push($questions, $this->generateInputQuestion($row['id']));
                    break;
                case 'contentListRef':
                    return $this->generateContentListRef($row['id'], $input);
                case 'label':
                    return $this->generateLabel($row['id']);
            }
        }

        return $questions;
    }

    private function generateMultipleChoiceQuestion($questionId)
    {
        $question = array("type" => "multipleChoiceQuestion", "id" => $this->generateQuestionId($questionId));
        try
        {
            // get allow_multiple_selections
            $query = 'SELECT allow_multiple_selections FROM questioninput WHERE question_id = :questionId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':questionId'] = $questionId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            if (!is_null($row['allow_multiple_selections'])) $question['allowMultipleSelections'] = 1;
            else $question['allowMultipleSelections'] = 0;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to query database for menu allow multiple selection');
        }
        try
        {
            // get choices
            $query = 'SELECT id FROM question WHERE parent_id = :parentId ORDER BY id ASC';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':parentId'] = $questionId;
            $sth->execute($values);
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
            $choices = array();
            foreach ($rows as $row)
            {
                array_push($choices, $this->generateQuestionId($row['id']));
            }
            $question["choices"] = $choices;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to query database for menu choices');
        }

        return $question;
    }

    private function generateInputQuestion($questionId)
    {
        $question = array("type" => "inputQuestion", "id" => $this->generateQuestionId($questionId));
        try
        {
            $query = 'SELECT * FROM questioninput WHERE question_id = :questionId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':questionId'] = $questionId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $inputTypes = array();
            if (!is_null($row['text_numeric'])) $inputTypes[] = "TEXT_NUMERIC";
            if (!is_null($row['text_alpanumeric'])) $inputTypes[] = "TEXT_ALPHANUMERIC";
            if (!is_null($row['audio'])) $inputTypes[] = "AUDIO";
            $question["inputTypes"] = $inputTypes;
            if (!is_null($row['default_value'])) $question["defaultValue"] = $row["defaultValue"];
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to query database for menu input types and default value');
        }
        return $question;
    }

    private function generateContentListRef($questionId, $input = null)
    {
        switch ($questionId)
        {
            case self::MENU_SEARCH_BY_AUTHOR:
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:creator' AND value LIKE :pattern";
                $values = array(':pattern' => "%$input%");
                $list = 'search';
                break;
            case self::MENU_SEARCH_BY_TITLE:
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:title' AND value LIKE :pattern";
                $values = array(':pattern' => "%$input%");
                $list = 'search';
                break;
            case self::MENU_BROWSE_BY_TITLE:
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:title'";
                $values = null;
                $list = 'browse';
                break;
            case self::MENU_BROWSE_BY_DAISY2:
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:format' AND value = 'Daisy 2.02'";
                $values = null;
                $list = 'browse';
                break;
            case self::MENU_BROWSE_BY_DAISY3:
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:format' AND value = 'ANSI/NIZO Z39.86-2005'";
                $values = null;
                $list = 'browse';
                break;
            default: // browse by title
                $query = "SELECT content_id FROM contentmetadata WHERE key = 'dc:title'";
                $values = null;
                $list = 'browse';
        }

        try
        {
            // get content matched by user criteria
            $sth = $this->dbh->prepare($query);
            $sth->execute($values);
            $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to query content by user criteria');
        }

        $contentListId = $this->contentListId($list);
        if ($contentListId < 0)
            throw new AdapterException('Invalid content list');

        // update user contentlist
        try
        {
            // delete existing content
            $query = 'DELETE FROM usercontent WHERE user_id = :userId AND contentlist_id = :contentListId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentListId'] = $contentListId;
            $sth->execute($values);
            // add new content
            $query = 'INSERT INTO usercontent (user_id, content_id, contentlist_id, return) VALUES (:userId, :contentId, :contentListId, 1)';
            $sth = $this->dbh->prepare($query);
            foreach ($rows as $row)
            {
                $values = array();
                $values[':userId'] = $this->user;
                $values['contentId'] = $row['content_id'];
                $values[':contentListId'] = $contentListId;
                $sth->execute($values);
            }
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Failed to update user contentlist');
        }

        return $list;
    }

    private function generateLabel($questionId)
    {
        // does not matter which of the menu label constants we pass since they all use the same code
        //
        // prefered language is not available in the adapter but we can obtain it by storing the value
        // from past calls to label function
        return $this->label($this->generateQuestionId($questionId), Adapter::LABEL_CHOICE, $this->preferredLang);
    }

    private function generateQuestionId($questionId)
    {
        return "que_$questionId";
    }
}

?>
