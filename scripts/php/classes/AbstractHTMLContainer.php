<?php
abstract class AbstractHTMLContainer extends AbstractAttributedMarkup implements HTMLContainer
{
    private array $children = [];

    protected function __construct(string $tag)
    {
        parent::__construct($tag);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);
        foreach ($this->children as $markup)
            $element->appendChild($markup->toDOMNode($document));

        return $element;
    }

    public function withAttribute(string $name, string $value = ""): AbstractHTMLContainer
    {
        return parent::withAttribute($name, $value);
    }

    public function withId(string $id): AbstractHTMLContainer
    {
        return parent::withId($id);
    }

    public function withClassList(string ...$classList): AbstractHTMLContainer
    {
        return parent::withClassList(...$classList);
    }

    public function withText(string $value): AbstractHTMLContainer
    {
        return $this->withChild(new RawText($value));
    }

    public function withChild(HTMLMarkup $markup): AbstractHTMLContainer
    {
        array_push($this->children, $markup);
        return $this;
    }
}
