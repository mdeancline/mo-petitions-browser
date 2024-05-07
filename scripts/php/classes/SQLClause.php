<?php
interface SQLClause
{
    public function prepare(): string;
    public function getParameter(): string;
    public function getValue(): string;
}
