<?php
class SQLInsertRequest extends SQLRequest
{
    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    function createPreparedQuery(): string
    {
        $queryString = "INSERT INTO {$this->tableName} (%s) VALUES (%s)";
        $parameters = [];
        $values = [];

        foreach ($this->absoluteClauses as $clause) {
            array_push($parameters, $clause->getParameter());
            array_push($values, $clause->prepare());
        }

        return sprintf($queryString, join(", ", $parameters), join(", ", $values));
    }

    public function addInsert(SQLValueInsert $insert)
    {
        array_push($this->absoluteClauses, $insert);
    }
}
