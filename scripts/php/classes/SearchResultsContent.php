<?php
class SearchResultsContent extends AbstractHTMLContainer implements HTMLDataDrivenMarkup
{
    private readonly HTMLVariable $modalsHtmlVariable;

    public function __construct(HTMLVariable $modalsHtmlVariable)
    {
        parent::__construct("div");
        parent::withId("results")
            ->withClassList("row", "list-view");

        $this->modalsHtmlVariable = $modalsHtmlVariable;
    }

    public function update(array $requestResult)
    {
        foreach ($requestResult["data"] as $dataRow)
            $this->appendContent($dataRow);
    }

    private function appendContent(array $dataRow)
    {
        $notes = strval($dataRow["notes"]);
        $title = strval($dataRow["title"]);
        $id = $dataRow["id"];

        $editBtn = BootstrapButton::forOpeningLink("/edit.php?id={$id}", "Edit", AnchorTarget::BLANK);

        $deletionModal = (new BootstrapModal("Delete: {$title}"))
            ->withBodyContent(new Paragraph("Are you sure you want to delete this petition?"));

        $infoModal = new BootstrapModal($title);
        $infoModal->withFooterContent(BootstrapButton::forClosing($infoModal, "Close"));

        $card = (new BootstrapCard($title))
            ->withRandomId()
            ->withFooterContent(BootstrapButton::forOpening($infoModal, "View More"))
            ->withFooterContent($editBtn)
            ->withFooterContent(BootstrapButton::forOpening($deletionModal, "Delete", BootstrapStyle::SECONDARY_OUTLINE));

        $confimedDeleteBtn = BootstrapButton::forClosing($deletionModal, "Yes", BootstrapStyle::DANGER)
            ->withAttribute("data-deletion-id", $id)
            ->withAttribute("data-card-id", $card->getAttribute("id"))
            ->withAttribute("data-modal-id", $deletionModal->getAttribute("id"));

        $deletionModal
            ->withFooterContent(BootstrapButton::forClosing($deletionModal, "No"))
            ->withFooterContent($confimedDeleteBtn);

        if (!empty($notes)) {
            $notesHtml = new RawHTML(nl2br(str_replace('\n', "\n", $notes)));
            $card->withBodyContent($notesHtml);
            $infoModal->withBodyContent($notesHtml);
        }

        $month = $dataRow["month"];
        $day = $dataRow["day"];
        $year = $dataRow["year"];

        $dateParagraph = new Paragraph(new Bold("Date: "));

        if ($month == null)
            $dateParagraph->withText($year == null ? "Unknown" : $year);
        else {
            $date = DateTime::createFromFormat("!m", $month);
            $monthName = $date->format("F");
            $dateParagraph->withText($day == null ? "Unknown" : "$monthName $day, $year");
        }

        $box = $dataRow["box"];
        $folder = $dataRow["folder"];
        $status = ucwords($dataRow["status"]);
        $location = $box == null || $folder == null ? "Unknown" : "Box $box. Folder $folder";

        $infoModal->withBodyContent($dateParagraph)
            ->withBodyContent((new Paragraph(new Bold("Status: ")))
                ->withText($status))
            ->withBodyContent((new Paragraph(new Bold("Location: ")))
                ->withText($location))
            ->withFooterContent($editBtn);

        $landSurveys = json_decode($dataRow["landSurveys"] ?? "", true);

        if ($landSurveys != null && count($landSurveys) > 0) {
            $landSurveyTable = (new Table(new TableHeader("Section", "Township", "Range")))
                ->withClassList("table");

            foreach ($landSurveys as $landSurvey)
                $landSurveyTable->withRow(new TableRow(
                    new TableCell($landSurvey["section"]),
                    new TableCell($landSurvey["township"]),
                    new TableCell($landSurvey["range"])
                ));

            $infoModal->withBodyContent($landSurveyTable);
        }

        $this->withChild($card);

        $this->modalsHtmlVariable->withContent($infoModal)
            ->withContent($deletionModal);
    }
}
