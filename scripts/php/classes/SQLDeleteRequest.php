<?php
class SQLDeleteRequest extends SQLRequest
{
    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    function createPreparedQuery(): string
    {
        $queryString = "DELETE FROM {$this->tableName} ";
        $clauseCount = count($this->absoluteClauses);

        if ($clauseCount >= 1) {
            $queryString .= "WHERE ";

            foreach ($this->absoluteClauses as $clause)
                $queryString .= $clause->prepare() . ($clauseCount >= 2 ? " AND " : "");

            $queryString = rtrim($queryString, " AND ");
        }

        return $queryString;
    }

    public function addConstraint(SQLConstraint $constraint)
    {
        array_push($this->absoluteClauses, $constraint);
    }
}
