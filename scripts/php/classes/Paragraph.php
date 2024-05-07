<?php
class Paragraph extends AbstractAttributedMarkup
{
    private array $components = [];

    public function __construct(string | InlineMarkup $content = "")
    {
        parent::__construct("p", $content);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $paragraphElem = parent::toDOMNode($document);
        foreach ($this->components as $component)
            $paragraphElem->appendChild($component->toDOMNode($document));

        return $paragraphElem;
    }

    public function withAttribute(string $name, string $value = ""): Paragraph
    {
        return parent::withAttribute($name, $value);
    }

    public function withText(string $content): Paragraph
    {
        return $this->withInline(new RawText($content));
    }

    public function withInline(InlineMarkup $markup): Paragraph
    {
        array_push($this->components, $markup);
        return $this;
    }
}
