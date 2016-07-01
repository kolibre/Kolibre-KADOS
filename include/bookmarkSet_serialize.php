<?php

$includePath = dirname(realpath(__FILE__)) . '/types';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('bookmarkSet.class.php');

/**
 * decode json string into a bookmarkSet object
 * @param string $json,json string to parse
 * @return object bookmarkSet, an object representation of an JSON string
 */
function bookmarkSet_from_json($json)
{
    // json_decode creates a stdClass object reprensentation, thus we
    // need to manually copy the elements into a bookmarkSet object
    $obj = json_decode($json);
    $bookmarkSet = new bookmarkSet();

    // title
    if (!is_null($obj->title) && is_a($obj->title, 'stdClass'))
    {
        $title = new title();
        if (!is_null($obj->title->text) && is_string($obj->title->text))
        {
            $title->text = $obj->title->text;
        }
        if (!is_null($obj->title->audio) && is_a($obj->title->audio, 'stdClass'))
        {
            $audio = new bookmarkAudio();
            if (!is_null($obj->title->audio->src) && is_string($obj->title->audio->src))
            {
                $audio->src = $obj->title->audio->src;
            }
            if (!is_null($obj->title->audio->clipBegin) && is_string($obj->title->audio->clipBegin))
            {
                $audio->clipBegin = $obj->title->audio->clipBegin;
            }
            if (!is_null($obj->title->audio->clipEnd) && is_string($obj->title->audio->clipEnd))
            {
                $audio->clipEnd = $obj->title->audio->clipEnd;
            }
            $title->audio = $audio;
        }
        $bookmarkSet->title = $title;
    }

    // uid
    if (!is_null($obj->uid) && is_string($obj->uid)) {
        $bookmarkSet->uid = $obj->uid;
    }

    // lastmark
    if (!is_null($obj->lastmark) && is_a($obj->lastmark, 'stdClass'))
    {
        $lastmark = new lastmark();
        if (!is_null($obj->lastmark->ncxRef) && is_string($obj->lastmark->ncxRef))
        {
            $lastmark->ncxRef = $obj->lastmark->ncxRef;
        }
        if (!is_null($obj->lastmark->URI) && is_string($obj->lastmark->URI))
        {
            $lastmark->URI = $obj->lastmark->URI;
        }
        if (!is_null($obj->lastmark->timeOffset) && is_string($obj->lastmark->timeOffset))
        {
            $lastmark->timeOffset = $obj->lastmark->timeOffset;
        }
        if (!is_null($obj->lastmark->charOffset) && is_int($obj->lastmark->charOffset))
        {
            $lastmark->charOffset = $obj->lastmark->charOffset;
        }
        $bookmarkSet->lastmark = $lastmark;
    }

    // bookmark
    if (!is_null($obj->bookmark) && (is_array($obj->bookmark) || is_a($obj->bookmark, 'stdClass')))
    {
        $bookmarks = array();
        foreach ($obj->bookmark as $key => $bm)
        {
            if (is_a($bm, 'stdClass'))
            {
                $bookmark = new bookmark();
                if (!is_null($bm->ncxRef) && is_string($bm->ncxRef))
                {
                    $bookmark->ncxRef = $bm->ncxRef;
                }
                if (!is_null($bm->URI) && is_string($bm->URI))
                {
                    $bookmark->URI = $bm->URI;
                }
                if (!is_null($bm->timeOffset) && is_string($bm->timeOffset))
                {
                    $bookmark->timeOffset = $bm->timeOffset;
                }
                if (!is_null($bm->charOffset) && is_int($bm->charOffset))
                {
                    $bookmark->charOffset = $bm->charOffset;
                }
                if (!is_null($bm->note) && is_a($bm->note, 'stdClass'))
                {
                    $note = new note();
                    if (!is_null($bm->note->text) && is_string($bm->note->text))
                    {
                        $note->text = $bm->note->text;
                    }
                    if (!is_null($bm->note->audio) && is_a($bm->note->audio, 'stdClass'))
                    {
                        $audio = new bookmarkAudio();
                        if (!is_null($bm->note->audio->src) && is_string($bm->note->audio->src))
                        {
                            $audio->src = $bm->note->audio->src;
                        }
                        if (!is_null($bm->note->audio->clipBegin) && is_string($bm->note->audio->clipBegin))
                        {
                            $audio->clipBegin = $bm->note->audio->clipBegin;
                        }
                        if (!is_null($bm->note->audio->clipEnd) && is_string($bm->note->audio->clipEnd))
                        {
                            $audio->clipEnd = $bm->note->audio->clipEnd;
                        }
                        $note->audio = $audio;
                    }
                    $bookmark->note = $note;
                }
                if (!is_null($bm->label) && is_string($bm->label))
                {
                    $bookmark->label = $bm->label;
                }
                if (!is_null($bm->lang) && is_string($bm->lang))
                {
                    $bookmark->lang = $bm->lang;
                }
                $bookmarks[$key] = $bookmark;
            }
        }
        $bookmarkSet->bookmark = $bookmarks;
    }

    // hilite
    if (!is_null($obj->hilite) && (is_array($obj->hilite) || is_a($obj->hilite, 'stdClass')))
    {
        $hilites = array();
        foreach ($obj->hilite as $key => $hl)
        {
            if (is_a($hl, 'stdClass'))
            {
                $hilite = new hilite();
                if (!is_null($hl->hiliteStart) && is_a($hl->hiliteStart, 'stdClass'))
                {
                    $hiliteStart = new hiliteStart();
                    if (!is_null($hl->hiliteStart->ncxRef) && is_string($hl->hiliteStart->ncxRef))
                    {
                        $hiliteStart->ncxRef = $hl->hiliteStart->ncxRef;
                    }
                    if (!is_null($hl->hiliteStart->URI) && is_string($hl->hiliteStart->URI))
                    {
                        $hiliteStart->URI = $hl->hiliteStart->URI;
                    }
                    if (!is_null($hl->hiliteStart->timeOffset) && is_string($hl->hiliteStart->timeOffset))
                    {
                        $hiliteStart->timeOffset = $hl->hiliteStart->timeOffset;
                    }
                    if (!is_null($hl->hiliteStart->charOffset) && is_int($hl->hiliteStart->charOffset))
                    {
                        $hiliteStart->charOffset = $hl->hiliteStart->charOffset;
                    }
                    $hilite->hiliteStart = $hiliteStart;
                }
                if (!is_null($hl->hiliteEnd) && is_a($hl->hiliteEnd, 'stdClass'))
                {
                    $hiliteEnd = new hiliteEnd();
                    if (!is_null($hl->hiliteEnd->ncxRef) && is_string($hl->hiliteEnd->ncxRef))
                    {
                        $hiliteEnd->ncxRef = $hl->hiliteEnd->ncxRef;
                    }
                    if (!is_null($hl->hiliteEnd->URI) && is_string($hl->hiliteEnd->URI))
                    {
                        $hiliteEnd->URI = $hl->hiliteEnd->URI;
                    }
                    if (!is_null($hl->hiliteEnd->timeOffset) && is_string($hl->hiliteEnd->timeOffset))
                    {
                        $hiliteEnd->timeOffset = $hl->hiliteEnd->timeOffset;
                    }
                    if (!is_null($hl->hiliteEnd->charOffset) && is_int($hl->hiliteEnd->charOffset))
                    {
                        $hiliteEnd->charOffset = $hl->hiliteEnd->charOffset;
                    }
                    $hilite->hiliteEnd = $hiliteEnd;
                }
                if (!is_null($hl->note) && is_a($hl->note, 'stdClass'))
                {
                    $note = new note();
                    if (!is_null($hl->note->text) && is_string($hl->note->text))
                    {
                        $note->text = $hl->note->text;
                    }
                    if (!is_null($hl->note->audio) && is_a($hl->note->audio, 'stdClass'))
                    {
                        $audio = new bookmarkAudio();
                        if (!is_null($hl->note->audio->src) && is_string($hl->note->audio->src))
                        {
                            $audio->src = $hl->note->audio->src;
                        }
                        if (!is_null($hl->note->audio->clipBegin) && is_string($hl->note->audio->clipBegin))
                        {
                            $audio->clipBegin = $hl->note->audio->clipBegin;
                        }
                        if (!is_null($hl->note->audio->clipEnd) && is_string($hl->note->audio->clipEnd))
                        {
                            $audio->clipEnd = $hl->note->audio->clipEnd;
                        }
                        $note->audio = $audio;
                    }
                    $hilite->note = $note;
                }
                if (!is_null($hl->label) && is_string($hl->label))
                {
                    $hilite->label = $hl->label;
                }
                $hilites[$key] = $hilite;
            }
        }
        $bookmarkSet->hilite = $hilites;
    }
    return $bookmarkSet;
}

?>
