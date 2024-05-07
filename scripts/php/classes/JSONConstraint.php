<?php
//https://stackoverflow.com/questions/40930896/how-to-create-and-insert-a-json-object-using-mysql-queries
class JSONConstraint extends AbstractSQLClause implements SQLConstraint
{
    public function __construct(string $parameter, array $value)
    {
        parent::__construct($parameter, json_encode($value));
    }

    public function prepare(): string
    {
        return "JSON_CONTAINS({$this->parameter}, ?)";
    }
}
