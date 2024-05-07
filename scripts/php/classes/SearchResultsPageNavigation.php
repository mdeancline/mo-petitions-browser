<?php
class SearchResultsPageNavigation extends AbstractHTMLContainer implements HTMLDataDrivenMarkup
{
    private const MAX_PAGE_LINKS = 10;

    private readonly HTMLList $pageItems;

    public function __construct()
    {
        parent::__construct("nav");

        $this->pageItems = (new HTMLList(ListType::UNORDERED))
            ->withClassList("pagination", "flex-wrap");

        $this->withClassList("page-nav")
            ->withAttribute("aria-roledescription", "Navigation")
            ->withAttribute("aria-label", "Page Navigation")
            ->withChild($this->pageItems);
    }

    public function update(array $requestResult)
    {
        $currentPage = $requestResult["currentPage"];
        $maxPages = ceil($requestResult["rowCount"] / $requestResult["resultsPerPage"]);

        if ($maxPages > 1)
            $this->populatePageNavigation($maxPages, $currentPage);
    }

    private function populatePageNavigation(int $maxPages, int $currentPage)
    {
        $pageItemPrevious = $this->createPageItem($currentPage, $currentPage - 1, "Previous");
        $pageItemNext = $this->createPageItem($currentPage, $currentPage + 1, "Next");
        $pageItemToFirst = $this->createPageItem($currentPage, 1, "<<");
        $pageItemToLast = $this->createPageItem($currentPage, $maxPages, ">>");

        if ($currentPage <= 1) {
            $pageItemPrevious->withClassList("disabled");
            $pageItemToFirst->withClassList("disabled");
        } else if ($currentPage >= $maxPages) {
            $pageItemNext->withClassList("disabled");
            $pageItemToLast->withClassList("disabled");
        }

        $this->pageItems->withListItem($pageItemPrevious)->withListItem($pageItemToFirst);
        $this->appendPageNumberLinks($currentPage, $maxPages);
        $this->pageItems->withListItem($pageItemToLast)->withListItem($pageItemNext);
    }

    private function appendPageNumberLinks(int $currentPage, int $maxPages)
    {
        $remainder = $currentPage % self::MAX_PAGE_LINKS;
        $pageNumStart = $remainder != 0
            ? $currentPage - ($remainder - ($remainder == 0 ? 1 : 0))
            : $currentPage;

        $pageNumEnd = min($maxPages, $pageNumStart + self::MAX_PAGE_LINKS);
        $pageNumStart = max(1, $pageNumStart);

        for ($pageNum = $pageNumStart; $pageNum <= $pageNumEnd; $pageNum++) {
            $pageItem = $this->createPageItem($currentPage, $pageNum);

            if ($pageNum == $currentPage)
                $pageItem->withClassList("active")
                    ->withAttribute("aria-current", "page");

            $this->pageItems->withListItem($pageItem);
        }
    }

    private function createPageItem(int $currentPage, int $pageNum, string $text = "")
    {
        $pageNumArg = "&page={$pageNum}";
        $currentPageArg = "&page={$currentPage}";
        $requestPath = $_SERVER["REQUEST_URI"];

        $requestPath = str_contains($requestPath, $currentPageArg)
            ? str_replace($currentPageArg, $pageNumArg, $requestPath)
            : $requestPath . $pageNumArg;

        $link = (new Anchor($requestPath, $text ?: $pageNum))
            ->withClassList("page-link");

        return (new ListItem($link))->withClassList("page-item");
    }
}
