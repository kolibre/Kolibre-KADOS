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

class KobraAdapter extends Adapter
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

    // database data source name
    private $databaseDSN = null;

    // database connection handler
    private $dbh = null;

    // preferredLang is the users preferred language
    private $preferredLang = null;

    // the secret key for decrypting passwords in database
    private $secretKey = null;

    public function __construct($database_dsn = null, $secret_key = null)
    {
        $this->databaseDSN = $database_dsn;
        $this->secretKey = $secret_key;

        // setup logger
        $this->setupLogger();

        // setup database connection
        $this->setupDatabase();
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
        array_push($instance_variables_to_serialize, 'protocolVersion');
        array_push($instance_variables_to_serialize, 'preferredLang');
        array_push($instance_variables_to_serialize, 'databaseDSN');
        array_push($instance_variables_to_serialize, 'secretKey');
        return $instance_variables_to_serialize;
    }

    private function setupLogger()
    {
        $this->logger = Logger::getLogger('kolibre.daisyonline.kobraadapter');
    }

    private function setupDatabase()
    {
        if (is_null($this->databaseDSN))
        {
            $this->logger->fatal("No database dsn specified");
            return;
        }

        try
        {
            $dsn = $this->databaseDSN;
            $this->logger->debug("Connecting to database $dsn");
            $this->dbh = new PDO($this->databaseDSN);
            $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
        }
    }

    public function setSecretKey($secret) {
        $this->secretKey = $secret;
    }

    public function setProtocolVersion($version)
    {
        $this->protocolVersion = $version;
    }

    public function logSoapRequestAndResponse($request, $response, $timestamp, $ip)
    {
        if ($this->userLoggingEnabled === false) return;

        try
        {
            $query = "INSERT INTO user_logs (user_id, request, response, ip, created_at, updated_at)
                VALUES(:user_id, :request, :response, :ip, :created_at, :updated_at)";
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':user_id'] = $this->user;
            $values[':request'] = $request;
            $values[':response'] = $response;
            $values[':ip'] = $ip;
            $values[':created_at'] = date('c');
            $values[':updated_at'] = date('c');
            if ($sth->execute($values) === false)
                $this->logger->error("Insert row to user_logs failed");
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

        try
        {
            // TODO: support filter by supported audio types
            $query = 'SELECT audio FROM content_audios WHERE id = :contentId limit 1';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving content audio file name failed');
        }

        $filename = $row['audio'];
        return $this->serviceBaseUri()."/contents/$contentId/audios/$filename";
    }

    public function contentAudioSize($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT size FROM content_audios WHERE id = :contentId';
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
            $query = 'SELECT title FROM contents WHERE id = :contentId';
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
            $query = 'SELECT value FROM content_metadata WHERE content_id = :contentId AND key = :key';
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
        try
        {
            // TODO: support filter by supported audio types
            $query = 'SELECT audio FROM announcement_audios WHERE id = :announcementId limit 1';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':announcementId' => $announcementId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving announcement audio file name failed');
        }

        $filename = $row['audio'];
        return $this->serviceBaseUri()."announcements/$announcementId/$filename";
    }

    public function announcementLabel($announcementId, $language = 'en')
    {
        $announcementId = $this->extractId($announcementId);

        try
        {
            $query = 'SELECT
                        announcement_texts.text as text,
                        languages.lang as lang,
                        announcement_audios.size as size,
                        announcement_audios.id as id
                    FROM announcements
                    JOIN announcement_texts ON announcements.id = announcement_texts.announcement_id
                    JOIN languages ON announcement_texts.language_id = languages.id
                    JOIN announcement_audios ON announcement_texts.id = announcement_audios.announcement_text_id
                    WHERE announcements.id = :announcementId';
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
        try
        {
            // TODO: support filter by supported audio types
            $query = 'SELECT audio FROM question_audios WHERE id = :questionId limit 1';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':questionId' => $questionId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving question audio file name failed');
        }

        $filename = $row['audio'];
        return $this->serviceBaseUri()."questions/$questionId/$filename";
    }

    public function questionLabel($questionId, $language = 'en')
    {
        $questionId = $this->extractId($questionId);

        try
        {
            $query = 'SELECT
                        question_texts.text as text,
                        languages.lang as lang,
                        question_audios.size as size,
                        question_audios.id as id
                    FROM question_question_texts
                    JOIN question_texts ON question_question_texts.question_text_id = question_texts.id
                    JOIN languages ON question_texts.language_id = languages.id
                    JOIN question_audios ON question_texts.id = question_audios.question_text_id
                    WHERE question_question_texts.question_id = :questionId';
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

    private function decrypt_gcm($cipher, $key, $data)
    {
        $tag_length = 16;
        $iv = substr(base64_decode($data), 0, openssl_cipher_iv_length($cipher));
        $tag = substr(base64_decode($data), openssl_cipher_iv_length($cipher), $tag_length);
        $data = substr(base64_decode($data), openssl_cipher_iv_length($cipher) + $tag_length);
        $plaintext = openssl_decrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return $plaintext;
    }

    public function decrypt($encrypted)
    {
        if (is_null($this->secreKey))
        {
            if (array_key_exists('KOBRA_SECRET_KEY', $_ENV))
            {
                $this->secretKey = $_ENV['KOBRA_SECRET_KEY'];
            }
        }
        $hashed_key = substr(hash('sha256', $this->secretKey), 0, 32);
        $decrypted = $this->decrypt_gcm('aes-256-gcm', $hashed_key, $encrypted);
        return $decrypted;
    }

    public function authenticate($username, $password)
    {
        try
        {
            $query = 'SELECT * FROM users WHERE username = :username';
            $sth = $this->dbh->prepare($query);
            $values = array(':username' => $username);
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
            $msg = "No user found with username = '$username'";
            $this->logger->warn($msg);
            return false;
        }
        else if (sizeof($users) > 1)
        {
            $count = sizeof($users);
            $msg = "$count users found with username = '$username'";
            $this->logger->error($msg);
            return false;
        }

        $user = $users[0];
        $decrypted_password = $this->decrypt(trim($user['password']));
        if (is_string($decrypted_password))
        {
            if ($decrypted_password != $password) {
                $msg = "provided password for user '$username' did not match with database";
                $this->logger->warn($msg);
                return false;
            }
        } else
        {
            $this->logger->error("password could not be decrypted");
            return false;
        }

        $this->user = $user['id'];
        if ($users[0]['log'] == true)
            $this->userLoggingEnabled = true;

        return true;
    }

    public function contentListExists($list)
    {
        try
        {
            $query = 'SELECT name FROM content_lists WHERE name = :name';
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
            $query = 'SELECT id FROM content_lists WHERE name = :name';
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
            $query = 'SELECT * FROM daisy_formats';
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
            $query = 'SELECT daisy_format_id FROM contents WHERE id = :contentId';
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

        return $row['daisy_format_id'];
    }

    public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null)
    {
        $listId = $this->contentListId($list);

        try
        {
            $query = 'SELECT * FROM user_contents WHERE user_id = :userId AND content_list_id = :listId AND returned = false ORDER BY updated_at DESC';
            if ($this->protocolVersion == Adapter::DODP_V1) $query = str_replace('content_list_id', 'content_list_v1_id', $query);
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
        $contentId = $this->extractId($contentId);

        try {
            $query = 'SELECT MAX(updated_at) as last_modified FROM contents WHERE id = :contentId';
            $sth = $this->dbh->prepare($query);
            $sth->execute(array(':contentId' => $contentId));
            $row = $sth->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Retrieving last modified date failed');
        }

        if ($row === false)
        {
            $this->logger->warn->error("No content found with id '$contentId'");
            return false;
        }

        return $this->dateFormatByProtocol($row['last_modified']);
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
            $query = "UPDATE user_contents SET state_id = :state WHERE user_id = :userId AND content_id = :contentId";
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
            $query = 'SELECT id FROM contents WHERE id = :contentId';
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
            $query = 'SELECT id FROM user_contents WHERE user_id = :userId AND content_id = :contentId';
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
            $query = 'SELECT categories.name FROM contents
                JOIN categories ON contents.category_id = categories.id
                WHERE contents.id = :contentId';
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
        if ($date == '0000-00-00 00:00:00') return false;
        $patternV1 = '/\d{4}\-\d{2}\-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        $patternV2 = '/\d{4}\-\d{2}\-\d{2}[ T]\d{2}:\d{2}:\d{2}(\+\d{2}:\d{2}|Z)/';
        if (preg_match($patternV1, $date) == 1 || preg_match($patternV2, $date) == 1) return true;
        return false;
    }

    private function dateFormatByProtocol($date)
    {
        $patternV1 = '/\d{4}\-\d{2}\-\d{2}[ T]\d{2}:\d{2}:\d{2}/';
        $patternV2 = '/\d{4}\-\d{2}\-\d{2}T\d{2}:\d{2}:\d{2}(\+\d{2}:\d{2}|Z)/';
        switch ($this->protocolVersion)
        {
            case Adapter::DODP_V1:
                if (preg_match($patternV2, $date) == 1) return str_replace(" ", "T", substr($date, 0, 19));
                return $date;
                break;
            case Adapter::DODP_V2:
                if (preg_match($patternV2, $date) == 1) return $date;
                if (preg_match($patternV1, $date) == 1) return str_replace(" ", "T", substr($date, 0, 19)) . "Z";
                return $date . "Z";
                break;
            default:
                return $date;
        }
    }

    public function contentReturnDate($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT return, return_at FROM user_contents WHERE user_id = :userId AND content_id = :contentId AND returned = false';
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
            throw new AdapterException('Retrieving content return date failed');
        }

        if ($row === false)
        {
            $this->logger->warn("No user content found for user id '$this->user' and content id '$contentId'");
            return false;
        }

        if ($row['return'] == false) return false;

        $returnDate = $row['return_at'];
        if ($this->isValidDate($returnDate) === false)
        {
            $timestamp = time() + $this->loanDuration;
            $returnDate = date('c', $timestamp);
            $updateReturnDate = false;
            $listName = $this->protocolVersion == Adapter::DODP_V2 ? 'bookshelf' : 'issued';
            if ($this->contentInList($contentId, $listName) === true) $updateReturnDate = true;
            if ($this->protocolVersion == Adapter::DODP_V1)
            {
                if ($this->contentInList($contentId, 'new') === true)
                {
                    $listName = 'new';
                    $updateReturnDate = true;
                }
            }
            if ($this->contentInList($contentId, $listName) === true)
            // mark content as not returned and set return date for content
            if ($updateReturnDate)
            {
                try
                {
                    $query = 'UPDATE user_contents SET return_at = :datetime, returned = false
                        WHERE user_id = :userId AND content_id = :contentId AND content_list_id = :listId AND return_at IS NULL';
                    if ($this->protocolVersion == Adapter::DODP_V1) $query = str_replace('content_list_id', 'content_list_v1_id', $query);
                    $sth = $this->dbh->prepare($query);
                    $values = array();
                    $values[':datetime'] = $returnDate;
                    $values[':userId'] = $this->user;
                    $values[':contentId'] = $contentId;
                    $values[':listId'] = $this->contentListId($listName);
                    if ($sth->execute($values) === false)
                    {
                        $this->logger->error("Updating return date for content with id '$contentId' for user with id '$this->user' failed");
                        return false;
                    }
                }
                catch (PDOException $e)
                {
                    $this->logger->fatal($e->getMessage());
                    throw new AdapterException('Updating return date and status failed');
                }
            }
        }

        return $this->dateFormatByProtocol($returnDate);
    }

    public function contentMetadata($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT key, value FROM content_metadata WHERE content_id = :contentId';
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
            $query = 'SELECT SUM(bytes) as size FROM content_resources WHERE content_id = :contentId';
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
            $query = 'SELECT content_lists.name FROM user_contents
                JOIN content_lists ON user_contents.content_list_id = content_lists.id
                WHERE user_id = :userId AND content_id = :contentId AND content_lists.name = :list';
            if ($this->protocolVersion == Adapter::DODP_V1) $query = str_replace('content_list_id', 'content_list_v1_id', $query);
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

        // check if content already in list and not returned
        if ($this->contentInList($contentId, 'issued'))
        {
            try
            {
                $query = "SELECT returned FROM user_contents WHERE user_id = :userId AND content_id = :contentId AND content_list_v1_id = :listId AND returned = false";
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
                $values[':listId'] = $this->contentListId('issued');
                if ($sth->execute($values) === false)
                {
                    $this->logger->error("Checking return status for content with id '$contentId' for user with id '$this->user' failed");
                    return false;
                }

                $rows = $sth->fetchAll(PDO::FETCH_ASSOC);
                if (count($rows) >= 1)
                {
                    return true;
                }
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Checking return status for content before adding to bookshelf failed');
            }
        }

        if ($this->contentInList($contentId, 'issued'))
        {
            try
            {
                // delete existing content
                $query = "DELETE FROM user_contents WHERE user_id = :userId AND content_id = :contentId AND content_list_v1_id = :contentListId";
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':userId'] = $this->user;
                $values[':contentId'] = $contentId;
                $values[':contentListId'] = $this->contentListId('issued');
                $sth->execute($values);
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Deleting issued content failed');
            }
        }
        if ($this->contentInList($contentId, 'new') || $this->contentInList($contentId, 'search') || $this->contentInList($contentId, 'browse'))
        {
            try
            {
                // get first row (in list new, search or browse) containing the content 
                $contentListIds = array($this->contentListId('new'), $this->contentListId('search'), $this->contentListId('browse'));
                $contentListIdValues = implode(',', $contentListIds); // variable substitution in PDO prepared statements doesn't support arrays
                $query = "SELECT id FROM user_contents
                    WHERE user_id = :userId AND content_id = :contentId AND content_list_v1_id IN ($contentListIdValues) ORDER BY content_list_v1_id";
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
                // update content_list_v1_id for the returned row
                $query = 'UPDATE user_contents SET content_list_v1_id = :contentListId, updated_at = :timestamp WHERE id = :id';
                $sth = $this->dbh->prepare($query);
                $values = array();
                $values[':contentListId'] = $this->contentListId('issued');
                $values[':timestamp'] = date('c');
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

    public function contentResources($contentId, $accessMethod = null)
    {
        if ($this->protocolVersion == Adapter::DODP_V1 && $this->contentInList($contentId, 'issued') === false)
        {
            $this->logger->warn("Resources requested for non-issued content");
            return array();
        }

        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT file_name, bytes, mime_type, resource FROM content_resources WHERE content_id = :contentId';
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
            $uri = $this->serviceBaseUri() . "contents/$contentId/resources/" . $resource['resource'];
            $contentResource['uri'] = $uri;
            $contentResource['mimeType'] = $resource['mime_type'];
            $contentResource['size'] = $resource['bytes'];
            $contentResource['localURI'] = $resource['file_name'];
            $lastModified = $this->protocolVersion == Adapter::DODP_V2 ? '1970-01-01T00:00:00+00:00' : '1970-01-01T00:00:00';
            $contentResource['lastModifiedDate'] = $lastModified;
            array_push($contentResources, $contentResource);
        }

        return $contentResources;
    }

    public function contentReturnable($contentId)
    {
        $contentId = $this->extractId($contentId);

        try
        {
            $query = 'SELECT return FROM user_contents WHERE user_id = :userId AND content_id = :contentId';
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
            $this->logger->warn("No user content found for user id '$this->user' and content id '$contentId'");
            return false;
        }

        if ($row['return'] == false) return false;
        return true;
    }

    public function contentReturn($contentId)
    {
        $contentId = $this->extractId($contentId);

        $contentList = $this->protocolVersion == Adapter::DODP_V2 ? 'bookshelf' : 'issued';
        if ($this->contentInList($contentId, $contentList))
        {
            try
            {
                // check if content already is returned
                $query = "SELECT returned FROM user_contents WHERE user_id = :userId AND content_id = :contentId";
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
                if ($row['returned'] == true)
                {
                    return true;
                }

                // mark as returned
                $query = "UPDATE user_contents SET returned = true WHERE user_id = :userId AND content_id = :contentId";
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

    public function announcements()
    {
        try
        {
            $query = 'SELECT * FROM user_announcements WHERE user_id = :userId AND read_at IS NULL ORDER BY updated_at DESC';
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
            $query = 'SELECT category as type,priority FROM announcements WHERE id = :announcementId';
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
            $query = 'SELECT * FROM user_announcements WHERE user_id = :userId AND announcement_id = :announcementId';
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
            $query = "SELECT read_at FROM user_announcements WHERE user_id = :userId AND announcement_id = :announcementId";
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
            $query = "UPDATE user_announcements SET read_at = :timeNow WHERE user_id = :userId AND announcement_id = :announcementId";
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
            $query = 'SELECT * FROM user_bookmarks WHERE user_id = :userId AND content_id = :contentId';
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
            $storedBookmarkSet = bookmarkSet_from_json($row['bookmark_set']);
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
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $values[':bookmarkset'] = $bookmark;
            if ($bookmarkExists)
               $query = 'UPDATE user_bookmarks SET bookmark_set = :bookmarkset WHERE user_id = :userId AND content_id = :contentId';
            else
            {
                $query = 'INSERT INTO user_bookmarks (user_id, content_id, bookmark_set, created_at, updated_at) VALUES(:userId, :contentId, :bookmarkset, :created_at, :updated_at)';
                $values[':created_at'] = date('c');
                $values[':updated_at'] = date('c');
            }
            $sth = $this->dbh->prepare($query);

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
            $query = 'SELECT * FROM user_bookmarks WHERE user_id = :userId AND content_id = :contentId';
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
                $bookmarks['bookmarkSet'] = $row['bookmark_set'];
                break;
            case Adapter::BMGET_LASTMARK:
                $bookmarkSet = bookmarkSet_from_json($row['bookmark_set']);
                $bookmarkSet->hilite = null;
                $bookmarkSet->bookmark = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            case Adapter::BMGET_HILITE:
                $bookmarkSet = bookmarkSet_from_json($row['bookmark_set']);
                $bookmarkSet->lastmark = null;
                $bookmarkSet->bookmark = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            case Adapter::BMGET_BOOKMARK:
                $bookmarkSet = bookmarkSet_from_json($row['bookmark_set']);
                $bookmarkSet->lastmark = null;
                $bookmarkSet->hilite = null;
                $bookmarks['bookmarkSet'] = json_encode($bookmarkSet);
                break;
            default:
                $bookmarks['bookmarkSet'] = $row['bookmark_set'];
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
                $query = 'SELECT question_types.name as type
                        FROM questions
                        JOIN question_types ON questions.question_type_id = question_types.id
                        WHERE questions.id = :questionId
                        ORDER BY questions.id';
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
                        questions.id,
                        question_types.name as type
                    FROM questions
                    JOIN question_types ON questions.question_type_id = question_types.id
                    WHERE parent_id = :parentId
                    ORDER BY questions.id ASC';
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
            $query = 'SELECT allow_multiple_selections FROM question_inputs WHERE question_id = :questionId';
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
            $query = 'SELECT id FROM questions WHERE parent_id = :parentId ORDER BY id ASC';
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
            $query = 'SELECT * FROM question_inputs WHERE question_id = :questionId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':questionId'] = $questionId;
            $sth->execute($values);
            $row = $sth->fetch(PDO::FETCH_ASSOC);
            $inputTypes = array();
            if (!is_null($row['text_numeric'])) $inputTypes[] = "TEXT_NUMERIC";
            if (!is_null($row['text_alphanumeric'])) $inputTypes[] = "TEXT_ALPHANUMERIC";
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
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:creator' AND value LIKE :pattern";
                $values = array(':pattern' => "%$input%");
                $list = 'search';
                break;
            case self::MENU_SEARCH_BY_TITLE:
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:title' AND value LIKE :pattern";
                $values = array(':pattern' => "%$input%");
                $list = 'search';
                break;
            case self::MENU_BROWSE_BY_TITLE:
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:title'";
                $values = null;
                $list = 'browse';
                break;
            case self::MENU_BROWSE_BY_DAISY2:
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:format' AND value = 'Daisy 2.02'";
                $values = null;
                $list = 'browse';
                break;
            case self::MENU_BROWSE_BY_DAISY3:
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:format' AND value = 'ANSI/NIZO Z39.86-2005'";
                $values = null;
                $list = 'browse';
                break;
            default: // browse by title
                $query = "SELECT content_id FROM content_metadata WHERE key = 'dc:title'";
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
            $query = 'DELETE FROM user_contents WHERE user_id = :userId AND content_list_id = :contentListId';
            if ($this->protocolVersion == Adapter::DODP_V1) $query = str_replace('content_list_id', 'content_list_v1_id', $query);
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentListId'] = $contentListId;
            $sth->execute($values);
            // add new content
            $query = 'INSERT INTO user_contents (user_id, content_id, content_list_id, content_list_v1_id, return, created_at, updated_at) VALUES (:userId, :contentId, :contentListId, :contentListIdV1, true, :datetime, :datetime)';
            $sth = $this->dbh->prepare($query);
            foreach ($rows as $row)
            {
                $values = array();
                $values[':userId'] = $this->user;
                $values['contentId'] = $row['content_id'];
                $values[':contentListId'] = $contentListId;
                $values[':contentListIdV1'] = $contentListId;
                $values[':datetime'] = date('c');
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

    public function contentAddBookshelf($contentId)
    {
        $contentId = $this->extractId($contentId);

        if ($this->contentExists($contentId) === false)
        {
            return false;
        }

        // check if content already in list and not returned
        if ($this->contentInList($contentId, 'bookshelf'))
        {
            try
            {
                $query = "SELECT returned FROM user_contents WHERE user_id = :userId AND content_id = :contentId";
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
                if ($row['returned'] == false)
                {
                    return true;
                }
            }
            catch (PDOException $e)
            {
                $this->logger->fatal($e->getMessage());
                throw new AdapterException('Checking return status for content before adding to bookshelf failed');
            }
        }

        try
        {
            $query = 'DELETE FROM user_contents WHERE user_id = :userId AND content_id = :contentId AND content_list_id = :contentListId';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $values[':contentListId'] = $this->contentListId('bookshelf');
            $sth->execute($values);
            $query = 'INSERT INTO user_contents (user_id,content_id,content_list_id,return,created_at,updated_at) VALUES(:userId, :contentId, :contentListId, false, :datetime, :datetime)';
            $sth = $this->dbh->prepare($query);
            $values = array();
            $values[':userId'] = $this->user;
            $values[':contentId'] = $contentId;
            $values[':contentListId'] = $this->contentListId('bookshelf');
            $values[':datetime'] = date('c');
            if ($sth->execute($values) === false)
            {
                $this->logger->error("Adding content '$contentId' to bookshelf for user with id '$this->user' failed");
                return false;
            }
        }
        catch (PDOException $e)
        {
            $this->logger->fatal($e->getMessage());
            throw new AdapterException('Adding content to bookshelf failed');
        }
        return true;
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
            $query = "UPDATE users SET terms_accepted = true WHERE id = :userId";
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
            $query = 'SELECT id FROM users WHERE id = :userId AND terms_accepted = true';
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
