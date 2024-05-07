<?php
interface OptionSelection extends AttributedMarkup
{
    #[\Override]
    public function withAttribute(string $name, string $value = ""): OptionSelection;
    public function getLength(): int;
}
