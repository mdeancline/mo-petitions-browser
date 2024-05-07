<?php
set_exception_handler(function ($e) {
    log_error($e);

    echo "<script>document.write('');</script>";

    $smarty = new Smarty();
    $smarty->display("error.tpl");

    die();
});
