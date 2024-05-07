<?php
require_once "prepend.php";

if (is_post_request()) {
    $formDataInput = json_decode(file_get_contents("php://input"), true);
    $referer = $_SERVER["HTTP_REFERER"];

    save_form_data($referer, $formDataInput);

    echo json_encode(get_form_data());
}
