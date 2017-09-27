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

/**
 * Definition of abstract class Adapter and exception AdapterException.
 */

/**
 * An adapter exception
 *
 * This is an exception that the adapter shall throw when an error occurs.
 * Errors might be a database query that failed or any other internal error.
 */
class AdapterException extends Exception
{
    /**
     * Contructor
     *
     * @param string $message The error message
     */
    public function __construct($message)
    {
        if (is_string($message)) $this->message = $message;
    }
}

/**
 * Abstract class definition for an adapter
 *
 * The adapter is utilized by the DaisyOnlineService to fetch/store data from/to a backend.
 *
 */
abstract class Adapter
{
    /**
     * Placeholder to store user information which , e.g. user id, user object or array containing user information
     *
     * @var mixed $user Instance of a object
     * @access protected
     */
    protected $user = null;

    /**
     * Placeholder to store the device's information
     *
     * @var string $deviceManufaturer Name of the device manufacturer
     * @access public
     */
    public $deviceManufaturer = null;

    /**
     * Placeholder to store the device's information
     *
     * @var string $deviceModel Name of the device model
     * @access public
     */
    public $deviceModel = null;

    /**
     * Placeholder to store the device's information
     *
     * @var string $deviceSerial The device serial number
     * @access public
     */
    public $deviceSerial = null;

    /**
     * Placeholder to store the device's information
     *
     * @var string $deviceVersion The device version number
     * @access public
     */
    public $deviceVersion = null;

    /**
     * Enum for retrieving a service attribute label
     */
    const LABEL_SERVICE = 1;
    /**
     * Enum for retrieving a content list label
     */
    const LABEL_CONTENTLIST = 2;
    /**
     * Enum for retrieving a content item label
     */
    const LABEL_CONTENTITEM = 3;
    /**
     * Enum for retrieving a service announcement label
     */
    const LABEL_ANNOUNCEMENT = 4;
    /**
     * Enum for retrieving a input question label
     */
    const LABEL_INPUTQUESTION = 5;
    /**
     * Enum for retrieving a multiple choice question label
     */
    const LABEL_CHOICEQUESTION = 6;
    /**
     * Enum for retrieving a question label
     */
    const LABEL_CHOICE = 7;
    /**
     * Enum for retrieving a category label
     */
    const LABEL_CATEGORY = 8;
    /**
     * Enum for retrieving a sub catergory label
     */
    const LABEL_SUBCATEGORY = 9;

    /**
     * Enum that content can ONLY be streamed.
     */
    const ACCESS_STREAM_ONLY = 'STREAM_ONLY';
    /**
     * Enum that content can ONLY be downloaded.
     */
    const ACCESS_DOWNLOAD_ONLY = 'DOWNLOAD_ONLY';
    /**
     * Enum that content can be streamed or downloaded.
     */
    const ACCESS_STREAM_AND_DOWNLOAD = 'STREAM_AND_DOWNLOAD';
    /**
     * Enum that content can be streamed or downloaded as Restricted Content.
     */
    const ACCESS_STREAM_AND_RESTRICTED_DOWNLOAD = 'STREAM_AND_RESTRICTED_DOWNLOAD';
    /**
     * Enum that content can ONLY be downloaded as Restricted Content.
     */
    const ACCESS_RESTRICTED_DOWNLOAD_ONLY = 'RESTRICTED_DOWNLOAD_ONLY';
    /**
     * Enum that content can ONLY be downloaded. Automatic Download is allowed.
     */
    const ACCESS_DOWNLOAD_ONLY_AUTOMATIC_ALLOWED = 'DOWNLOAD_ONLY_AUTOMATIC_ALLOWED';
    /**
     * Enum that content can be streamed or downloaded. Automatic Download is allowed.
     */
    const ACCESS_STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED = 'STREAM_AND_DOWNLOAD_AUTOMATIC_ALLOWED';
    /**
     * Enum that content can be streamed or downloaded as Restricted Content. Automatic Download is allowed.
     */
    const ACCESS_STREAM_AND_RESTRICTED_DOWNLOAD_AUTOMATIC_ALLOWED = 'STREAM_AND_RESTRICTED_DOWNLOAD_AUTOMATIC_ALLOWED';
    /**
     * Enum that content can ONLY be downloaded as Restricted Content. Automatic Download is allowed.
     */
    const ACCESS_RESTRICTED_DOWNLOAD_ONLY_AUTOMATIC_ALLOWED = 'RESTRICTED_DOWNLOAD_ONLY_AUTOMATIC_ALLOWED';

