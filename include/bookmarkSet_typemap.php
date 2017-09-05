<?php

$includePath = dirname(realpath(__FILE__)) . '/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('bookmarkSet.class.php');

const NS_URI = "http://www.daisy.org/z3986/2005/bookmark/";
const NS_PREFIX = "ns2";

$bookmarkSet_typemap = array("type_ns" => NS_URI,
                             "type_name" => "bookmarkSet",
                             "from_xml" => "bookmarkSet_from_xml",
                             "to_xml" => "bookmarkSet_to_xml");

/**
 * decode xml string into a bookmarkSet object
 * @param string $xml, xml string to parse
 * @return object bookmarkSet, an object representation of an XML string
 */
function bookmarkSet_from_xml($xml)
{
    // load the xml snippet into a DOM document
    $dom = new DomDocument();
    $dom->loadXML($xml);
    $bookmarkSetNodeList = $dom->getElementsByTagName("bookmarkSet");
    if ($bookmarkSetNodeList->length != 1)
    {
        // TODO: log that no bookmarkSet element was found in XML
        return new bookmarkSet();
    }

    // manually build the bookmarkSet object from XML
    $node = $bookmarkSetNodeList->item(0);
    return decodeBookmarkSet($node);
}

/**
 * encode bookmarkSet object into a xml string
 * @param object $bookmarkSet, the bookmarkSet object
 * @return string, the XML representation of a PHP bookmarkSet object
 */
function bookmarkSet_to_xml($bookmarkSet)
{
    $dom = new DomDocument();
    $node = $dom->createElementNS(NS_URI, NS_PREFIX.":bookmarkSet");
    $dom->appendChild($node);

    encodeBookmarkSet($dom, $node, $bookmarkSet);
    return $dom->saveXML($node);
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a bookmarkSet node
 * @return object bookmarkSet, an object representation of an XML string
 */
function decodeBookmarkSet($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $bookmarkSet = new bookmarkSet();
    $choiceCounter = 0;
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'title':
                $title = decodeTitle($child);
                $bookmarkSet->setTitle($title);
                break;
            case 'uid':
                $bookmarkSet->setUid($child->nodeValue);
                break;
            case 'lastmark':
                $lastmark = decodeLastmark($child);
                $bookmarkSet->setLastmark($lastmark);
                break;
            case 'bookmark':
                $bookmark = decodeBookmark($child);
                $bookmarkSet->bookmark[$choiceCounter] = $bookmark;
                $choiceCounter++;
                break;
            case 'hilite':
                $hilite = decodeHilite($child);
                $bookmarkSet->hilite[$choiceCounter] = $hilite;
                $choiceCounter++;
                break;
        }
    }

    return $bookmarkSet;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $bookmarkSet, the bookmarkSet object
 */
function encodeBookmarkSet($dom, $node, $bookmarkSet)
{
    if (!is_null($bookmarkSet->title) && is_a($bookmarkSet->title, "title"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":title");
        $node->appendChild($child);
        encodeTitle($dom, $child, $bookmarkSet->title);
    }
    if (!is_null($bookmarkSet->uid) && is_string($bookmarkSet->uid))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":uid", $bookmarkSet->uid);
        $node->appendChild($child);
    }
    if (!is_null($bookmarkSet->lastmark) && is_a($bookmarkSet->lastmark, "lastmark"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":lastmark");
        $node->appendChild($child);
        encodeLastmark($dom, $child, $bookmarkSet->lastmark);
    }
    if (!is_array($bookmarkSet->bookmark)) $bookmarkSet->bookmark = array();
    if (!is_array($bookmarkSet->hilite)) $bookmarkSet->hilite = array();
    $choices = $bookmarkSet->bookmark + $bookmarkSet->hilite;
    ksort($choices);
    foreach ($choices as $choice)
    {
        if (is_a($choice, "bookmark"))
        {
            $child = $dom->createElementNS(NS_URI, NS_PREFIX.":bookmark");
            $node->appendChild($child);
            encodeBookmark($dom, $child, $choice);
        }
        else if (is_a($choice, "hilite"))
        {
            $child = $dom->createElementNS(NS_URI, NS_PREFIX.":hilite");
            $node->appendChild($child);
            encodeHilite($dom, $child, $choice);
        }
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a title node
 * @return object title, an object representation of an XML string
 */
function decodeTitle($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $title = new title();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'text':
                $title->setText($child->nodeValue);
                break;
            case 'audio':
                $audio = decodeBookmarkAudio($child);
                $title->setAudio($audio);
                break;
        }
    }
    return $title;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $title, the title object
 */
