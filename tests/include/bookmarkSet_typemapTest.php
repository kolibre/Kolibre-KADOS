<?php

$includePath = dirname(realpath(__FILE__)) . '/../../include';
set_include_path(get_include_path() . PATH_SEPARATOR . $includePath);

require_once('bookmarkSet_typemap.php');

class BookmarkSetTypemap extends PHPUnit_Framework_TestCase
{
    protected static $inputXML;
    protected static $inputPHP;

    public static function setUpBeforeClass()
    {
        self::$inputXML = <<<XML
<bookmarkSet xmlns="http://www.daisy.org/z3986/2005/bookmark/">
<title>
<text>Gone with the Wind</text>
<audio src="gwtw_title.mp3"/>
</title>
<uid>us-rfbd-JT065</uid>
<lastmark>
<ncxRef>gwtw.ncx#lvl1_5</ncxRef>
<URI>gwtw_ch5.smil#para023</URI>
<timeOffset>03:52.00</timeOffset>
</lastmark>
<bookmark>
<ncxRef>gwtw.ncx#lvl1_1</ncxRef>
<URI>gwtw_ch1.smil#para008</URI>
<timeOffset>00:22.00</timeOffset>
</bookmark>
<bookmark>
<ncxRef>gwtw.ncx#lvl1_3</ncxRef>
<URI>gwtw_ch3.smil#para012</URI>
<timeOffset>01:28.00</timeOffset>
<note>
<text>Atlanta burns.</text>
</note>
</bookmark>
<hilite>
<hiliteStart>
<ncxRef>gwtw.ncx#lvl1_4</ncxRef>
<URI>gwtw_ch4.smil#para001</URI>
<timeOffset>00:00.00</timeOffset>
</hiliteStart>
<hiliteEnd>
<ncxRef>gwtw.ncx#lvl1_4</ncxRef>
<URI>gwtw_ch4.smil#para006</URI>
<timeOffset>04:06.00</timeOffset>
</hiliteEnd>
<note>
<audio src="us-rfbd-JT065.wav" clipBegin="00:00.00" clipEnd="00:10.00"/>
</note>
</hilite>
</bookmarkSet>
XML;
    }

    /**
     * @group bookmarkSet
     * @group typemap
     */
    public function testDecodeBookmarkSet()
    {
        $bookmarkSet = bookmarkSet_from_xml(self::$inputXML);
        $bookmarkSet->validate();
        $this->assertTrue($bookmarkSet->validate());
        self::$inputPHP = $bookmarkSet;
    }

    /**
     * @depends testDecodeBookmarkSet
     * @group bookmarkSet
     * @group typemap
     */
    public function testEncodeBookmarkSet()
    {
        $outputXML = bookmarkSet_to_xml(self::$inputPHP);

        // tweak output and input to make them compareable
        $outputXML = str_replace("ns2:", "", $outputXML);
        $outputXML = str_replace(":ns2", "", $outputXML);
        $inputXML = str_replace("\n", "", self::$inputXML);
        $this->assertEquals($outputXML, $inputXML);
    }
}
?>