    /**
     * Enum for only retrieving lastmark object
     */
    const BMGET_LASTMARK = 1;
    /**
     * Enum for only retrieving hilite objects
     */
    const BMGET_HILITE = 2;
    /**
     * Enum for only retrieving bookmark objects
     */
    const BMGET_BOOKMARK = 3;
    /**
     * Enum for retrieving all (lastmark, hilite and bookmarks) objects
     */
    const BMGET_ALL = 4;

    /**
     * Enum to replace bookmarks
     */
    const BMSET_REPLACE = 1;
    /**
     * Enum to add bookmarks
     */
    const BMSET_ADD = 2;
    /**
     * Enum to remove bookmarks
     */
    const BMSET_REMOVE = 3;

    /**
     * Enum to begin content download/stream
     */
    const STATE_START = 1;
    /**
     * Enum to pasue content download/stream
     */
    const STATE_PAUSE = 2;
    /**
     * Enum to resume content download/stream
     */
    const STATE_RESUME = 3;
    /**
     * Enum to finish content download/stream
     */
    const STATE_FINISH = 4;


    /**
     * Store the SOAP request and response for an invoke of a service operation.
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service at the very end of the execution.
     *
     * @param string $request The request as an XML snippet
     * @param string $response The response as an XML snippet
     * @param int $timestamp The time of the invoke measured in the number of seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
     * @param string $ip IP address from where the request originated
     */
    public function logSoapRequestAndResponse($request, $response, $timestamp, $ip)
    {
    }

    /**
     * Start a session on the backend or check if the session is still active
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when the session initialization sequence is completed and for every subsequent call, except for logOff or logOn.
     *
     * @return boolean Returns True if the backend session is active, otherwise False.
     */
    public function startSession()
    {
        return true;
    }

    /**
     * Stop a session on the backend
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when the session initialization sequence is completer and either logOff or logOn operation is called.
     *
     * @return boolean Returns True if the backend session is stopped, otherwise False.
     */
    public function stopSession()
    {
        return true;
    }

    /**
     * Get label for a resource
     *
     * This method is required and must be implemeted for a basic service.
     * It is invoked by the service whenever a label may occur in the response.
     *
     * @param string $id Id for the label, i.e. a content id or an announcement id
     * @param int $type Type of the label. Must be one of the defined LABEL_ values.
     * @param string $language Optional. The preferred language for the label specified as an ISO 2 letter language code. If not set the backend may choose which language to use.
     * @return mixed Returns False if no label exists, otherwise returns an associative array.
     *
     * <p>The associative array must be a direct match a of label object and must contain the required elements.
     * Example of an array with an optional audio element.</p>
     * <pre>
     * Array
     * (
     *     [text] => "A sample label"
     *     [lang] => "en"
     *     [audio] => Array
     *         (
     *             [uri] => "http://localhost/sample.mp3"
     *             [size] => 123
     *         )
     * )
     * </pre>
     */
    abstract public function label($id, $type, $language = null);

