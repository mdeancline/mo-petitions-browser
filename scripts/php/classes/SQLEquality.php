<?php
class SQLEquality extends AbstractSQLClause implements SQLConstraint, SQLValueUpdate
{
    public function __construct(string $parameter, mixed $value)
    {
        parent::__construct($parameter, $value);
    }

    public function prepare(): string
    {
        return "{$this->parameter} = ?";
    }
}
