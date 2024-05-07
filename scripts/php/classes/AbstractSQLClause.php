<?php
abstract class AbstractSQLClause implements SQLClause
{
    protected readonly string $parameter;
    protected readonly string $value;

    protected function __construct(string $parameter, mixed $value)
    {
        $this->parameter = $parameter;
        $this->value = (string)$value;
    }

    public final function getParameter(): string
    {
        return $this->parameter;
    }

    public final function getValue(): string
    {
        return $this->value;
    }
}
