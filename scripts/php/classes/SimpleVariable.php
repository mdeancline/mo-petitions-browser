<?php
class SimpleVariable implements SmartyDataVariable
{
    private readonly string $value;
    private readonly string $name;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = str_replace('\n', "\n", $value);
    }

    public function getValue(array $requestResult): string
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