    /**
     * Authenticate a client for the service
     *
     * This method is required and must be implemeted for a basic service.
     * It is invoked by the service when logOn operation is called.
     *
     * @param string $username The username for the client
     * @param string $password The password for the client
     * @return boolean Returns True if a user exists with the provided username and password, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function authenticate($username, $password);

    /**
     * Check if the specified content list exists
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when getContentList operation is called.
     *
     * @param string $list The identifier of the list
     * @return boolean Returns True if the list exists, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentListExists($list);

    /**
     * Retrieve a list of content
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when getContentList operation is called.
     *
     * @param string $list The identifier of the list
     * @param array $contentFormats Optional array of strings. If present, the returned list shall be filtered and may not include content of other types not specified in the array.
     * @param array $protectionFormats Optional array of strings. If present, the returned list shall be filtered and may not include protected content of other types not specified in the array.
     * @param array $mimeTypes Optional array of strings. If present, the returned list shall be filtered and may not inlcude content consisting of other mime types then specified in the arrary.
     * @return array Returns an array of content identifiers represented as strings.
     *
     * @throws AdapterException
     */
    abstract public function contentList($list, $contentFormats = null, $protectionFormats = null, $mimeTypes = null);

    /**
     * Retrieve last modified date for a content
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when operations getContentList and getContentResources is called.
     *
     * @param string $contentId The identifier of the content
     * @return mixed Returns False if not supported, otherwise a date string including time zone with format 'YYYY-MM-DDThh:mm:ss+hh:mm' or 'YYYY-MM-DDThh:mmZ'
     *
     * @throws AdapterException
     */
    abstract public function contentLastModifiedDate($contentId);

    /**
     * Retrieve first and last access date for a content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when operation getContentList is called.
     *
     * @param string $contentId The identifier of the content
     * @return mixed Returns False if no dates exists, otherwise returns an associative array.
     *
     * <p>The associative array must contain the keys 'first' and 'last' and the value must be a date string including time zone.
     * Example of an array>/p>
     * <pre>
     * Array
     * (
     *     [first] => "2016-03-05T13:43:26+02:00"
     *     [last] => "2016-03-05T13:43:26+02:00"
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    public function contentAccessDate($contentId)
    {
        return false;
    }

    /**
     * Retrieve the allowed method for accessing a content
     *
     * This method is required and must be imlemented for a basic service.
     * It is invoked by the service when operations getContentList is called.
     *
     * @param string $contentId THe identifier of the content
     * @return string Returns the allowed access method. Must be oneo of the defined ACCESS_ values.
     *
     * @throws AdapterException
     */
    abstract public function contentAccessMethod($contentId);

    /**
     * Set the current access state for content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when setProgressState operation is called.
     * If the service supports setting progress state (PROGRESS_STATE), this method must be implemented.
     *
     * @param string $contentId The identifier of the content
     * @param int $state The current state. Must be one of the defined STATE_ values.
     * @return bool
     *
     * @throws AdapterException
     */
    public function contentAccessState($contentId, $state)
    {
        return false;
    }

    /**
     * Check if the specified content exists
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when operations getContentMetadata, issueContent, getContentResources, returnContent or addContentToBookshelf is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Returns True if the content exists, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentExists($contentId);

    /**
     * Check if the specified content is accessible for the current user
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when operations getContentMetadata, issueContent, getContentResources, returnContent or addContentToBookshelf is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Returns True if the content is accessible, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentAccessible($contentId);

    /**
     * Retrieve a sample for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getContentMetadata operation is called.
     *
     * @param string $contentId The identifier for the content
     * @return mixed Returns False if not supported, otherwise a string containing the identifier for the sample.
     *
     * @throws AdapterException
     */
    public function contentSample($contentId)
    {
        return false;
    }

    /**
     * Retrieve a category for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getContentList operation is called.
     *
     * @param string $contentId The identifier of the content
     * @return mixed Returns False if not supported, otherwise a string with the category as value. Recommended values are BOOK, MAGAZINE, NEWSPAPER and OTHER.
     *
     * @throws AdapterException
     */
    public function contentCategory($contentId)
    {
        return false;
    }

