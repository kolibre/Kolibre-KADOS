<?php

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('bookmarkSet_serialize.php');

class BookmarkSetSerialize extends PHPUnit_Framework_TestCase
{
    public function minimalBookmarkSet()
    {
        // setup a minimal valid bookmarkSet
        $title = new title('title');
        $uid = 'uid';
        return new bookmarkSet($title, $uid);
    }

    /**
     * @group bookmarkSet
     * @group serialize
     */
    public function testJsonEncodeDecodeMinimal()
    {
        $bookmarkSet = $this->minimalBookmarkSet();

        $this->assertTrue($bookmarkSet->validate());
        $json = json_encode($bookmarkSet);
        $obj = bookmarkSet_from_json($json);
        $this->assertEquals($obj, $bookmarkSet);
        $this->assertTrue($obj->validate());
    }

    /**
     * @depends testJsonEncodeDecodeMinimal
     * @group bookmarkSet
     * @group serialize
     */
    public function testJsonEncodeDecodeWithLastmark()
    {
        $bookmarkSet = $this->minimalBookmarkSet();

        $lastmark = new lastmark('ncxRef', 'uri', '00:00:00', 1);
        $bookmarkSet->lastmark = $lastmark;
        $this->assertTrue($bookmarkSet->validate());
        $json = json_encode($bookmarkSet);
        $obj = bookmarkSet_from_json($json);
        $this->assertEquals($obj, $bookmarkSet);
        $this->assertTrue($obj->validate());
    }

    /**
     * @depends testJsonEncodeDecodeWithLastmark
     * @group bookmarkSet
     * @group serialize
     */
    public function testJsonEncodeDecodeWithBookmarkAndHilite()
    {
        $bookmarkSet = $this->minimalBookmarkSet();

        $bookmark = new bookmark('ncxRef', 'uri', '00:00:00', 1);
        $bookmarkSet->bookmark[] = $bookmark;
        $hiliteStart = new hiliteStart('ncxRef', 'uri', '00:00:00', 1);
        $hiliteEnd = new hiliteEnd('ncxRef', 'uri', '00:00:00', 1);
        $hilite = new hilite($hiliteStart, $hiliteEnd);
        $bookmarkSet->hilite[] = $hilite;
        $this->assertTrue($bookmarkSet->validate());
        $json = json_encode($bookmarkSet);
        $obj = bookmarkSet_from_json($json);
        $this->assertEquals($obj, $bookmarkSet);
        $this->assertTrue($obj->validate());
    }

    /**
     * @depends testJsonEncodeDecodeWithBookmarkAndHilite
     * @group bookmarkSet
     * @group serialize
     */
    public function testJsonEncodeDecodeWithEverything()
    {
        $bookmarkSet = $this->minimalBookmarkSet();

        $audio = new bookmarkAudio('src', '00:00:00', '00:00:00');
        $bookmarkSet->title->audio = $audio;
        $lastmark = new lastmark('ncxRef', 'uri', '00:00:00', 1);
        $bookmarkSet->lastmark = $lastmark;
        $note = new note('text', $audio);
        $bookmark = new bookmark('ncxRef', 'uri', '00:00:00', 1, $note, 'label', 'lang');
        $bookmarkSet->bookmark[] = $bookmark;
        $hiliteStart = new hiliteStart('ncxRef', 'uri', '00:00:00', 1);
        $hiliteEnd = new hiliteEnd('ncxRef', 'uri', '00:00:00', 1);
        $hilite = new hilite($hiliteStart, $hiliteEnd, $note, 'label');
        $bookmarkSet->hilite[] = $hilite;
        $this->assertTrue($bookmarkSet->validate());
        $json = json_encode($bookmarkSet);
        $obj = bookmarkSet_from_json($json);
        $this->assertEquals($obj, $bookmarkSet);
        $this->assertTrue($obj->validate());
    }

    /**
     * @depends testJsonEncodeDecodeWithEverything
     * @group bookmarkSet
     * @group serialize
     */
    public function testJsonEncodeDecodeWithMixedBookmarksAndHilites()
    {
        $bookmarkSet = $this->minimalBookmarkSet();

        $bookmark = new bookmark('ncxRef', 'uri', '00:00:00', 1);
        $bookmarkSet->bookmark = array();
        $bookmarkSet->bookmark[1] = $bookmark;
        $bookmarkSet->bookmark[2] = $bookmark;
        $hiliteStart = new hiliteStart('ncxRef', 'uri', '00:00:00', 1);
        $hiliteEnd = new hiliteEnd('ncxRef', 'uri', '00:00:00', 1);
        $hilite = new hilite($hiliteStart, $hiliteEnd);
        $bookmarkSet->hilite = array();
        $bookmarkSet->hilite[0] = $hilite;
        $bookmarkSet->hilite[3] = $hilite;
        $this->assertTrue($bookmarkSet->validate());
        $json = json_encode($bookmarkSet);
        $obj = bookmarkSet_from_json($json);
        $this->assertEquals($obj, $bookmarkSet);
        $this->assertTrue($obj->validate());
    }

    /**
     * @depends testJsonEncodeDecodeMinimal
     * @group bookmarkSet
     * @group serialize
     */
     public function testJsonDecodeMimimalBookmarkSet()
     {
         $bookmarkSet = $this->minimalBookmarkSet();

         $this->assertTrue($bookmarkSet->validate());
         $obj = bookmarkSet_from_json('{"title":{"text":"title"}, "uid":"uid"}');
         $this->assertEquals($obj, $bookmarkSet);
         $this->assertTrue($obj->validate());
     }
}
?>