function encodeTitle($dom, $node, $title)
{
    if (!is_null($title->text) && is_string($title->text))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":text", $title->text);
        $node->appendChild($child);
    }
    if (!is_null($title->audio) && is_a($title->audio, "bookmarkAudio"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":audio");
        $node->appendChild($child);
        encodeBookmarkAudio($dom, $child, $title->audio);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to an audio node
 * @return object bookmarkAudio, an object representation of an XML string
 */
function decodeBookmarkAudio($node)
{
    if ($node->hasAttributes() === false)
        return null;

    $bookmarkAudio = new bookmarkAudio();
    foreach ($node->attributes as $attribute)
    {
        switch ($attribute->localName)
        {
            case 'src':
                $bookmarkAudio->setSrc($attribute->nodeValue);
                break;
            case 'clipBegin':
                $bookmarkAudio->setClipBegin($attribute->nodeValue);
                break;
            case 'clipEnd':
                $bookmarkAudio->setClipEnd($attribute->nodeValue);
                break;
        }
    }

    return $bookmarkAudio;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $audio, the bookmarkAudio object
 */
function encodeBookmarkAudio($dom, $node, $audio)
{
    if (!is_null($audio->src) && is_string($audio->src))
    {
        $node->setAttribute("src", $audio->src);
    }
    if (!is_null($audio->clipBegin) && is_string($audio->clipBegin))
    {
        $node->setAttribute("clipBegin", $audio->clipBegin);
    }
    if (!is_null($audio->clipEnd) && is_string($audio->clipEnd))
    {
        $node->setAttribute("clipEnd", $audio->clipEnd);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a lastmark node
 * @return object lastmark, an object representation of an XML string
 */
function decodeLastmark($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $lastmark = new lastmark();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'ncxRef':
                $lastmark->setNcxRef($child->nodeValue);
                break;
            case 'URI':
                $lastmark->setURI($child->nodeValue);
                break;
            case 'timeOffset':
                $lastmark->setTimeOffset($child->nodeValue);
                break;
            case 'charOffset':
                $lastmark->setCharOffset((int)($child->nodeValue));
                break;
        }
    }
    return $lastmark;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $lastmark, the lastmark object
 */
function encodeLastmark($dom, $node, $lastmark)
{
    if (!is_null($lastmark->ncxRef) && is_string($lastmark->ncxRef))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":ncxRef", $lastmark->ncxRef);
        $node->appendChild($child);
    }
    if (!is_null($lastmark->URI) && is_string($lastmark->URI))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":URI", $lastmark->URI);
        $node->appendChild($child);
    }
    if (!is_null($lastmark->timeOffset) && is_string($lastmark->timeOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":timeOffset", $lastmark->timeOffset);
        $node->appendChild($child);
    }
    if (!is_null($lastmark->charOffset) && is_int($lastmark->charOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":charOffset", $lastmark->charOffset);
        $node->appendChild($child);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a bookmark node
 * @return object bookmark, an object representation of an XML string
 */
function decodeBookmark($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $bookmark = new bookmark();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'ncxRef':
                $bookmark->setNcxRef($child->nodeValue);
                break;
            case 'URI':
                $bookmark->setURI($child->nodeValue);
                break;
            case 'timeOffset':
                $bookmark->setTimeOffset($child->nodeValue);
                break;
            case 'charOffset':
                $bookmark->setCharOffset((int)($child->nodeValue));
                break;
            case 'note':
                $note = decodeNote($child);
                $bookmark->setNote($note);
                break;
        }
    }

    if ($node->hasAttributes())
    {
        foreach ($node->attributes as $attribute)
        {
            switch ($attribute->localName)
            {
                case 'label':
                    $bookmark->setLabel($attribute->nodeValue);
                    break;
                case 'lang':
                    $bookmark->setLang($attribute->nodeValue);
                    break;
            }
        }
    }
    return $bookmark;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $bookmark, the bookmark object
 */
