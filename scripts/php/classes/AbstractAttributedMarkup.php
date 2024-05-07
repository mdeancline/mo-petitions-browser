<?php
abstract class AbstractAttributedMarkup implements AttributedMarkup
{
    private const ATTRIBUTE_SETTER_TRANSLATIONS = [
        "id" => "Id",
        "class" => "ClassList"
    ];

    private readonly string $tag;
    protected readonly InlineMarkup $value;
    protected array $attributes = [];
    protected array $classList = [];

    protected function __construct(string $tag, string | InlineMarkup $value = "")
    {
        $this->tag = $tag;
        $this->value = empty($value) ? RawText::emptyText() : (is_string($value) ? new RawText($value) : $value);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = $document->createElement($this->tag);

        if (count($this->classList) > 0)
            $element->setAttribute("class", join(" ", $this->classList));

        $element->appendChild($this->value->toDOMNode($document));

        foreach ($this->attributes as $name => $value) {
            $domAttribute = $document->createAttribute($name);
            $domAttribute->value = $value;
            $element->setAttributeNode($domAttribute);
        }

        return $element;
    }

    public function withId(string $id): AttributedMarkup
    {
        $this->attributes["id"] = $id;
        return $this;
    }

    public function withRandomId(): AttributedMarkup
    {
        return $this->withId(uniqid());
    }

    public function withClassList(string ...$classList): AttributedMarkup
    {
        foreach ($classList as $class)
            array_push($this->classList, $class);

        return $this;
    }

    public final function getAttribute(string $name): string
    {
        return $this->attributes[$name];
    }

    public function withAttribute(string $name, string $value = ""): AttributedMarkup
    {
        $this->requireAttributeNot($name);
        $this->attributes[$name] = preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;", $value);
        return $this;
    }

    private function requireAttributeNot(string $givenName)
    {
        foreach (self::ATTRIBUTE_SETTER_TRANSLATIONS as $name => $translation) {
            if ($givenName == $name)
                throw new InvalidArgumentException(sprintf(
                    "Use %s::with%s(\$%s) to set the %s attribute",
                    get_class($this),
                    $translation,
                    to_camel_case($translation),
                    $givenName
                ));
        }
    }
}
