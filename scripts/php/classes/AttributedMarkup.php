<?php
interface AttributedMarkup extends HTMLMarkup
{
    #[\Override]
    public function toDOMNode(DOMDocument $document): DOMElement;
    public function withId(string $id): AttributedMarkup;
    public function withRandomId(): AttributedMarkup;
    public function withClassList(string ...$classList): AttributedMarkup;
    public function withAttribute(string $name, string $value = ""): AttributedMarkup;
    public function getAttribute(string $name): string;
}
