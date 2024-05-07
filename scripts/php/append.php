<?php
if (was_redirected()) {
    unset($_SESSION["redirect"]);
    $_SESSION["redirected"] = false;
}
