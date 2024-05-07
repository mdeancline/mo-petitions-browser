<?php
class AbstractOptionSelection extends AbstractAttributedMarkup implements OptionSelection
{
    private int $selectedOptionIndex = -1;
    private array $options = [];

    public function __construct(string $name)
    {
        parent::__construct("select");
        parent::withAttribute("name", $name);
    }

    public function toDOMNode(DOMDocument $document): DOMElement
    {
        $element = parent::toDOMNode($document);

        for ($i = 0; $i < count($this->options); $i++) {
            $option = $this->options[$i];
            $optionElem = $document->createElement("option", $option["caption"]);
            $optionElem->setAttribute("value", $option["value"]);

            if ($i == $this->selectedOptionIndex)
                $optionElem->setAttributeNode($document->createAttribute("selected"));

            $element->appendChild($optionElem);
        }

        return $element;
    }

    public function withAttribute(string $name, string $value = ""): OptionSelection
    {
        return parent::withAttribute($name, $value);
    }

    protected function addOption(string $caption, string $value = "")
    {
        array_push($this->options, ["caption" => $caption, "value" => $value]);
    }

    protected function getSelectedOption(): int
    {
        return $this->selectedOptionIndex;
    }

    protected function setSelectedOption(int $index)
    {
        $count = $this->getLength();
        if ($index < 0 || $index > $count - 1)
            throw new OutOfRangeException("Given index: $index, Options count: $count");

        $this->selectedOptionIndex = $index;
    }

    public function getLength(): int
    {
        return count($this->options);
    }
}
