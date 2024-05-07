<?php
class RawText implements InlineMarkup
{
    private static RawText $empty;

    private readonly string $value;
    private array $content = [];

    public function __construct(string $value)
    {
        $this->value = preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;", $value);
    }

    public static function emptyText(): RawText
    {
        return self::$empty ?? self::$empty = new RawText("");
    }

    public function toDOMNode(DOMDocument $document): DOMNode
    {
        $textNode = $document->createTextNode($this->value);

        foreach ($this->content as $inlineMarkup)
            $textNode->appendChild($inlineMarkup->toDOMNode($document));

        return $textNode;
    }

    public function withText(string $value): InlineMarkup
    {
        return $this->withInline(new RawText($value));
    }

    public function withInline(InlineMarkup $markup): InlineMarkup
    {
        array_push($this->content);
        return $this;
    }
}
