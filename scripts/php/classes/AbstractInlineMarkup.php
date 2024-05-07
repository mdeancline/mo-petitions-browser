<?php
abstract class AbstractInlineMarkup implements InlineMarkup
{
    private readonly string $tag;
    protected array $components = [];

    protected function __construct(string $tag, string | InlineMarkup $value)
    {
        $this->tag = $tag;

        if (is_string($value))
            $this->withText($value);
        else
            $this->withInline($value);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = $document->createElement($this->tag);
        foreach ($this->components as $component)
            $element->appendChild($component->toDOMNode($document));

        return $element;
    }

    public function withText(string $value): InlineMarkup
    {
        return $this->withInline(new RawText($value));
    }

    public function withInline(InlineMarkup $markup): InlineMarkup
    {
        array_push($this->components, $markup);
        return $this;
    }
}
