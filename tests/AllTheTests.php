<?php
define ( AMF_BASE_PATH, '../amf/' );
include_once '../amf/core.php';

$path = pathinfo ( $_SERVER [SCRIPT_FILENAME], PATHINFO_DIRNAME );
MassTest::runAll ( $path );

?>