function encodeBookmark($dom, $node, $bookmark)
{
    if (!is_null($bookmark->ncxRef) && is_string($bookmark->ncxRef))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":ncxRef", $bookmark->ncxRef);
        $node->appendChild($child);
    }
    if (!is_null($bookmark->URI) && is_string($bookmark->URI))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":URI", $bookmark->URI);
        $node->appendChild($child);
    }
    if (!is_null($bookmark->timeOffset) && is_string($bookmark->timeOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":timeOffset", $bookmark->timeOffset);
        $node->appendChild($child);
    }
    if (!is_null($bookmark->charOffset) && is_int($bookmark->charOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":charOffset", $bookmark->charOffset);
        $node->appendChild($child);
    }
    if (!is_null($bookmark->note) && is_a($bookmark->note, "note"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":note");
        $node->appendChild($child);
        encodeNote($dom, $child, $bookmark->note);
    }
    if (!is_null($bookmark->label) && is_string($bookmark->label))
    {
        $node->setAttribute("label", $bookmark->label);
    }
    if (!is_null($bookmark->lang) && is_string($bookmark->lang))
    {
        $node->setAttribute("xml:lang", $bookmark->lang);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a note node
 * @return object note, an object representation of an XML string
 */
function decodeNote($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $note = new note();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'text':
                $note->setText($child->nodeValue);
                break;
            case 'audio':
                $audio = decodeBookmarkAudio($child);
                $note->setAudio($audio);
                break;
        }
    }
    return $note;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $note, the note object
 */
function encodeNote($dom, $node, $note)
{
    if (!is_null($note->text) && is_string($note->text))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":text", $note->text);
        $node->appendChild($child);
    }
    if (!is_null($note->audio) && is_a($note->audio, "bookmarkAudio"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":audio");
        $node->appendChild($child);
        encodeBookmarkAudio($dom, $child, $note->audio);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a hilite node
 * @return object hilite, an object representation of an XML string
 */
function decodeHilite($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $hilite = new hilite();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'hiliteStart':
                $hiliteStart = decodeHiliteStart($child);
                $hilite->setHiliteStart($hiliteStart);
                break;
            case 'hiliteEnd':
                $hiliteEnd = decodeHiliteEnd($child);
                $hilite->setHiliteEnd($hiliteEnd);
                break;
            case 'note':
                $note = decodeNote($child);
                $hilite->setNote($note);
                break;
        }
    }

    if ($node->hasAttributes())
    {
        foreach ($node->attributes as $attribute)
        {
            switch ($attribute->localName)
            {
                case 'label':
                    $hilite->setLabel($attribute->nodeValue);
                    break;
            }
        }
    }
    return $hilite;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $hilite, the hilite object
 */