    /**
     * Retrieve a sub category for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getContentList operation is called.
     *
     * @param string $contentId The identifier of the content
     * @return mixed Returns False if not supported, otherwise a string with the category as value. Recommended values are BOOK, MAGAZINE, NEWSPAPER and OTHER.
     *
     * @throws AdapterException
     */
    public function contentSubCategory($contentId)
    {
        return false;
    }

    /**
     * Retrieve a return date for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when operations getContentListor is called.
     *
     * @param string $contentId The identifier for the content
     * @return mixed Returns False if content does not require return, otherwise a date string including time zone with format 'YYYY-MM-DDThh:mm:ss+hh:mm' or 'YYYY-MM-DDThh:mm:ssZhh:mm:ssZ'
     *
     * @throws AdapterException
     */
    abstract public function contentReturnDate($contentId);

    /**
     * Retrieve metadata for the specified content
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when getContentMetadata operation is called.
     *
     * @param string $contentId The identifier for the content
     * @return array Returns an associative key and value array.
     *
     * <p>Valid key names are all elements the Dublin Core namespace plus the reserved key 'pdtb2:specVersion' which indicates that the content is protected using PDTB2.</p>
     * <p>Apart from the valid keys, an additional key 'size' must be present. It's value should be the total size (in bytes) of the content.</p>
     * <p>Example of an array with multiple creators.</p>
     * <pre>
     * Array
     * (
     *     [dc:title] => "Content title"
     *     [dc:format] => "DAISY 2.02"
     *     [dc:type] => "Genre"
     *     [dc:creator] => Array
     *         (
     *             [0] => "Company A"
     *             [1] => "Company B"
     *         )
     *     [size] => 12345
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    abstract public function contentMetadata($contentId);

    /**
     * Check if the specified content is issuable by the current user
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when issueContent operation is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Returns True if content is issuable, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentIssuable($contentId);

    /**
     * Issue the specified content or check if the specified content is issued
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when operations issueContent or getContentResources is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Returns True if the content is issued, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentIssue($contentId);

    /**
     * Add the speciified content to the users bookshelf
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when addContentToBookshelf operation is called.
     * If the service supports ADD_CONTENT, this method must be implemented.
     *
     * @param string $contentId the identifier for the content
     * @return boolean Returns True if the content is added, otherwise False.
     *
     * @throws AdapterException
     */
    public function contentAddBookshelf($contentId)
    {
        return false;
    }

    /**
     * Retrieve resources for the specified content
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when getContentResources operation is called.
     *
     * @param string $contentId The identifier for the content
     * @param string $accessMethod The method used by the reading system used to retrieve the resources. Must be one of the following defined values ACCESS_STREAM_ONLY, ACCESS_DOWNLOAD_ONLY or ACCESS_DOWNLOAD_ONLY_AUTOMATIC_ALLOWED.
     * @return array Returns an associative array.
     *
     * <p>The associative array must be a direct match of a resources object and must contain the required elements.
     * Include a key 'resourceRef' with a string array value to indicate that the resource is a package.
     * Example of an array with two resources and one package</p>
     * <pre>
     * Array
     * (
     *     [0] => Array
     *         (
     *             [uri] => "http://localhost/ncc.html"
     *             [mimeType] => "text/html"
     *             [size] => 1233
     *             [localURI] => "ncc.html"
     *             [lastModifiedDate] => "2016-01-01T00:00:00Z"
     *             [serverSideHash] => "bf0f0abb1a185f618f96b684232b7579"
     *         )
     *     [1] => Array
     *         (
     *             [uri] => "http://localhost/master.smil"
     *             [mimeType] => "text/plain"
     *             [size] => 12
     *             [localURI] => "master.smil"
     *             [lastModifiedDate] => "2016-01-01T00:00:00Z"
     *             [serverSideHash] => "dbb3e3fe26ec0a3b3b44884a4b917b10"
     *         )
     *     [2] => Array
     *         (
     *             [uri] => "http://localhost/package.zip"
     *             [mimeType] => "application/zip"
     *             [size] => 321
     *             [lastModifiedDate] => "2016-01-01T00:00:00Z"
     *             [resourceRef] => Array
     *                 (
     *                     [0] => "dtb_0001.smil"
     *                     [1] => "dtb_0002.smil"
     *                 )
     *         )
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    abstract public function contentResources($contentId, $accessMethod = null);

    /**
     * Check if the specified content is returnable by the current user
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when returnContent operation is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Return True if content is returnable, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentReturnable($contentId);

    /**
     * Return the specified content or check if the specified content is returned
     *
     * This method is required and must be implemented for a basic service.
     * It is invoked by the service when returnContent operation is called.
     *
     * @param string $contentId The identifier for the content
     * @return boolean Returns True if content is returned, otherwise False.
     *
     * @throws AdapterException
     */
    abstract public function contentReturn($contentId);

