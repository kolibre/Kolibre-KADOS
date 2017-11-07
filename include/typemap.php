<?php

require_once('bookmarkSet_typemap.php');
require_once('questions_typemap.php');

// typemap defines functions to use for encoding (from PHP to XML) or decoding (from XML to PHP)
// a specific type within a namespace.
// typemap is necessary when PHP can't handle complex types (eg. bookmarkSet) correctly
$typemap = array($bookmarkSet_typemap, $questions_typemap);

?>