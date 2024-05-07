<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

use IvoPetkov\HTML5DOMDocument;

$smartyDisplay = new SmartyDataDisplay(new Smarty, "add.tpl");

$document = new HTML5DOMDocument;

$smartyDisplay->assign(new SimpleVariable("action", "added.php"));
$smartyDisplay->assign((new HTMLVariable("monthSelectionHtml", $document))
    ->withContent(new MonthSelection));
$smartyDisplay->assign((new HTMLVariable("petitionStatusOptionsHtml", $document))
    ->withContent(new PetitionStatusOptions(false, true)));
$smartyDisplay->assign((new HTMLVariable("landSurveyInputsHtml", $document))
    ->withContentDataDriven(new LandSurveyInputsSection));

$redirectNotifier = new RedirectNotifier($smartyDisplay, $document);
$redirectNotifier->notify();

$formDisplay = new FormDisplay($smartyDisplay);
$displayer = new GetDisplayer($formDisplay);
$displayer->show();

require_once dirname(__FILE__) . "/scripts/php/append.php";
