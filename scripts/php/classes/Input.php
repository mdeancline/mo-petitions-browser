<?php
class Input extends AbstractAttributedMarkup
{
    public function __construct(InputType $type)
    {
        parent::__construct("input");
        parent::withAttribute("type", $type->value);
    }

    public function withAttribute(string $name, string $value = ""): Input
    {
        if ($name == "type")
            throw new InvalidArgumentException("Cannot directly change input type");

        return parent::withAttribute($name, $value);
    }

    public function withId(string $id): Input
    {
        return parent::withId($id);
    }

    public function withClassList(string ...$classList): Input
    {
        return parent::withClassList(...$classList);
    }

    public function withPlaceholder(string $value): Input
    {
        return parent::withAttribute("placeholder", $value);
    }

    public function withName(string $name): input
    {
        return parent::withAttribute("name", $name);
    }

    public function withValue(mixed $value): Input
    {
        return parent::withAttribute("value", (string)$value);
    }

    public function getValue(): string
    {
        return parent::getAttribute("value");
    }
}
