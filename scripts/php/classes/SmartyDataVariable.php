<?php
interface SmartyDataVariable
{
    public function getValue(array $requestResult): string;
    public function getName(): string;
}
