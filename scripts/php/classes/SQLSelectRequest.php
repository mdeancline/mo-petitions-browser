<?php
class SQLSelectRequest extends SQLRequest
{
    private int $startIndex = 0;
    private int $maxRows = PHP_INT_MAX;
    private string $orderBy = "";
    private bool $descending = false;

    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    function createPreparedQuery(): string
    {
        $queryString = "SELECT * FROM {$this->tableName} ";
        $absoluteClausesCount = count($this->absoluteClauses);
        $alternateClausesCount = count($this->alternatedClauses);

        if ($absoluteClausesCount + $alternateClausesCount >= 1)
            $queryString = $this->createWherePart($queryString, $absoluteClausesCount, $alternateClausesCount);

        if (!empty($this->orderBy))
            $queryString .= " ORDER BY CAST({$this->orderBy} AS INTEGER)" . ($this->descending ? " DESC" : "");

        $queryString .= " LIMIT {$this->startIndex}, {$this->maxRows}";

        return $queryString;
    }

    private function createWherePart(string $queryString, int $absoluteClausesCount, int $alternateClausesCount): string
    {
        $queryString .= "WHERE ";

        if ($absoluteClausesCount > 0)
            $queryString = $this->appendRawAbsoluteClauses($queryString);

        if ($alternateClausesCount > 0) {
            if ($absoluteClausesCount >= 1)
                $queryString .= " AND ";

            $queryString = $this->appendRawAlternatedClauses($queryString);
        }

        return $queryString;
    }

    public function startIndex(int $startIndex): SQLSelectRequest
    {
        $this->startIndex = $startIndex;
        return $this;
    }

    public function maxRows(int $maxRows): SQLSelectRequest
    {
        $this->maxRows = $maxRows;
        return $this;
    }

    public function orderBy(string $orderBy): SQLSelectRequest
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function descending(bool $descending): SQLSelectRequest
    {
        if (empty($this->orderBy) && $descending)
            throw new RuntimeException("Order by column must be defined for descending row order");

        $this->descending = $descending;
        return $this;
    }

    public function addAbsoluteConstraint(SQLConstraint $constraint)
    {
        array_push($this->absoluteClauses, $constraint);
    }

    public function addAlternatedConstraint(SQLConstraint $constraint)
    {
        array_push($this->alternatedClauses, $constraint);
    }
}
