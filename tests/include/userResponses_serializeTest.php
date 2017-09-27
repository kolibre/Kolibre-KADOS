<?php

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('userResponses_serialize.php');

class UserResponsesSerialize extends PHPUnit_Framework_TestCase
{
    /**
     * @group userResponses
     * @group serialize
     */
    public function testToArray()
    {
        $userResponses = new userResponses(array(new userResponse('questionID', 'value'), new userResponse('back')));
        $responses = userResponses_to_array($userResponses);
        $this->assertCount(2, $responses);
        $this->assertArrayHasKey(0, $responses);
        $this->assertArrayHasKey('questionID', $responses[0]);
        $this->assertArrayHasKey('value', $responses[0]);
        $this->assertEquals('questionID', $responses[0]['questionID']);
        $this->assertArrayNotHasKey('value', $responses[1]);
        $this->assertEquals('back', $responses[1]['questionID']);
    }
}
?>
