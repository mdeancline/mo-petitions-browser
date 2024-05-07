<?php
class TableHeader extends AbstractAttributedMarkup
{
    private array $captions;

    public function __construct(string ...$captions)
    {
        parent::__construct("tr");
        $this->captions = $captions;
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);
        foreach ($this->captions as $caption)
            $element->appendChild((new RawHTML("<th>$caption</th>"))->toDOMNode($document));

        return $element;
    }

    public function getLength(): int
    {
        return count($this->captions);
    }
}