    /**
     * Retrieve a list of announcements
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getServiceAnnouncements operation is called.
     * If the service supports SERVICE_ANNOUNCEMENTS, this method must be implemented.
     *
     * @return array Returns an array of announcement identifiers represented as strings
     *
     * @throws AdapterException
     */
    public function announcements()
    {
        return array();
    }

    /**
     * Retrieve information for the specified announcement
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getServiceAnnouncements operation is called.
     *
     * @param string $announcementId The identifier for the announcement
     * @return mixed Returns false is not supported, otherwise an associative kay and value array.
     *
     * <p>Valid key names are 'type' and 'priority'. Allowed values for key 'type' are: [WARNING, ERROR, INFORMATION, SYSTEM]. Allowed values for key 'priority' are: [HIGH, MEDIUM, LOW].</p>
     * <p>Example of an array.</p>
     * <pre>
     * Array
     * (
     *     [type] => "INFORMATION"
     *     [priority] => 3
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    public function announcementInfo($announcementId)
    {
        return false;
    }

    /**
     * Check if the specified announcement exists
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when markAnnouncementsAsRead operation is called.
     * If the service supports SERVICE_ANNOUNCEMENTS, this method must be implemented.
     *
     * @param string $announcementId The identifier for the announcement
     * @return boolean Returns True if the announcement exists, otherwise False.
     *
     * @throws AdapterException
     */
    public function announcementExists($announcementId)
    {
        return false;
    }

    /**
     * Mark the specified announcement as read
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when markAnnouncementsAsRead operation is called.
     * If the service supports SERVICE_ANNOUNCEMENTS, this method must be implemented.
     *
     * @param string $announcementId The identifier for the announcement
     * @return boolean Returns True is the announcement was marked as read or already read, otherwise False.
     *
     * @throws AdapterException
     */
    public function announcementRead($announcementId)
    {
        return false;
    }

    /**
     * Save bookmark for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when setBookmarks operation is called.
     * If the service supports SET_BOOKMARKS, this method must be implemented.
     *
     * @param string $contentId The identifier for the content
     * @param string $bookmark A JSON encoded string of a bookmarkSet object
     * @param int $action Specifies whether to replace, add or remove bookmarks. Must be one of the defined BMSET_ values.
     * @param string $lastModifiedDate A date string including time zone when the bookmark was last modified.
     * @return boolean Returns True if the bookmark is saved, otherwise False.
     *
     * @throws AdapterException
     */
    public function setBookmarks($contentId, $bookmark, $action = null, $lastModifiedDate = null)
    {
        return false;
    }

