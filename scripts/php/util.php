<?php

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

function log_error(Throwable $e)
{
    $logFilePath = $_SERVER["DOCUMENT_ROOT"] . "/logs/error.log";

    $formatter = new LineFormatter(
        "[%datetime%] %level_name%: %message%\n",
        LineFormatter::SIMPLE_DATE
    );
    $formatter->includeStacktraces(true);

    $stream = new StreamHandler($logFilePath);
    $stream->setFormatter($formatter);

    $logger = new Logger("");
    $logger->pushHandler($stream);
    $logger->error((string)$e);
}

function is_associative(array $array): bool
{
    $keyCount = count(array_filter(array_keys($array), "is_string"));
    $arrayCount = count($array);
    return $arrayCount == $keyCount;
}

function redirect_to_page(string $url, RedirectResponse $response = null, bool $allowFromSamePage = false)
{
    $basename = basename($_SERVER["PHP_SELF"]);

    if (!$allowFromSamePage && str_contains($url, $basename) && $basename != "index.php")
        redirect_to_index($response);

    $_SESSION["redirect"] = [];

    if (!is_null($response))
        $_SESSION["redirect"]["response"] = $response;

    $_SESSION["redirect"]["method"] = $_SERVER["REQUEST_METHOD"];

    header("Location: " . ($url ?: "/"));
    exit;
}

function redirect_to_index(RedirectResponse $response = null, bool $allowFromSamePage = false)
{
    redirect_to_page("", $response, $allowFromSamePage);
}

function redirect_to_previous(RedirectResponse $response = null, bool $allowFromSamePage = false)
{
    redirect_to_page($_SERVER["HTTP_REFERER"] ?? "", $response, $allowFromSamePage);
}

function get_redirect_breakdown(): array
{
    if (!was_redirected())
        throw new RuntimeException("Attempted to get a redirect breakdown without a redirect happening");

    return $_SESSION["redirect"];
}

function was_redirected(): bool
{
    return isset($_SESSION["redirected"]) && $_SESSION["redirected"];
}

function get_url(): string
{
    return (empty($_SERVER["HTTPS"]) ? "http" : "https") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}

function is_post_request(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "POST";
}

function was_post_request()
{
    return was_redirected() && get_redirect_breakdown()["method"] == "POST";
}

function is_get_request(): bool
{
    return $_SERVER["REQUEST_METHOD"] == "GET";
}

function was_get_request()
{
    return was_redirected() && get_redirect_breakdown()["method"] == "GET";
}

function get_form_data(string $url = null): array
{
    $formDataKey = get_form_data_key($url ?? get_url());
    return get_session_data($formDataKey) ?? [];
}

function remove_form_data(string $url)
{
    $formDataKey = get_form_data_key($url);

    foreach (array_keys($_SESSION) as $key)
        if (str_contains($formDataKey, $key))
            unset($_SESSION[$key]);
}

function save_form_data(string $url, array $data)
{
    $formDataKey = get_form_data_key($url);
    $_SESSION[$formDataKey] = $data;
}

function get_form_data_key(string $url)
{
    $base64Url = base64_encode($url);
    return "formData-{$base64Url}";
}

function get_session_data(string $name): mixed
{
    return $_SESSION[$name] ?? null;
}

function to_camel_case($string, $capitalizeFirstCharacter = false)
{
    if (empty($string)) return $string;
    $str = str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));

    if (!$capitalizeFirstCharacter)
        $str[0] = strtolower($str[0]);

    return $str;
}
