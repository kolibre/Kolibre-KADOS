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

    public function contentLastModifiedDate($contentId)
    {
        // TODO: implement demo cases
        return '1970-01-01T00:00:00+00:00';
    }

    public function contentAccessMethod($contentId)
    {
        // TODO: implement demo cases
        return Adapter::ACCESS_STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED;
    }

    public function contentAccessState($contentId, $state)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            // update state
            $query = "UPDATE usercontent SET state_id = :state WHERE user_id = :userId AND content_id = :contentId";
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':state'] = $state;
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            if ($sth->execute($values) === false)
            {
                $this->logger->error("Updating progress state to '$state' with id '$contentId' for user with id '$this->user' failed");
                return false;
            }
            if ($sth->rowCount() != 1) return false;
            return true;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Updating progress failed');
        }

        return false;

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
        $pattern = '/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}(\+\d{2}:\d{2}|Z)/';
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
            $returnDate = date('Y-m-d\TH:i:sP', $timestamp);
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

        if ($this->contentInList($contentId, 'new') || $this->contentInList($contentId, 'issued'))
            return true;

        return false;
    }

    public function contentIssue($contentId)
    {
        // This adapter function was desinged for the first DaisyOnline specification
        // and not needed in in version 2.x of the speification, but it's not remove
        // from the adapter API to maintain backwards compatibility.

        // Hence we always return true
        return true;
    }

    public function contentResources($contentId, $accessMethod = null)
    {
        $contentId = $this->extractId($contentId);

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
            $contentResource['lastModifiedDate'] = '1970-01-01T00:00:00+00:00';
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

        if ($this->contentInList($contentId, 'bookshelf'))
        {
            try
            {
                // check if content already is returned
                $query = "SELECT returned FROM usercontent WHERE user_id = :userId AND content_id = :contentId";
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
                if ($sth->execute($values) === false)
                {
                    $this->logger->error("Checking return status for content with id '$contentId' for user with id '$this->user' failed");
                    return false;
                }

                $row = $sth->fetch(PDO::FETCH_ASSOC);
                if ($row['returned'] == 1)
                {
                    return true;
                }

                // mark as returned
                $query = "UPDATE usercontent SET returned = 1 WHERE user_id = :userId AND content_id = :contentId";
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
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

    public function termsOfService()
    {
        $label = array();
        $label['text'] = "Welcome to the Kolibre demo Daisy Online service. This service is free to use for all.";
        $label['lang'] = "en";
        $audio = array();
        $audio['uri'] = $this->serviceBaseUri()."media/terms_of_service.ogg";
        $audio['size'] = 43874;
        $label['audio'] = $audio;
        return $label;
    }

    public function termsOfServiceAccept()
    {
        try
        {
            // mark terms as accepted
            $query = "UPDATE user SET terms_accepted = 1 WHERE id = :userId";
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            if ($sth->execute($values) === false)
            {
                $this->logger->error("Marking terms as accpeted for user with id '$this->user' failed");
                return false;
            }
            if ($sth->rowCount() != 1) return false;
            return true;
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Mark terms as accepted failed');
        }

        return false;
    }

    public function termsOfServiceAccepted()
    {
        try
        {
            $query = 'SELECT id FROM user WHERE id = :userId AND terms_accepted = 1';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':userId' => $this->user));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Checking if terms are accepted');
        }

        if ($row === false) return false;
        return true;
    }
}

?>
