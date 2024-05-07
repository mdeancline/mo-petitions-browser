<?php
class SearchResultsMeta extends Paragraph implements HTMLDataDrivenMarkup
{
    public function __construct()
    {
        parent::__construct(new Bold("Showing: "));
    }

    public function update(array $requestResult)
    {
        $rowCount = $requestResult["rowCount"];
        $rowCountFormatted = number_format($rowCount);
        $currentPageFormatted = number_format($requestResult["currentPage"]);

        $resultsPerPage = $requestResult["resultsPerPage"];

        $maxPages = $rowCount / $resultsPerPage;
        $maxPagesFormatted = number_format(ceil($maxPages));

        $this->withText($rowCount > $resultsPerPage
            ? "Page {$currentPageFormatted}-{$maxPagesFormatted} of "
            : "");

        $this->withInline((new Span($rowCountFormatted))
            ->withId("resultsCount"))
            ->withText(" result" . ($rowCount == 1 ? "" : "s"));
    }
}
