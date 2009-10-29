<?php

    require_once('lib/TestHelper.php');
 
	ini_set('include_path', ini_get('include_path') . ":" . TestHelper::getIncludePath());
 
    function __autoload($className)
    {
        require_once(TestHelper::parseClassName($className));
    }
 
	TestHelper::init();
?>
