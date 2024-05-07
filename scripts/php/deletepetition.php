<?php
if (is_post_request()) {
    $petitionData = json_decode(file_get_contents("php://input"), true);

    $request = new SQLDeleteRequest(TABLE_NAME);
    $request->addConstraint(new SQLEquality("id", $petitionData["id"]));

    $dictionary = new DataMessageDictionary(
        "Petition successfully deleted.",
        "The petition you tried to delete is already nonexistent.",
        "An internal error occured while attempting to delete a petition."
    );

    $displayer = new RequestDisplayer($request, $dictionary);
    $displayer->show();
}