    /**
     * Retrieve bookmark for the specified content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getBookmarks or getContentList operation is called.
     * If the service supports GET_BOOKMARKS, this method must be implemented.
     *
     * @param string $contentId The identifier for the content
     * @param int $action Specifies which bookmarks to retreive. Must be one of the defined BMGET_ values.
     * @return mixed Returns False if bookmark not found, otherwise an associative array.
     *
     * <p>Valid key names are 'lastModifiedDate' and 'bookmarkSet'. The value for key 'lastModifiedDate' must be a date string containing time zone. The value for key 'bookmarkSet' must be a JSON encoded string of bookmarkSet object.</p>
     *
     * <p>Example of an array.</p>
     * <pre>
     * Array
     * (
     *     [lastModifiedDate => "2016-01-01T00:00:00Z"
     *     [bookmarkSet] => '{"title":{"text":"content title"}, "uid":"uniqe id", "lastmark":{"ncxRef":"ncxRef", "URI":"uri", "charOffset":10}}'
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    public function getBookmarks($contentId, $action = null)
    {
        return false;
    }

    /**
     * Retrieve main menu
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getQuestions operation is called.
     * If the service supports DYNAMIC_MENUS, this method must be implemented.
     *
     * @return mixed Returns identifiers for questions and choices as an associative array. See example for menuNext.
     *
     * @throws AdapterException
     */
    public function menuDefault()
    {
        return array();
    }

    /**
     * Retrieve search menu
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getQuestions operation is called.
     * If the service supports DYNAMIC_MENUS and search, this method must be implemented.
     *
     * @return mixed Returns False in not supported, otherwise an associative array. See example for menuNext.
     *
     * @throws AdapterException
     */
    public function menuSearch()
    {
        return false;
    }

    /**
     * Retrieve previous menu
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getQuestions operation is called.
     * If the service supports DYNAMIC_MENUS and back, this method must be implemented.
     *
     * @return mixed Returns False in not supported, otherwise an associative array. See example for menuNext.
     *
     * @throws AdapterException
     */
    public function menuBack()
    {
        return false;
    }

    /**
     * Retrieve next menu
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getQuestions operation is called.
     * If the service supports DYNAMIC_MENUS, this method must be implemented.
     *
     * @param array $responses An associative array containing the reponses from a previous question.
     * <p>Example of an associative array</p>
     * <pre>
     * Array
     * (
     *     [0] => Array
     *     (
     *         [questionID] => "question 1"
     *         [value] => "value is always a string"
     *     )
     *     [1] => Array
     *     (
     *         [questionID] => "question 2"
     *         [base64] => "base64 encoded binary data"
     *     )
     * )
     * </pre>
     * @return mixed Returns either a content list identifier represented as a string or a label represented as an associative array, when an endpoint in the dynamic menu is reached. Otherwise an associative array is returned.
     *
     * <p>Example of an associative array for a dynamic menu</p>
     * <pre>
     * Array
     * (
     *     [0] => Array
     *     (
     *         [type] => "multipleChoiceQuestion"
     *         [id] => "question 1"
     *         [choices] => Array
     *         (
     *             [0] => "choice 1"
     *             [1] => "choice 2"
     *             [2] => "choice 3"
     *         )
     *         [allowMultipleSelections] = 1
     *     )
     *     [1] => Array
     *     (
     *        [type] => "inputQuestion"
     *        [id] => "question 2"
     *        [inputTypes] = Array
     *        (
     *            [0] => "TEXT_ALPHANUMERIC"
     *        )
     *     )
     *     [2] => Array
     *     (
     *        [type] => "inputQuestion"
     *        [id] => "question 3"
     *        [inputTypes] = Array
     *        (
     *            [0] => "TEXT_ALPHANUMERIC"
     *        )
     *        [defaultValue] => "value"
     *     )
     *     [3] => Array
     *     (
     *         [type] => "multipleChoiceQuestion"
     *         [id] => "question 4"
     *         [choices] => Array
     *         (
     *             [0] => "choice 1"
     *             [1] => "choice 2"
     *         )
     *         [allowMultipleSelections] = 0
     *     )
     * )
     * </pre>
     *
     *
     * @throws AdapterException
     */
    public function menuNext($responses)
    {
        return array();
    }