function encodeHilite($dom, $node, $hilite)
{
    if (!is_null($hilite->hiliteStart) && is_a($hilite->hiliteStart, "hiliteStart"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":hiliteStart");
        $node->appendChild($child);
        encodeHiliteStart($dom, $child, $hilite->hiliteStart);
    }
    if (!is_null($hilite->hiliteEnd) && is_a($hilite->hiliteEnd, "hiliteEnd"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":hiliteEnd");
        $node->appendChild($child);
        encodeHiliteEnd($dom, $child, $hilite->hiliteEnd);
    }
    if (!is_null($hilite->note) && is_a($hilite->note, "note"))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":note");
        $node->appendChild($child);
        encodeNote($dom, $child, $hilite->note);
    }
    if (!is_null($hilite->label) && is_string($hilite->label))
    {
        $node->setAttribute("label", $hilite->label);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a hiliteStart node
 * @return object hiliteStart, an object representation of an XML string
 */
function decodeHiliteStart($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $hiliteStart = new hiliteStart();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'ncxRef':
                $hiliteStart->setNcxRef($child->nodeValue);
                break;
            case 'URI':
                $hiliteStart->setURI($child->nodeValue);
                break;
            case 'timeOffset':
                $hiliteStart->setTimeOffset($child->nodeValue);
                break;
            case 'charOffset':
                $hiliteStart->setCharOffset((int)($child->nodeValue));
                break;
        }
    }
    return $hiliteStart;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $hiliteStart, the hiliteStart object
 */
function encodeHiliteStart($dom, $node, $hiliteStart)
{
    if (!is_null($hiliteStart->ncxRef) && is_string($hiliteStart->ncxRef))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":ncxRef", $hiliteStart->ncxRef);
        $node->appendChild($child);
    }
    if (!is_null($hiliteStart->URI) && is_string($hiliteStart->URI))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":URI", $hiliteStart->URI);
        $node->appendChild($child);
    }
    if (!is_null($hiliteStart->timeOffset) && is_string($hiliteStart->timeOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":timeOffset", $hiliteStart->timeOffset);
        $node->appendChild($child);
    }
    if (!is_null($hiliteStart->charOffset) && is_int($hiliteStart->charOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":charOffset", $hiliteStart->charOffset);
        $node->appendChild($child);
    }
}

/**
 * decode from DOM object
 * @param DomNode $node, pointer to a hiliteEnd node
 * @return object hiliteEnd, an object representation of an XML string
 */
function decodeHiliteEnd($node)
{
    if ($node->hasChildNodes() === false)
        return null;

    $hiliteEnd = new hiliteEnd();
    foreach ($node->childNodes as $child)
    {
        switch ($child->localName)
        {
            case 'ncxRef':
                $hiliteEnd->setNcxRef($child->nodeValue);
                break;
            case 'URI':
                $hiliteEnd->setURI($child->nodeValue);
                break;
            case 'timeOffset':
                $hiliteEnd->setTimeOffset($child->nodeValue);
                break;
            case 'charOffset':
                $hiliteEnd->setCharOffset((int)($child->nodeValue));
                break;
        }
    }
    return $hiliteEnd;
}

/**
 * encode to XML from PHP object
 * @param DomNode $dom, pointer to a DOM object
 * @param DomNode $node, pointer to a DOM node
 * @param object $hiliteEnd, the hiliteEnd object
 */
function encodeHiliteEnd($dom, $node, $hiliteEnd)
{
    if (!is_null($hiliteEnd->ncxRef) && is_string($hiliteEnd->ncxRef))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":ncxRef", $hiliteEnd->ncxRef);
        $node->appendChild($child);
    }
    if (!is_null($hiliteEnd->URI) && is_string($hiliteEnd->URI))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":URI", $hiliteEnd->URI);
        $node->appendChild($child);
    }
    if (!is_null($hiliteEnd->timeOffset) && is_string($hiliteEnd->timeOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":timeOffset", $hiliteEnd->timeOffset);
        $node->appendChild($child);
    }
    if (!is_null($hiliteEnd->charOffset) && is_int($hiliteEnd->charOffset))
    {
        $child = $dom->createElementNS(NS_URI, NS_PREFIX.":charOffset", $hiliteEnd->charOffset);
        $node->appendChild($child);
    }
}

?>
