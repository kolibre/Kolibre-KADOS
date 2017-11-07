<?php

$includePath = dirname(realpath(__FILE__)) . '/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('userResponses.class.php');

/**
 * transform userResponses to a associative array
 * @param object $userResponses, the object to transform
 * @return array an associative array of userResponses
 */
function userResponses_to_array($userResponses)
{
    if (is_null($userResponses->userResponse))
        return array();

    $responses = array();
    foreach ($userResponses->userResponse as $userResponse)
    {
        $response = array();
        if (!is_null($userResponse->questionID))
        {
            $response['questionID'] = $userResponse->questionID;
        }
        if (!is_null($userResponse->value))
        {
            $response['value'] = $userResponse->value;
        }
        if (!is_null($userResponse->data))
        {
            $response['data'] = $userResponse->data;
        }
        if (!is_null($userResponse->data_encoded))
        {
            $response['data_encoded'] = $userResponse->data_encoded;
        }
        array_push($responses, $response);
    }
    return $responses;
}

?>
