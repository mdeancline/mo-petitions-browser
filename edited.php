<?php
require_once dirname(__FILE__) . "/scripts/php/prepend.php";

if (!isset($_POST["id"]) || !is_numeric($_POST["id"]))
    redirect_to_index();

$request = new SQLUpdateRequest(TABLE_NAME);
$request->setPrimaryConstraint(new SQLEquality("id", $_POST["id"]));
unset($_POST["id"]);

foreach ($_POST as $key => $value)
    if (!empty($value))
        $request->addUpdate(new SQLEquality($key, $value));

$dictionary = new DataMessageDictionary(
    "Petition successfully edited.",
    "The petition you tried to edit no longer exists.",
    "An internal error occured while attempting to edit this petition."
);

$display = new BootstrapModalDisplay("OK", new class implements BootstrapModalDisplayListener
{
    public function onShow(BootstrapModal $modal, array $requestResult)
    {
        $modal->withFooterContent(BootstrapButton::normal("Close Window")
            ->withAttribute("onclick", "window.close();"));
    }
});

$displayer = new PostDisplayer($display, $request, $dictionary, new NoTagsValidation);
$displayer->show();

require_once dirname(__FILE__) . "/scripts/php/append.php";
