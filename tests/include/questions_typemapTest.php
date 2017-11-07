<?php

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('questions_typemap.php');

class QuestionsTypemap extends PHPUnit_Framework_TestCase
{
    protected static $objectXML;

    public static function setUpBeforeClass()
    {
        self::$objectXML = <<<XML
<questions xmlns="http://www.daisy.org/ns/daisy-online/">
<multipleChoiceQuestion id="q1" allowMultipleSelections="true">
<label xml:lang="en" dir="ltr">
<text>question 1</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
<choices>
<choice id="c1">
<label xml:lang="en" dir="ltr">
<text>choice 1</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</choice>
<choice id="c2">
<label xml:lang="en" dir="ltr">
<text>choice 2</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</choice>
</choices>
</multipleChoiceQuestion>
<inputQuestion id="q2">
<inputTypes>
<input type="TEXT_NUMERIC"/>
<input type="AUDIO"/>
</inputTypes>
<label xml:lang="en" dir="ltr">
<text>question 2</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</inputQuestion>
<multipleChoiceQuestion id="q3" allowMultipleSelections="true">
<label xml:lang="en" dir="ltr">
<text>question 3</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
<choices>
<choice id="c3">
<label xml:lang="en" dir="ltr">
<text>choice 3</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</choice>
<choice id="c4">
<label xml:lang="en" dir="ltr">
<text>choice 4</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</choice>
</choices>
</multipleChoiceQuestion>
<contentListRef>contentListRef</contentListRef>
<label xml:lang="en" dir="ltr">
<text>label</text>
<audio uri="src" rangeBegin="1" rangeEnd="2" size="3"/>
</label>
</questions>
XML;
    }

    /**
     * @group questions
     * @group typemap
     */
    public function testEncodeQuestions()
    {
        // build questions
        // NOTE: it's not a valid response but we're testing the serialization so we want to test all code paths
        $questions = new questions();
        // question 1
        $q1label = new label('question 1',new audio('src',1,2,3),'en','ltr');
        $c1label = new label('choice 1',new audio('src',1,2,3),'en','ltr');
        $c2label = new label('choice 2',new audio('src',1,2,3),'en','ltr');
        $choices = new choices(array(new choice($c1label, 'c1'), new choice($c2label, 'c2')));
        $q1 = new multipleChoiceQuestion($q1label, $choices, 'q1', true);
        $questions->addMultipleChoiceQuestion($q1);
        // question 2
        $inputTypes = new inputTypes(array(new input('TEXT_NUMERIC'), new input('AUDIO')));
        $q2label = new label('question 2',new audio('src',1,2,3),'en','ltr');
        $q2 = new inputQuestion($inputTypes, $q2label, 'q2');
        $questions->addInputQuestion($q2);
        // question 3
        $q3label = new label('question 3',new audio('src',1,2,3),'en','ltr');
        $c3label = new label('choice 3',new audio('src',1,2,3),'en','ltr');
        $c4label = new label('choice 4',new audio('src',1,2,3),'en','ltr');
        $choices = new choices(array(new choice($c3label, 'c3'), new choice($c4label, 'c4')));
        $q3 = new multipleChoiceQuestion($q3label, $choices, 'q3', true);
        $questions->addMultipleChoiceQuestion($q3);
        $questions->setContentListRef('contentListRef');
        $questions->setLabel(new label('label',new audio('src',1,2,3),'en','ltr'));
        $outputXML = questions_to_xml($questions);

        // tweak output and input to make them compareable
        $outputXML = str_replace("ns1:", "", $outputXML);
        $outputXML = str_replace(":ns1", "", $outputXML);
        $objectXML = str_replace("\n", "", self::$objectXML);
        $this->assertEquals($outputXML, $objectXML);
    }
}
?>
