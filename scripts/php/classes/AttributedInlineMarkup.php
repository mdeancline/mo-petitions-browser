<?php
abstract class AttributedInlineMarkup extends AbstractAttributedMarkup implements AttributedMarkup, InlineMarkup
{
    protected array $content = [];

    protected function __construct(string $tag, string | InlineMarkup $value)
    {
        parent::__construct($tag, $value);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);
        foreach ($this->content as $component)
            $element->appendChild($component->toDOMNode($document));

        return $element;
    }

    public function withId(string $id): AttributedInlineMarkup
    {
        return parent::withId($id);
    }

    public function withAttribute(string $name, string $value = ""): AttributedInlineMarkup
    {
        return parent::withAttribute($name, $value);
    }

    public function withText(string $text): AttributedInlineMarkup
    {
        return $this->withInline(new RawText($text));
    }

    public function withInline(InlineMarkup $markup): AttributedInlineMarkup
    {
        array_push($this->content, $markup);
        return $this;
    }
}