    /**
     * Retrieve a dynamic menu action related to a content
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getContentList operation is called.
     *
     * @param string $contentId The identifier of a content.
     * @return array Returns an empty array if a content has no action. Otherwise an associative array is returned that represents a multipleChoiceQuestion.
     *
     * <p>Example of an associative array.</p>
     * <pre>
     * Array
     * (
     *      [type] => "multipleChoiceQuestion"
     *      [id] => "question 1"
     *      [choices] => Array
     *      (
     *          [0] => "choice 1"
     *          [1] => "choice 2"
     *          [2] => "choice 3"
     *      )
     *      [allowMultipleSelections] = 0
     * )
     * </pre>
     *
     * @throws AdapterException
     */
    public function menuContentQuestion($contentId)
    {
        return array();
    }

    /**
     * Retrieve public key for the requested key
     *
     * The service encrypts this key with one the client's public keys, thus only the client is able to decrypt the key.
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getKeyExchangeObject operation is called.
     * If the service supports PDTB2_KEY_PROVISION, this method must be implemented.
     *
     * @param string $name Name of the requested key
     * @return mixed Returns False if no key exists with the specified name, otherwise the public key as a string.
     *
     * @throws AdapterException
     */
    public function requestedKey($name)
    {
        return false;
    }

    /**
     * Retrieve client's public key identified by name
     *
     * The service goes through each key in the client's key ring until a key is found on the backend.
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getKeyExchangeObject operation is called.
     * If the service supports PDTB2_KEY_PROVISION, this method must be implemented.
     *
     * @param string $name Name of the client key
     * @return mixed Return False if no key exists with the specified name, otherwise an associative array is returned.
     * <p>The array must contain the following keys: 'key', 'modulus' and 'exponent'. Key is the public key, modulus and exponent represents the modulues and exponent values of the public key.</p>
     *
     * @throws AdapterException
     */
    public function clientKey($name)
    {
        return false;
    }

    /**
     * Retrieve issuer information
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getKeyExchangeObject operation is called.
     * If the service supports PDTB2_KEY_PROVISION, this method must be implemented.
     *
     * @return array Returns an associative array containing keys 'uid' and 'name'.
     *
     * @throws AdapterException
     */
    public function issuerInfo()
    {
        return array();
    }

    /**
     * Retrieve user credentials
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getUserCredentials operation is called.
     * If the service supports automatic reading system configuration (CREDENTIALS), this method must be implemented.
     *
     * @param string $manufacturer The manufacturer reported by the reading system
     * @param string $model The model reported by the reading system
     * @param string $serialNumber The serial number reported by the reading system
     * @param string $version The version number reported by the reading system
     * @return mixed Returns False when credentials not found. Otherwise an associative array.
     *
     * <p>The password must be encrypted</p>
     * <p>Example of an array</p>
     * Array
     * (
     *     [username] => "username"
     *     [password] => "encrypted password"
     * )
     *
     * @throws AdapterException
     */
    public function userCredentials($manufacturer, $model, $serialNumber, $version)
    {
        return false;
    }

    /**
     * Retrieve Terms of Service
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when getTermsOfService operation in called.
     * If the service supports terms of service (TERMS_OF_SERVICE), this method must be implemented.
     *
     * @return array Returns an associative array representing a label object.
     *
     * @throws AdapterException
     */
    public function termsOfService()
    {
        return array();
    }

    /**
     * Accept Terms of Service
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when accpetTermsOfService operation in called.
     * If the service supports terms of service (TERMS_OF_SERVICE), this method must be implemented.
     *
     * @return bool Returns True if terms accepted. Otherwise False.
     *
     * @throws AdapterException
     */
    public function termsOfServiceAccept()
    {
        return false;
    }

    /**
     * Check if Terms of Service are accepted
     *
     * This method is optional and does not require implementation.
     * It is invoked by the service when session initialization.
     * If the service supports terms of service (TERMS_OF_SERVICE), this method must be implemented.
     *
     * @return bool Returns True if terms accepted. Otherwise False.
     *
     * @throws AdapterException
     */
    public function termsOfServiceAccepted()
    {
        return false;
    }
}

?>
