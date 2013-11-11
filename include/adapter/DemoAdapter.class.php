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

    // logger instance
    private $logger = null;

    // database connection handler
    private $dbh = null;

    public function __construct()
    {
        // stup logger
        $this->setupLogger();

        // setup database connection
        $this->setupDatabase();
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

    private function setupDatabase()
    {
        try
        {
            $database = realpath(dirname(__FILE__)) . '/../../data/db/demo.db';
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

    public function label($id, $type, $language = null)
    {
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
