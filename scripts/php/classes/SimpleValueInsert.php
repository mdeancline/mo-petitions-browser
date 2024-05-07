<?php
class SimpleValueInsert extends AbstractSQLClause implements SQLValueInsert
{
    public function __construct(string $parameter, string $value)
    {
        parent::__construct($parameter, $value);
    }

    public function prepare(): string
    {
        return "?";
    }
}
