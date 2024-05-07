<?php
class TableRow extends AbstractAttributedMarkup
{
    private array $cells;

    public function __construct(TableCell ...$cells)
    {
        parent::__construct("tr");
        $this->cells = $cells;
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);
        foreach ($this->cells as $cell)
            $element->appendChild($cell->toDOMNode($document));

        return $element;
    }

    public function getLength(): int
    {
        return count($this->cells);
    }
}
