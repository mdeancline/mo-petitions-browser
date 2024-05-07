<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

use IvoPetkov\HTML5DOMDocument;

$smartyDisplay = new SmartyDataDisplay(new Smarty, "index.tpl");

$document = new HTML5DOMDocument;

$smartyDisplay->assign(new SimpleVariable("action", "search.php"));
$smartyDisplay->assign((new HTMLVariable("monthSelectionHtml", $document))
    ->withContent(new MonthSelection));
$smartyDisplay->assign((new HTMLVariable("petitionStatusOptionsHtml", $document))
    ->withContent(new PetitionStatusOptions(true)));

$redirectNotifier = new RedirectNotifier($smartyDisplay, $document);
$redirectNotifier->notify();

$formDisplay = new FormDisplay($smartyDisplay);
$displayer = new GetDisplayer($formDisplay);
$displayer->show();

require_once dirname(__FILE__) . "/scripts/php/append.php";
