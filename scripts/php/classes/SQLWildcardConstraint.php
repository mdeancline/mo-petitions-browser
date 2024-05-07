<?php
class SQLWildcardConstraint extends AbstractSQLClause implements SQLConstraint
{
    public function __construct(string $parameter, string $value)
    {
        parent::__construct($parameter, $value);
    }

    public function prepare(): string
    {
        return "{$this->parameter} LIKE '%' || ? || '%'";
    }
}
