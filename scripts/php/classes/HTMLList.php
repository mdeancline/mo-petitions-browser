<?php
class HTMLList extends AbstractAttributedMarkup
{
    private array $listItems = [];

    public function __construct(ListType $type)
    {
        parent::__construct($type->value);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);
        foreach ($this->listItems as $item)
            $element->appendChild($item->toDOMNode($document));

        return $element;
    }

    public function withListItem(ListItem $item)
    {
        array_push($this->listItems, $item);
        return $this;
    }

    public function withClassList(string ...$classList): HTMLList
    {
        return parent::withClassList(...$classList);
    }

    public function withAttribute(string $name, string $value = ""): HTMLList
    {
        return parent::withAttribute($name, $value);
    }
}
