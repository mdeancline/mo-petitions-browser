<?php
class Table extends AbstractAttributedMarkup
{
    private readonly int $columns;
    private readonly TableHeader $header;
    private array $rows = [];

    public function __construct(int | TableHeader $columnsOrHeader)
    {
        parent::__construct("table");

        if (is_int($columnsOrHeader)) {
            $this->columns = $columnsOrHeader;
            $this->header = null;
        } else {
            $this->columns = $columnsOrHeader->getLength();
            $this->header = $columnsOrHeader;
        }
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $body = new class extends AbstractHTMLContainer
        {
            public function __construct()
            {
                parent::__construct("tbody");
            }
        };

        if ($this->header != null)
            $body->withChild($this->header);

        foreach ($this->rows as $rows)
            $body->withChild($rows);

        $element = parent::toDOMNode($document);
        $element->appendChild($body->toDOMNode($document));

        return $element;
    }

    public function withClassList(string ...$classList): Table
    {
        return parent::withClassList(...$classList);
    }

    public function withAttribute(string $name, string $value = ""): Table
    {
        return parent::withAttribute($name, $value);
    }

    public function withRow(TableRow $row): Table
    {
        if ($row->getLength() > $this->columns)
            throw new InvalidArgumentException("Given table row exceeds column count");

        array_push($this->rows, $row);
        return $this;
    }
}
