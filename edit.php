<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

use IvoPetkov\HTML5DOMDocument;

if (!isset($_GET["id"]) || !is_numeric($_GET["id"]))
    redirect_to_index();

$request = new SQLSelectRequest(TABLE_NAME);
$request->addAbsoluteConstraint(new SQLEquality("id", $_GET["id"]));

$smartyDisplay = new SmartyDataDisplay(new Smarty, "edit.tpl");

$document = new HTML5DOMDocument;

$smartyDisplay->assign(new SimpleVariable("action", "edited.php"));
$smartyDisplay->assign((new HTMLVariable("monthSelectionHtml", $document))
    ->withContentDataDriven(new MonthSelection));
$smartyDisplay->assign((new HTMLVariable("petitionStatusOptionsHtml", $document))
    ->withContentDataDriven(new PetitionStatusOptions));
$smartyDisplay->assign((new HTMLVariable("landSurveyInputsHtml", $document))
    ->withContentDataDriven(new LandSurveyInputsSection));

$redirectNotifier = new RedirectNotifier($smartyDisplay, $document);
$redirectNotifier->notify();

$msgDictionary = new DataMessageDictionary(
    "",
    "The petition you tried to edit no longer exists.",
    "An internal error occured while attempting to edit a petition."
);

$fallbackedDisplay = new FallbackedDisplay(new FormDisplay($smartyDisplay), new BootstrapModalDisplay);
$displayer = new GetDisplayer($fallbackedDisplay, $request, $msgDictionary);
$displayer->show();

require_once dirname(__FILE__) . "/scripts/php/append.php";
