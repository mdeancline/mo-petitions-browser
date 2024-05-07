<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

use IvoPetkov\HTML5DOMDocument;

if (is_post_request()) {
    $query = [];

    foreach ($_POST as $key => $value)
        if (!empty($value))
            $query[$key] = htmlspecialchars($value);

    $queryBase64 = base64_encode(json_encode($query));
    $redirectUrl = "{$_SERVER["REQUEST_URI"]}?q={$queryBase64}";
    redirect_to_page($redirectUrl, null, true);
} else if (!isset($_GET["q"])) {
    reject_invalid_search();
} else {
    $queryBase64 = $_GET["q"];
    $currentPage = intval($_GET["page"] ?? 1);

    if ($currentPage < 1) {
        $requestPath = $_SERVER["REQUEST_URI"];
        redirect_to_page("{$requestPath}?q={$queryBase64}", null, true);
    }

    $query = json_decode(base64_decode($queryBase64), true);

    if ($query === null)
        reject_invalid_search();

    $requestAggregate = new DataRequestAggregate();
    $requestAggregate->addRequest(new PageInfo(RESULTS_PER_PAGE, $currentPage));

    $sqlRequests = [
        new SQLCountRequest(TABLE_NAME),
        (new SQLSelectRequest(TABLE_NAME))
            ->startIndex((RESULTS_PER_PAGE * $currentPage) - RESULTS_PER_PAGE)
            ->maxRows(RESULTS_PER_PAGE)
    ];

    foreach ($sqlRequests as $sqlRequest)
        $requestAggregate->addRequest($sqlRequest);

    $wildcardConstraintKeys = ["year", "title", "notes"];

    $landSurvey = [];
    $landSurveyKeys = ["section", "township", "range"];

    foreach ($landSurveyKeys as $key) {
        if (isset($query[$key])) {
            $landSurvey[$key] = intval($query[$key]);
            unset($query[$key]);
        }
    }

    $additionalConstraints = [];
    if (!empty($landSurvey))
        array_push($additionalConstraints, new JSONConstraint("landSurveys", $landSurvey));

    foreach ($sqlRequests as $sqlRequest) {
        foreach ($query as $key => $value) {

            if (str_contains($key, "status"))
                $sqlRequest->addAlternatedConstraint(new SQLEquality("status", $value));
            else {
                $constraint = in_array($key, $wildcardConstraintKeys)
                    ? new SQLWildcardConstraint($key, $value)
                    : new SQLEquality($key, $value);

                $sqlRequest->addAbsoluteConstraint($constraint);
            }
        }

        foreach ($additionalConstraints as $additionalConstraint)
            $sqlRequest->addAbsoluteConstraint($additionalConstraint);
    }

    $smartyDisplay = new SmartyDataDisplay(new Smarty, "results.tpl");

    $document = new HTML5DOMDocument;
    $modalsHtmlVariable = new HTMLVariable("modalsHtml", $document);
    $smartyDisplay->assign((new HTMLVariable("resultsAmountHtml", $document))
        ->withContentDataDriven(new SearchResultsMeta));
    $smartyDisplay->assign((new HTMLVariable("resultsHtml", $document))
        ->withContentDataDriven(new SearchResultsContent($modalsHtmlVariable)));
    $smartyDisplay->assign((new HTMLVariable("pageNavHtml", $document))
        ->withContentDataDriven(new SearchResultsPageNavigation));
    $smartyDisplay->assign($modalsHtmlVariable);

    $redirectNotifier = new RedirectNotifier($smartyDisplay, $document);
    $redirectNotifier->notify();

    $msgDictionary = new DataMessageDictionary(
        "",
        "No petitions under the given data were found.",
        "An internal error occured while attempting to conduct a petitions search."
    );

    $fallbackedDisplay = new FallbackedDisplay($smartyDisplay, new BootstrapModalDisplay);
    $displayer = new PostDisplayer($fallbackedDisplay, $requestAggregate, $msgDictionary);
    $displayer->show();
}

function reject_invalid_search()
{
    $dialog = new Dialog("Error", "The search results page you tried to view was invalid.");
    $response = new BootstrapTargetResponse($dialog);
    redirect_to_index($response);
}

require_once dirname(__FILE__) . "/scripts/php/append.php";
