<?php
error_reporting(E_ALL);

require_once "autoload.php";
require_once "util.php";
require_once "errorhandle.php";

ini_set("session.cookie_httponly", 1);
session_start();

$config = parse_ini_file($_SERVER["DOCUMENT_ROOT"] . DIRECTORY_SEPARATOR . "config.ini");

define("RESULTS_PER_PAGE", $config["results_per_page"]);
define("TABLE_NAME", "petitions");

if (isset($_SESSION["redirect"]))
    $_SESSION["redirected"] = true;
