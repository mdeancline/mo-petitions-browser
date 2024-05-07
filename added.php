<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

$insertRequest = new SQLInsertRequest(TABLE_NAME);

foreach ($_POST as $key => $value)
    if (!empty($value))
        $insertRequest->addInsert(new SimpleValueInsert($key, $value));

$dictionary = new DataMessageDictionary(
    "Petition successfully added.",
    "A database issue occured while attempting to add a petition.",
    "An internal error occured while attempting to add a petition."
);

$display = new BootstrapModalDisplay("OK", new class implements BootstrapModalDisplayListener
{
    public function onShow(BootstrapModal $modal, array $requestResult)
    {
        $id = $requestResult["data"][0]["id"];
        $modal->withFooterContent(BootstrapButton::forOpeningLink("/edit.php?id={$id}", "Edit", AnchorTarget::BLANK));
    }
});

$requestAggregate = new DataRequestAggregate();
$requestAggregate->addRequest($insertRequest);
$requestAggregate->addRequest((new SQLSelectRequest(TABLE_NAME))
    ->maxRows(1)
    ->orderBy("id")
    ->descending(true));

// echo $requestAggregate;
// exit;
$displayer = new PostDisplayer($display, $requestAggregate, $dictionary, new NoTagsValidation);
$displayer->show();

require_once dirname(__FILE__) . "/scripts/php/append.php";
