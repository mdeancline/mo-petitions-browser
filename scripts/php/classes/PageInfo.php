<?php
class PageInfo implements DataRequest
{
    private readonly int $resultsPerPage;
    private readonly int $currentPage;

    public function __construct(int $resultsPerPage, int $currentPage)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->currentPage = $currentPage;
    }

    public function submit(DataMessageDictionary $dictionary): array
    {
        return [
            "resultsPerPage" => $this->resultsPerPage,
            "currentPage" => $this->currentPage
        ];
    }

    public function __toString(): string
    {
        return "Results per page: {$this->resultsPerPage}, Current page: {$this->currentPage} ";
    }
}
