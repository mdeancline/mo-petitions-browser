<?php
class SQLUpdateRequest extends SQLRequest
{
    private SQLConstraint $primaryConstraint;

    public function __construct(string $tableName)
    {
        parent::__construct($tableName);
    }

    function createPreparedQuery(): string
    {
        if (($key = array_search($this->primaryConstraint, $this->absoluteClauses, true)))
            unset($this->absoluteClauses[$key]);

        $queryString = "UPDATE {$this->tableName} ";
        $clauseCount = count($this->absoluteClauses);

        if ($clauseCount >= 1) {
            $queryString .= "SET ";

            foreach ($this->absoluteClauses as $clause)
                $queryString .= "{$clause->prepare()}" . ($clauseCount >= 2 ? ", " : "");

            $queryString = rtrim($queryString, ", ");
            $queryString .= " WHERE {$this->primaryConstraint->prepare()}";
        }

        array_push($this->absoluteClauses, $this->primaryConstraint);

        return $queryString;
    }

    public function addUpdate(SQLValueUpdate $update)
    {
        array_push($this->absoluteClauses, $update);
    }

    public function setPrimaryConstraint(SQLConstraint $primaryConstraint)
    {
        $this->primaryConstraint = $primaryConstraint;
    }
}
