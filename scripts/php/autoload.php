<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

spl_autoload_register(function ($className) {
    $iterator = new DirectoryIterator(dirname(__FILE__));
    $files = $iterator->getPath() . "/classes/" . $className . ".php";

    if (file_exists($files))
        include($files);
});
