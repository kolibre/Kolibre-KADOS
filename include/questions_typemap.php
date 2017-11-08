<?php

$includePath = dirname(realpath(__FILE__)) . '/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('questions.class.php');

const NS1_URI = "http://www.daisy.org/ns/daisy-online/";
const NS1_PREFIX = "ns1";

$questions_typemap = array("type_ns" => NS1_URI,
                             "type_name" => "questions",
                             "to_xml" => "questions_to_xml");

/**
 * encode questions object into a xml string
 * @param object $questions, the questions object
 * @return string, the XML representation of a PHP questions object
 */
function questions_to_xml($questions)
{
    $dom = new DomDocument();
    $node = $dom->createElementNS(NS1_URI, NS1_PREFIX.":questions");
    $dom->appendChild($node);

    encodeQuestions($dom, $node, $questions);
    return $dom->saveXML($node);
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $questions, the questions object
 */
function encodeQuestions($dom, $node, $questions)
{
    if (!is_array($questions->multipleChoiceQuestion)) $questions->multipleChoiceQuestion = array();
    if (!is_array($questions->inputQuestion)) $questions->inputQuestion = array();
    $choices = $questions->multipleChoiceQuestion + $questions->inputQuestion;
    ksort($choices);
    foreach ($choices as $choice)
    {
        if (is_a($choice, "multipleChoiceQuestion"))
        {
            $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":multipleChoiceQuestion");
            $node->appendChild($child);
            encodeMultipleChoiceQuestion($dom, $child, $choice);
        }
        else if (is_a($choice, "inputQuestion"))
        {
            $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":inputQuestion");
            $node->appendChild($child);
            encodeInputQuestion($dom, $child, $choice);
        }
    }
    if (!is_null($questions->contentListRef) && is_string($questions->contentListRef))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":contentListRef", $questions->contentListRef);
        $node->appendChild($child);
    }
    if (!is_null($questions->label) && is_a($questions->label, "label"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":label");
        $node->appendChild($child);
        encodeLabel($dom, $child, $questions->label);
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $multipleChoiceQuestion, the multipleChoiceQuestion object
 */
function encodeMultipleChoiceQuestion($dom, $node, $multipleChoiceQuestion)
{
    if (!is_null($multipleChoiceQuestion->label) && is_a($multipleChoiceQuestion->label, "label"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":label");
        $node->appendChild($child);
        encodeLabel($dom, $child, $multipleChoiceQuestion->label);
    }
    if (!is_null($multipleChoiceQuestion->choices) && is_a($multipleChoiceQuestion->choices, "choices"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":choices");
        $node->appendChild($child);
        encodeChoices($dom, $child, $multipleChoiceQuestion->choices);
    }
    if (!is_null($multipleChoiceQuestion->id) && is_string($multipleChoiceQuestion->id))
    {
        $node->setAttribute("id", $multipleChoiceQuestion->id);
    }
    if (!is_null($multipleChoiceQuestion->allowMultipleSelections) && is_bool($multipleChoiceQuestion->allowMultipleSelections))
    {
        $node->setAttribute("allowMultipleSelections", $multipleChoiceQuestion->allowMultipleSelections ? "true" : "false");
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $choices, the choices object
 */
function encodeChoices($dom, $node, $choices)
{
    if (!is_null($choices->choice) && is_array($choices->choice))
    {
        foreach ($choices->choice as $choice)
        {
            if (is_a($choice, "choice"))
            {
                $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":choice");
                $node->appendChild($child);
                encodeChoice($dom, $child, $choice);
            }
        }
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $choice, the choice object
 */
function encodeChoice($dom, $node, $choice)
{
    if (!is_null($choice->label) && is_a($choice->label, "label"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":label");
        $node->appendChild($child);
        encodeLabel($dom, $child, $choice->label);
    }
    if (!is_null($choice->id) && is_string($choice->id))
    {
        $node->setAttribute("id", $choice->id);
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $inputQuestion, the inputQuestion object
 */
function encodeInputQuestion($dom, $node, $inputQuestion)
{
    if (!is_null($inputQuestion->inputTypes) && is_a($inputQuestion->inputTypes, "inputTypes"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":inputTypes");
        $node->appendChild($child);
        encodeInputTypes($dom, $child, $inputQuestion->inputTypes);
    }
    if (!is_null($inputQuestion->label) && is_a($inputQuestion->label, "label"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":label");
        $node->appendChild($child);
        encodeLabel($dom, $child, $inputQuestion->label);
    }
    if (!is_null($inputQuestion->id) && is_string($inputQuestion->id))
    {
        $node->setAttribute("id", $inputQuestion->id);
    }
    if (!is_null($inputQuestion->defaultValue) && is_string($inputQuestion->defaultValue))
    {
        $node->setAttribute("defaultValue", $inputQuestion->defaultValue);
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $inputTypes, the inputTypes object
 */
function encodeInputTypes($dom, $node, $inputTypes)
{
    if (!is_null($inputTypes->input) && is_array($inputTypes->input))
    {
        foreach ($inputTypes->input as $input)
        {
            if (is_a($input, "input"))
            {
                $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":input");
                $node->appendChild($child);
                encodeInput($dom, $child, $input);
            }
        }
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $input, the input object
 */
function encodeInput($dom, $node, $input)
{
    if (!is_null($input->type) && is_string($input->type))
    {
        $node->setAttribute("type", $input->type);
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $label, the label object
 */
function encodeLabel($dom, $node, $label)
{
    if (!is_null($label->text) && is_string($label->text))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":text", $label->text);
        $node->appendChild($child);
    }
    if (!is_null($label->audio) && is_a($label->audio, "audio"))
    {
        $child = $dom->createElementNS(NS1_URI, NS1_PREFIX.":audio");
        $node->appendChild($child);
        encodeAudio($dom, $child, $label->audio);
    }
    if (!is_null($label->lang) && is_string($label->lang))
    {
        $node->setAttribute("xml:lang", $label->lang);
    }
    if (!is_null($label->dir) && is_string($label->dir))
    {
        $node->setAttribute("dir", $label->dir);
    }
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $audio, the audio object
 */
function encodeAudio($dom, $node, $audio)
{
    if (!is_null($audio->uri) && is_string($audio->uri))
    {
        $node->setAttribute("uri", $audio->uri);
    }
    if (!is_null($audio->rangeBegin) && is_int($audio->rangeBegin))
    {
        $node->setAttribute("rangeBegin", $audio->rangeBegin);
    }
    if (!is_null($audio->rangeEnd) && is_int($audio->rangeEnd))
    {
        $node->setAttribute("rangeEnd", $audio->rangeEnd);
    }
    if (!is_null($audio->size) && is_int($audio->size))
    {
        $node->setAttribute("size", $audio->size);
    }
}

?>